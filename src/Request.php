<?php 

namespace Uxint\Curl;



class Request {

    const ENCODING_QUERY = 0;
	const ENCODING_JSON = 1;
	const ENCODING_RAW = 2; 

	private $headers = [];

	private $allowedMethods = [

		'GET' => true,
		'POST' => true,
		'PUT' => false,
		'DELETE' => false,
		'PATCH' => false
	];

	private $data;

	private $encodedData;

	private $curl;

	private $method;

	private $url;

	private $encoding;


	public function setHeader($key, $value, $preserveCase = false) {
		if(!$preserveCase) {
			$key = strtolower($key);
		}
		$header = "{$key}: {$value}";
		array_push($this->headers, $header);
	}


	public function setUrl($url) {
		$this->url = $url;
	}

	public function  setMethod($method) {
		$method = strtoupper($method);
		if($this->methodIsAllowed($method)){
			$this->method = $method;
		} else {
			$message = "Method not allowed";
			throw new \Exception($message);
		}
		
	}


	public function getHeaders() {
		return  $this->headers;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function setEncoding($encoding) {
		$this->encoding =  $encoding;
	}

	public function getData() {
		return $this->data;
	}

	public function getEncodedData() {
		return $this->encodedData;
	}

	public function send() {
		$this->curl;
	}

	public function encodeData() {
		switch ($this->encoding) {
			case Request::ENCODING_QUERY :
				# code...
				break;

			case Request::ENCODING_JSON :
				$this->encodedData = json_encode($this->data);
			break;
			
			default:
				# code...
				break;
		}
	}

	public function  isValidMethod($method) {
		
		$method = strtoupper($method);

		if(array_key_exists($method, $this->allowedMethods)) {
			return true;
		}
		return false;
	}

	public function methodIsAllowed($method) {
		$method = strtoupper($method);
		if($this->isValidMethod($method) && $this->allowedMethods[$method] == true) {
			return true;
		}

		return false;
	}






}

?>