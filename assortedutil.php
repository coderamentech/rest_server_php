<?php
/*
Copyright (c) 2016, coderamen.tech
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this
   list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

The views and conclusions contained in the software and documentation are those
of the authors and should not be interpreted as representing official policies,
either expressed or implied, of the FreeBSD Project.
*/

/**
 * Contains assorted utility methods.
 */
class AssortedUtil
{
  /**
   * Enables error displaying.
   */
  public static function enableErrors() {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  }

  /**
   * Handles errors and throw them as exceptions.
   *
   * @param $errno error number
   * @param $errstr error message
   * @param $file file where error occured
   * @param $line line number where error occured
   */
  public static function handleErrors($errno, $errstr, $file, $line ) {
      throw new ErrorException($errstr, $errno, 0, $file, $line);
  }

  /**
   * Enables error displaying.
   */
  public static function enableThrowingExceptionsForErrors() {
    set_error_handler("AssortedUtil::handleErrors");
  }

  /**
   * Spits out headers to enable cross domain access
   */
  public static function echoSuperLaxedCorsHeaders() {
    // header("Access-Control-Allow-Credentials: true");
    // header("Access-Control-Allow-Origin: *");
    // header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
    // header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Headers: *");
    header('Access-Control-Max-Age: 0');
  }

  /**
   * Gets value of the specified variable, if unset, default value
   * would be used instead.
   *
   * @param $var variable variable whose value is to be used
   * @param $default fallback or default value
   *
   * @return $var, when non-empty; otherwise, $default
   */
  public static function getValue($var, $default) {
    $value = $default;

    if (!empty($var)) {
      $value = $var;
    }

    return $value;
  }

  /**
   * Fetches the collection and entry ID values from a REST call.
   *
   * @param $collection output parameter to store collection
   * @param $entry_id output parameter to store entity ID
   * @param $offset index to start in tokenized paths
   */
  public static function assignRestCollectionAndId(&$collection, &$entry_id, $offset = 2) {
    $uri = $_SERVER['REQUEST_URI']; //or $_SERVER['PHP_SELF']

    // Note: parts will have empty string in index 0
    $parts = explode("/", $uri);

    // Fetch collection name
    if (count($parts) > $offset) {
      $collection = $parts[$offset];
    }

    // Fetch entity ID
    if (count($parts) > $offset + 1) {
      $entry_id = $parts[$offset + 1];
    }
  }
}

?>
