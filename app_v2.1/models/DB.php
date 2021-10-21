<?php
/*
* Класс для работы с базой данных
*/
class DB
{
    use SingletonTrait;

    protected $dbh;
    protected $driver;
    protected $host;
    protected $dbname;
    protected $dbuser;
    protected $dbpass;

    protected function __construct()
    {
		// Берем данные для подключения к базе из конфигурации
        $dbconfig = Config::getInstance()->get('database');
        $this->driver = $dbconfig->driver;
        $this->host   = $dbconfig->host;
        $this->dbname = $dbconfig->dbname;
        $this->dbuser = $dbconfig->dbuser;
        $this->dbpass = $dbconfig->dbpass;
        $dsn = "{$this->driver}:host={$this->host};dbname={$this->dbname}";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->dbh = new PDO($dsn, $this->dbuser, $this->dbpass, $opt);
        } catch (PDOException $ex) {
            die("DB error");
        }
		$this->query("SET NAMES 'utf8'");
    }

	// Запрос на получение данных
	// Возвращает массив найденных строк
    public function query($queryText)
    {
        $stmt = $this->dbh->query($queryText);
        return $stmt->fetchAll();
    }
	
	// Запрос на вставку данных 
	// Возвращает id вставленной записи
	public function insert($queryText) 
	{
		$this->dbh->query($queryText);
		return $this->dbh->lastInsertId();
	}
}
