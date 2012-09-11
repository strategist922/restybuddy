<?php

class VenderTest extends PHPUnit_Framework_TestCase
{



  public function testgetbaidupage()
  {	
    $url = "http://www.baidu.com";
    $html = file_get_html($url);
    echo $html;
    $r = true;
    $this->assertEquals(true,$r);

  }



}
