<?php

require_once 'assortedutil.php';
require_once 'data.php';

class Controller {

  /**
    * Handles process of user collection.
    *
    * @param $verb HTTP verb
    * @param $collection collection name
    * @param $entry_id entry ID
    */
  public static function handle__users($verb, $collection, $entry_id) {
    switch ($verb) {
      case 'GET':
        echo json_encode(Data::$users);
        break;

      case 'POST':
        // Fetch request body
        $body = file_get_contents('php://input');

        // Treat as JSON data
        $data = json_decode($body, true);

        $data['password'] = md5($data['password']);
        Data::addOrUpdateUser($data);

        Data::save();
        break;

      case 'DELETE':
        if (empty($entry_id)) {
          break;
        }
        Data::deleteUser($entry_id);
        Data::save();
        break;
    }
  }
}

use AssortedUtil as AU;

// Enable displaying of errors
AU::enableErrors();

// Throw exceptions for errors
AU::enableThrowingExceptionsForErrors();

$verb = $_SERVER['REQUEST_METHOD'];

// Fetch REST collection and entity ID
$collection = '';
$entry_id = null;
AU::assignRestCollectionAndId($collection, $entry_id);

// Construct name of handler
$method = 'handle__' . $collection;

try {
  Data::initialize();

  // Invoke appropriate handler
  call_user_func_array( array('Controller', $method),
    array($verb, $collection, $entry_id));

} catch(Exception $e) {
}

?>
