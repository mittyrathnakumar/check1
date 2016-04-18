<?php

/** 
 * Path to important application folders 
 */

define('APPLICATION_ROOT', 'http://'.$_SERVER['HTTP_HOST']."/ART/");

define('APPLICATION_JS', APPLICATION_ROOT.'/web/js');

define('APPLICATION_CSS', APPLICATION_ROOT.'/web/css');

define('APPLICATION_IMAGES', APPLICATION_ROOT.'/web/images');

define('APPLICATION_VENDOR', APPLICATION_ROOT.'/web/assets/vendor');

define('TESTDATAREQUEST_FILEUPLOAD_FOLDER', $_SERVER['HTTP_HOST']."/uploads");
