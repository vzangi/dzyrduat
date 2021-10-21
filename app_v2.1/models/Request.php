<?php

/**
 * Класс, отвечающий за информацию о пришедшем запросе
 */
class Request
{
    protected $server;
    protected $request;
    protected $parts;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->request = $_REQUEST;

        $uri = $this->getRequestUri();

        if (!$uri || $uri == '') $uri = "/";

        $qPos = strpos($uri, '?');
        if ($qPos !== false) {
            $uri = substr($uri, 0, $qPos);
        }

        $this->parts = preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function getRequestUri()
    {
        return $this->server['REQUEST_URI'];
    }

    public function getRequestTimeStamp()
    {
        return $this->server['REQUEST_TIME'];
    }

    public function getRemoteIp()
    {
        return $this->server['REMOTE_ADDR'];
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function isGetRequest()
    {
        return $this->getMethod() == 'GET';
    }

    public function isPostRequest()
    {
        return $this->getMethod() == 'POST';
    }

    public function getGetParams()
    {
        return $this->request;
    }

    public function getGetParam($paramName, $defValue = null)
    {
        if (isset($this->request[$paramName])) {
            return $this->request[$paramName];
        }
        return $defValue;
    }

    public function getParts()
    {
        return $this->parts;
    }

    public function getPart($partNumber, $defValue = null)
    {
        if (isset($this->parts[$partNumber])) {
            return $this->parts[$partNumber];
        }
        return $defValue;
    }

	public function redirect($url) {
		header('Location: ' . $url);
		exit;
	}
}
