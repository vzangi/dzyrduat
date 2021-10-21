<?php
/*
* Класс - обертка для получения данных из файла конфигурации
*/
final class Config
{
    use SingletonTrait;

    private $config_file = 'config/config.xml';
	private $config;

    protected function __construct()
    {
		$this->config = simplexml_load_file(INC_FOLDER . $this->config_file);
    }
	
	/* получение значения поля из конфигурации */
	public function get($field) 
	{
		return $this->config->$field;
	}
}
