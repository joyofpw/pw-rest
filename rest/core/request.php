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
* request.php
* Contains the Helper methods for a Request
* Some portions of code are based on the Lime Project
* https://github.com/aheinze/Lime/
* Copyright (c) 2014 Artur Heinze
*/

class Request {

  public static $types = array (
  'get', 
  'post', 
  'put', 
  'patch', 
  'delete',
  'head', 
  'options', 
  'ajax', 
  'mobile', 
  'ssl'
  );

  /**
   * Request helper function
   * @param  String $type
   * @return Boolean
   */
   public static function is($type) {

       switch(strtolower($type)) {

           case 'ajax':
           return (
               (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'))       ||
               (isset($_SERVER["CONTENT_TYPE"]) && stripos($_SERVER["CONTENT_TYPE"],'application/json')!==false)           ||
               (isset($_SERVER["HTTP_CONTENT_TYPE"]) && stripos($_SERVER["HTTP_CONTENT_TYPE"],'application/json')!==false)
           );
           break;
           
           case 'mobile':
           $mobileDevices = array(
               "midp","240x320","blackberry","netfront","nokia","panasonic","portalmmm","sharp","sie-","sonyericsson",
               "symbian","windows ce","benq","mda","mot-","opera mini","philips","pocket pc","sagem","samsung",
               "sda","sgh-","vodafone","xda","iphone", "ipod","android"
           );
           
           return preg_match('/(' . implode('|', $mobileDevices). ')/i',strtolower($_SERVER['HTTP_USER_AGENT']));
           break;
           
           case 'post':
           return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
           break;
           
           case 'get':
           return (strtolower($_SERVER['REQUEST_METHOD']) == 'get');
           break;
           
           case 'put':
           return (strtolower($_SERVER['REQUEST_METHOD']) == 'put');
           break;

           case 'options':
           return (strtolower($_SERVER['REQUEST_METHOD']) == 'options');
           break;

           case 'patch':
           return (strtolower($_SERVER['REQUEST_METHOD']) == 'patch');
           break;
           
           case 'delete':
           return (strtolower($_SERVER['REQUEST_METHOD']) == 'delete');
           break;

           case 'head':
           return (strtolower($_SERVER['REQUEST_METHOD']) == 'head');
           break;
           
           case 'ssl':
           return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
           break;
       }

       return false;
   }

  public static function isAjax() {
    return Request::is('ajax');
  }

  public static function isMobile() {
    return Request::is('mobile');
  }

  public static function isPost() {
    return Request::is('post');
  }

  public static function isGet() {
    return Request::is('get');
  }

  public static function isPut() {
    return Request::is('put');
  }

  public static function isPatch() {
    return Request::is('patch');
  }

  public static function isDelete() {
    return Request::is('delete');
  }

  public static function isHead() {
    return Request::is('head');
  }

  public static function isOptions() {
    return Request::is('options');
  }

  public static function isSSL() {
    return Request::is('ssl');
  }

  /**
  * Get current request method
  * @return String
  */
  public static function currentMethod() {
    
    $currentType = null;

    foreach(Request::$types as $type) {
    
      if(Request::is($type)){
        $currentType = strtoupper($type);
        break;
      }

    }

    return $currentType;
  }

  public static function contentType() {
    
    $contentType = '';

    if (array_key_exists('CONTENT_TYPE', $_SERVER) && 
      isset($_SERVER["CONTENT_TYPE"])) {
    
        $contentType = $_SERVER["CONTENT_TYPE"];
    
    } else {
        
        if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER) && 
          isset($_SERVER["HTTP_CONTENT_TYPE"])) {
            $contentType = $_SERVER['HTTP_CONTENT_TYPE'];
        }
    }

    return $contentType;
  }

  /**
  * Get request variables
  * @param  String $index
  * @param  Misc $default
  * @param  Array $source
  * @return Misc
  */
  public static function params($index=null, $default = null, $source = null) {

        // check for php://input and merge with $_REQUEST
    
        if ((isset($_SERVER["CONTENT_TYPE"]) && 
  		  stripos($_SERVER["CONTENT_TYPE"],'application/json') !== false) ||
            (isset($_SERVER["HTTP_CONTENT_TYPE"]) && 
  		  stripos($_SERVER["HTTP_CONTENT_TYPE"],'application/json') !== false) // PHP build in Webserver !?
  	  	  ) {
              if ($json = json_decode(@file_get_contents('php://input'), true)) {
                  $_REQUEST = array_merge($_REQUEST, $json);
              }
          }

        $src = $source ? $source : $_REQUEST;

        return Request::fetch_from_array($src, $index, $default);
    }


  private static function fetch_from_array(&$array, $index=null, $default = null) {

      if (is_null($index)) {

          return $array;

      } elseif (isset($array[$index])) {

          return $array[$index];

      } elseif (strpos($index, '/')) {

          $keys = explode('/', $index);
        
          switch(count($keys)) {

              case 1:
                  if (isset($array[$keys[0]])){
                      return $array[$keys[0]];
                  }
                  break;
              case 2:
                  if (isset($array[$keys[0]][$keys[1]])){
                      return $array[$keys[0]][$keys[1]];
                  }
                  break;
              case 3:
                  if (isset($array[$keys[0]][$keys[1]][$keys[2]])){
                      return $array[$keys[0]][$keys[1]][$keys[2]];
                  }
                  break;
              case 4:
                  if (isset($array[$keys[0]][$keys[1]][$keys[2]][$keys[3]])){
                      return $array[$keys[0]][$keys[1]][$keys[2]][$keys[3]];
                  }
                  break;
          }
      }

      return $default;
  }

  /**
  * Enables getting easily the params.
  * Also if you got the params previously you can use that array.
  * If not it fetches the current params with Request::params();
  *
  * Example
  * Request::getParam('username');
  */
  public static function getParam($_name, $_defaultValue = null, $_params = null) {

    $params = $_params;
    
    if (!is_array($_params) || $_params == null ) {
          $params = Request::params();
    }

    if (!is_string($_name)) {
      $_name = "";
    }

    return (array_key_exists($_name, $params) ? $params[$_name] : $_defaultValue);
  }
}
