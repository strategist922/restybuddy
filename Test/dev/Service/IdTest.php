<?php

class IdTest extends PHPUnit_Framework_TestCase
{

  protected $_url = '';

  public function __construct()
  {
    $this->_url = ROOT_URL.'/id';
  }

	public function testgetid()
	{
		$data = Common_Curl::get($this->_url);
		$this->assertEquals(true,is_numeric($data));
  }


  public function testmultyget()
  {
    $arr = array();
    $n = 10000;
    debug_start('id');
    for($i=0;$i<$n;$i++)
    {
      $id = Common_Curl::get($this->_url);
      $arr[] = $id;
    }
    debug_end('id');
    $count = count($arr);
    $this->assertEquals(true,$n == $count);
  }


}
