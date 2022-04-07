<?php
/*
* Контроллер основной страницы
*/
class Main extends PageController
{
	protected $title = 'Дзырдуат';
	protected $description = 'Ирон-урыссаг нывгонд дзырдуат. Дзырдуаты мидӕг аллы ныхасӕн дӕр ис тӕлмац уырыссаг ӕвзагыл, бирӕтӕн дзурыны ахаст, нывтӕ ӕмӕ дӕнцӕгтӕ.';
	
    protected function index($data = [])
    {
		// Закрываем прямой вызов контроллера в URL
		if ($this->request->getPart(0) == 'main') {
			return $this->page404();
		}
		
		if (isset($data['finded'])) {
			$w = new Word;
			$trans = $w->getWordByPage($data['finded']['page']);
			
			// Заносим слово в заголовок
			$this->title = $trans['word'];
			if ($trans['description'] != '') {
				$this->title .= ' ' . $trans['description'];
			}
			$translates = $trans['translates'];
			
			// выводим переводы в description..
			$description = '';
			for( $i = 0; $i < count($translates); $i++ ) {
				$words = $translates[$i]['words'];
				for ( $j = 0; $j < count($words) - 1; $j++ ) {
					$description .= $words[$j]['translate'] . ', ';
				}
				$description .= $words[count($words) - 1]['translate'] . '; ';
				
				$examples = $translates[$i]['examples'];
				if (count($examples) != 0) {
					for ( $j = 0; $j < count($examples) - 1; $j++ ) {
						$description .= strip_tags($examples[$j]['example']) . ', ';
					}
					$description .= strip_tags($examples[count($examples) - 1]['example']) . '; ';
				}
			}
			$this->description = trim($description);
			
			// Если у слова есть фото, выводим его в meta-заголовок
			if ($trans['image'] != '') {
				$this->image = $trans['image'];
			}
		}
		
		$words = new Word;
		$data['page_count'] = $words->getCount();
		
		$mobileDetector = new Mobile_Detect;
		if ($mobileDetector->isMobile()) {
			$this->mainTemplate = 'mobile';
		}
		
		return $this->template($this->mainTemplate, $data);
    }
}
