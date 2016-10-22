<?php
namespace Common\Libs\Request;
/**
 * PhalApi_Request_Formatter_Enum 格式化枚举类型
 *
 * @package     PhalApi\Request
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-11-07
 */
class Formatter_Enum extends Formatter_Base implements Request_Formatter {

    /**
     * 检测枚举类型
     * @param string $value 变量值
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @return 当不符合时返回$rule
     */
    public function parse($value, $rule) {
        $this->formatEnumRule($rule);

        $this->formatEnumValue($value, $rule);

        return $value;
    }

    /**
     * 检测枚举规则的合法性
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @throws PhalApi_Exception_InternalServerError
     */
    protected function formatEnumRule($rule) {
        if (!isset($rule['range'])) {
          ERR(
                L("miss {name}'s enum range", ['NAME' => $rule['name']]));
        }

        if (empty($rule['range']) || !is_array($rule['range'])) {
          ERR(
                L("{name}'s enum range can not be empty", ['NAME' => $rule['name']]));
        }
    }
}
