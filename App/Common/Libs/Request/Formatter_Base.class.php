<?php
namespace Common\Libs\Request;
/**
 * PhalApi_Request_Formatter_Base 公共基类
 *
 * - 提供基本的公共功能，便于子类重用
 *
 * @package     PhalApi\Request
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-11-07
 */
class Formatter_Base {

    /**
     * 根据范围进行控制
     */
    protected function filterByRange($value, $rule) {
        $this->filterRangeMinLessThanOrEqualsMax($rule);

        $this->filterRangeCheckMin($value, $rule);

        $this->filterRangeCheckMax($value, $rule);

        return $value;
    }

    protected function filterRangeMinLessThanOrEqualsMax($rule) {

        if (isset($rule['min']) && isset($rule['max']) && $rule['min'] > $rule['max']) {
          ERR(
                L('min should <= max, but now {$name} min = {$min} and max = {$max}',
                    ['NAME' => $rule['name'], 'MIN' => $rule['min'], 'MAX' => $rule['max']])
            );
        }
    }

    protected function filterRangeCheckMin($value, $rule) {
        if ($value == 0 && isset($rule['require']) && !$rule['require']) return;
        if (isset($rule['min']) && $value < $rule['min']) {
          ERR(
                L('{$name} should >= {$min}, but now {$name} = {$value}',
                    ['NAME' => $rule['name'], 'MIN' => $rule['min'], 'VALUE' => $value])
            );
        }
    }

    protected function filterRangeCheckMax($value, $rule) {
        if ($value == 0 && isset($rule['require']) && !$rule['require']) return;
        if (isset($rule['max']) && $value > $rule['max']) {
          ERR(
                L('{$name} should <= {$max}, but now {$name} = {$value}',
                    ['NAME' => $rule['name'], 'MAX' => $rule['max'], 'VALUE' => $value])
            );
        }
    }

    /**
     * 格式化枚举类型
     * @param string $value 变量值
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @throws PhalApi_Exception_BadRequest
     */
    protected function formatEnumValue($value, $rule) {
        if (!in_array($value, $rule['range'])) {
            ERR(
                L('{$name} should be in {$range}, but now {$name} = {$value}',
                    ['NAME' => $rule['name'], 'RANGE' => implode('/', $rule['range']), 'VALUE' => $value])
            );
        }
    }
}
