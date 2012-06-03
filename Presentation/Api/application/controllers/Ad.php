<?php
class AdController extends Yaf_Controller_abstract{
	private $_code = Common_Errorcode::PARAMERROR;
	private $_info = '';
	private $_data = '';
	
	
	public function updateAction()
	{
		//param validate then do act
		if(!empty($_POST)){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Service_Ad",$_POST,'update');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function destroyAction()
	{
		if(!empty($_POST)){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Service_Ad",$_POST,'destroy');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function showAction()
	{
		if(!empty($_POST)){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Service_Ad",$_POST,'show');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function listAction()
	{
		if(!empty($_POST)){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Service_Ad",$_POST,'getlist');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	
}