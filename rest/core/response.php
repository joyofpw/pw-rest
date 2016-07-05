<?php
/*
 * ProcessWire REST Helper.
 *
 * Copyright (c) 2016 Camilo Castro <camilo@ninjas.cl>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Rest;

/**
* Helps with the Response
*/

include_once __DIR__ . '/mimetypes.php';

use Rest\MimeType as MimeType;
use Rest\Method as Method;

class Response {
	
	public $mimeType;
	
	public $responseCode;

	public $headers;

	public $output;

	public $error;

	private $gotError;

	public function __construct($_output = [], $_mimeType = '', $_responseCode = 200, $_headers = []) {

		$this->output = $_output;


		if($_mimeType == '') {
			$_mimeType = MimeType::json();
		}

		$this->mimeType = $_mimeType;
		$this->responseCode = $_responseCode;
		
		$this->headers = $_headers;

		$this->headers[] = $_mimeType;

		$this->gotError = false;
		$this->error = null;

	}

	public function addArray($data) {

		$this->output = array_merge($this->output, $data);
	}

	public function setError($error) {

		$this->error = $error;

		$this->gotError = true;
	}

	public function acceptMethod($method) {
		self::acceptMethods([$method]);
	}

	public function acceptMethods($methods = []) {
			
		$validMethods = Method::allMethods();

		$outputMethods = [];

		if (is_array($methods)) {

			foreach ($methods as $method) {
				
				$method = strtoupper($method);

				if (in_array($method, $validMethods)) {
					$outputMethods[] = $method;
				}
			}	
		}

		if (count($outputMethods) > 0) {
			$this->headers[] = 'Accept: ' . implode(',', $outputMethods);
		}

	}

	public function render() {

		header_remove();

		if ($this->gotError) {
			
			$this->output = $this->error->output();
			
			$this->mimeType = $this->error->mimeType;
			
			$this->responseCode = $this->error->responseCode;

			$this->headers = [];

			$this->headers[] = $this->error->mimeType;
		} 

		foreach($this->headers as $header) {
			header($header);
		}
		

		http_response_code($this->responseCode);

		echo json_encode($this->output);	
	}
}