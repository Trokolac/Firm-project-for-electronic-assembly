<?php 

class User {
    private $db;
    public $id;
    public $email;
    public $name;
    public $password;
    public $password_repeat;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    function __construct($id = null) {
      require_once './Helper.class.php';
      $this->db = require './db.inc.php';
  
      if( $id != null ) {
        $this->id = $id;
        $this->loadUserFromDB();
      }
    }
  
    public function loadUserFromDB() {
      $stmt_get = $this->db->prepare("
        SELECT *
        FROM `users`
        WHERE `id` = :id
      ");
      $stmt_get->execute([ ':id' => $this->id ]);
      $user = $stmt_get->fetch();
  
      if( !$user ) {
        return false;
      }
  
      foreach( get_object_vars($user) as $key => $value ) {
        $this->$key = $value;
      }
    }
     
    
    public function insert() {

      if( !$this->nameIsEmpty() ) {
        return false;
      }  

      if( !$this->emailIsValid() ) {
        return false;
      }  

      if( !$this->passwordIsValid() ){
        return false;
      }

      if( !$this->emailIsAvailable() ){
        return false;
      }

      $stmt_insert = $this->db->prepare("
          INSERT INTO `users`
            (`name`, `email`, `password`)
          VALUES
            (:name, :email, :password)
        ");
      $success = $stmt_insert->execute([
          ':name' => ucfirst($this->name),
          ':email' => $this->email,
          ':password' => md5($this->password) 
        ]);
        
      if(!$success) {
        return false;
      }

      Helper::sessionStart();
      $_SESSION['user_id'] = $this->db->lastInsertId();
      return true;

    }

    public function passwordIsValid() {

      if( $this->password == "" ) {
        Helper::addError('Password is empty.');
        return false;
      }
  
      if( $this->password != $this->password_repeat ) {
        Helper::addError('Passwords dont match.');
        return false;
      }
  
      return true;
    }
    
    public function emailIsValid() {

      if( !filter_var($this->email, FILTER_VALIDATE_EMAIL) ) {
        Helper::addError('Email is not valid.');
        return false;
      }
  
      return true;
    }

    public function nameIsEmpty() {

      if( $this->name == "" ) {
        Helper::addError('Username must be filled.');
        return false;
      }

      return true;

    }

    public function emailIsAvailable() {
      $stmt_getUserByEmail = $this->db->prepare("
        SELECT *
        FROM `users`
        WHERE `email` = :email
      ");
      $stmt_getUserByEmail->execute([ ':email' => $this->email ]);
  
      if( $stmt_getUserByEmail->rowCount() > 0 ) {
        Helper::addError('Email is already taken.');
        return false;
      }
  
      return true;
    }  
    
    public function login() {
      $stmt_getUser = $this->db->prepare("
        SELECT *
        FROM `users`
        WHERE `email` = :email
        AND `password` = :password
      ");
      $stmt_getUser->execute([
        ':email' => $this->email,
        ':password' => md5($this->password)
      ]);
  
      $user = $stmt_getUser->fetch();
  
      if( !$user ) {
        Helper::addError('Login failed. Please check your email and/or password.');
        return false;
      }
  
      Helper::sessionStart();
      $_SESSION['user_id'] = $user->id;
      return true;
    }
  
    
    public static function isLoggedIn() {
      require_once './Helper.class.php';
      Helper::sessionStart();
      return isset($_SESSION['user_id']) && $_SESSION['user_id'] != "";
    }
  
    public function loadLoggedInUser() {
      if( !User::isLoggedIn() ) {
        return false;
      }
      Helper::sessionStart();
      $this->id = $_SESSION['user_id'];
      $this->loadUserFromDB();
    }

    public function update() {
      require_once './Helper.class.php';
  
      if( !$this->emailIsValid() ) {
        return false;
      }
  
      if( $this->new_password == "" ) {
        Helper::addError('Password can not be empty.');
        header('Location: ./update-profile.php');
        die();
      } else {
  
        if( $this->new_password != $this->password_repeat ) {
          Helper::addError('Passwords do not match.');
          return false;
        }
  
        $password = md5($this->new_password);
      }
  
      $stmt_update = $this->db->prepare("
        UPDATE `users`
        SET
         `name` = :name,
         `email` = :email,
         `password` = :password,
         `acc_type` = :acc_type
        WHERE `id` = :id
      ");
      return $stmt_update->execute([
        ':name' => ucfirst($this->name),
        ':email' => $this->email,
        ':password' => $password,
        ':acc_type' => $this->acc_type,
        ':id' => $this->id
      ]);
    }
  
}