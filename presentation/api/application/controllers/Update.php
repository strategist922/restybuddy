<?php

class UpdateController extends Yaf_Controller_abstract {
	
	protected $_code = Common_Errorcode::SUCCESS;
	protected $_data = '';
	
	public function androidAction() {
		$this->_data = C('version.android');
		jsonReturn($this->_code,$this->_data);
	}
	
	public function iosAction()
	{
		$this->_data = C('version.ios');;
		jsonReturn($this->_code,$this->_data);
	}
	
	
}