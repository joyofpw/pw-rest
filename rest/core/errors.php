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
namespace Rest\Errors;

/**
* errors.php
* Generic Client Errors Created for
* displaying on json requests
*/

include_once __DIR__ . '/mimetypes.php';
include_once __DIR__ . '/statuscodes.php';

use \Rest\MimeType as MimeType;
use \Rest\StatusCode as Status;

use function \Processwire\_x as _x;
use function \Processwire\__ as __;



interface JSONErrorInterface {
	public static function error();
}

class JSONException extends \Exception {
	public $error;
	public $response;
}

class JSONError {
    
    // The error code number is the same as the status code
	public $responseCode;

	// The name is the same as the status code
	public $name;

	// Describes the reason for this error to happen
	public $message;
	
	// Additional info for what to do or how to solve
	public $info;

	// A Unique string identifier for this error
	public $uuid;

	// A Unique integer identifier for this error
	public $number;

	// The mime type for this error to render
	public $mimeType;

	

	public function __construct($_code = 500, $_message = '', $_uuid = '', $_info = '', $_number = '', $_mime = '') {

		$this->responseCode = $_code;
		$this->name = Status::nameForCode($_code);
 
		$this->message = $_message;
		$this->uuid = $_uuid;
		$this->info = $_info;
		$this->number = $_number;

		if($_mime == '') {
			$_mime = MimeType::json();
		}

		$this->mimeType = $_mime;
	}

	public function output() {

		$output['error']['code'] = $this->responseCode;
		$output['error']['name'] = $this->name;
		$output['error']['uuid'] = $this->uuid;
		$output['error']['number'] = $this->number;
		$output['error']['message'] = $this->message;
		$output['error']['info'] = $this->info;

		return $output;
	}

	public function exception($_response = null) {
		
		$exception = new JSONException($this->message, $this->number);
		
		$exception->error = $this;
		$exception->response = $_response;

		return $exception;
	}

}

class BadRequest implements JSONErrorInterface {

	public static function error() {
		
		$code = Status::badRequest();
		$message = _x('Generic Bad Request Message', 'Something bad was bad at the request ');
		$info = _x('Generic Bad Request Error', 'Additional info for knowing what happened');
		$number = $code;

		$error = new JSONError($code, $message, 'kBadRequest', $info, $number);

		return $error;
	}

}

class MethodNotAllowed implements JSONErrorInterface {

	public static function error() {
		
		$code = Status::methodNotAllowed();
		$message = _x('Generic Method Not Allowed Error Message', 'The Method used was not allowed');
		$info = _x('Generic Method Not Allowed Error', 'The Method used was not allowed for some reason');
		$number = $code;

		$error = new JSONError($code, $message, 'kMethodNotAllowed', $info, $number);

		return $error;
	}
}

class NotFound implements JSONErrorInterface {

	public static function error() {
		
		$code = Status::notFound();
		$message = _x('Generic Not Found Error Message', 'The resource was not found');
		$info = _x('Generic Not Found Error', 'The resource was not found for some reason');
		$number = $code;

		$error = new JSONError($code, $message, 'kNotFound', $info, $number);

		return $error;
	}
}

class Unauthorized implements JSONErrorInterface {

	public static function error() {
		
		$code = Status::unauthorized();
		$message = _x('Generic Not Found Error Message', 'The resource was not found');
		$info = _x('Generic Not Found Error', 'The resource was not found for some reason');
		$number = $code;

		$error = new JSONError($code, $message, 'kUnauthorized', $info, $number);

		return $error;
	}
}

class Forbidden implements JSONErrorInterface {

	public static function error() {
		
		$code = Status::forbidden();
		$message = _x('Generic Not Found Error Message', 'The resource was not found');
		$info = _x('Generic Not Found Error', 'The resource was not found for some reason');
		$number = $code;

		$error = new JSONError($code, $message, 'kForbidden', $info, $number);

		return $error;
	}
}

class Conflict implements JSONErrorInterface {

	public static function error() {
		
		$code = Status::conflict();
		$message = _x('Generic Conflict Message', 'There was a conflict');
		$info = _x('Generic Conflict Error', 'The was a conflict for a reason');
		$number = $code;

		$error = new JSONError($code, $message, 'kConflict', $info, $number);

		return $error;
	}
}

class NotImplemented implements JSONErrorInterface {

	public static function error() {
		
		$code = Status::notImplemented();
		$message = _x('Generic Not Implemented Message', 'The resource is not implemented');
		$info = _x('Generic Not Implemented Error', 'The resource is not implemented');
		$number = $code;

		$error = new JSONError($code, $message, 'kNotImplemented', $info, $number);

		return $error;
	}
}


class InternalServerError implements JSONErrorInterface {

	public static function error() {
		
		$code = Status::internalServerError();
		$message = _x('Generic Internal Server Message', 'Something is wrong with the server');
		$info = _x('Generic Internal Server Error', 'The server does not function correctly');
		$number = $code;

		$error = new JSONError($code, $message, 'kInternalServerError', $info, $number);

		return $error;
	}
}

// Special Errors
class RequestContentTypeMisMatchError extends BadRequest {
	public static function error() {
		
		$error = parent::error();
		$error->message = __('The Content Type Given in the Request is Not Valid.');
		$error->info = __('The content type must be changed.');
		$error->uuid = 'kRequestContentTypeMisMatchError';
		$error->number = 1000;
		return $error;
	}
}

class InvalidCredentials extends Unauthorized {
	public static function error() {
		
		$error = parent::error();
		$error->message = __('The credentials you gave were not valid');
		$error->info = __('User or password were incorrect or wrong format of params.');
		$error->uuid = 'kLoginInvalidCredentials';
		$error->number = 1001;
		return $error;
	}
}