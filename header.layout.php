<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Electric</title>
    <link rel="stylesheet" href="./CSS/main.css" />
    <link rel="stylesheet" href="./CSS/bootstrap.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

</head>
<body>
    <?php include './navbar.inc.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 mt-3">
                <?php include './sidebar.inc.php'; ?>
            </div>
            <div class="col-md-9 mt-3">
                <div class="content">

                <?php require_once './Helper.class.php'; ?>

                <?php if(Helper::ifError()) { ?>
                    <div class="alert alert-danger">
                    <strong>Error!</strong> <?php echo Helper::getError(); ?>
                    </div>
                <?php } ?>

                <?php if(Helper::ifMessage()) { ?>
                    <div class="alert alert-dark">
                    <strong>Success!</strong> <?php echo Helper::getMessage(); ?>
                    </div>
                <?php } ?>


                
   