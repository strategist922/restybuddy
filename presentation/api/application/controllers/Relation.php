<?php
class RelationController extends Yaf_Controller_abstract {

	protected $_code = Common_Errorcode::PARAMERROR;
	protected $_data = '';

	public function createAction()
	{
		if(!empty($_POST['id']) && !empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Relation',$_GET,'setRelation');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function destoryAction()
	{
		if(!empty($_POST['id']) && !empty($_POST['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Relation',$_GET,'destroyRelation');
		}
		jsonReturn($this->_code,$this->_data);
	}


	public function followingAction()
	{
		if(!empty($_GET['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Relation',$_GET,'getFollowing');
		}
		jsonReturn($this->_code,$this->_data);
	}

	public function followerAction()
	{
		if(!empty($_GET['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Relation',$_GET,'getFollower');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function followingidsAction()
	{
		if(!empty($_GET['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Relation',$_GET,'getFollowingIds');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	public function followeridsAction()
	{
		if(!empty($_GET['uid'])){
			$this->_code = Common_Errorcode::SUCCESS;
			$this->_data = Q('Business_Relation',$_GET,'getFollowerIds');
		}
		jsonReturn($this->_code,$this->_data);
	}
	
	
	
}