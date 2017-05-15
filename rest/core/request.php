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

class Request 
{

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
   public static function is($type) 
   {

       $method = (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '');
       $method = strtolower($method);

       switch(strtolower($type)) 
       {

           case 'ajax':
           return (
               (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) ||

               (isset($_SERVER["CONTENT_TYPE"]) && 
                stripos($_SERVER["CONTENT_TYPE"],'application/json')!==false) ||

               (isset($_SERVER["HTTP_CONTENT_TYPE"]) && 
               stripos($_SERVER["HTTP_CONTENT_TYPE"], 'application/json') !== false)
                );
           break;
           
           case 'mobile':
           $mobileDevices = array(
               "midp","240x320","blackberry","netfront","nokia","panasonic",
               "portalmmm","sharp","sie-","sonyericsson",
               "symbian","windows ce","benq","mda","mot-","opera mini",
               "philips","pocket pc","sagem","samsung",
               "sda","sgh-","vodafone","xda","iphone", "ipod", "ipad", "android"
           );
           
           return preg_match('/(' . implode('|', $mobileDevices). ')/i', 
                            strtolower($_SERVER['HTTP_USER_AGENT'])
                            );
           break;
           
           case 'post':
           return ($method == 'post');
           break;
           
           case 'get':
           return ($method == 'get');
           break;
           
           case 'put':
           return ($method == 'put');
           break;

           case 'options':
           return ($method == 'options');
           break;

           case 'patch':
           return ($method == 'patch');
           break;
           
           case 'delete':
           return ($method == 'delete');
           break;

           case 'head':
           return ($method == 'head');
           break;
           
           case 'ssl':
           return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
           break;
       }

       return false;
   }

  public static function isAjax()
  {
    return self::is('ajax');
  }

  public static function isMobile() 
  {
    return self::is('mobile');
  }

  public static function isPost() 
  {
    return self::is('post');
  }

  public static function isGet() 
  {
    return self::is('get');
  }

  public static function isPut() 
  {
    return self::is('put');
  }

  public static function isPatch() 
  {
    return self::is('patch');
  }

  public static function isDelete() 
  {
    return self::is('delete');
  }

  public static function isHead() 
  {
    return self::is('head');
  }

  public static function isOptions() 
  {
    return self::is('options');
  }

  public static function isSSL() 
  {
    return self::is('ssl');
  }

  /**
  * Get current request method
  * @return String
  */
  public static function currentMethod() 
  {
    
    $currentType = null;

    foreach(self::$types as $type) 
    {
    
      if(self::is($type))
      {
        $currentType = strtoupper($type);
        break;
      }

    }

    return $currentType;
  }

  public static function contentType() 
  {
    
    $contentType = '';

    if (array_key_exists('CONTENT_TYPE', $_SERVER) && 
      isset($_SERVER["CONTENT_TYPE"])) 
      {
    
        $contentType = $_SERVER["CONTENT_TYPE"];
    
    } 
    else 
    {
        
        if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER) && 
          isset($_SERVER["HTTP_CONTENT_TYPE"])) 
        {
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
  public static function params($index=null, $default = null, $source = null) 
  {

    // check for php://input and merge with $_REQUEST

    $contentType = self::contentType();

    if(stripos($contentType, 'application/json') !== false)
    {
          if ($json = json_decode(@file_get_contents('php://input'), true)) 
          {
              $_REQUEST = array_merge($_REQUEST, $json);
          }
    }

    $src = $source ? $source : $_REQUEST;

    return self::fetch_from_array($src, $index, $default);
  }

  /**
  * Same as params. Just sintax sugar.
  */
  public static function inputs($index=null, $default = null, $source = null)
  {
    return self::params($index=null, $default = null, $source = null);
  }

  /**
  * Enables getting easily the params.
  * Also if you got the params previously you can use that array.
  * If not it fetches the current params with Request::params();
  *
  * Example
  * Request::getParam('username');
  */
  public static function getParam($_name, $_defaultValue = null, $_params = null) 
  {

    $params = $_params;
    
    if (!is_array($_params) || $_params == null ) 
    {
          $params = self::params();
    }

    if (!is_string($_name)) 
    {
      $_name = "";
    }

    return (array_key_exists($_name, $params) ? $params[$_name] : $_defaultValue);
  }

  /**
  * Same as getParam. Just sintax sugar.
  */
  public static function input($_name, $_defaultValue = null, $_params = null)
  {
    return self::getParam($_name, $_defaultValue = null, $_params = null);
  }

  private static function fetch_from_array(&$array, $index=null, $default = null) {

      if (is_null($index)) 
      {

          return $array;

      } 
      elseif (isset($array[$index])) 
      {

          return $array[$index];

      } 
      elseif (strpos($index, '/')) 
      {

          $keys = explode('/', $index);
        
          switch(count($keys)) 
          {

              case 1:
                  if (isset($array[$keys[0]]))
                  {
                      return $array[$keys[0]];
                  }
                  break;
              case 2:
                  if (isset($array[$keys[0]][$keys[1]]))
                  {
                      return $array[$keys[0]][$keys[1]];
                  }
                  break;
              case 3:
                  if (isset($array[$keys[0]][$keys[1]][$keys[2]]))
                  {
                      return $array[$keys[0]][$keys[1]][$keys[2]];
                  }
                  break;
              case 4:
                  if (isset($array[$keys[0]][$keys[1]][$keys[2]][$keys[3]]))
                  {
                      return $array[$keys[0]][$keys[1]][$keys[2]][$keys[3]];
                  }
                  break;
          }
      }

      return $default;
  }
}
