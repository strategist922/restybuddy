<?php

class StreamController extends Yaf_Controller_abstract {
	
	protected $_code = Common_Errorcode::PARAMERROR;
	protected $_data = '';
	
	public function updateAction()
	{
		if(!empty($_POST['stream']) && !empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Stream',$_POST,'updateStream');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	//upload post
	public function uploadAction()
	{
		if(!empty($_POST['stream'])  && !empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Stream',$_POST,'uploadStream');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	
	public function repostAction()
	{
		if(!empty($_POST['id']) && !empty($_POST['uid'])  && !empty($_POST['stream'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Stream',$_POST,'repostStream');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function showAction()
	{
		if(!empty($_GET['id'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Stream',$_GET,'getStream');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function destroyAction()
	{
		if(!empty($_GET['id']) && !empty($_GET['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Stream',$_GET,'destoryStream');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	
	
	
	
	
}