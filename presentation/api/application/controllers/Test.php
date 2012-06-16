<?php


class TestController extends Yaf_Controller_abstract {


	public function indexAction()
	{
		if(!empty($_POST)) {
			//var_dump($_POST);
			Common_Function::jsonReturn($_POST);
		}
		// echo "aaa";
		//exit("testbbb");
		//Yaf_Dispatcher::getInstance()->disableView();
	}

}
