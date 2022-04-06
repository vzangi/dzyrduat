<?php
/*
* Класс для созания xml карты сайта
*/
class Sitemap extends PageController
{
	public function index($data = []){
		$m_user = new User;
		$isAuth = $m_user->isAuthed();
		if ($isAuth) {
			$sitemap = new SitemapMaker;
			$sitemap->makeSitemap();
		} else {
			return $this->page404("error");
		}
	}
}
