<?php 

namespace Admin\Model;
use Think\Model;

//修改密码模型
class UpdatePasswordModel extends Model{
	
	protected $_validate = array(
		//-1,'密码长度不合法！'
		array('password', '6,30', -1, self::VALUE_VALIDATE,'length'),
		//-2 '密码确认不一致'
		array('respass','newpass',-2,0,'confirm'),
	);
	//自定义数据表
	protected  $tableName = 'manage';
	
    //修改密码
	public function updatePass($id,$password,$newpass,$respass){
		$data = array(
			 'password'=> $password,
			 'newpass' => $newpass,
			 'respass' => $respass	
		);
		if($this->create($data)){
			$obj = $this->field('id,manager')->where($data)->find();
			if($obj){
				$map['newpass'] = sha1($newpass);
				$row = $this->where(array('id'=>$id))->setField('password',$map['newpass']);
			    return $row ? $row : '';
			}
		}else{
			return $this->getError();
		}
	}
	
}






