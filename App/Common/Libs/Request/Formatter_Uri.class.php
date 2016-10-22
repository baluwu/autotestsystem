<?php
namespace Common\Libs\Request;
/**
 * PhalApi_Request_Formatter_Uri uri资源验证
 *
 * Created by andy on 03/23/16
 * @author      andy <www@webx32.com> 2016-03-17
 */
class Formatter_Uri extends Formatter_Base implements Request_Formatter {

    /**
     * uri资源验证
     *
     * @param string $value 变量值
     * @return string
     *
     */
    public function parse($value, $rule) {
        if($value)$value=urldecode($value);
        if (($value || $rule["require"]) && !preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $value)) {
            E(
                L("{name} should be URI", ['NAME' => $rule['name']]));


        }

        return $value;
    }
}
