<?php


class IndexController extends AppBaseController {
	
	public function init() {
		$this->_init();
	}
	
	public function indexAction()
	{
		$this->assign('title','i am title');
		$this->assign('body','i am  mis body');
		$this->renderOut();
	}
	 
	public function mainAction()
	{
		
	}

}
