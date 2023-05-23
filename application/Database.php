<?php

/*
 * -------------------------------------
 * Database.php
 * -------------------------------------
 */

class Database
{
    private static $Connection;
	private static $Connection1;
	
	public function __construct() {

		include_once BASE_ROOT.'application/config.php';
		include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';

		/*self::$Connection = ADONewConnection('odbc_oracle');
		self::$Connection->curmode = SQL_CUR_USE_DRIVER;
		self::$Connection->Connect(DB_NAME,DB_USER,DB_PASS);*/
		
		$this->open_Connection();
		$this->open_Connection1();
		 
	}
	
	public static function open_Connection(){
		if(!isset(self::$Connection)){
			try{

				include_once BASE_ROOT.'application/config.php';
				include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';

				self::$Connection = ADONewConnection('odbc_oracle');
				self::$Connection->curmode = SQL_CUR_USE_DRIVER;
				self::$Connection->Connect(DB_NAME,DB_USER,DB_PASS);

			} catch (Exception $ex) {
				print "ERROR: ".$ex->getMessage(). "<br>";
				die();
			}
		}
	}
	
	public static function open_Connection1(){
		if(!isset(self::$Connection1)){
			try{
	
				include_once BASE_ROOT.'application/config.php';
				include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';
	
				self::$Connection1 = ADONewConnection(DB_ENGINE_mysql);
				self::$Connection1->Connect(DB_HOST_mysql,DB_USER_mysql,DB_PASS_mysql,DB_NAME_mysql);
		
			} catch (Exception $ex) {
				print "ERROR: ".$ex->getMessage(). "<br>";
				die();
			}
		}
	}

	public static function close_Connection() {
		if(isset(self::$Connection)){
			self::$Connection = null;
		}
	}
	
	public static function close_Connection1() {
		if(isset(self::$Connection1)){
			self::$Connection1 = null;
		}
	}
	
	public static function get_Connection() {
		return self::$Connection;
	}
	
	public static function get_Connection1() {
		return self::$Connection1;
	}

}