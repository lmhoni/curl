<?php 

namespace Uxint\Curl;

class Curl {

	private $request;
	private $response;
	private $ch;


	public function newRequest($method, $url, $data = [], $encoding = Request::ENCODING_QUERY) {
		$this->ch = curl_init();
		$this->request = new Request();
		$this->request->setUrl($url);
		$this->request->setMethod($method);
		$this->request->setData($data);
		$this->request->setEncoding($encoding); 
		return $this;
	}

	public function setHeader($key, $value, $peserveCase = false) {
		$this->request->setHeader($key, $value, $peserveCase);
	}

	public function prepareRequest() {
		$this->request->encodeData();

		//Curl automatic content length is not reliable.

		curl_setopt($this->ch, CURLOPT_URL,$this->request->getUrl());
		curl_setopt($this->ch, CURLOPT_HEADER, true);

		if(count($this->request->getHeaders())) {
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->request->getHeaders());
		}


		switch($this->request->getMethod()) {
			case 'GET':
				curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->request->getMethod());
			break;
			case 'POST':

				curl_setopt($this->ch, CURLOPT_POST, 1);
				curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->request->getEncodedData());
			break;
			default:

			break;
		}

		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
	}


	public function buildUrl($url, array $query)
	{
		if (empty($query)) {
			return $url;
		}

		$parts = parse_url($url);

		$queryString = '';
		if (isset($parts['query']) && $parts['query']) {
			$queryString .= $parts['query'].'&'.http_build_query($query);
		} else {
			$queryString .= http_build_query($query);
		}

		
		$retUrl = $parts['scheme'].'://'.$parts['host'];
		if (isset($parts['port'])) {
			$retUrl .= ':'.$parts['port'];
		}

		if (isset($parts['path'])) {
			$retUrl .= $parts['path'];
		}

		if ($queryString) {
			$retUrl .= '?' . $queryString;
		}

		return $retUrl;
	}


	public function sendRequest() {
		$this->prepareRequest();
		$result = curl_exec( $this->ch );


		if ($result === false) {
			$errno = curl_errno($this->ch);
			$errmsg = curl_error($this->ch);
			$msg = "Curl request failed with error [$errno]: $errmsg";
			curl_close($this->ch);
			//throw new CurlException($request, $msg, $errno);
		}

		$info = curl_getinfo($this->ch);

		curl_close( $this->ch );

		$headerSize = $info['header_size'];


		$headers = substr($result, 0, $headerSize);
		$body = substr($result, $headerSize);

		$this->response = new Response($headers, $body, $info);

		return $this->response;
	}

}



?>