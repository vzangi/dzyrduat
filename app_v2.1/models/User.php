<?php
/*
*	Класс для работы со словами из базы
*/
class User
{
    protected $db;
		
	public function __construct(){
		session_start();
		$this->db = DB::getInstance();
	}
	
	public function isAuthed() {
		return isset($_SESSION['authorized']);
	}
	
	public function auth($login, $password) {
		if ($this->isValid($login) && $this->isValid($password)) {
			$passHash = md5($password);
			$query = "SELECT id, name, login FROM users WHERE password = '$passHash' AND login = '$login'";
			$user = $this->db->query($query);
			if (count($user) > 0) {
				$_SESSION['authorized'] = true;
				$_SESSION['user'] = $user[0];
				$this->updateLastLoginTime();
				return true;
			}
		} 
		return false;
	}
	
	public function get(){
		if ($this->isAuthed()) {
			return $_SESSION['user'];
		}
		return null;
	}
	
	public function logout(){
		unset($_SESSION['authorized']);
		unset($_SESSION['user']);
	}
	
	/* Обновление даты и времени поледней авторизации */
	protected function updateLastLoginTime() {
		$user = $this->get();
		if (!$user) {
			return false;
		}
		$userId = $user['id'];
		$query = "UPDATE `users` SET `lastauth`= CURRENT_TIMESTAMP WHERE id = $userId";
		$this->db->query($query);
		return true;
	}
	
	/* Проверка на валидность пришедших данных (логина и пароля)*/
	protected function isValid($text) {
		return preg_match("/^[a-zA-z0-9]+$/i", $text);
	}
}
