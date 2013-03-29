<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

defined('APP_URL') || define('APP_URL', 'http://im.gogins.ru');
defined('APP_FILES') || define('APP_FILE', 'http://im.gogins.ru/protected/data/files/');

// remove the following line when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);

Yii::createWebApplication($config)->run();




