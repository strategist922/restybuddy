<?php
require_once "PHPUnit/Framework/TestSuite.php";


class serviceSuite extends PHPUnit_Framework_TestSuite {

	public function __construct()
	{

		define("APP_PATH", ROOT_PATH."/Presentation/Api");
		define("RUN_MODE",'product');
		require_once dirname(dirname(dirname(__FILE__)))."/Env/env.inc.php";
		define("ROOT_URL",'http://s.dianxing.cn');
		$application = new Yaf_Application(CONF_PATH . DIRECTORY_SEPARATOR. RUN_MODE. DIRECTORY_SEPARATOR . "application.ini");
		$application->bootstrap();

		require_once './Service/HotwordsTest.php';

		$this->setName('Test Suite');

		$this->addTestSuite('WordexpandTest');

	}

	public static function suite() {
		return new self();
	}
}