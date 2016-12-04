<?php
namespace Rest;

include_once __DIR__ . '/methods.php';

use Rest\Method as Method;

class Header {
	
   	public static function removeAllHeaders() {
   		header_remove();
   	}

   	public static function contentType($_type) {
         $header = "Content-Type: $_type";
   		return $header;
   	}

   	public static function acceptLanguage($_langs = []) {

   		$langs = [];

   		$header = '';

   		if (is_array($_langs)) {
   			foreach ($_langs as $lang) {
   				if (is_string($lang)) {
   					$langs[] = $lang;
   				}
   			}
   		}

   		if (count($langs) > 0) {
   			$header = "Accept-Language: " . implode(',', $langs);
   		}

   		return $header;
   	}

   	private static function gmtDate($timestamp) {
   		
   		// Dates in Headers should have this format
   		// http://tools.ietf.org/html/rfc2616#section-3.3
   		// Ex
   		// Tue, 15 Nov 1994 12:45:26 GMT
   		
   		$format = 'D, d M Y H:i:s T';
   		
   		$date = gmdate($format);
   		
   		if ($timestamp && is_integer($timestamp)) {
   			$date = gmdate($format, $timestamp);
   		}

   		return $date;
   	}

   	// Date should be a unix timestamp
   	public static function date($timestamp) {
   		
   		$date = Header::gmtDate($timestamp);

   		return "Date: $date";
   	}

   	// Date should be a unix timestamp
   	public static function lastModified($timestamp) {
   		
   		$date = Header::gmtDate($timestamp);

   		return "Last-Modified: $date";
   	}

   	public static function allow($_methods = []) {

      	$validMethods = Method::allMethods();

   		$outputMethods = [];

   		$header = '';

   		if (is_array($_methods)) {

   			foreach ($_methods as $method) {

   				if (is_string($method)) {	

   					$method = strtoupper($method);

   					if (in_array($method, $validMethods)) {
   						$outputMethods[] = $method;
   					}
   				}
   			}	
   		}

   		if (count($outputMethods) > 0) {
   			$header = "Allow: " . implode(',', $outputMethods);
   		}

   		return $header;

   	}

   	public static function set($header) {
         
         if (is_string($header)) {
            header($header);
         }
   	}

      public static function setWithNameAndValue($name, $value) {

         if (is_string($name) && is_string($value)) {

            header("$name: $value");
         }

      }


   	public static function getAll() {
         
         $headers = array();
         
         foreach ($_SERVER as $key => $value) {
            
            if (strpos($key, 'HTTP_') === 0) {

               $key = substr($key, strlen('HTTP_'));
               $key = str_replace('_', ' ', $key);
               $key = ucwords(strtolower($key));
               $key = str_replace(' ', '', $key);

               // Keys now have all Camel Case
               // LikeThis

               $headers[$key] = $value;
            }

         }

         return $headers;
      }

      public static function get($key = '') {
         
         // You should find all HTTP headers in the $_SERVER global variable prefixed
         // with HTTP_ uppercased and with dashes (-) replaced by underscores (_).
         // For instance X-Requested-With can be found in:
         // $_SERVER['HTTP_X_REQUESTED_WITH']
         // idea from http://stackoverflow.com/questions/541430/how-do-i-read-any-request-header-in-php

         $result = null;

         if (is_string($key)) {
            
            $key = str_replace(' ', '', $key);
            $key = str_replace('-', '_', $key);
            $key = strtoupper($key);
            
            $key = "HTTP_$key";
            $result = (isset($_SERVER[$key]) ? $_SERVER[$key] : null);
         }

         return $result;
      }

      // Basic HTTP AUTH
      public static function getBasicAuth() {

         $username = (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null);
         $password = (isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null);

         if (!$username || !$password) {

            $auth = (isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null);

            if ($auth) {

               $itsBasicAuth = (strpos(strtolower($auth), 'basic') === 0);

               if ($itsBasicAuth) {
                  list($username, $password) = explode(':', base64_decode(substr($auth, strlen('basic:'))));
               }
            }
         }

         return ['username' => $username, 
                 'password' => $password];

      }

      public static function username() {

         $auth = Header::getBasicAuth();

         return $auth['username'];
      }

      public static function password() {

         $auth = Header::getBasicAuth();

         return $auth['password'];
      }
}