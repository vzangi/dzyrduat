<?php
/* 
*	Контроллер для поиска слов 
*/
class Find extends PageController
{
	protected function index($data = [])
    {
		return $this->page404();
    }
	
	public function __call($find, $attributes) {
		$words = new Word;
		$finded_words = $words->findWords( trim(urldecode($find)) );
		$this->sendJSON($finded_words);
	}
}
