<?php
  class Rsvp {
    private $data = array();

    function __construct($options='') {
      $this->setNewRsvpClass($options);
    }

    private function setNewRsvpClass($options) {
      foreach ($options as $key => $value) {
        $method = 'set'.ucfirst($key);
        if (is_callable(array($this, $method))) {
          $this->$method($value);
        } else {
          $this->data[$key] = $value;
        }
      }
    }

    public function setEmail($value) {
      $this->data['email'] = strtolower($value);
      return $this->data['email'];
    }

    public function setFirstName($value) {
      $this->data['firstName'] = $value;
      return $this->data['firstName'];
    }

    public function setLastName($value) {
      $this->data['lastName'] = $value;
      return $this->data['lastName'];
    }

    public function setPostal($value) {
      $this->data['postal'] = strtoupper($value);
      return $this->data['postal'];
    }

    public function setGender($value) {
      $this->data['gender'] = strtoupper($value);
      return $this->data['gender'];
    }

    public function setCategory($value) {
      $this->data['category'] = $value;
      return $this->data['category'];
    }

    public function setCompany($value) {
      $this->data['company'] = $value;
      return $this->data['company'];
    }

    public function setGuestOf($value) {
      $this->data['guestOf'] = $value;
      return $this->data['guestOf'];
    }

    public function setHasGuest($value) {
      $this->data['hasGuest'] = $value;
      return $this->data['hasGuest'];
    }

    public function setGuestFirstName($value) {
      $this->data['guestFirstName'] = $value;
      return $this->data['guestFirstName'];
    }

    public function setGuestLastName($value) {
      $this->data['guestLastName'] = $value;
      return $this->data['guestLastName'];
    }

    public function setGuestEmail($value) {
      $this->data['guestEmail'] = $value;
      return $this->data['guestEmail'];
    }

    public function setAction($value) {
      $this->data['action'] = $value;
      return $this->data['action'];
    }

    function __set($key, $value) {
      $method = 'set'.ucfirst($key); //capitalize first letter of the key to preserv camell case convention naming
      if (is_callable(array($this, $method))) {  //use seters setMethod() to set value for this data[key];
        $this->$method($value); //execute the seeter function
      } else {
        $this->data[$key] = $value; //create new set data[key] = value without seeters;
      }
    }

    public function getEmail() {
      return $this->data['email'];
    }

    public function getFirstName() {
      return $this->data['firstName'];
    }

    public function getLastName() {
      return $this->data['lastName'];
    }

    public function getPostal() {
      return $this->data['postal'];
    }
    public function getGender() {
      return $this->data['gender'];
    }
    public function getCompany() {
      return $this->data['company'];
    }
    public function getCategory() {
      return $this->data['category'];
    }
    public function getGuestOf() {
      return $this->data['guestOf'];
    }
    public function getHasGuest() {
      return $this->data['hasGuest'];
    }
    public function getGuestEmail() {
      return $this->data['guestEmail'];
    }
    public function getGuestFirstName() {
      return $this->data['guestFirstName'];
    }
    public function getGuestLastName() {
      return $this->data['guestLastName'];
    }
    public function getAction() {
      return $this->data['action'];
    }

    function dump() {
      var_dump($this);
    }

    public function checkEmail($email){
      //Check email and compare to list, if match, grab ancillary information
      $row = 1;

      // convert email string to all lowercase to make sure variable capitalization doesn't miss the email in wtf.csv
      $emailLower = strtolower($email);

      $event_list = BASEPATH . '/admin/list/event-invites.csv';

      if (($handle = fopen($event_list, 'r')) !== FALSE){
        while (($data = fgetcsv($handle, 1500, ',')) !== FALSE){
          $row++;
          if ($data[3] == $emailLower){
            return true;
          }
        }
        fclose($handle);
      } else {
        return false;
      }

    } // end of checkEmail();

    public function getUserInfo($email) {
      $row = 1;
      $event_list = BASEPATH . '/admin/list/event-invites.csv';

      if (file_exists($event_list)) {
        if (($handle = fopen($event_list, 'r')) !== false) {
          while (($data = fgetcsv($handle, 1500, ',')) !== false) {
            $row++;
            if ($data[3] == $this->getEmail()) {
              $this->setGender($data[4]);
              $this->setCategory($data[5]);
              $this->setCompany($data[6]);
              $this->setGuestOf($data[7]);
            } // end of if ($data[3] ... )
          } // end of while
        } // end of if $handle
        fclose($handle);
      } else {
        echo 'Please Upload Event List';
      } // end of else
    } // end of getUserInfo();

  }
?>
