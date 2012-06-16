<?php
/**
 * BOOT STRAP FILE FOR PROJECT
 *
 */

class BootStrap extends Yaf_Bootstrap_Abstract {

	public function _initConfig() {
		//get all config from ini file,then to set to C function
		$config = new Yaf_Config_Ini(CONF_PATH.'/application.ini');
		C($config->toArray());
		Yaf_Dispatcher::getInstance()->disableView();
	}
	 
}
