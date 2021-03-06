<?php
namespace Common\Libs\Request;
/**
 * PhalApi_Request_Formatter_Array 格式化数组
 *
 * @package     PhalApi\Request
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-11-07
 */
class Formatter_Array extends Formatter_Base implements Request_Formatter {

    /**
     * 对数组格式化/数组转换
     * @param string $value 变量值
     * @param array $rule array('name' => '', 'type' => 'array', 'default' => '', 'format' => 'json/explode', 'separator' => '', 'min' => '', 'max' => '')
     * @return array
     */
    public function parse($value, $rule) {
        $rs = $value;

        if (!is_array($rs)) {
            $ruleFormat = !empty($rule['format']) ? strtolower($rule['format']) : '';
            if ($ruleFormat == 'explode') {
                $rs = explode(isset($rule['separator']) ? $rule['separator'] : ',', $rs);
            } else if ($ruleFormat == 'json') {
                $rs = json_decode($rs, TRUE);
            } else {
                $rs = [$rs];
            }
        }

        $this->filterByRange(count($rs), $rule);

        return $rs;
    }
}
