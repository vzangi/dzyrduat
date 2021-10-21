<?php
/*
 * Модель, представляющая собой веб-приложение 
 */
class Application
{
    use SingletonTrait;	// Реализует паттерн Singleton

    protected $request;
    protected $defaultController;
	protected $whiteList;

    protected function __construct()
    {
		$this->request = new Request();
        $this->defaultController = "".Config::getInstance()->get('default_controller');
		$this->whiteList = ['main', 'find', 'page', 'admin'];
    }

	/* Запуск приложения */
    public function start()
    {
		$dc = $this->defaultController;
		$controller = strtolower($this->request->getPart(0, $dc));
		if (in_array($controller, $this->whiteList)) {
			$this->response(ucfirst( $controller ));
		} else {
			$words = new Word;
			$finded = $words->getWord( urldecode( $controller ) );
			if ($finded) {
				// Если слово найдено, в контроллер передается страница, которую нужно будет открыть в книге
				$this->response($dc, $dc::DEFAULT_METHOD, ["finded" => $finded]);
			} else {
				// Если не найдено, то возращется ответ 404 
				$this->response($dc, $dc::PAGE_NOT_FOUND_METHOD);
			}
		}
    }
	
	/* Подключение нужного контроллера и отправка ответа */
    protected function response($controller, $method = null, $data = null)
    {
        try {
            $page = new $controller($this->request);
            if (!$method) {
                $method = $this->request->getPart(1, $controller::DEFAULT_METHOD);
            }
			echo $page->$method($data);
        } catch (\Exception $error) {
			//$this->response($this->defaultController, $this->defaultController::PAGE_NOT_FOUND_METHOD, $error);
        }
    }
}
