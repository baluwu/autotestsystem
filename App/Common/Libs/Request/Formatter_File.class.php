<?php
namespace Common\Libs\Request;
/**
 * PhalApi_Request_Formatter_File 格式化上传文件
 * @package     PhalApi\Request
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-11-07
 */
class Formatter_File extends Formatter_Base implements Request_Formatter {

    /**
     * 格式化文件类型
     *
     * @param array $rule array('name' => '', 'type' => 'file', 'default' => array(...), 'min' => '', 'max' => '', 'range' => array(...))
     *
     * @throws PhalApi_Exception_BadRequest
     */
    public function parse($value, $rule) {

        $default = isset($rule['default']) ? $rule['default'] : NULL;

        $index = $rule['name'];
        // 未上传
        if (!isset($_FILES[$index])) {
            // 有默认值 || 非必须
            if ($default !== NULL || (isset($rule['require']) && !$rule['require'])) {
                return $default;
            }
        }

        if (!isset($_FILES[$index]) || !isset($_FILES[$index]['error']) || !is_array($_FILES[$index])) {
          ERR(L('miss upload file: {file}', ['FILE' => $index]));
        }

        if ($_FILES[$index]['error'] != UPLOAD_ERR_OK) {
          ERR(L('fail to upload file with error = {error}', ['ERROR' => $_FILES[$index]['error']]));
        }

        $sizeRule = $rule;
        $sizeRule['name'] = $sizeRule['name'] . '.size';
        $this->filterByRange($_FILES[$index]['size'], $sizeRule);

        if (!empty($rule['range']) && is_array($rule['range'])) {
            $rule['range'] = array_map('strtolower', $rule['range']);
            $this->formatEnumValue(strtolower($_FILES[$index]['type']), $rule);
        }

        //对于文件后缀进行验证
        if (!empty($rule['ext'])) {
            $ext = trim(strrchr($_FILES[$index]['name'], '.'), '.');
            if (is_string($rule['ext'])) {
                $rule['ext'] = explode(',', $rule['ext']);
            }
            if (!$ext) {
              ERR(L('Not the file type {ext}', ['EXT' => json_encode($rule['ext'])]));
            }
            if (is_array($rule['ext'])) {
                $rule['ext'] = array_map('strtolower', $rule['ext']);
                $rule['ext'] = array_map('trim', $rule['ext']);
                if (!in_array(strtolower($ext), $rule['ext'])) {
                  ERR(L('Not the file type {ext}', ['EXT' => json_encode($rule['ext'])]));
                }
            }
        }


        return $_FILES[$index];
    }
}
