<?php

class Image {
  private $db;
  public $id;
  public $project_img_id;
  public $img;
  public $imageData;
  public $productImgDir = './img/products/';
  public $allowedImgTypes = ['image/jpeg', 'image/gif', 'image/png'];
  public $maxImageSize = 2097152; // 2 * 1024 * 1024
  public $created_at;
  public $updated_at;
  public $deleted_at;

  function __construct($id = null) {
    require_once './Helper.class.php';
    $this->db = require './db.inc.php';

    if($id) {
      $this->id = $id;
      $this->loadFromDB();
    }
  }

  public function loadFromDB() {
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `images`
      WHERE `id` = :id
    ");
    $stmt_get->execute([ ':id' => $this->id ]);
    $product = $stmt_get->fetch();

    if( !$product ) {
      return false;
    }

    foreach( get_object_vars($product) as $key => $value ) {
      $this->$key = $value;
    }
  }

  public function insert() {
    $stmt_insert = $this->db->prepare("
      INSERT INTO `images`
        (`project_img_id`, `img`)
      VALUES
        (:project_img_id, :img)
    ");
    $success = $stmt_insert->execute([
      ':project_img_id' => $this->project_img_id,
      ':img' => $this->img
    ]);

    if( $success && $this->imageData ) {
      $this->id = $this->db->lastInsertId();
      return $this->handleImageUpload();
    } else {
      return $success;
    }
  }

  public function handleImageUpload() {
    file_exists($this->productImgDir) or mkdir($this->productImgDir, 0777, true);
    $ext = pathinfo($this->imageData['name'], PATHINFO_EXTENSION);
    $imagePath = "{$this->productImgDir}{$this->id}.$ext";

    if( !$this->imageIsValid() ) {
      // $this->delete();
      return false;
    }

    move_uploaded_file($this->imageData['tmp_name'], $imagePath);
    $this->img = $imagePath;
    $this->update();
    return true;
  }

  public function imageIsValid() {
    if( !in_array($this->imageData['type'], $this->allowedImgTypes) ) {
      Helper::addError('File type not allowed.');
      return false;
    }

    if ( $this->imageData['size'] > $this->maxImageSize ) {
      Helper::addError('Please choose smaller image.');
      return false;
    }

    return true;
  }

  public function all() {
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `images`
      WHERE `deleted_at` IS NULL
    ");
    $stmt_get->execute();
    return $stmt_get->fetchAll();
  }

  public function update() {
    $stmt_update = $this->db->prepare("
      UPDATE `images`
      SET
       `project_img_id` = :project_img_id,
       `img` = :img
      WHERE `id` = :id
    ");
    return $stmt_update->execute([
      ':project_img_id' => $this->project_img_id,
      ':img' => $this->img,
      ':id' => $this->id
    ]);
  }

  public function fromImage($id) {
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `images`
      WHERE `deleted_at` IS NULL
      AND `project_img_id` = :project_img_id
    ");
    $stmt_get->execute([ ':project_img_id' => $id ]);
    return $stmt_get->fetchAll();
  }

  public function deleteImage() {
    $stmt_deleteImages = $this->db->prepare("
      DELETE
      FROM `images`
      WHERE `id` = :id
    ");
    return $stmt_deleteImages->execute([ ':id' => $this->id ]);
  }

}
