<?php
class DB {
	static private $dbInstance;
	
	static public function getDB() {
		if (!self::$dbInstance) {
			self::$dbInstance = new PDO('mysql:host=localhost;dbname=slotegrator', 'root', '');
		}
		return self::$dbInstance;
	}
}