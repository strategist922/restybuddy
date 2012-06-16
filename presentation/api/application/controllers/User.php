<?php

class UserController extends Yaf_Controller_abstract {

	protected $_code = Common_Errorcode::PARAMERROR;
	protected $_data = '';

	public function showAction()
	{
		if(!empty($_GET['id'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_User',$_GET,'getUser');
		}
		jsonReturn($this->_code,$this->_data);

	}

	public function domainAction()
	{
		if(!empty($_GET['domain'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_User',$_GET,'domainUser');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function countsAction()
	{
		if(!empty($_GET['id'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_User',$_GET,'countsUser');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function infoAction()
	{
		if(!empty($_GET['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_User',$_GET,'infoUser');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function uploadAction()
	{
		if(!empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_User',$_GET,'uploadUser');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function remarkAction()
	{
		if(!empty($_POST['id']) && !empty($_POST['remark']) && !empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_User',$_GET,'remarkUser');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function signAction()
	{
		if(!empty($_POST['uid']) && !empty($_POST['sign'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_User',$_GET,'signUser');
		}
		jsonReturn($this->_code,$this->_data);
	}
}