<?php

class Helper {

  public static function sessionStart() {
    if( !isset($_SESSION) ) {
      session_start();
    }
  }

  public static function addMessage($message) {
    Helper::sessionStart();
    return $_SESSION['message'] = $message;
  }

  public static function getMessage() {
    Helper::sessionStart();

    if( !isset($_SESSION['message']) ) {
      return false;
    }

    $message = $_SESSION['message'];
    unset($_SESSION['message']);
    return $message;
  }

  public static function ifMessage() {
    Helper::sessionStart();
    return isset($_SESSION['message']);
  }

  public static function addError($error) {
    Helper::sessionStart();
    return $_SESSION['error'] = $error;
  }

  public static function getError() {
    Helper::sessionStart();

    if( !isset($_SESSION['error']) ) {
      return false;
    }

    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    return $error;
  }

  public static function ifError() {
    Helper::sessionStart();
    return isset($_SESSION['error']);
  }

}
