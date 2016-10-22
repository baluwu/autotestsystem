<?php
namespace Admin\Model;

use Think\Model;
use Think\Auth;

//管理员模型
class ManageModel extends Model {

    //管理员帐号自动验证
    protected $_validate = [
        //-1,'密码长度不合法！'
        ['password', '6,30', '密码长度不合法！', self::VALUE_VALIDATE, 'length'],
        //-7,'帐号长度不合法！'
        ['manager', '2,20', '帐号长度不合法！', self::VALUE_VALIDATE, 'length', 5],
        //-3,'邮箱格式不正确！'
        ['email', 'email', -3, self::EXISTS_VALIDATE],
        //-4,'帐号被占用！'
        ['manager', '', -4, self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT],
        //-5,'邮箱被占用！'
        ['email', '', -5, self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT],
        //-6,'昵称长度不合法！'
        ['nickname', '2,20', '昵称长度不合法！', self::EXISTS_VALIDATE, 'length'],
        //-8,'昵称被占用！'
        ['nickname', '', -8, self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT],
    ];

    //用户表自动完成
    protected $_auto = [
        ['password', 'sha1', self::MODEL_BOTH, 'function'],
    ];

    //获取管理员列表
    public function getList($order, $sort, $page, $rows, $where = []) {
        $map = [];

        foreach ($where as $key => $value) {
            $map['m.' . $key] = $value;
        }


        $obj = $this
            ->field('m.*,a.group_id,b.title as group_name')
            ->join('m LEFT JOIN  __AUTH_GROUP_ACCESS__ a  ON m.id = a.uid')
            ->join('LEFT JOIN  __AUTH_GROUP__ b  ON b.id = a.group_id')
            ->where($map)
            ->order([$order => $sort])
            ->limit($page, $rows)
            ->select();

        foreach ($obj as $key => $v) {
            $obj[$key]['headImg'] = get_gravatar($obj[$key]['email']);
        }
        $total = $this->where($map)->join('m LEFT JOIN  __AUTH_GROUP_ACCESS__ a  ON m.id = a.uid')->count();
        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $obj ? $obj : [],
        ];
    }


    //新增管理员
    public function saveManage($Manager, $password, $repassword, $nickname, $email, $groupid) {
        $data = [
            'manager'     => $Manager,
            'password'    => $password,
            'nickname'    => $nickname,
            'email'       => $email,
            'create_time' => REQUEST_TIME
        ];
        if (!$this->create($data)) {
            return $this->getError();
        }
        $mid = $this->add();
        if (!$mid) {
            return 0;
        }
        $data = [
            'uid'      => $mid,
            'group_id' => $groupid,
        ];
        $AuthGroupAccess = M('AuthGroupAccess');
        $AuthGroupAccess->add($data);
        return $mid;
    }

    //修改管理员
    public function updateManage($id, $password, $repassword, $nickname, $email, $role) {
        $data = [
            'id' => $id,
        ];

        if (!empty($nickname)) {
            $data['nickname'] = $nickname;
        }
        if (!empty($email)) {
            $data['email'] = $email;
        }

        if (!empty($password)) {
            $data['password'] = sha1($password);
        }
        $edit_mid = $this->save($data);

        //修改角色 //todo 判断 groupid是否存在
        $AuthGroupAccess = M('AuthGroupAccess');
        $aid = $AuthGroupAccess->where(['uid' => $id])->setField('group_id', $role);
        if ($edit_mid || $aid) {
            return 1;
        }
        return 0;
    }

    //删除管理员
    public function remove($ids) {
        $uid = session('admin')['id'];
        //删除管理员时不能删除当前登陆管理员
        $map['id'] = [['in', $ids], ['neq', $uid], 'and'];
        $rows = $this->where($map)->setField('isrecovery', 1);
        return $rows;
    }

    //还原管理员
    public function Restore($ids) {
        $uid = session('admin')['id'];
        //还原管理员时不能还原当前登陆管理员
        $map['id'] = [['in', $ids], ['neq', $uid], 'and'];
        $rows = $this->where($map)->setField('isrecovery', 0);
        return $rows;
    }

    //获取一条数据
    public function getManager($id) {
        $obj = $this
            ->field('m.*,a.group_id,b.title as group_name')
            ->join('m LEFT JOIN  __AUTH_GROUP_ACCESS__ a  ON m.id = a.uid')
            ->join('LEFT JOIN  __AUTH_GROUP__ b  ON b.id = a.group_id')
            ->where(['m.id' => $id])
            ->find();
        $obj['headImg'] = get_gravatar($obj['email']);
        return $obj;
    }

    //LDAP认证
    public function ldap_register($username, $pwd, $isremember) {
        //连接ldap服务器
        $ldap_conn = @ldap_connect(C('LDAP_HOST'), C('LDAP_PORT'));
        if (!$ldap_conn) return -1;//die('连接LDAP服务器失败');

        //绑定服务器
        if (!ldap_bind($ldap_conn, C('LDAP_BN'), C('LDAP_PASS'))) return -2;// die('无法绑定到服务器');
        $uid = C('LDAP_UID');
        //从服务器查询用户信息
        $getUserInfo = @ldap_search($ldap_conn, C('LDAP_BIND_DN'), "($uid=$username)", ['uid', 'dn', 'givenName', 'sn', 'mail', 'displayName']);

        $user = @ldap_first_entry($ldap_conn, $getUserInfo);
        if (!$user) return -3;//die("找不到用户");


        //验证用户名及密码
        $dn = @ldap_get_dn($ldap_conn, $user);
        $isPass = @ldap_bind($ldap_conn, $dn, $pwd);
        if (!$isPass) {
            return -4;  //用户名或密码错误
        }

        //返回LDAP服务器用户信息
        $user_info = ldap_get_attributes($ldap_conn, $user);
        if ($user_info) {
            $email = $user_info['mail'][0];
            $nickname = $user_info['displayName'][0];
            if (!$nickname) {
                $nickname = $user_info['givenName'][0];
                $nickname .= $user_info['sn'][0];
            }
        }

        //从数据库查询用户，用户不存在则写入用户信息,用户存在则更新登陆信息
        $manage = $this
            ->field('m.*,a.group_id,b.title as group_name')
            ->join('m LEFT JOIN  __AUTH_GROUP_ACCESS__ a  ON m.id = a.uid')
            ->join('LEFT JOIN  __AUTH_GROUP__ b  ON b.id = a.group_id')
            ->where(['m.ldap_uid' => $username])
            ->find();

        //自动绑定
        if (!$manage) {
            $data = [
                'manager'     => $username,
                'nickname'    => $nickname,
                'email'       => $email,
                'create_time' => REQUEST_TIME,
                'last_login'  => REQUEST_TIME,
                'last_ip'     => get_client_ip(),
                'ldap_uid'    => $username
            ];
            $manage_id = $this->add($data);
            if (!$manage_id) {
                return 0;
            }

            //写入权限表
            $auth_data = [
                'uid'      => $manage_id,
                'group_id' => 2
            ];
            $AuthGroupAccess = M('AuthGroupAccess');
            $AuthGroupAccess->add($auth_data);
            //写入session
            session('admin', [
                'id'       => $manage_id,
                'manager'  => $username,
                'nickname' => $nickname,
                'email'    => $email,
                'logtime'  => REQUEST_TIME,
                'group_id' => 2,
                'headImg'  => get_gravatar($email)
            ]);

            return 1;


        }
        if ($manage['isrecovery'] == 1) {
            return -9;
        }
        //写入session
        session('admin', [
            'id'       => $manage['id'],
            'manager'  => $manage['manager'],
            'nickname' => $manage['nickname'],
            'email'    => $manage['email'],
            'group_id' => $manage['group_id'],
            'logtime'  => REQUEST_TIME,
            'headImg'  => get_gravatar($manage['email'])
        ]);
        $update = [
            'id'         => $manage['id'],
            'last_login' => REQUEST_TIME,
            'last_ip'    => get_client_ip()
        ];
        $this->save($update);

        return 1;
    }

    //验证管理员登录
    public function checkManager($manager, $password, $isremember) {
        $data = [
            'manager'  => $manager,
            'password' => $password,
        ];

        if (!$this->create($data, 5)) {
            return $this->getError();
        }

        $map['m.manager'] = $manager;
        $map['m.password'] = sha1($password);

        $obj = $this
            ->field('m.*,a.group_id,b.title as group_name')
            ->join('m LEFT JOIN  __AUTH_GROUP_ACCESS__ a  ON m.id = a.uid')
            ->join('LEFT JOIN  __AUTH_GROUP__ b  ON b.id = a.group_id')
            ->where($map)
            ->find();
        if (!$obj) {
            return 0;
        }
        if ($obj['isrecovery'] == 1) {
            return -9;
        }

        //写入session
        session('admin', [
            'id'       => $obj['id'],
            'manager'  => $obj['manager'],
            'nickname' => $obj['nickname'],
            'email'    => $obj['email'],
            'group_id' => $obj['group_id'],
            'logtime'  => REQUEST_TIME,
            'headImg'  => get_gravatar($obj['email'])
        ]);

        //登陆验证后写入登陆信息
        $update = [
            'id'         => $obj['id'],
            'last_login' => REQUEST_TIME,
            'last_ip'    => get_client_ip()
        ];
        $this->save($update);

        return $obj['id'];

    }
}
