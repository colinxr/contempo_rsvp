<?php

class Rsvp {
  private $email;
  private $firstName;
  private $lastName;
  private $postal;
  private $gender;
  private $category;
  private $company;
  private $guestOf;
  private $guestFirstName;
  private $guestLastName;
  private $guestEmail;
  private $action;
  private $hasGuest = false;

  public function &__get($prop){
    if (property_exists($this, $prop)){
      return $this->$prop;
    }
  }

  public function __set($prop, $value){
    if (property_exists($this, $prop)){
      $this->$prop = $value;
    }
  }

  public function checkEmail($email){
    //Check email and compare to list, if match, grab ancillary information
    $row = 1;
    $emailMatch = false;

    global $gender;
    global $category;
    global $company;
    global $guestOf;

    // convert email string to all lowercase to make sure variable capitalization doesn't miss the email in wtf.csv
    $emailLower = strtolower($email);

    if (($handle = fopen(BASEPATH . '/wtf.csv', 'r')) !== FALSE){
      while (($data = fgetcsv($handle, 1500, ',')) !== FALSE){
        $row++;
        if ($data[3] == $emailLower){
          $rsvp->gender = $data[4];
          $rsvp->category = $data[5];
          $rsvp->company = $data[6];
          $rsvp->guestOf = $data[7];

          $emailMatch = true;
          fclose($handle);
          return true;
        }
      }
    } else {
      return false;
    }
  }
}

;?>
