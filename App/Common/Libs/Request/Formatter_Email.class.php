<?php
namespace Common\Libs\Request;
/**
 * PhalApi_Request_Formatter_Email 手机号码验证
 *
 * Created by andy on 03/17/16
 * @author      andy <www@webx32.com> 2016-03-17
 */
class Formatter_Email extends Formatter_Base implements Request_Formatter {

    /**
     * 邮箱验证
     *
     * @param string $value 变量值
     * @return string 格式化后的变量
     *
     */
    public function parse($value, $rule) {

        if (!self::validEmail($value)) {
          ERR(
                L("{name} should be email", ['NAME' => $rule['name']]));
        }

        return $value;
    }

    /**
     * 验证邮箱地址
     * @param $email
     * @return bool
     */
    static function validEmail($email = null) {
        $isValid = true;
        if (!is_string($email)) return false;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            return false;
        }
        $domain = substr($email, $atIndex + 1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
            // local part length exceeded
            $isValid = false;
        } else if ($domainLen < 1 || $domainLen > 255) {
            // domain part length exceeded
            $isValid = false;
        } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
            // local part starts or ends with '.'
            $isValid = false;
        } else if (preg_match('/\\.\\./', $local)) {
            // local part has two consecutive dots
            $isValid = false;
        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
            // character not valid in domain part
            $isValid = false;
        } else if (preg_match('/\\.\\./', $domain)) {
            // domain part has two consecutive dots
            $isValid = false;
        } else if
        (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
            str_replace("\\\\", "", $local))
        ) {
            // character not valid in local part unless
            // local part is quoted
            if (!preg_match('/^"(\\\\"|[^"])+"$/',
                str_replace("\\\\", "", $local))
            ) {
                $isValid = false;
            }
        }
        if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
            // domain not found in DNS
            $isValid = false;
        }

        return $isValid;
    }
}
