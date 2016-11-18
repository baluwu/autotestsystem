<?php
namespace Admin\Controller;

use Think\Controller;
use Common\Libs\Request;

//权限控制器
class BaseController extends Controller {

    protected $_req;

    protected function _initialize() {
        $this->_req = new Request();
        $this->createMemberValue();
        $this->_assign();

        $this->_initLang();
    }

    //网站国际化
    private function _initLang()
    {
        $lan_config = C('LANG');
        $set_lan = I('get.'.$lan_config['get_lan'], $lan_config['def']);
        if( !in_array($set_lan, $lan_config['all']) ) {
            $set_lan = $lan_config['def'];
        }
        putenv('LANG='.$set_lan );
        setlocale(LC_ALL, $set_lan );
        $domain = 'rokid_lang';
        bindtextdomain ( $domain ,  ABS_ROOT."/Lang/" ); //设置某个域的mo文件路径
        bind_textdomain_codeset($domain, 'UTF-8');  //设置mo文件的编码为UTF-8
        textdomain($domain);                    //设置gettext()函数从哪个域去找mo文件
        $this->assign('set_lan', $set_lan);
    }


    /**
     * 按参数规则解析生成接口参数
     *
     * 根据配置的参数规则，解析过滤，并将接口参数存放于类成员变量
     *
     */
    protected function createMemberValue() {
        foreach ($this->getApiRules() as $key => $rule) {
            $this->$key = $this->_req->getByRule($rule);
        }
    }

    /**
     * 取接口参数规则
     *
     * 主要包括有：
     * - 1、[固定]系统级的service参数
     * - 2、应用级统一接口参数规则，在app.apiCommonRules中配置
     * - 3、接口级通常参数规则，在子类的*中配置
     * - 4、接口级当前操作参数规则
     *
     * <b>当规则有冲突时，以后面为准。另外，被请求的函数名和配置的下标都转成小写再进行匹配。</b>
     *
     * @uses PhalApi_Api::getRules()
     * @return array
     */
    public function getApiRules() {
        $rules = [];

        $allRules = $this->getRules();

        if (!is_array($allRules)) {
            $allRules = [];
        }
        $allRules = array_change_key_case($allRules, CASE_LOWER);

        if (isset($allRules[strtolower(ACTION_NAME)]) && is_array($allRules[strtolower(ACTION_NAME)])) {
            $rules = $allRules[strtolower(ACTION_NAME)];
        }
        if (isset($allRules['*'])) {
            $rules = array_merge($allRules['*'], $rules);
        }
//    dump($allRules);
        return $rules;
    }

    /**
     * 获取参数设置的规则
     *
     * 可由开发人员根据需要重载
     *
     * @return array
     */
    public function getRules() {
        $rules = [];
        $method = get_class_methods($this);
        $rStaic = new \ReflectionClass($this);
        $staic = $rStaic->getStaticProperties();
        foreach ($method as $k => $v) {
            $rMethod = new \ReflectionMethod($this, $v);
            if (!$rMethod->isPublic()) {
                continue;
            }
            if (!isset($staic[$v . 'Rules'])) {
                continue;
            }
            $rules[$v] = $staic[$v . 'Rules'];
        }

        return $rules;
    }


    protected function _assign() {
        $this->assign('admin_info', session('admin'));
        $this->assign('CONTROLLER_NAME', CONTROLLER_NAME);
        $this->assign('MODULE_NAME', MODULE_NAME);
        $this->assign('ACTION_NAME', ACTION_NAME);
    }

    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
    protected function gotoURL($message, $status = 1, $jumpUrl = '', $ajax = false) {

        if (is_int($ajax)) $this->assign('waitSecond', $ajax);
        if (!empty($jumpUrl)) $this->assign('jumpUrl', $jumpUrl);
        // 提示标题
        $this->assign('msgTitle', $status ? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
        //如果设置了关闭窗口，则提示完毕后自动关闭窗口
        if ($this->get('closeWin')) $this->assign('jumpUrl', 'javascript:window.close();');
        $this->assign('status', $status);   // 状态
        //保证输出不受静态缓存影响
        C('HTML_CACHE_ON', false);

        $this->assign('error', $message);// 提示信息
        //发生错误时候默认停留3秒
        if (!isset($this->waitSecond)) $this->assign('waitSecond', '0');
        // 默认发生错误的话自动返回上页
        if (!isset($this->jumpUrl)) $this->assign('jumpUrl', "javascript:history.back(-1);");
        $this->display(C('TMPL_ACTION_ERROR'));
        // 中止执行  避免出错后继续执行
        exit;
    }

    //空方法
    public function _empty() {
        $this->redirect('Index/index');
    }
}
