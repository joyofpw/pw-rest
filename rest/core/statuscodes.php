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
* status.codes.php
* Contains the Status Codes for a Response
*/

class StatusCode {

	  public static $statusCodes = array (
       // Informational 1xx
       100 => 'Continue',
       101 => 'Switching Protocols',
       // Successful 2xx
       200 => 'OK',
       201 => 'Created',
       202 => 'Accepted',
       203 => 'Non-Authoritative Information',
       204 => 'No Content',
       205 => 'Reset Content',
       206 => 'Partial Content',
       // Redirection 3xx
       300 => 'Multiple Choices',
       301 => 'Moved Permanently',
       302 => 'Found',
       303 => 'See Other',
       304 => 'Not Modified',
       305 => 'Use Proxy',
       307 => 'Temporary Redirect',
       // Client Error 4xx
       400 => 'Bad Request',
       401 => 'Unauthorized',
       402 => 'Payment Required',
       403 => 'Forbidden',
       404 => 'Not Found',
       405 => 'Method Not Allowed',
       406 => 'Not Acceptable',
       407 => 'Proxy Authentication Required',
       408 => 'Request Timeout',
       409 => 'Conflict',
       410 => 'Gone',
       411 => 'Length Required',
       412 => 'Precondition Failed',
       413 => 'Request Entity Too Large',
       414 => 'Request-URI Too Long',
       415 => 'Unsupported Media Type',
       416 => 'Request Range Not Satisfiable',
       417 => 'Expectation Failed',
       // Server Error 5xx
       500 => 'Internal Server Error',
       501 => 'Not Implemented',
       502 => 'Bad Gateway',
       503 => 'Service Unavailable',
       504 => 'Gateway Timeout',
       505 => 'HTTP Version Not Supported'
       );

	/**
   	* Returns a status code name
   	*/
    public static function nameForCode($_code) {
   		$name = StatusCode::$statusCodes[$_code];
   		return $name;
   	}

    /**
   	* Returns a 200 OK Status
   	*/
   	public static function ok() {
   		return 200;
   	}

    /**
   	* Returns a 201 Created Status
   	*/
   	public static function created() {
   		return 201;
   	}

    /**
   	* Returns a 202 Accepted Status
   	*/
   	public static function accepted() {
   		return 202;
   	}
	
	/**
   	* Returns a 400 Bad Request Status
   	*/
   	public static function badRequest() {
   		return 400;
   	}

   	/**
   	* Returns a 401 Unauthorized Status
   	*/
   	public static function unauthorized() {
   		return 401;
   	}

   	/**
   	* Returns a 403 Forbidden Status
   	*/
   	public static function forbidden() {
   		return 403;
   	}

   	/**
   	* Returns a 404 Not Found Status
   	*/
   	public static function notFound() {
   		return 404;
   	}

   	/**
   	* Returns a 405 Method Not Allowed Status
   	*/
   	public static function methodNotAllowed() {
   		return 405;
   	}

   	/**
   	* Returns a 409 Conflict Status
   	*/
   	public static function conflict() {
   		return 409;
   	}

   	/**
   	* Returns a 500 Internal Server Error Status
   	*/
   	public static function internalServerError() {
   		return 500;
   	}

   	/**
   	* Returns a 501 Not Implemented Status
   	*/
   	public static function notImplemented() {
   		return 501;
   	}
}