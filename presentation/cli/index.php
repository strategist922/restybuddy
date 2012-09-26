<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xinqiyang
 * Date: 2012/09/26
 * Time: 13:08
 * To change this template use File | Settings | File Templates.
 */

    define("APP_PATH",  dirname(__FILE__));
    require_once dirname(dirname(APP_PATH))."/env/env.inc.php";
    $app  = new Yaf_Application(CONF_PATH."/application.ini");
    $app->bootstrap();

    //get params then run
    $functions = array(
        'saveStreamToDb',
    );

    if(!empty($_SERVER['argv']) && count($_SERVER['argv']) >= 2) {
        $argv0 = $_SERVER['argv'][1];
        if(!empty($argv0) && in_array($argv0, $functions))
        {
            $b = new Business_Backend();
            if(method_exists($b, $argv0)){
                if(isset($_SERVER['argv'][2])) {
                    $argv1 = $_SERVER['argv'][2];
                    echo $b->$argv0($argv1);
                }else{
                    echo $b->$argv0();
                }
            }
            echo "\n";
        }else{
                echo "Useage:php index.php functionname params \n";

        }
    }