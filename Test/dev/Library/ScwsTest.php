<?php

class ScwsTest extends PHPUnit_Framework_TestCase
{



  public function testscws()
  {	
    $words = "我是来自百度的王小强，我很喜欢百度，现在来做个测试";
    $type = 'brand';
    $result = Matchmaker_Scws::keywords($words,$type);
    var_dump($result);
    $r = true;
    $this->assertEquals(true,$r);

  }


  public function testwords()
  {	
    $words = "我是来自百度的王小强，我很喜欢百度，现在来做个测试";
    $type = 'brand';
    $type = 'dict.utf8.xdb';
    $result = Matchmaker_Scws::wordtype($words,$type);
    var_dump($result);
    $r = true;
    $this->assertEquals(true,$r);

  }



}
