<?php
/*
*	Класс для работы со словами из базы
*/
class Word
{
    protected $db;
	
	const FIND_WORD_MAX_LENGTH = 25;
	
	public function __construct(){
		$this->db = DB::getInstance();
	}
	
	/* Количество слов в базе */
	public function getCount() 
	{
		$query = "SELECT COUNT(*) as cnt FROM nyhas";
		$count = $this->db->query($query);
		return $count[0]['cnt'];
	}
	
	/* Поиск слова по номеру страницы */
	public function getWordByPage($page) 
	{
		$query = "SELECT * FROM nyhas WHERE page = $page";
		$word = $this->db->query($query);
		if (count($word) != 1) {
			return null;
		}
		$word = $word[0];
		$id = $word['id'];
		
		// Подтягиваем переводы и примеры
		$word['translates'] = [];
		$query = "SELECT id FROM translates WHERE nyhas_id = $id ORDER BY sort";
		$translates = $this->db->query($query);
		foreach($translates as $translate) {
			$tid = $translate['id'];
			$query = "SELECT * FROM translate_words WHERE translate_id = $tid ORDER BY sort";
			$words = $this->db->query($query);
			
			$query = "SELECT * FROM examples WHERE translate_id = $tid ORDER BY sort";
			$examples = $this->db->query($query);
			$word['translates'][] = [
				'id' => $tid, 
				'words' => $words, 
				'examples' => $examples
			];
		}
		
		return $word;
	}
	
	/* Ищет слова в базе по строке поиска */
	public function findWords($find) 
	{
		if (!$this->validateFind($find)) {
			return [];
		}
		$limit = Config::getInstance()->get('find_item_count');
		
		$query = "SELECT word, page FROM nyhas WHERE word LIKE '{$find}%' ORDER BY page LIMIT 0, $limit";
		
		$words = $this->db->query($query);
		if (count($words) > 0) {
			return $words;
		} else {
			// Поиск в переводах
			$query = "SELECT nyhas.word, nyhas.page, translate_words.translate 
						FROM translate_words 
							LEFT JOIN
							 translates
							ON translates.id = translate_words.translate_id
							LEFT JOIN 
							 nyhas
							ON nyhas.id = translates.nyhas_id
						WHERE translate LIKE '$find%'
						GROUP BY nyhas.word
						ORDER BY nyhas.page
						LIMIT 0, $limit";
			
			$words = $this->db->query($query);
			if (count($words) > 0) {
				return $words;
			}

			// Поиск в примерах
			// идёт по началу слов в примере
			$query = "SELECT nyhas.word, nyhas.page, translate_words.translate 
						FROM examples 
							LEFT JOIN 
								translates
							ON translates.id = examples.translate_id
							LEFT JOIN
								nyhas
							ON nyhas.id = translates.nyhas_id
							LEFT JOIN 
								translate_words
							ON translates.id = translate_words.translate_id
						WHERE example LIKE '% $find%' OR example LIKE '$find%'
						GROUP BY nyhas.word
						ORDER BY nyhas.page
						LIMIT 0, $limit";
			
			$words = $this->db->query($query);
			if (count($words) > 0) {
				return $words;
			}
			// Если простой поиск не нашёл совпадений, 
			// то можно попробовать поиск близких по написанию слов
			// Алгоритм надо придумать...
		}
		return [];
	}
	
	/* Поиск слова */
	public function getWord($word) 
	{
		if (!$this->validateFind($word)) {
			return null;
		}
		$query = "SELECT word, page, image, sound FROM nyhas WHERE word = '$word'";
		$words = $this->db->query($query);
		if (count($words) == 1) {
			return $words[0];
		}
		return null;
	}

	/* Добавление слова в базу */
	public function insertWord($data) 
	{
		$word = $data['word'];
		$translit = $data['translit'];
		$user_id = $data['user_id'];
		$description = $data['description'];
		$translates = $data['translates'];
		
		if (!$this->validateFind($word)) {
			return false;
		}
		
		$query = "INSERT INTO nyhas (word, translit, description, user_id) 
				VALUES ('$word', '$translit', '$description', $user_id)";
		
		
		$word_id = $this->db->insert($query);
		
		if (!$word_id) {
			return false;
		}
		
		$sort = 10;
		foreach($translates AS $translate) {
			$query = "INSERT INTO translates (nyhas_id, sort)
						VALUES ($word_id, $sort)";
			$translate_id = $this->db->insert($query);
			if (!$translate_id) {
				return false;
			}
			$sort += 10;
			
			$tw_sort = 10;
			if (isset($translate['words']) && count($translate['words']) > 0) {
				foreach($translate['words'] AS $tword) {
					$query = "INSERT INTO translate_words (translate_id, translate, sort)
							VALUES ($translate_id, '$tword', $tw_sort)";
					$this->db->insert($query);
					$tw_sort += 10;
				}
			} else {
				return false;
			}
			
			$ex_sort = 10;
			if (isset($translate['examples']) && count($translate['examples']) > 0) {
				foreach($translate['examples'] AS $example) {
					$query = "INSERT INTO examples (translate_id, example, sort)
							VALUES ($translate_id, '$example', $ex_sort)";
					$this->db->insert($query);
					$ex_sort += 10;
				}
			}
		}
		
		$this->updatePageNumbers();
		
		return true;
	}
	
	/* Изменение слова в базе */
	public function chageWord($item, $r_translates, $r_words, $r_examples) 
	{
		$query = "UPDATE nyhas SET 
					word = '{$item['word']}',
					translit = '{$item['translit']}',
					description = '{$item['description']}',
					dt_change = CURRENT_TIMESTAMP
				  WHERE id = {$item['id']}";
				  
		// Измнение самого слова
		$this->db->query($query);
		
		// Добавление переводов
		foreach ($item['translates'] as $translate) {
			$tid = $translate['id'];
			if ($tid) {
				$query = "UPDATE translates 
							SET sort = {$translate['sort']} 
							WHERE id = $tid";
				$this->db->query($query);
			} else {
				$query = "INSERT INTO translates (nyhas_id, sort)
							VALUES ({$item['id']}, {$translate['sort']})";
							
				// Получить id вставленной записи, она понадобиться для следующих запросов
				$tid = $this->db->insert($query);
			}
			
			// Переводы
			foreach ($translate['words'] as $word) {
				if ($word['id']) {
					$query = "UPDATE translate_words 
								SET translate = '{$word['value']}',
									sort = {$word['sort']}
								WHERE id = {$word['id']}";
					$this->db->query($query);
				} else {
					$query = "INSERT translate_words (translate_id, translate, sort)
								VALUES ({$tid}, '{$word['value']}', {$word['sort']})";
					$this->db->insert($query);
				}
			}
			
			// Примеры
			if ($translate['examples'])
			foreach ($translate['examples'] as $example) {
				 if ($example['id']) {
					$query = "UPDATE examples 
								SET example = '{$example['value']}',
									sort = {$example['sort']}
								WHERE id = {$example['id']}";
					$this->db->query($query);
				} else {
					$query = "INSERT examples (translate_id, example, sort)
								VALUES ({$tid}, '{$example['value']}', {$example['sort']})";
					$this->db->insert($query);
				}
			}
		}
		
		// Удаление переводов и примеров
		if ($r_translates) {
			foreach($r_translates as $tr_id) {
				$query = "DELETE FROM translates WHERE id = $tr_id";
				$this->db->query($query);
			}
		}
		if ($r_words) {
			foreach($r_words as $w_id) {
				$query = "DELETE FROM translate_words WHERE id = $w_id";
				$this->db->query($query);
			}
		}
		if ($r_examples) {
			foreach($r_examples as $e_id) {
				$query = "DELETE FROM examples WHERE id = $e_id";
				$this->db->query($query);
			}
		}
		
		$this->updatePageNumbers();
		
		return 'ok';
	}
	
	/* Установка картинки слова */
	public function setWordImage($word_id, $image_file_name) 
	{
		if (!$this->validateId($word_id)) {
			return false;
		}
		$query = "UPDATE nyhas SET 
					image = '{$image_file_name}',
					dt_change = CURRENT_TIMESTAMP
				  WHERE id = {$word_id}";
		$this->db->query($query);
		return true;
	}
	
	/* Установка озвучки слова */
	public function setWordSound($word_id, $sound_file_name) 
	{
		if (!$this->validateId($word_id)) {
			return false;
		}
		$query = "UPDATE nyhas SET 
					sound = '{$sound_file_name}',
					dt_change = CURRENT_TIMESTAMP
				  WHERE id = {$word_id}";
		$this->db->query($query);
		return true;
	}
	
	/* Пересчитывает номера страниц для слов */
	protected function updatePageNumbers() {
		$query = "UPDATE nyhas
					SET page = (@rownum := 1 + @rownum)
					WHERE 0 = (@rownum:=0)
					ORDER BY translit";
		$this->db->insert($query);
	}

	/* Валидация поисковой строки */
	protected function validateFind($find) 
	{
		if (strlen($find) > self::FIND_WORD_MAX_LENGTH) return false;
		if (!preg_match("/^[ӕабвгдеёжзийклмнопрстуфхцчшщъыьэюяÆАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ\s]+[-]{0,1}[ӕабвгдеёжзийклмнопрстуфхцчшщъыьэюяÆАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ\s]*$/i", $find)) {
			return false;
		}
		return true;
	}
	
	/* Валидация id (число) */
	protected function validateId($id) 
	{
		return is_numeric($id);
	}
}
