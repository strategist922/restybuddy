<?php
class SearchController extends Yaf_Controller_abstract {
	
	private $_code = Common_Errorcode::PARAMERROR;
	private $_info = '';
	private $_data = '';
	
	public function userAction()
	{
		if(!empty($_GET['keyword'])) {
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Business_Search",$_GET,'user');
		}
		jsonReturn($this->_code,$this->_data,$this->_info);
	}
	
	
	public function streamAction()
	{
		if(!empty($_GET['keyword'])) {
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Business_Search",$_GET,'stream');
		}
		jsonReturn($this->_code,$this->_data,$this->_info);
	}
	
	public function productAction()
	{
		if(!empty($_GET['keyword'])) {
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Business_Search",$_GET,'product');
		}
		jsonReturn($this->_code,$this->_data,$this->_info);
	}
	
	public function wordexpandAction()
	{
		if(!empty($_GET['keyword'])) {
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q("Business_Search",$_GET,'wordexpand');
		}
		jsonReturn($this->_code,$this->_data,$this->_info);
	}
}