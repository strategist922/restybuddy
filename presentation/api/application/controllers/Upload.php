<?php
class UploadController extends Yaf_Controller_abstract
{
	public function indexAction()
	{
		$u = new Common_Upload();
		$data = $u->upload();
		$info = $u->getErrorMsg();
		jsonReturn(Common_Errorcode::SUCCESS,$data,$info);
	}
}