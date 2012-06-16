<?php
// +----------------------------------------------------------------------
// | Buddy Framework 
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://buddy.woshimaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xinqiyang <xinqiyang@gmail.com>
// +----------------------------------------------------------------------
/**
 * scws  word spilte class
 * This is open source extension of php
 * official site:http://www.ftphp.com/scws
 * @author xinqiyang
 *
 */
class Matchmaker_Scws
{
  /**
   * get tops content of the input, get number of word from return
   * @param string $content input content
   * @param int $tops number of words
   */
  public static function keywords($content,$type='',$tops=10)
  {
    $obj = scws_open();
    if(empty($type)) {
      throw_exception("Load dictionary node: type is empty,get dic file Error");
    }
    $dicpath = CONF_PATH."/dictionary/$type.txt";
    $rules = CONF_PATH.'/dictionary/rules.utf8.ini';
    scws_set_charset($obj,'utf8');
    scws_set_dict($obj,$dicpath,SCWS_XDICT_TXT);
    scws_set_duality($obj,true);
    scws_set_rule($obj,$rules);
    scws_send_text($obj,$content);
    return scws_get_tops($obj,$tops);
  }

  /**
   * get the special words judge
   * Enter description here ...
   * @param unknown_type $content
   * @param unknown_type $type
   */
  public static function wordtype($content,$type='')
  {
    $obj = scws_new();
    if(empty($type)) {
      throw_exception("Load dictionary node:type is empty, dic file Error");
    }
    $dicpath = CONF_PATH.'/dictionary/'.$type;
    error_log($dicpath);
    try {
      $obj->set_charset('utf8');
      if(substr($type,-3,3) == 'txt') {
        $obj->set_dict($dicpath,SCWS_XDICT_TXT);
      }else{
        $obj->set_dict($dicpath,SCWS_XDICT_XDB);
      }
      $obj->send_text($content);
      $result = $obj->get_result();
      $obj->close();
    }catch (Exception $e) {
      throw $e;
    }
    return $result;
  }


}
