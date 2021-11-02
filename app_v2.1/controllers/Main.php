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
			$this->title = $data['finded']['word'];
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
