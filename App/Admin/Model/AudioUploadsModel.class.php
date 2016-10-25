<?php
namespace Admin\Model;
use Think\Model;

//文件上传模型
class AudioUploadsModel extends Model{
    
    public function uploadLocalAudio(){
        $Upload = new Upload();
        $Upload->rootPath = '.'.C('UPLOAD_PATH');

        $Upload->maxSize = 0;
        $Upload->exts = array('mp3', 'wav', 'wma', 'mid', 'amr');
        $info = $Upload->upload();
        
        $ret = [ 'status' => 0, 'path' => '', 'msg' => '' ];
        if ($info) {
            $savePath = $info['file']['savepath'];
            $saveName = $info['file']['savename'];
            $filePath = C('UPLOAD_PATH').$savePath.$saveName;

            if (file_exists($filePath)) {
                $ret['msg'] = '文件名已使用';
                return $ret;
            }

            $r = $this->add([
                'source' => 0,
                'name' => $saveName,
                'path' => $savePath,
                'uploader' => session('admin')['manager'],
                'when' => date('Y-m-d H:i:s')
            ]);

            if ($r > 0) {
                $ret['status'] = 1;
                $ret['path'] = $filePath;
                $ret['msg'] = '上传成功';
                return $ret;
            }
            else {
                $ret['msg'] = '上传失败, 换个文件名试试';
                return $ret;
            }
        } else {
            $ret['msg'] = $Upload->getError();
            return $ret;
        }
    }

    public function uploadRecordAudio() {
        $ret = [ 'status' => 0, 'path' => '', 'msg' => '' ];

        $name = I('post.name');
        /*web显示路径*/
        $web_dir = C('UPLOAD_PATH') . date('Y-m-d');

        if (!is_dir($dst_dir)) {
            mkdir($dst_dir, 0777, true);            
        }

        $obj = each($_FILES);
        $file = $obj['value'];

        if (!in_array($file['type'], array('audio/wav', 'audio/mp3', 'audio/wma', 'audio/mid', 'audio/amr'))) {
            return array('status'=>0,'msg'=>'不支持的文件类型');
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            return array('status'=>0,'msg'=>'文件大小不能超过2M');
        }

        $web_path = $web_dir . '/' . $name . '.wav';

        if (file_exists('.' . $web_path)) {
            $ret['msg'] = '文件名已使用';
            return $ret;
        }

        if (move_uploaded_file($file['tmp_name'], '.' . $web_path)) {
            $r = $this->add([
                'source' => 1,
                'name' => $name,
                'path' => $web_path,
                'uploader' => session('admin')['manager'],
                'when' => date('Y-m-d H:i:s')
            ]);

            if ($r > 0) {
                $ret['status'] = 1;
                $ret['path'] = $web_path;
                $ret['msg'] = '上传成功';
                return $ret;
            }
            else {
                $ret['msg'] = '上传失败, 换个文件名试试';
                return $ret;
            }
        }
        else {
            $ret['msg'] = '上传失败';
            return $ret;
        }
    }

    public function getAudioList($cond, $page, $page_size) {
        $data = $this->where($cond)->order('`when` DESC')->limit($page, $page_size)->select();
        $total = $this->where($cond)->count();
        return [
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data ? $data : [],
        ];
    }
}
