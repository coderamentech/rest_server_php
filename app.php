<?php

require_once 'assortedutil.php';
require_once 'data.php';

class Controller {

  public static function enforceBasicAuth() {

    $flag = false;
    if (isset($_SERVER['PHP_AUTH_USER'])) {
      $pass = $_SERVER['PHP_AUTH_PW'];
      $email = $_SERVER['PHP_AUTH_USER'];
      $flag = Data::isUserExist($email, md5($pass));
    }

    if ($flag == false) {
      header('WWW-Authenticate: Basic realm="App Realm"');
      header('HTTP/1.0 401 Unauthorized');
      exit;
    }
  }

  /**
   * Handles process of sessions collection.
   *
   * @param $verb HTTP verb
   * @param $collection collection name
   * @param $entry_id entry ID
   * @param $code outgoing HTTP return code
   * @param $return HTTP response data
   */
  public static function handle__sessions($verb, $collection, $entry_id,
       &$code, &$return) {

    // Fetch request body
    $body = file_get_contents('php://input');

    // Treat as JSON data
    $data = json_decode($body, true);

    $email = $data['email'];
    $pass = $data['password'];
    $status = Data::isUserExist($email, md5($pass));

    if ($status) {
      Data::save();
      $code = 200;
    } else {
      $code = 401;
    }
  }

  /**
   * Handles process of user collection.
   *
   * @param $verb HTTP verb
   * @param $collection collection name
   * @param $entry_id entry ID
   * @param $code outgoing HTTP return code
   * @param $return HTTP response data
   */
  public static function handle__users($verb, $collection, $entry_id,
       &$code, &$return) {

    switch ($verb) {
      case 'GET':
        $code = 200;
        $return = json_encode(Data::$users);
        break;

      case 'POST':
        // Fetch request body
        $body = file_get_contents('php://input');

        // Treat as JSON data
        $data = json_decode($body, true);

        $data['password'] = md5($data['password']);
        $status = Data::addOrUpdateUser($data);

        if ($status) {
          Data::save();
          $code = 200;
        } else {
          $code = 400;
        }
        break;

      case 'DELETE':
        if (empty($entry_id)) {
          break;
        }
        $status = Data::deleteUser($entry_id);

        if ($status) {
          $code = 200;
          Data::save();
        } else {
          $code = 400;
        }
        break;
    }
  }
}

use AssortedUtil as AU;

// Enable displaying of errors
AU::enableErrors();

// Throw exceptions for errors
AU::enableThrowingExceptionsForErrors();

// Echo CORS headers that are super relaxed
AU::echoSuperLaxedCorsHeaders();

$verb = $_SERVER['REQUEST_METHOD'];

// Fetch REST collection and entity ID
$collection = '';
$entry_id = null;
AU::assignRestCollectionAndId($collection, $entry_id);

// Construct name of handler
$method = 'handle__' . $collection;

$code = 200;
$return = '';

Data::initialize();

// if (!($collection == 'users' && $verb == 'POST')) {
//   Controller::enforceBasicAuth();
// }

// if (empty($collection)) {
//   exit;
// }

try {
  // Invoke appropriate handler
  call_user_func_array( array('Controller', $method),
    array($verb, $collection, $entry_id, &$code, &$return));

  http_response_code($code);
} catch(Exception $e) {
  http_response_code('400');
}

echo $return;
?>
