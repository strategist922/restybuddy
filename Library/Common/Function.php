<?php

class Common_Function {

   public static function jsonReturn($code,$data='')
   {
     header("Content-Type:text/html;charset:utf-8");
     $return = array_merge(array('errorCode'=>$code),$data);
     exit(json_encode($return));
   }


}
