<?php

$db=require './db.inc.php';
$conf=require './config.inc.php';

// ADDING USERS TO DB
$stmt_createUsersTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `users`(
        `id` int AUTO_INCREMENT PRIMARY KEY,
        `email` varchar(30),
        `name` varchar(30),
        `password` varchar(32),
        `acc_type` enum('user', 'admin') DEFAULT 'user',
        `created_at` datetime DEFAULT now(),
        `updated_at` datetime DEFAULT now() ON UPDATE now(),
        `deleted_at` datetime DEFAULT NULL  
    )
");

$stmt_createUsersTable->execute();

// ADDING ADMIN TO USERS
$stmt_getUsers = $db->prepare("
  SELECT *
  FROM `users`
");
$stmt_getUsers->execute();
$numOfUsers = $stmt_getUsers->rowCount();

if( $numOfUsers <= 0 ) {
  $stmt_addAdmin = $db->prepare("
    INSERT INTO `users`
      (`name`, `email`, `password`, `acc_type`)
    VALUES
      (:name, :email, :password, :acc_type)
  ");
  $stmt_addAdmin->execute([
    ':name' => $conf['admin_name'],
    ':email' => $conf['admin_email'],
    ':password' => md5($conf['admin_password']),
    ':acc_type' => 'admin'
  ]);
}

// ADDING PROJECTS TO DB
$stmt_createProjectsTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `projects`(
        `id` int AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255),
        `client_name` varchar(255),
        `ident` int,
        `quantity` int,
        `created_at` datetime DEFAULT now(),
        `updated_at` datetime DEFAULT now() ON UPDATE now(),
        `deleted_at` datetime DEFAULT NULL  
    )
");

$stmt_createProjectsTable->execute();

// ADDING COMPONENTS TO DB
$stmt_createComponentsTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `components`(
        `id` int AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255),
        `created_at` datetime DEFAULT now(),
        `updated_at` datetime DEFAULT now() ON UPDATE now(),
        `deleted_at` datetime DEFAULT NULL  
    )
");

$stmt_createComponentsTable->execute();

// PIVOT TABLE IN DB FOR PROJECTS AND COMPONENTS
$stmt_createProjectsComponentsTable = $db->prepare("
  CREATE TABLE IF NOT EXISTS `projects_components` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `project_id` int,
    `components_id` int,
    `feeder_slot` int,
    `designator` varchar(30),
    `side` enum('Top', 'Bot') DEFAULT 'Top',
    `created_at` datetime DEFAULT now(),
    `deleted_at` datetime DEFAULT null
  )
");
$stmt_createProjectsComponentsTable->execute();

$stmt_createImagesTable = $db->prepare("
  CREATE TABLE IF NOT EXISTS `images` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `project_img_id` int,
    `img` varchar(255),
    `created_at` datetime DEFAULT now(),
    `updated_at` datetime DEFAULT now() ON UPDATE now(),
    `deleted_at` datetime DEFAULT null
  )
");
$stmt_createImagesTable->execute();



var_dump( $db->errorInfo() );