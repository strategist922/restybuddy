<?php

class CommentController extends Yaf_Controller_abstract{
	
	protected $_code = Common_Errorcode::PARAMERROR;
	protected $_data = '';
	
	public function showAction()
	{
		if(!empty($_GET['id'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Comment',$_GET,'getComment');
		}
		jsonReturn($this->_code,$this->_data);
		
	}
	
	public function createAction()
	{
		if(!empty($_POST['rid']) && !empty($_POST['comment']) && !empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Comment',$_POST,'createComment');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function destoryAction()
	{
		if(!empty($_GET['id']) && !empty($_GET['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Comment',$_GET,'destoryComment');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function replyAction()
	{
		if(!empty($_POST['comment']) && !empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Comment',$_POST,'replyComment');
		}
		jsonReturn($this->_code,$this->_data);
	}
}