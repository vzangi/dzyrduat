<?php
/* 
* Абстрактный класс представляющий функциональность контроллера сраницы
*/
abstract class PageController
{
    protected $mainTemplate;
    protected $contentView;
    protected $request;
    protected $title;
    protected $description;
    protected $keywords;
    protected $image;

    const DEFAULT_METHOD = 'veryStrongIndexCaller';
    const PAGE_NOT_FOUND_METHOD = 'page404';
	const DEF_404_MESSAGE = 'Ахӕм сыф чиныджы нӕй';
	const VIEWS_FOLDER = 'views/';

    public function __construct(Request $request, $config = null)
    {
        $this->mainTemplate = 'main';
		$this->request = $request;
    }

	/* Метод, вызываемый котнтроллером, если другой явно не указан */
    abstract protected function index($data = []);

	/* Вызов метода по умолчанию */
    public function veryStrongIndexCaller($data = [])
    {
        return $this->index($data);
    }

	/* Ответ с кодом 404 для не найденных страниц */
    public function page404($message = null)
    {
		$data = [];
		$this->title = self::DEF_404_MESSAGE;
		if (!is_null($message)) {
			$this->title = $message;
		} 
        header("HTTP/1.1 404 Not Found");
        return $this->template('404');
    }

	/* Вызов динамических методов */
    public function __call($method, $attributes)
    {
		return $this->page404(); // По умолчанию запрещен
    }

	 /* Функция - шаблонизатор */
    protected function render($fileName, $vars = array())
    {
        // Установка переменных для шаблона.
        foreach ($vars as $k => $v) {
            $$k = $v;
        }

        // Генерация HTML в строку.
        ob_start();
        include INC_FOLDER . self::VIEWS_FOLDER . $fileName . '.php';
        return ob_get_clean();
    }

	/* Фызов шаблонизатора с утсановкой основных данных */
    protected function template($view, $data = [])
    {
        $data['title'] = $this->title;
        $data['description'] = $this->description;
        $data['keywords'] = $this->keywords;
        $data['image'] = $this->image;

        return $this->render($view, $data);
    }
	
	/* Отправка ответа в формате JSON */
    protected function sendJSON($data) 
	{
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
	}
	
	/* Редирект на любой URL */
	protected function redirect($url) {
		$this->request->redirect($url);
	}
}
