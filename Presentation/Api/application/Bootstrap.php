<?php
/**
 * BOOT STRAP FILE FOR PROJECT
 *  
 */

class BootStrap extends Yaf_Bootstrap_Abstract {

	public function _initConfig() {
		//get all config from ini file,then to set to C function
		$config = new Yaf_Config_Ini(CONF_PATH.'/'.RUN_MODE.'/application.ini');
		C($config->toArray());
		
		//set productdbname
		$productdbname = C('productdbname.name');

		if(!empty($productdbname) && !defined("PRODUCTDBNAME")) {
			define("PRODUCTDBNAME",$productdbname);
		}else{
		    throw new Exception("PLEASE SET PRODUCT DB NAME");
		}
		
	}
}
