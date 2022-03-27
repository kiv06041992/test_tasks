<?php
/**
 * Класс для взаимодействия с пользователями
 * регистрация/авторизация/восстановление пароля
 */
class User {
	public 	$id;
	public 	$email;
	public 	$password;
	public 	$isAuth = false;
	private $db;
	
	public function __construct() {
		$this->db = DB::getDB();
		
		session_start();
		if ($_SESSION['user']) {
			$this->isAuth = true;
			
			$this->id 		= $_SESSION['user']['id'];
			$this->email	= $_SESSION['user']['email'];
			$this->password = $_SESSION['user']['password'];
		}
	}
	
	
	
	/**
	 * Авторизуем пользователя
	 *
	 * @param string $email  	емеил пользователя
	 * @param string $password 	пароль пользователя
	 *
	 * @retrun bool в случаи удачной авторизации возвращаем true. Иначе false
	 *
	*/
	public function auth($email, $password) {
		
		if ($email && $password) {
			$db = $this->db->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
			$db->execute(['email' => $email,
						'password' => $password]);
						
			$user = $db->fetch(PDO::FETCH_ASSOC);
			//если получили id пользователя - значит авторизовались
			if ($user['id']) {
				
				
				session_start();
				$_SESSION['user']['id'] 		= $this->id 		= $user['id'];
				$_SESSION['user']['email'] 		= $this->email 		= $user['email'];
				$_SESSION['user']['password'] 	= $this->password 	= $user['password'];
				
				$this->isAuth = true;
				
				return true;
			} 
		}
		
		return false;
	}
	
}