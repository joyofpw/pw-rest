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
* mime.types.php
* Contains several mimetypes for the headers
*/

class MimeType {

    /**
    * A collection of mime types
    */
   	public static $mimeTypes = array(
           'asc'   => 'text/plain',
           'au'    => 'audio/basic',
           'avi'   => 'video/x-msvideo',
           'bin'   => 'application/octet-stream',
           'class' => 'application/octet-stream',
           'css'   => 'text/css',
           'csv' => 'application/vnd.ms-excel',
           'doc'   => 'application/msword',
           'dll'   => 'application/octet-stream',
           'dvi'   => 'application/x-dvi',
           'exe'   => 'application/octet-stream',
           'htm'   => 'text/html',
           'html'  => 'text/html',
           'json'  => 'application/json',
           'js'    => 'application/x-javascript',
           'txt'   => 'text/plain',
           'bmp'   => 'image/bmp',
           'rss'   => 'application/rss+xml',
           'atom'  => 'application/atom+xml',
           'gif'   => 'image/gif',
           'jpeg'  => 'image/jpeg',
           'jpg'   => 'image/jpeg',
           'jpe'   => 'image/jpeg',
           'png'   => 'image/png',
           'ico'   => 'image/vnd.microsoft.icon',
           'mpeg'  => 'video/mpeg',
           'mpg'   => 'video/mpeg',
           'mpe'   => 'video/mpeg',
           'qt'    => 'video/quicktime',
           'mov'   => 'video/quicktime',
           'wmv'   => 'video/x-ms-wmv',
           'mp2'   => 'audio/mpeg',
           'mp3'   => 'audio/mpeg',
           'rm'    => 'audio/x-pn-realaudio',
           'ram'   => 'audio/x-pn-realaudio',
           'rpm'   => 'audio/x-pn-realaudio-plugin',
           'ra'    => 'audio/x-realaudio',
           'wav'   => 'audio/x-wav',
           'zip'   => 'application/zip',
           'pdf'   => 'application/pdf',
           'xls'   => 'application/vnd.ms-excel',
           'ppt'   => 'application/vnd.ms-powerpoint',
           'wbxml' => 'application/vnd.wap.wbxml',
           'wmlc'  => 'application/vnd.wap.wmlc',
           'wmlsc' => 'application/vnd.wap.wmlscriptc',
           'spl'   => 'application/x-futuresplash',
           'gtar'  => 'application/x-gtar',
           'gzip'  => 'application/x-gzip',
           'swf'   => 'application/x-shockwave-flash',
           'tar'   => 'application/x-tar',
           'xhtml' => 'application/xhtml+xml',
           'snd'   => 'audio/basic',
           'midi'  => 'audio/midi',
           'mid'   => 'audio/midi',
           'm3u'   => 'audio/x-mpegurl',
           'tiff'  => 'image/tiff',
           'tif'   => 'image/tiff',
           'rtf'   => 'text/rtf',
           'wml'   => 'text/vnd.wap.wml',
           'wmls'  => 'text/vnd.wap.wmlscript',
           'xsl'   => 'text/xml',
           'xml'   => 'text/xml'
       );

   	/**
   	* Returns a mime type by name
   	*/
    public static function mimeType($_mime) {
   		$mime = MimeType::$mimeTypes[$_mime];
      return $mime;
   	}

   	/**
   	* Returns the json mime type
   	*/
   	public static function json(){
   		return MimeType::mimeType('json');
   	}
}