<?php
/*
* Класс для получения страницы книги по его номеру
*/
class SitemapMaker
{
	public function makeSitemap() {
		$w = new Word;
		$words = $w->getAll();
		
		$site = "https://дзырдуат.рф";
		$date = date("Y-m-d")."T15:30:01+01:00";
		
		$data = '<?xml version="1.0" encoding="UTF-8"?>';
		$data = $data."\r\n";
		$data = $data.'<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
		$data = $data."\r\n";
		$data = $data.$this->url("$site/", $date, "1.0");
		
		foreach($words as $word) {
			$data = $data.$this->url("$site/".$word['word'], $date, "1.0");
		}
		
		$data = $data."</urlset>\r\n";
		
		file_put_contents( "sitemap.xml", $data );
	}
	
	private function url( $loc, $lastmod, $priority ) {
		$t = "<url>\r\n";
		$t = $t."<loc>".$loc."</loc>\r\n";
		$t = $t."<lastmod>".$lastmod."</lastmod>\r\n";
		$t = $t."<priority>".$priority."</priority>\r\n";
		$t = $t."</url>\r\n";
		return $t;
	}
}
