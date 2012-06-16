<?php

class TagsController extends Yaf_Controller_abstract {

	private $_code = Common_Errorcode::PARAMERROR;
	Private $_data = '';
	private $_info = '';

	public function createAction()
	{
		if(!empty($_POST['id']) && !empty($_POST['tag']) && !empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Tags',$_POST,'create');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function destroyAction()
	{
		if(!empty($_GET['tag'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Tags',$_GET,'destroy');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function tagsbatchAction()
	{
		if(!empty($_GET['id'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$_GET['n'] = isset($_GET['n']) ? intval($_GET['n']) : 10;
			$this->_data = Q('Business_Tags',$_GET,'tagsbatch');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function tagsAction()
	{
		if(!empty($_GET['id'])){
			$this->_code = Common_Errorcode::SUCCESS;
			isset($_GET['n']) ? (int)$_GET['n'] : $_GET['n']=10;
			$this->_data = Q('Business_Tags',$_GET,'tags');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function hotAction()
	{
		if(isset($_GET['min']) && isset($_GET['max'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Tags','','hot');
		}
		jsonReturn($this->_code,$this->_data);
	}


}