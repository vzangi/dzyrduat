<?php

class Admin extends PageController
{
	protected $title = 'Дзырдуаты хицау';
	protected $user = null;
	protected $m_user;
	protected $isAuth = false;
	protected $max_upload_image_size = 524000;
	protected $max_upload_sound_size = 262000;
	
	public function __construct(Request $request, $config = null) {
		parent::__construct($request, $config);
		$this->m_user = new User;
		$this->isAuth = $this->m_user->isAuthed();
		if ($this->isAuth) {
			$this->user = $this->m_user->get();
		}
		
		$fileSize = Config::getInstance()->get('max_upload_image_size');
		if ($fileSize) {
			$this->max_upload_image_size = $fileSize;
		}
		
		$fileSize = Config::getInstance()->get('max_upload_sound_size');
		if ($fileSize) {
			$this->max_upload_sound_size = $fileSize;
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
				$sitemap = new SitemapMaker;
				$sitemap->makeSitemap();
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

	public function changeImage() {
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
			return $this->template( 'changeimage', $data );
		} else {
			return $this->page404();
		}
	}
	
	public function uploadImage() {
		if (!$this->isAuth) {
			return $this->page404();
		}
		
		if (!isset($_POST['nyhas_id'])) {
			echo 'id not found';
			return;
		}
		$nyhas_id = $_POST['nyhas_id'];
				
		if (!isset($_POST['nyhas_word'])) {
			echo 'word not found';
			return;
		}
		$nyhas_word = $_POST['nyhas_word'];
		
		if ($_FILES["kam"]["error"] != 0) {
			echo "error";
			return;
		}
		
		if ($_FILES["kam"]["size"] > $this->max_upload_image_size) {
			echo "too large";
			return;
		}
		
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["kam"]["tmp_name"]);
			if($check === false) {
				echo "file is not image";
				return;
			}
		}
		
		
		$target_dir = "asserts/uploads/";
		$file_name = basename($_FILES["kam"]["name"]);
		$target_file = $target_dir . $file_name;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
		move_uploaded_file($_FILES['kam']['tmp_name'], $target_file);
		
		$words = new Word;
		
		$words->setWordImage($nyhas_id, $file_name);
		
		$word = $words->getWord($nyhas_word);
		
		if ($word) {
			$this->redirect('/admin/changeImage/'.$word['word']);
		} else {
			echo 'find error';
		}
	}

	public function removeImage(){
		if (!$this->isAuth) {
			return $this->page404();
		}
		
		if (!isset($_POST['nyhas_id'])) {
			echo 'id not found';
			return;
		}
		$nyhas_id = $_POST['nyhas_id'];
		
		if (!isset($_POST['nyhas_word'])) {
			echo 'word not found';
			return;
		}
		$nyhas_word = $_POST['nyhas_word'];
		
		$words = new Word;
		
		$words->setWordImage($nyhas_id, '');
		
		$word = $words->getWord($nyhas_word);
		
		if ($word) {
			$this->redirect('/admin/changeImage/'.$word['word']);
		} else {
			echo 'find error';
		}
	
	}

	public function changeSound() {
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
			return $this->template( 'changesound', $data );
		} else {
			return $this->page404();
		}
	}

	public function uploadSound() {
		if (!$this->isAuth) {
			return $this->page404();
		}
		
		if (!isset($_POST['nyhas_id'])) {
			echo 'id not found';
			return;
		}
		$nyhas_id = $_POST['nyhas_id'];
				
		if (!isset($_POST['nyhas_word'])) {
			echo 'word not found';
			return;
		}
		$nyhas_word = $_POST['nyhas_word'];
		
		if ($_FILES["zal"]["error"] != 0) {
			echo "error";
			return;
		}
		
		if ($_FILES["zal"]["size"] > $this->max_upload_sound_size) {
			echo "too large";
			return;
		}
			
		$target_dir = "asserts/sounds/";
		$file_name = basename($_FILES["zal"]["name"]);
		$target_file = $target_dir . $file_name;
		$soundFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
		if ($soundFileType != 'mp3') {
			echo 'bad sound type';
			return;
		}
		
		move_uploaded_file($_FILES['zal']['tmp_name'], $target_file);
		
		$words = new Word;
		
		$words->setWordSound($nyhas_id, $file_name);
		
		$word = $words->getWord($nyhas_word);
		
		if ($word) {
			$this->redirect('/admin/changeSound/'.$word['word']);
		} else {
			echo 'find error';
		}
	}
	
	public function removeSound(){
		if (!$this->isAuth) {
			return $this->page404();
		}
		
		if (!isset($_POST['nyhas_id'])) {
			echo 'id not found';
			return;
		}
		$nyhas_id = $_POST['nyhas_id'];
		
		if (!isset($_POST['nyhas_word'])) {
			echo 'word not found';
			return;
		}
		$nyhas_word = $_POST['nyhas_word'];
		
		$words = new Word;
		
		$words->setWordSound($nyhas_id, '');
		
		$word = $words->getWord($nyhas_word);
		
		if ($word) {
			$this->redirect('/admin/changeSound/'.$word['word']);
		} else {
			echo 'find error';
		}
	
	}
}
