<?php
require_once "PHPUnit/Framework/TestSuite.php";


class serviceSuite extends PHPUnit_Framework_TestSuite {

	public function __construct()
	{
		define("RUN_MODE",'dev');
		define("APP_PATH", ROOT_PATH."/Presentation/Api");
		require_once dirname(dirname(dirname(__FILE__)))."/Env/env.inc.php";
		define("ROOT_URL",'http://api.dev.woshimaijia.com');
		$application = new Yaf_Application(CONF_PATH . DIRECTORY_SEPARATOR. RUN_MODE. DIRECTORY_SEPARATOR . "application.ini");
		$application->bootstrap();

		require_once dirname(__FILE__).'/Library/ShardingRedisTest.php';
		require_once dirname(__FILE__).'/Library/ShardingMysqliTest.php';
		require_once dirname(__FILE__).'/Library/BusinessNearplaceTest.php';

		$this->setName('Test Suite');

		$this->addTestSuite('BusinessNearplaceTest');
	}

	public static function suite() {
		return new self();
	}
}
