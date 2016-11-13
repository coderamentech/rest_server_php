<?php
  /**
   * Data manager class
   */
  class Data {

    /**
     * User data collection
     */
    static $users;

    /**
     * Users JSON data file
     */
    const USER_DATA_FILE = 'data_users.json';

    /**
     * Initializes data component by loading all data files.
     */
    public static function initialize() {
      // Data::$users = array(
      //     array(
      //       'email' => 'member1@mail.com',
      //       'password' => md5('test123')
      //     ),
      //     array(
      //       'email' => 'member2@mail.com',
      //       'password' => md5('test123')
      //     ),
      //     array(
      //       'email' => 'member3@mail.com',
      //       'password' => md5('test123')
      //     ),
      //   );
      $data = file_get_contents(Data::USER_DATA_FILE);
      Data::$users = json_decode($data, true);
    }

    /**
     * Saves all data.
     */
    public static function save() {
      file_put_contents(Data::USER_DATA_FILE, json_encode(Data::$users));
    }

    /**
     * Deletes corresponding user collection entry.
     *
     * @param $id entry ID
     */
    public static function deleteUser($id) {
      try {
        unset(Data::$users[$id]);
      } catch (Exception $e) {
      }

      return true;
    }

    /**
     * Adds or updates a user entry.
     *
     * @param $user collection entry
     */
    public static function addOrUpdateUser($user) {
      $entries = Data::$users;
      $len = count($entries);
      $index = $len;

      // Traverse entries
      for ($i = 0; $i < $len; $i++) {
        $entry = $entries[$i];

        // Get index of matching entry
        if ($entry['email'] == $user['email']) {
          $len = $i;
          break;
        }
      }

      Data::$users[$len] = $user;

      return true;
    }
  }

?>
