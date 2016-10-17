<?php
namespace Common\Libs\Request;
/**
 * PhalApi_Request_Formatter_Mobile 手机号码验证
 *
 * Created by andy on 03/17/16
 * @author      andy <www@webx32.com> 2016-03-17
 */
class Formatter_Mobile extends Formatter_Base implements Request_Formatter {

    /**
     * 手机号码验证
     *
     * @param int $value 变量值
     * @return int 格式化后的变量
     *
     */
    public function parse($value, $rule) {

        if (($value || $rule["require"]) && !preg_match("/^((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0,6,7,8]{1}))[0-9]{8}$/", $value)) {
            ERR(
                L("{name} should be cell phone NO.", ['NAME' => $rule['name']]));
        }

        return $value;
    }
}
