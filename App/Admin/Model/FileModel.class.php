<?php
namespace Admin\Model;
use Think\Model;
use Think\Upload;
//文件上传模型
class  FileModel extends Model{
    public function uploadFile(){
        $Upload = new Upload();
        $Upload->rootPath = '.'.C('UPLOAD_PATH');

        $Upload->maxSize = 0;
        $Upload->exts = array('mp3', 'wav', 'wma', 'mid', 'amr');
        $info = $Upload->upload();
        if ($info) {
            $savePath = $info['file']['savepath'];
            $saveName = $info['file']['savename'];
            $filePath = C('UPLOAD_PATH').$savePath.$saveName;
            M('AudioUploads')->add([
                'name' => $saveName,
                'path' => $savePath,
                'uploader' => 'admin',
                'when' => date('Y-m-d H:i:s')
            ]);
            return array('status'=>1,'path'=>$filePath,'msg'=>'上传成功');
        } else {
            return array('status'=>0,'msg'=>$Upload->getError());
        }
    }

    public function uploadAsr() {
        $dst_dir = '.' . C('UPLOAD_PATH') . date('Y-m-d');

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

        $dst_path = $dst_dir . '/' . $_POST['name'] . '.wav';
        if (move_uploaded_file($file['tmp_name'], $dst_path)) {
            M('AudioUploads')->add([
                'name' => $_POST['name'],
                'path' => addslashes($dst_path),
                'uploader' => 'admin',
                'when' => date('Y-m-d H:i:s')
            ]);

            return array('status'=>1,'path'=>$dst_path,'msg'=>'上传成功');
        }
        else {
            return array('status'=>0,'msg'=>'上传失败');
        }
    }
}
