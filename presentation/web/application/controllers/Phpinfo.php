<?php


class PhpinfoController extends Yaf_Controller_Abstract {

   public function indexAction()
   {
	phpinfo();
	die;
   }


  public function testAction()
	{
		exit("TTTT");
	}

}
