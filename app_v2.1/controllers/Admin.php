<?php

class Admin extends PageController
{
	protected $title = 'Дзырдуаты хицау';
	protected $user = null;
	protected $m_user;
	protected $isAuth = false;
	
	public function __construct(Request $request, $config = null) {
		parent::__construct($request, $config);
		$this->m_user = new User;
		$this->isAuth = $this->m_user->isAuthed();
		if ($this->isAuth) {
			$this->user = $this->m_user->get();
		}
		
	}
	
    public function index($data = []) {
		if (!$this->isAuth) {
			return $this->login();
		}
		$data['user'] = $this->user;
		return $this->template( 'admin', $data );
    }
	
	public function logout() {
		$this->m_user->logout();
		header("Location: /admin");
		exit;
	}

	protected function login() {
		if (isset($_POST['login']) && isset($_POST['password'])) {
			if ($this->m_user->auth($_POST['login'], $_POST['password'])) {
				$this->redirect('/admin/add');
			} else {
				$data['login_error'] = 1;
			}
		}
		return $this->template( 'login', $data );
	}
	
	public function add() {
		if (!$this->isAuth) {
			return $this->login();
		}
		
		if (isset($_POST['data'])) {
			$data = $_POST['data'];
			
			$word = new Word;
			if ($word->insertWord($data)) {
				return 'ok';
			} else {
				return 'error';
			}
		}
		$data['user'] = $this->user;
		return $this->template( 'addnyhas', $data );
	}
	
	public function change() {
		if (!$this->isAuth) {
			return $this->login();
		}
		
		$word = $this->request->getPart(2);
		if (!$word) {
			return $this->page404();
		}
		$words = new Word;
		$finded = $words->getWord( urldecode( $word ) );
		if ($finded) {
			$data['finded'] = $words->getWordByPage($finded['page']);
			$data['user'] = $this->user;
			//echo "<pre>";print_r($data['finded']);echo "</pre>";
			return $this->template( 'changenyhas', $data );
		} else {
			return $this->page404();
		}
	}
	
	public function tchange(){
		if (!$this->isAuth) {
			return $this->page404();
		}
		
		if (!$_POST['data']) {
			return $this->page404();
		}
		
		//print_r($_POST['data']); return;
		
		$m_words = new Word;
		return $m_words->chageWord(
			$_POST['data'],
			$_POST['r_translates'],
			$_POST['r_words'],
			$_POST['r_examples']
		);
	}
}
