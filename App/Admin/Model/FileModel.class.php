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
            return array('status'=>1,'path'=>$filePath,'msg'=>'上传成功');
        } else {
            return array('status'=>0,'msg'=>$Upload->getError());
        }
    }
}
