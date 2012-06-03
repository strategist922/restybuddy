<?php
class RemindController extends  Yaf_Controller_abstract {
	
	private $_code = Common_Errorcode::PARAMERROR;
	private $_info = '';
	private $_data = '';
	
	public function unreadAction()
	{
		if(!empty($_GET['id']))
		{
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Business_Remind",$_GET,'unread');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function setcountAction()
	{
		if(!empty($_POST['id']) && !empty($_POST['type']))
		{
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Business_Remind",$_POST,'setcountZero');
		}
		jsonReturn($this->_code,$this->_data);
	}
}