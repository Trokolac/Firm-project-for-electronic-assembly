<?php

class Project {
  private $db;
  public $id;
  public $name;
  public $client_name;
  public $ident;
  public $quantity;

  function __construct($id = null) {
    $this->db = require './db.inc.php';

    if($id) {
      $this->id = $id;
      $this->loadFromDB();
    }
  }

  public function loadFromDB() {
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `projects`
      WHERE `id` = :id
    ");
    $stmt_get->execute([ ':id' => $this->id ]);
    $project = $stmt_get->fetch();

    if( !$project ) {
      return false;
    }

    foreach( get_object_vars($project) as $key => $value ) {
      $this->$key = $value;
    }
  }

  public function insert() {

    if( !$this->identIsAvailable() ) {
      return false;
    }
    
    if( !$this->identIsEmpty() ) {
      return false;
    } 

    if( !$this->nameIsEmpty() ) {
      return false;
    }

    if( !$this->clientNameIsEmpty() ) {
      return false;
    }

    if( !$this->projectIsAvailable() ) {
      return false;
    } 

    $stmt_insert = $this->db->prepare("
      INSERT INTO `projects`
        (`name`, `client_name`, `ident`, `quantity`)
      VALUES
        (:name, :client_name, :ident, :quantity)
    ");
    return $stmt_insert->execute([
      ':name' => ucfirst($this->name),
      ':client_name' => ucfirst($this->client_name),
      ':ident' => $this->ident,
      ':quantity' => $this->quantity
    ]);
  }

  public function delete() {
    $stmt_delete = $this->db->prepare("
      DELETE
      FROM `projects`
      WHERE `id` = :id
    ");
    $stmt_delete->execute([ ':id' => $this->id ]);

    $stmt_deleteImages = $this->db->prepare("
      DELETE
      FROM `images`
      WHERE `project_img_id` = :project_img_id
    ");
    $stmt_deleteImages->execute([ ':project_img_id' => $this->id ]);

    $stmt_deleteComponents = $this->db->prepare("
      DELETE
      FROM `projects_components`
      WHERE `project_id` = :project_id
    ");
    return $stmt_deleteComponents->execute([':project_id' => $this->id]);
  }

  public function all() {
    $stmt_get = $this->db->prepare("
          SELECT (
          SELECT count(*)
          FROM `projects`
          WHERE `deleted_at` IS NULL
          ) as number_of_projects
    ");
    $stmt_get->execute();
    return $stmt_get->fetchAll();
  }

  public function allProjects() { 
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `projects`
      WHERE `deleted_at` IS NULL
      ORDER BY client_name DESC
    ");
    $stmt_get->execute();
    return $stmt_get->fetchAll();
  }

  public function projectDescription() { 
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `projects`
      WHERE `id` = :id
    ");
    $stmt_get->execute();
    return $stmt_get->fetchAll();
  }

  public function identIsAvailable() {
    $stmt_getIdent = $this->db->prepare("
      SELECT *
      FROM `projects`
      WHERE `ident` = :ident
    ");
    $stmt_getIdent->execute([ ':ident' => $this->ident ]);

    if( $stmt_getIdent->rowCount() > 0 ) {
      Helper::addError('Ident is already taken.');
      return false;
    }

    return true;
  }

  public function projectIsAvailable() {
    $stmt_getName = $this->db->prepare("
      SELECT *
      FROM `projects`
      WHERE `name` = :name
    ");
    $stmt_getName->execute([ ':name' => $this->name ]);

    if( $stmt_getName->rowCount() > 0 ) {
      Helper::addError('Name is already taken.');
      return false;
    }

    return true;
  }

  public function updateQuantity($cartId, $newQuantity) {
    $stmt_updateQuantity = $this->db->prepare("
      UPDATE `projects`
      SET `quantity` = :new_quantity
      WHERE `id` = :cart_id
    ");
    return $stmt_updateQuantity->execute([
      ':cart_id' => $cartId,
      ':new_quantity' => $newQuantity
    ]);
  }

  public function updateProjectName($project_id, $newName) {

    $stmt_checkName = $this->db->prepare("
    SELECT *
    FROM `projects`
    WHERE `name` = :new_name
  ");
  $stmt_checkName->execute([ 
    ':new_name' => $newName
     ]);

  if( $stmt_checkName->rowCount() > 0 ) {
    Helper::addError('Project name is already taken.');
  } else if($newName === ""){
    Helper::addError('Project name can not be empty.');
  } else{
      $stmt_updateName = $this->db->prepare("
      UPDATE `projects`
      SET `name` = :new_name
      WHERE `id` = :project_id
    ");
    return $stmt_updateName->execute([
      ':project_id' => $project_id,
      ':new_name' => ucfirst($newName)
    ]);
  }
  }

  public function updateClientName($project_id, $newClientName) {
    if($newClientName === ""){
      Helper::addError('Client name can not be empty.');
    } else{
    $stmt_updateClientName = $this->db->prepare("
      UPDATE `projects`
      SET `client_name` = :new_clientName
      WHERE `id` = :project_id
    ");
    return $stmt_updateClientName->execute([
      ':project_id' => $project_id,
      ':new_clientName' => ucfirst($newClientName)
    ]);
    }
  }

  public function identIsEmpty() {

    if( $this->ident == "" ) {
      Helper::addError('Have to have ident and must be unique.');
      return false;
    }

    return true;
  } 

  public function nameIsEmpty() {

    if( $this->name == "" ) {
      Helper::addError('Project has to have a name.');
      return false;
    }

    return true;
  }

  public function clientNameIsEmpty() {

    if( $this->client_name == "" ) {
      Helper::addError('Client has to have a name.');
      return false;
    }

    return true;
  } 

  public function addComponents($designator,$components) {

    if (!$designator || $designator == "") {
      Helper::addError('You need to have designator.');
      return false;
    }

    $stmt_addComponent = $this->db->prepare("
      INSERT INTO `projects_components`
      (`project_id`, `components_id`, `designator`, `side`)
      VALUES
      (:project_id, :components_id, :designator, :side)
      ");
    return $stmt_addComponent->execute([
      ':project_id' => $this->id,
      ':components_id' => $components,
      ':designator' => $designator,
      ':side' => $this->side
      ]);
    }

    public function componentsInProjectTop($id) { 
      $stmt_get = $this->db->prepare("
      SELECT
        projects_components.designator,
        projects_components.project_id,
        projects_components.components_id,
        projects_components.id,
        projects_components.deleted_at,
        components.name
      FROM `projects_components`, `components` , `projects`
      WHERE `projects_components`.`components_id` = `components`.`id` 
      AND `projects`.`id` = `projects_components`.`project_id`
      AND `projects_components`.`project_id` = $id
      AND `projects_components`.`side` = 'Top'
      ORDER BY 
        `projects_components`.`deleted_at` ASC,
        `components`.`name` ASC
      ");
      $stmt_get->execute();
      return $stmt_get->fetchAll();
    }

    public function componentsInProjectBot($id) { 
      $stmt_get = $this->db->prepare("
      SELECT
        projects_components.designator,
        projects_components.project_id,
        projects_components.components_id,
        projects_components.id,
        projects_components.deleted_at,
        components.name
      FROM `projects_components`, `components` , `projects`
      WHERE `projects_components`.`components_id` = `components`.`id` 
      AND `projects`.`id` = `projects_components`.`project_id`
      AND `projects_components`.`project_id` = $id
      AND `projects_components`.`side` = 'Bot'
      ORDER BY 
        `projects_components`.`deleted_at` ASC,
        `components`.`name` ASC
      ");
      $stmt_get->execute();
      return $stmt_get->fetchAll();
    }

    public function componentsInProjectTopGroup($id) { 
      $stmt_get = $this->db->prepare("
      SELECT DISTINCT
        projects_components.id,
        projects_components.designator,
        projects_components.project_id,
        projects_components.components_id,
        projects_components.feeder_slot,
        components.name
      FROM `projects_components`, `components` 
      WHERE `projects_components`.`components_id` = `components`.`id` 
      AND `projects_components`.`project_id` = $id
      AND `projects_components`.`side` = 'Top'
      AND `projects_components`.`deleted_at` IS NULL
      ORDER BY 
        `components`.`name` ASC,
        `projects_components`.`designator` ASC
      ");
      $stmt_get->execute();
      $results = $stmt_get->fetchAll();

      $items = [];

      foreach($results as $result) {
        if ( !array_key_exists($result->components_id, $items) ) {
          $items[$result->components_id] = [
            'id' => $result->id,
            'name' => $result->name,
            'feeder_slot' => $result->feeder_slot,
            'components_id' => $result->components_id,
            'designator' => [ $result->designator ],
          ];
        } else {
          $items[$result->components_id]['designator'][] = $result->designator;
        }
      }
      return $items;
    }

    public function componentsInProjectBotGroup($id) { 
      $stmt_get = $this->db->prepare("
      SELECT DISTINCT
        projects_components.id,
        projects_components.designator,
        projects_components.project_id,
        projects_components.components_id,
        projects_components.feeder_slot,
        components.name
      FROM `projects_components`, `components` 
      WHERE `projects_components`.`components_id` = `components`.`id` 
      AND `projects_components`.`project_id` = $id
      AND `projects_components`.`side` = 'Bot'
      AND `projects_components`.`deleted_at` IS NULL
      ORDER BY 
        `components`.`name` ASC,
        `projects_components`.`designator` ASC
      ");
      $stmt_get->execute();
      $results = $stmt_get->fetchAll();

      $items = [];

      foreach($results as $result) {
        if ( !array_key_exists($result->components_id, $items) ) {
          $items[$result->components_id] = [
            'id' => $result->id,
            'name' => $result->name,
            'feeder_slot' => $result->feeder_slot,
            'components_id' => $result->components_id,
            'designator' => [ $result->designator ],
          ];
        } else {
          $items[$result->components_id]['designator'][] = $result->designator;
        }
      }
      return $items;
    }
  
    public function removeComponent($id) {
      $stmt_removeFromCart = $this->db->prepare("
        DELETE
        FROM `projects_components`
        WHERE `id` = $id
      ");
      return $stmt_removeFromCart->execute([ ':id' => $id]);
    }  

    public function unuse($id) {
      $stmt_unuse = $this->db->prepare("
        UPDATE `projects_components`
        SET `deleted_at` = now()
        WHERE `id` = :id
      ");
      return $stmt_unuse->execute([ ':id' => $id ]);
    }

    public function reuse($id) {
      $stmt_reuse = $this->db->prepare("
        UPDATE `projects_components`
        SET `deleted_at` = null
        WHERE `id` = :id
      ");
      return $stmt_reuse->execute([ ':id' => $id ]);
    }

    public function addFeederTop($compId,$project_id,$feederSlot) {

      $stmt_feeder = $this->db->prepare("
        UPDATE `projects_components`
        SET `feeder_slot` = :new_feeder
        WHERE `components_id` = :components_id
        AND `project_id` = :project_id
        AND `side` = 'Top'
      ");
      return $stmt_feeder->execute([ 
        ':components_id' => $compId,
        ':project_id' => $project_id,
        ':new_feeder' => $feederSlot
        ]);
    }

    public function addFeederBot($compId,$project_id,$feederSlot) {

      $stmt_feeder = $this->db->prepare("
        UPDATE `projects_components`
        SET `feeder_slot` = :new_feeder
        WHERE `components_id` = :components_id
        AND `project_id` = :project_id
        AND `side` = 'Bot'
      ");
      return $stmt_feeder->execute([ 
        ':components_id' => $compId,
        ':project_id' => $project_id,
        ':new_feeder' => $feederSlot
        ]);
    }
    
    

}
