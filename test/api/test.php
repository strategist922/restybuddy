<?php
require_once "PHPUnit/Framework/TestSuite.php";

class serviceSuite extends PHPUnit_Framework_TestSuite {

	public function __construct()
	{
		define("APP_PATH", ROOT_PATH."/presentation/api");
		require_once dirname(dirname(dirname(__FILE__)))."/env/env.inc.php";
		define("ROOT_URL",'http://api.dev.woshimaijia.com');
		$application = new Yaf_Application(CONF_PATH .  "/application.ini");
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
