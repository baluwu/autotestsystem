<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Auth;

//后台框架首页控制器
class IndexController extends AuthController {
  protected $not_verify_action = ['_empty', 'index', 'getNav', 'getNavAll', 'setLangConf'];


  //显示后台框架
  public function index() {
    if (!session('admin')) $this->redirect('Login/index');
    $group = D("Group");

    $single = D("Single");
    $groupCount = $group->countData([
      'isrecovery' => ['eq', 0],
      'uid'        => ['eq', session('admin')['id']]
    ]);
    $singleCount = $single->countData([
      'isrecovery' => ['eq', 0],
      'uid'        => ['eq', session('admin')['id']]
    ]);
    $grouPubCount = $group->countData([
      'ispublic' => ['eq', 1]
    ]);
    $singlePubCount = $single->countData([
      'ispublic' => ['eq', 1]
    ]);

    $this->assign('count', ['group' => $groupCount, 'single' => $singleCount, 'singlePub' => $singlePubCount, 'groupPub' => $grouPubCount,]);
    $this->display();
  }


  //获取菜单导航
    static $getNavRules = [
        'id' => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
    ];
  public function getNav() {

    $Auth = new Auth();

    $groups = $Auth->getGroups(session('admin')['id'])[0]['rules'];

    $AuthRule = M('AuthRule');
    $map['id'] = ['in', $groups];
    $obj = $AuthRule->field('title')->where($map)->select();

    $texts = '';

    foreach ($obj as $key => $value) {
      $texts .= $value['title'] . ',';
    }

    $Nav = D('Nav');
    $this->ajaxReturn($Nav->getNav($this->id), substr($texts, 0, -1));
  }

  //获取所有菜单导航
    static $getNavAllRules = [
        'id' => ['name' => 'id', 'type' => 'int', 'method' => 'post', 'desc' => 'id'],
    ];
  public function getNavAll() {

    $Nav = D('Nav');
    $this->ajaxReturn($Nav->getNav($this->id));
  }

  //空方法
  public function _empty() {
    $this->redirect('Index/index');
  }

  //设置国际化语言
  public function setLangConf()
  {
    $get_set_lan = I('get.lan', '');
    //如果无lan参数，就直接进行 中英文切换
    if( empty($get_set_lan) ) {
      $s_lan = session('SET_LANG_CONF');
      if(empty($s_lan) || $s_lan=='zh_CN') {
        $get_set_lan = 'en_US';
      } else {
        $get_set_lan = 'zh_CN';
      }
    }
    $lan_config = C('LANG');
    if( !in_array($get_set_lan, $lan_config['all']) ) {
      $get_set_lan = $lan_config['def'];
    }
    session('SET_LANG_CONF', $get_set_lan);

    $this->ajaxReturn(1);
  }

}
