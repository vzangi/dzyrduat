<?php
/*
* Класс для получения страницы книги по его номеру
*/
class Page extends PageController
{
	const NOMER_ERROR_MESSAGE = 'Сыфы номыр хъӕуы рвитын';
	
    public function index($data = []){
		return $this->page404(self::NOMER_ERROR_MESSAGE);
	}
	
	public function __call($page_number, $attributes)
    {
		if (!isset($page_number)) {
			return $this->page404(self::NOMER_ERROR_MESSAGE);
		}

		if (!is_numeric($page_number)) {
			return $this->page404(self::NOMER_ERROR_MESSAGE);
		}
		
		// Берем номер страницы на 2 меньше, так как первые две страницы 
		// занимают обложка и страница поиска слов (не пронумерованы)
		$page_number = floor( 1 * $page_number ) - 2;
		
		// Модель для доступа к базе слов
		$words = new Word();
		
		// Общее количество слов в книге
		$item_count = $words->getCount();
		
		// Чтобы обложка показывалась правильно, нужно чтобы слов было чётное количество
		// Если слов нечётное количество, то искуственно делаем их четным
		// В таком случае в конец просто добавиться пустая страница
		$item_count += $item_count % 2;
		
		// Предпоследняя страница с контактами автора книги
		if ($page_number == $item_count + 1) {
			return $this->render('lastpage');
		}
		
		// Последняя страница с тыльной частью обложки книги
		if ($page_number == $item_count + 2) {
			return $this->render('cover');
		}
		
		// Ищем только страницы в диапазоне от 1 до $item_count
		if ($page_number < 1 || $page_number > $item_count) {
			return $this->page404();
		}
		
		// Получение слова на указанной странице
		$word = $words->getWordByPage($page_number);
		
		// Возращаем шаблон страницы с найденным словом
		return $this->render('item', ['item' => $word]);
	}
}
