<?php
class SignupController extends Yaf_Controller_abstract {
	
	private $_code = Common_Errorcode::PARAMERROR;
	private $_info = '';
	private $_data = '';
	
	public function signupAction()
	{
		if(!empty($_POST['email']) && !empty($_POST['screenname']) && !empty($_POST['password'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Business_User",$_POST,'setUser');
		}
		jsonReturn($this->_code,$this->_data);
	}
}