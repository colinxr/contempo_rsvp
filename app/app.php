<?php
  require_once __DIR__ . '/../config/config.php';
  // include(__DIR__ . '/email-postmark.php');

  // *****
  // Cleans form data and prepares RSVP class for use
  //
  // @param array $arr: array of form data from homepage rsvp form
  // @param object $obj: rsvp class object
  //
  // return object: transformed rsvp object with sanitized data,
  // augmented with aditional info if plus one
  //
  // *****
  function formSanitize($arr) {

    $data = array();

    //Get form info
    $data['email']     = strtolower($arr['rsvp']['email']); //match with Email Lower column in wtf.csv
    $data['firstName'] = ucwords(strtolower($arr['rsvp']['first-name'])); //stray capitals in user form
    $data['lastName']  = ucwords(strtolower($arr['rsvp']['last-name'])); //stray capitals in user form
    $data['postal']    = strtoupper($arr['rsvp']['postal']); // proper Postal Code form

    $data['hasGuest']       = false;
    $data['guestFirstName'] = '';
    $data['guestLastName']  = '';
    $data['guestEmail']     = '';

    if (isset($arr['rsvp']['plus-one'])){
      $data['hasGuest']       = true; // handles plus one inputs, used to set sql query
      $data['guestFirstName'] = ucwords(strtolower($arr['rsvp']['guest-firstName']));// Formats data for any stray capitals in user form
      $data['guestLastName']  = ucwords(strtolower($arr['rsvp']['guest-lastName']));// Formats data for any stray capitals in user form
      $data['guestEmail']     = strtolower($arr['rsvp']['guest-email']);
    }

    return $data; // return obj for use in other functions
  }

?>
