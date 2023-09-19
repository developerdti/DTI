<?php

#HOST running aplication localhost:8083
define('HOST',$_SERVER['HTTP_HOST']);

#Path of the domain aplication "http://localhost8083/DTI"
define('DOMAIN_PATH',$_SERVER['REQUEST_SCHEME'].'://'.HOST.'/DTI');

#Path where the aplication is running "http://localhost8083/DTI/app"
define('APLICATION_PATH',DOMAIN_PATH.'/app');

#Path of local aplication
define('PROJECT_PATH',dirname(__DIR__,2));

#Path where controllers aplication's are stored
define('CONTROLLERS_PATH',PROJECT_PATH.'/app/controllers');

#Path where views aplication's are stored
define('VIEWS_PATH',PROJECT_PATH.'/app/views');

#Path where modesl aplication's are stored
define('MODELS_PATH',PROJECT_PATH.'/app/models');

define('DEPENDENCE_PATH', DOMAIN_PATH .'/node_modules');

define('STYLE_PATH', APLICATION_PATH .'/assets/css');

define('SCRIPT_PATH', APLICATION_PATH .'/assets/js');

define('IMAGE_PATH',DOMAIN_PATH.'/public/src/img'); 