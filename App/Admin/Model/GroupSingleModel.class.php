<?php
namespace Admin\Model;

use Think\Model;

//组用例模型
class GroupSingleModel extends Model {

    //自动验证
    protected $_validate = [
        //-1,'名称长度不合法！'
        ['name', '/^[^@]{2,100}$/i', -1, self::EXISTS_VALIDATE],
        //-5,'帐号被占用！'
//    ['name', '', -5, self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT],
    ];

    //用户表自动完成
    protected $_auto = [
        ['create_time', 'time', self::MODEL_INSERT, 'function'],
    ];


    /**
     * 获组用例列表
     * @param $page 第几页
     * @param $rows
     * @param $order
     * @param $sort
     * @param $single
     * @param $gsingle
     * @param $date_from
     * @param $date_to
     * @return array
     */
    public function getList($tid, $order, $sort, $page, $rows, $all = false, $where = [], $isrecovery = 0,$ispublic=null) {
        if ($all) {
            return $this
                ->field('g.id,g.name,u.manager,u.nickname')
                ->join('g LEFT JOIN  __MANAGE__ u  ON s.uid = u.id')
                ->select();
        }
        $map = [];
        foreach ($where as $key => $value) {
            $map['g.' . $key] = $value;
        }

        $obj = $this
            ->field('g.id,g.tid,g.name,g.nlp,g.arc,g.validates,g.create_time,u.manager,u.nickname')
            ->join('g LEFT JOIN  __MANAGE__ u  ON g.tid = u.id')
            ->where($map)
            ->order([$order => $sort])
            ->limit($page, $rows)
            ->select();

        if ($obj) foreach ($obj as $key => $value) {
            $obj[$key]['validates'] = unserialize($value['validates']);
            $obj[$key]['short_name'] = mb_substr($value['name'],0,10,"utf-8")."...";
            $obj[$key]['short_nlp'] = mb_substr($value['nlp'],0,10,"utf-8")."...";
            $obj[$key]['short_arc'] = mb_substr($value['arc'],0,10,"utf-8")."...";
        }

        //统计
        $total = $this->where($map)->join('g LEFT JOIN  __MANAGE__ u  ON g.tid = u.id')->count();

        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $obj ? $obj : [],
        ];
    }

    /**
     * 根据id获取所有数据
     * @param $id
     * @return array
     */
    public function getListById($id) {
        $map = ['tid' => $id];
        $map[] = 'a.tid = b.id';
        $obj = $this->table('__GROUP_SINGLE__ a, __GROUP__ b')
            ->field('a.id,a.tid,a.name,a.nlp,a.arc,a.validates,a.create_time,a.isrecovery,b.uid,b.ispublic,b.name')
            ->where($map)
            ->order(['create_time' => 'desc'])
            ->select();
        return $obj;
    }

    //获取规则公用方法
    private function getVali($data1, $data2, $data3) {
        \Think\Log::write(json_encode($data1,TRUE),'info');
        $temp = [];
        foreach ($data1 as $key => $value) {
            if ($data1[$key] && $data2[$key] && $data3[$key]) {
                $temp[] = [
                    'v1'   => $data1[$key],
                    'dept' => $data2[$key],
                    'v2'   => $data3[$key],
                ];
            }
        }
        return $temp;
    }

    //新增组用例
    public function addSingle($name, $tid, $nlp, $arc, $v1, $dept, $v2) {
        $data = [
            'name' => $name,
            'tid'  => $tid,
            'uid' => session('admin')['id']
        ];
        $data['arc'] = $arc;
        $data['nlp'] = $nlp;

        $data['validates'] = serialize($this->getVali($v1, $dept, $v2));

        if ($this->create($data)) {
            $data['create_time'] = date('Y-m-d H:i:s');
            $sid = $this->add($data);

            return $sid ? $sid : '';
        }
        return $this->getError();
    }

    //获取一条数据
    public function getSingle($id) {
        $vali = $this->field('id,tid,name,nlp,arc,validates')->where(['id' => $id])->find();
        if (!$vali) return false;
        $vali['validates'] = unserialize($vali['validates']);
        return $vali;
    }

    //修改组用例
    public function updateSingle($id, $type, $name_edit, $nlp_edit, $arc_edit, $v1_edit, $dept_edit, $v2_edit, $tid) {
        $data = [
            'id'   => $id,
            'name' => $name_edit
        ];

        if ($type == 'ASR') {
            $data['arc'] = $arc_edit;
            $data['nlp'] = NULL;
        } else {
            $data['arc'] = NULL;
            $data['nlp'] = $nlp_edit;
        }
        if( !empty($tid) ) {
            $data['tid'] = $tid;
        }

        $data['validates'] = serialize($this->getVali($v1_edit, $dept_edit, $v2_edit));

        if (!$this->create($data)) {
            return $this->getError();
        }
        $sid = $this->save($data);
        
        return $sid >= 0 ? 1 : -2;
    }

    //状态修改
    public function setStatus($id, $status) {
        return $this->where(['id' => $id])->setField('status', $status);

    }

    public function canAddSingle($group_id) {
        $sess = session('admin');
        $ug_id = $sess['group_id'];

        if ($ug_id == 1) return true;

        $mdl = M('ManageGroupClassify');

        /*检查用例组*/
        $grp = $mdl->where(['id' => $group_id])->find();
        if (!$grp) return false;
        if ($grp['uid'] == $sess['id']) return true;

        /*检查模块*/
        $model = $mdl->where(['id' => $grp['pid']])->find();
        if (!$model) return false;
        if ($model['uid'] == $sess['id']) return true;

        /*检查项目*/
        $project = $mdl->where(['id' => $model['pid']])->find();
        if (!$project) return false;
        if ($project['uid'] == $sess['id']) return true;

        /*检查项目授权*/
        $allowPids = M('AuthGroup')->where(['id' => $ug_id])->getField('project_ids');
        $allowPids = explode(',', $allowPids);

        return in_array($project['id'], $allowPids);
    }
}
