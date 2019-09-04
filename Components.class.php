<?php 

class Component {
    private $db;
    public $id;
    public $name;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    function __construct($id = null) {
      require_once './Helper.class.php';
      $this->db = require './db.inc.php';
  
      if( $id ) {
        $this->id = $id;
        $this->loadComponentsFromDB();
      }
    }
  
    public function loadComponentsFromDB() {
      $stmt_get = $this->db->prepare("
        SELECT *
        FROM `components`
        WHERE `id` = :id
      ");
      $stmt_get->execute([ ':id' => $this->id ]);
      $component = $stmt_get->fetch();
  
      if( !$component ) {
        return false;
      }
  
      foreach( get_object_vars($component) as $key => $value ) {
        $this->$key = $value;
      }
    }
     
    
    public function insert() {

      if( !$this->nameIsEmpty() ) {
        return false;
      }
      
      if( !$this->componentIsAvailable() ) {
        return false;
      } 

      $stmt_insert = $this->db->prepare("
          INSERT INTO `components`
            (`name`)
          VALUES
            (:name)
        ");
      return $stmt_insert->execute([
          ':name' => $this->name 
        ]);

    }


    public function nameIsEmpty() {

      if( $this->name == "" ) {
        Helper::addError('Component has to have a name.');
        return false;
      }

      return true;

    }  

    public function componentIsAvailable() {
      $stmt_getName = $this->db->prepare("
        SELECT *
        FROM `components`
        WHERE `name` = :name
      ");
      $stmt_getName->execute([ ':name' => $this->name ]);
  
      if( $stmt_getName->rowCount() > 0 ) {
        Helper::addError('This component already exists.');
        return false;
      }
  
      return true;
    }

    public function allComponents() { 
      $stmt_get = $this->db->prepare("
        SELECT *
        FROM `components`
        WHERE `deleted_at` IS NULL
        ORDER BY name DESC
      ");
      $stmt_get->execute();
      return $stmt_get->fetchAll();
    }
  
    public function delete() {
      $stmt_delete = $this->db->prepare("
        DELETE
        FROM `components`
        WHERE `id` = :id
      ");
      $stmt_delete->execute([ ':id' => $this->id ]);

      $stmt_deleteComp = $this->db->prepare("
      DELETE
      FROM `projects_components`
      WHERE `components_id` = :components_id
    ");
    return $stmt_deleteComp->execute([':components_id' => $this->id]);
    }  

    public function search($q) {
      $q = "%$q%";
      $stmt_search = $this->db->prepare("
        SELECT *
        FROM `components`
        WHERE `name` LIKE :q
      ");
      $stmt_search->execute([ ':q' => $q ]);
      return $stmt_search->fetchAll();
    }
  
}