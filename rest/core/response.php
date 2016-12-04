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
include_once __DIR__ . '/methods.php';
include_once __DIR__ . '/request.php';
include_once __DIR__ . '/errors.php';

use \Rest\MimeType as MimeType;
use \Rest\Header as Header;
use \Rest\Method as Method;
use \Rest\Request as Request;
use \Rest\Errors\JSONError as JSONError;
use \Rest\Errors\MethodNotAllowed as MethodNotAllowed;
use \Rest\Errors\BadRequest as BadRequest;
use \Rest\Errors\RequestContentTypeMisMatchError as RequestContentTypeMisMatchError;

class Response {
	
	public $mimeType;
	
	public $code;

	public $headers;

	public $output;

	public $meta;

	public $data;

	public $error;

	private $gotError;

	private $allowMethodsHeader;

	private $clearHeaders;

	private $methods;

	public function __construct($_output = [], $_mimeType = '', $_responseCode = 200, $_headers = []) {

		$this->output = $_output;


		if($_mimeType == '') {
			$_mimeType = MimeType::json();
		}

		$this->mimeType = $_mimeType;
		$this->code = $_responseCode;
		
		$this->headers = $_headers;

		$this->headers[] = Header::contentType($_mimeType);

		$this->gotError = false;
		$this->error = null;

		$this->clearHeaders = true;

		$this->meta = null;

		$this->data = null;

		$this->methods = [];
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
			$this->methods = $_methods;
		}
	}

	// Render Methods

	public function render() {

		if ($this->clearHeaders) {
			Header::removeAllHeaders();
		}

		if ($this->gotError) {
			
			$this->output = $this->error->output();
			
			$this->mimeType = $this->error->mimeType;
			
			$this->code = $this->error->responseCode;

			$this->headers = [];

			$this->headers[] = Header::contentType($this->error->mimeType);
		} 

		if ($this->allowMethodsHeader) {
			$this->headers[] = $this->allowMethodsHeader;
		}

		foreach($this->headers as $header) {
			Header::set($header);
		}
		
		if (isset($this->meta)) {
			$this->output['_meta'] = $this->meta;
		}

		if (isset($this->data)) {
			$this->output['data'] = $this->data;
		}

		http_response_code($this->code);

		echo json_encode($this->output);	
	}

	/**
	* Renders the Error as JSON and Exit.
	* if $_throwException is true the error is not rendered.
	* instead triggers an exception (\Rest\Errors\JSONException) so you can
	* render the error yourself in a try catch block.
	*/
	public function renderErrorAndExit($_error, $_throwException = false) {


		if (get_class($_error) == 'Rest\Errors\JSONError') {

			$this->setError($_error);

			if ($_throwException === true) {
				
				throw $_error->exception($this);

			} else {

				$this->render();

				exit(1);
			}
		}

		throw new \Exception("Not Supposed to reach here. You must pass a subclass of 'Rest\Errors\JSONError' class given " . get_class($_error), 1);
	}

	public function renderAndExit() {
		$this->render();
		exit(0);
	}

	public function renderErrorAndExitUnlessTheseMethodsAreUsed($_methods = [], $_throwException = false) {

		if (!isset($_methods)) {
			$_methods = [Method::GET];
		}

		if (!is_array($_methods)) {
			$_methods = [$_methods];
		}

		$this->allowMethods($_methods);
		
		$currentRequestMethod = Request::currentMethod();

		if(!in_array($currentRequestMethod, $this->methods)) {
			
			$this->meta['method_used'] = $currentRequestMethod;

			$this->meta['allowed_methods'] = $this->methods;

			$this->renderErrorAndExit(MethodNotAllowed::error(), $_throwException);
		}
	}

	public function renderErrorAndExitIfTheseParamsAreNotFound($_params = [], $_error = null, $_throwException = false) {

		if ($_error == null || !(get_class($_error) == 'Rest\Errors\JSONError')) {
			$_error = BadRequest::error();
		}

		if (is_array($_params)) {

			foreach ($_params as $key => $value) {

				if (!isset($value) || $value == '') {
					
					$this->meta['required'] = array_keys($_params);
					$this->meta['params'] = $_params;
					$this->meta['missing'] = $key;

					$this->renderErrorAndExit($_error, $_throwException);
					break;
				}
			}
		}
	}

	public function renderErrorAndExitUnlessThisContentTypeIsUsed($_contentType, $_throwException = false) {

		if (Request::contentType() != $_contentType) {

			$this->meta['contentType'] = Request::contentType();
			$this->meta['valid'] = $_contentType;

			$this->renderErrorAndExit(RequestContentTypeMisMatchError::error(), $_throwException);
		}
	}
}