<?php

require_once './User.class.php';
require_once './Helper.class.php';

$loggedInUser = new User();
$loggedInUser->loadLoggedInUser();

if( $loggedInUser->acc_type != 'admin' ) {
  Helper::addError('You do not have permission to access requested page.');
  header('Location: ./index.php');
  die();
}
