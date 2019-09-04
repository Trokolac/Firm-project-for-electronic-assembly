<?php
if( !isset($_GET['id']) ) {
    header("Location: ./index.php");
  }
require_once './Projects.class.php';
require_once './Image.class.php';
require_once './Helper.class.php';

?>

<?php 

if( isset($_POST['update_feeder_top']) ) {
  $projectObject = new Project();
    if( $projectObject->addFeederTop($_POST['components_id'], $_POST['project_id'], (int)$_POST['feeder_slot']) ) {
      Helper::addMessage("Feeder added/updated successfully.");
      header("Location: ./project-details.php?id=".$_GET['id']);
      die();
    }
  }

if( isset($_POST['update_feeder_bot']) ) {
  $projectObject = new Project();
    if( $projectObject->addFeederBot($_POST['components_id'], $_POST['project_id'], (int)$_POST['feeder_slot']) ) {
      Helper::addMessage("Feeder added/updated successfully.");
      header("Location: ./project-details.php?id=".$_GET['id']);
      die();
    }
  }

// Display components per project and per side
$componentsInProjectTop = new Project();
$compInProjectTop = $componentsInProjectTop->componentsInProjectTop($_GET['id']);
$componentsInProjectBot = new Project();
$compInProjectBot = $componentsInProjectBot->componentsInProjectBot($_GET['id']);
$componentsInProjectTopGroup = new Project();
$compInProjectTopGroup = $componentsInProjectTopGroup->componentsInProjectTopGroup($_GET['id']);
$componentsInProjectBotGroup = new Project();
$compInProjectBotGroup = $componentsInProjectBotGroup->componentsInProjectBotGroup($_GET['id']);
?>

<?php 

$projectUpdate = new Project();

if( isset($_POST['add']) ) {
  $p = new Image();
  $p->project_img_id = $_POST['project_img_id'];
  $p->imageData = $_FILES['image'];
  if( $p->insert() ) {
    Helper::addMessage("Image added successfully.");
    header("Location: ./project-details.php?id=".$_GET['id']);
    die();
  }
}

if( isset($_POST['update_name']) ) {
    if( $projectUpdate->updateProjectName($_POST['project_id'], $_POST['new_name']) ) {
      Helper::addMessage('Project name updated successfully.');
      header("Location: ./project-details.php?id=".$_GET['id']);
      die();
    } else {
      header("Location: ./project-details.php?id=".$_GET['id']);
      die();
    }
  }

  if( isset($_POST['update_clientName']) ) {
    if( $projectUpdate->updateClientName($_POST['project_id'], $_POST['new_clientName']) ) {
      Helper::addMessage('Client name updated successfully.');
      header("Location: ./project-details.php?id=".$_GET['id']);
      die();
    } else {
      header("Location: ./project-details.php?id=".$_GET['id']);
      die();
    }
  }

  if( isset($_POST['removeImage']) ) {
    $imageToDelete = new Image($_POST['image_id']);
    if( $imageToDelete->deleteImage() ) {
    Helper::addMessage("Image deleted successfully.");
    header("Location: ./project-details.php?id=".$_GET['id']);
    die();
    } else {
    Helper::addError("Failed to delete image.");
    header("Location: ./project-details.php?id=".$_GET['id']);
    die();
    } 
  }

  
  $projectDesc = new Project($_GET['id']);

  $projectImage = new Image();
  $projectImg = $projectImage->fromImage($_GET['id']);
?>
<?php include './header.layout.php'; ?>

<section id="project">
  <div class="row">
    <div class="col-md-2">
      <h5> <strong> Name: </strong> </h5>
    </div>
    <div class="col-md-5">
      <?php echo "$projectDesc->name"; ?>
    </div>
    <div class="col-md-5">
      <?php if($loggedInUser->acc_type == 'admin') { ?>
      <form action="./project-details.php?id=<?php echo $projectDesc->id; ?>" method="post">
        <div class="input-group input-group-sm" style="width: 100%;">
          <input type="hidden" name="project_id" value="<?php echo $projectDesc->id; ?>" />
          <input type="text" style="width: 70%;" name="new_name"
            placeholder="Update name of <?php echo $projectDesc->name; ?> . . ." />

          <div class="input-group-append">
            <button name="update_name" class="btn btn-outline-dark form-control">Update</button>
          </div>
        </div>
      </form>
      <?php } ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-2">
      <strong>Client:</strong>
    </div>
    <div class="col-md-5">
      <?php echo "$projectDesc->client_name"; ?>
    </div>
    <div class="col-md-5">
      <?php if($loggedInUser->acc_type == 'admin') { ?>
      <form action="./project-details.php?id=<?php echo $projectDesc->id; ?>" method="post">
        <div class="input-group input-group-sm">
          <input type="hidden" name="project_id" value="<?php echo $projectDesc->id; ?>" />
          <input type="text" style="width: 70%;" name="new_clientName"
            placeholder="Update client <?php echo $projectDesc->client_name; ?> . . ." />

          <div class="input-group-append">
            <button name="update_clientName" class="btn btn-outline-dark form-control">Update</button>
          </div>
        </div>
      </form>
      <?php } ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-2">
      <strong>Ident:</strong>
    </div>
    <div class="col-md-5">
      <?php echo "$projectDesc->ident"; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <strong>Quantity:</strong>
    </div>
    <div class="col-md-5">
      <?php if($projectDesc->quantity === null){
            echo "0";
          } else{
            echo "$projectDesc->quantity"; 
          } ?>
    </div>
    <div class="col-md-5">
      <button class="btn btn-sm btn-dark" onclick="myFunction()"><i class="fas fa-print"></i> Print project</button> &emsp;
      <a href="#image"><button class="btn btn-sm btn-dark">Show images</button></a>
    </div>
  </div>
  <hr class="mt-3">
  <div class="row mt-4">
    <div class="col-md-2">
      <p>Created at:</p>
    </div>
    <div class="col-md-10">
      <?php echo "<u>$projectDesc->created_at</u>"; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <p>Updated at:</p>
    </div>
    <div class="col-md-10">
      <?php echo "<u>$projectDesc->updated_at</u>"; ?>
    </div>
  </div>
  <?php if($loggedInUser->acc_type == 'admin') { ?>
  <div class="row mt-3">
    <div class="col-md-12">
      <form action="./project-details.php?id=<?php echo $projectDesc->id; ?>" method="post" class="clearfix"
        enctype="multipart/form-data">

        <input type="hidden" name="project_img_id" value="<?php echo $projectDesc->id;?>" />
        <label for="inputImage">
          <h5>Image:</h5>
        </label> &emsp;
        <input type="file" name="image" id="inputImage" style="width:25%;" />

        <button name="add" class="btn btn-sm btn-dark">Add image</button>
      </form>
      
    </div>
  </div>
  <?php } ?>
  <div class="row">
    <div class="col-md-12">
      <?php if($compInProjectTop){ ?>
      <p class="mt-5" style="font-size:20px;"><strong>Top side</strong></p>
      <table class="table">

        <thead>
          <tr>
            <th style="width:25%;">Feeder slot</th>
            <th style="width:20%;">Component name</th>
            <th style="width:50%;">Designator</th>
            <th style="width:5%;">Quantity</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach($compInProjectTopGroup as $compInGroup) { ?>
          <tr>
          <?php if($loggedInUser->acc_type == 'admin') { ?>
            <td>
              <form action="./project-details.php?id=<?php echo $projectDesc->id; ?>" method="post">
              <div class="input-group input-group-sm">
                <input type="hidden" name="components_id" value="<?php echo $compInGroup['components_id']; ?>" />
                <input type="hidden" name="project_id" value="<?php echo $projectDesc->id; ?>" />
                <input type="number" name="feeder_slot" style="width: 20%;" placeholder="<?php echo $compInGroup['feeder_slot']; ?>" />

                <div class="input-group-append">
                  <button name="update_feeder_top" class="btn btn-outline-dark form-control">Set</button>
                </div>
              </div>
              </form>
            </td>
          <?php } ?>
          <?php if($loggedInUser->acc_type == 'user') { ?>
            <td>
              <?php 
              if($compInGroup['feeder_slot'] == ""){
                echo "0";
              } else {
                echo $compInGroup['feeder_slot']; 
              }
              ?>
            </td>
          <?php } ?>
            <td><?php echo $compInGroup['name']; ?></td>
            <td><?php echo implode(', ', $compInGroup['designator']); ?></td>
            <td><?php echo count($compInGroup['designator']); ?></td>
          </tr>
          <?php } ?>
        </tbody>

      </table>
      <?php } ?>


    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php if($compInProjectBot){ ?>
      <p class="mt-5" style="font-size:20px;"><strong>Bottom side</strong></p>
      <table class="table">

        <thead>
          <tr>
            <th style="width:25%;">Feeder slot</th>
            <th style="width:20%;">Component name</th>
            <th style="width:50%;">Designator</th>
            <th style="width:5%;">Quantity</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach($compInProjectBotGroup as $compInGroup) { ?>
          <tr>
          <?php if($loggedInUser->acc_type == 'admin') { ?>
            <td>
              <form action="./project-details.php?id=<?php echo $projectDesc->id; ?>" method="post">
              <div class="input-group input-group-sm">
                <input type="hidden" name="components_id" value="<?php echo $compInGroup['components_id']; ?>" />
                <input type="hidden" name="project_id" value="<?php echo $projectDesc->id; ?>" />
                <input type="number" name="feeder_slot" style="width: 20%;" placeholder="<?php echo $compInGroup['feeder_slot']; ?>" />

                <div class="input-group-append">
                  <button name="update_feeder_bot" class="btn btn-outline-dark form-control">Set</button>
                </div>
              </div>
              </form>
            </td>
          <?php } ?>
          <?php if($loggedInUser->acc_type == 'user') { ?>
            <td>
              <?php 
              if($compInGroup['feeder_slot'] == ""){
                echo "0";
              } else {
                echo $compInGroup['feeder_slot']; 
              }
              ?>
            </td>
          <?php } ?>
            <td><?php echo $compInGroup['name']; ?></td>
            <td><?php echo implode(', ', $compInGroup['designator']); ?></td>
            <td><?php echo count($compInGroup['designator']); ?></td>
          </tr>
          <?php } ?>
        </tbody>

      </table>
      <?php } ?>
    </div>
  </div>
</section>


<?php if($projectImg){ ?>
<p class="mt-5" style="font-size:20px;"><strong>Images</strong></p>
<?php } ?>
<?php foreach($projectImg as $img) { ?>
<div class="row mt-1" id="image">
  <div class="col-md-12 img-wrapper">
      <img src="<?php echo ($img->img) ? $img->img : './img/no-image.png' ?>" class="mt-1" style="width:100%;" />
    <div class="img-overlay">
    <?php if($loggedInUser->acc_type == 'admin') { ?>
    <form action="./project-details.php?id=<?php echo $projectDesc->id; ?>" method="post"> 
      <input type="hidden" name="image_id" value="<?php echo $img->id; ?>" />
      <button name="removeImage" class="btn btn-md btn-outline-danger"><i class="fas fa-times"></i></button>
    </form>
    <?php } ?>
    </div>
  </div>
</div>
<?php } ?>

<div class="row">
  <div class="col-md-12 mt-3 mb-3">
  <a href="#top"><button class="btn btn-dark float-right">Back to top</button></a>
  </div>
</div>

<script>
  function myFunction() {
    window.print();
  }
</script>




<?php include './footer.layout.php'; ?>