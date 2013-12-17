<?php

// uncomment the following to define a path alias
//Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../modules/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return array(
    'aliases' => array(
        'bootstrap' => dirname(__FILE__) . '/../modules/bootstrap',
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Dispatch',
    'defaultController' => 'home', 
    'theme' => 'bootstrap', // requires you to copy the theme under your themes directory
    // preloading 'log' component
    'preload' => array('log', 'bootstrap'),
    // autoloading model and component classes
    'import' => array(
        'bootstrap.*',
        'bootstrap.components.*',
        'bootstrap.models.*',
        'bootstrap.controllers.*',
        'bootstrap.helpers.*',
        'bootstrap.widgets.*',
        'bootstrap.extensions.*',
        'application.modules.*',
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        'bootstrap' => array(
            'class' => 'bootstrap.BootStrapModule'
        ),
        
        'gii' => array(
            'generatorPaths' => array('bootstrap.gii'),
            'class' => 'system.gii.GiiModule',
            'password' => 'yaa',
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    // uncomment the following to enable the Gii tool
    
       /*
      'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'Enter Your Password Here',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1'),
      ),
     */
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser'
        ),
        'bsHtml' => array(
            'class' => 'bootstrap.components.BSHtml'
        ),
        // uncomment the following to enable URLs in path-format
        /* */
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
        
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        /*
          'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=testdrive',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          ),
         */
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'ldap' => array(
            'host' => 'fbwndc22.cats.gwu.edu',
            'port' => 389,
            'domain' => "@cats.gwu.edu",
            'oustaff' => 'staff', // such as "people" or "users"
            'oupeople' => 'people', // such as "people" or "users"
            'outest' => 'test1',
            'dc' => array('cats', 'gwu', 'edu'),
        ),
        // this is used in contact page
        'adminEmail' => 'scaperoth@gmail.com',
    ),
);