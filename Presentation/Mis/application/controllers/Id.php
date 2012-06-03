<?php


class IdController extends Yaf_Controller_abstract {


	public function indexAction() 
  {
     header("Content-Type:text/html;charset:utf-8;");
     exit(objid());
  }


}
