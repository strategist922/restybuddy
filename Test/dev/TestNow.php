<?php
require_once "PHPUnit/Framework/TestSuite.php";


class TestNow extends PHPUnit_Framework_TestSuite {

  public function __construct()
  {
  	define("RUN_MODE",'dev');
  	define("APP_PATH", dirname(dirname(dirname(__FILE__)))."/Presentation/Api");
  	require_once dirname(dirname(dirname(__FILE__)))."/Env/env.inc.php";
  	define("ROOT_URL",'http://api.dev.woshimaijia.com');
    $application = new Yaf_Application(CONF_PATH . "/application.ini");
    $application->bootstrap();

    require_once dirname(__FILE__).'/Service/IdTest.php';	
    require_once dirname(__FILE__).'/Library/VenderTest.php';
    require_once dirname(__FILE__).'/Library/ScwsTest.php';
    require_once dirname(__FILE__).'/Service/AdTest.php';
    require_once dirname(__FILE__).'/Library/RemindTest.php';
    require_once dirname(__FILE__).'/Library/TagsTest.php';
    
    //require_once dirname(__FILE__).'/Service/UserviewTest.php';
    
    $this->setName('Test Suite');


    //$this->addTestSuite('BusinessNearplaceTest');
    //$this->addTestSuite('TimeTest');
    //$this->addTestSuite('VenderTest');
    //$this->addTestSuite('ScwsTest');
    //$this->addTestSuite('AdTest');
    //$this->addTestSuite('RemindTest');
    $this->addTestSuite('TagsTest');
    
  }

  public static function suite() {
    return new self();
  }



}
