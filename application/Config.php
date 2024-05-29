<?php

/*
 * -------------------------------------
 *  Config.php
 * -------------------------------------
 */


define('BASE_URL', 'https://192.168.1.207:8080/ORACLEOP/');
define('BASE_ROOT',$_SERVER['DOCUMENT_ROOT'].'/ORACLEOP/');
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_METHOD', 'index');
define('DEFAULT_LAYOUT', 'default');

/* Conexion oracle */
/*define('DB_USER', 'jpena@biomont.com.pe');
define('DB_PASS', 'Pena140721');
define('DB_NAME', 'NetSuite');*/
/*define('DB_USER', 'fcastro@biomont.com.pe');
define('DB_PASS', 'Biomont2022#');*/
define('DB_USER', 'gcrisolo@biomont.com.pe');
define('DB_PASS', 'NetSuite01');

define('DB_NAME', 'NetSuite');

define("LAST_VERSION_SOURCE",strtotime("2021-03-04 09:24:11"));

define('MAIL_APP_HOST','outlook.office365.com');
//define('MAIL_APP_USER','jpena@biomont.com.pe');
define('MAIL_APP_USER','notificaciones@biomont.com.pe');
//define('MAIL_APP_PASSWORD','Pena140721');
define('MAIL_APP_PASSWORD','Notifi2020');


define('DB_HOST_mysql', 'localhost');
define('DB_USER_mysql', 'root');
define('DB_PASS_mysql', '');
define('DB_NAME_mysql', 'bd_cant_gen');
define('DB_ENGINE_mysql','mysqli');