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
include_once __DIR__ . '/headers.php';

use Rest\MimeType as MimeType;
use Rest\Header as Header;

class Response {
	
	public $mimeType;
	
	public $responseCode;

	public $headers;

	public $output;

	public $error;

	private $gotError;

	private $allowMethodsHeader;

	private $clearHeaders;

	public function __construct($_output = [], $_mimeType = '', $_responseCode = 200, $_headers = []) {

		$this->output = $_output;


		if($_mimeType == '') {
			$_mimeType = MimeType::json();
		}

		$this->mimeType = $_mimeType;
		$this->responseCode = $_responseCode;
		
		$this->headers = $_headers;

		$this->headers[] = Header::contentType($_mimeType);

		$this->gotError = false;
		$this->error = null;

		$this->clearHeaders = true;

	}

	// Calls header_remove() on render if true
	public function clearHeaders($_clearHeaders = true) {
		$this->clearHeaders = $_clearHeaders;
	}

	public function addArray($_data) {

		$this->output = array_merge($this->output, $_data);
	}

	public function setError($_error) {

		$this->error = $_error;

		$this->gotError = true;
	}

	public function allowMethod($_method) {

		if (is_string($_method)) {
			self::allowMethods([$_method]);
		}
	}

	public function allowMethods($_methods = []) {
		
		if (is_array($_methods)) {
			$this->allowMethodsHeader = Header::allow($_methods);
		}
	}

	public function render() {

		if ($this->clearHeaders) {
			Header::removeAllHeaders();
		}

		if ($this->gotError) {
			
			$this->output = $this->error->output();
			
			$this->mimeType = $this->error->mimeType;
			
			$this->responseCode = $this->error->responseCode;

			$this->headers = [];

			$this->headers[] = Header::contentType($this->error->mimeType);
		} 

		if ($this->allowMethodsHeader) {
			$this->headers[] = $this->allowMethodsHeader;
		}

		foreach($this->headers as $header) {
			Header::set($header);
		}
		

		http_response_code($this->responseCode);

		echo json_encode($this->output);	
	}
}