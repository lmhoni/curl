<?php 

namespace Uxint\Curl;

class Response {

	private $statusCode;

	private $message;

	private $body;

	private $httpVersion;

	private $headers = [];

	private $info;

	public function __construct($headers, $body, $info) {

		$this->processHeaders($headers);
		$this->body = $body;
		$this->info = $info;

	}

	public function processHeaders($headers) {
		$headerLines = explode("\r\n", $headers);
		$statusLine = array_shift($headerLines);
		$this->processStatusLine($statusLine);

		foreach($headerLines as $line) {
			$lineArr = explode(": ", $line);

			if(isset($lineArr[0]) && $lineArr[0] && isset($lineArr[1]) ) {

				$key   = strtolower($lineArr[0]);
				$value = $lineArr[1];

				array_push($this->headers, [
						$key => $value
				]);
			}
		}

	}

	public function processStatusLine($statusLine) {
		$arr = explode(" ", $statusLine);
		$this->httpVersion = isset($arr[0])? trim($arr[0]) : '';
		$this->statusCode  = (int) isset($arr[1])? trim($arr[1]) : '';
		$this->message     = isset($arr[2])? $arr[2] : '';
	}


	public function getHeaders() {
		return $this->headers;
	}

	public function getHeader(string $key) {
		$key = strtolower($key);
		if(isset($this->headers[$key])) {
			return $headers[$key];
		}

		return null;
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

	public function getMessage() {
		return $this->message;
	}

	public function getBody() {
		return $this->body;
	}

	public function getInfo() {
		return $info;
	}


	
}




?>