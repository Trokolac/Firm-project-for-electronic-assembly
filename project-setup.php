<?php 
  require './admin-only.inc.php';
  require_once './Projects.class.php';
  require_once './Components.class.php'; 

    
  $comp = new Component();

  if ( isset($_POST['search']) ) {
    $components = $comp->search($_POST['search']);
  } else {
    $components = $comp->allComponents();
  }
   
?>
<?php
// Display components per project and per side
$componentsInProjectTop = new Project();
$compInProjectTop = $componentsInProjectTop->componentsInProjectTop($_GET['id']);
$componentsInProjectBot = new Project();
$compInProjectBot = $componentsInProjectBot->componentsInProjectBot($_GET['id']);

if( isset($_POST['add']) ) {
  $addingComps = new Project($_GET['id']);
  $addingComps->side = $_POST['side'];
  if( $addingComps->addComponents($_POST['designator'] , $_POST['components_id']) ) {
    Helper::addMessage("Component added to project.");
    header("Location: ./project-setup.php?id=".$_GET['id']);
    die();
  } else {
    header("Location: ./project-setup.php?id=".$_GET['id']);
    die();
  }
}
?>
<?php 
$productObject = new Project();
if( isset($_POST['deleteFromProjectsComponents']) ) {
  if( $productObject->removeComponent($_POST['components_id']) ) {
    Helper::addMessage('Component deleted from project.');
    header("Location: ./project-setup.php?id=".$_GET['id']);
    die();
  } else {
    Helper::addError('Failed to remove component from project.');
    header("Location: ./project-setup.php?id=".$_GET['id']);
    die();
  }
}
$unuse = new Project();
if( isset($_POST['unuse']) ) {
  if( $unuse->unuse($_POST['components_id']) ) {
    Helper::addMessage('Component removed from project.');
    header("Location: ./project-setup.php?id=".$_GET['id']);
    die();
  } else {
    Helper::addError('Failed to remove component from project.');
    header("Location: ./project-setup.php?id=".$_GET['id']);
    die();
  }
}

$reuse = new Project();
if( isset($_POST['reuse']) ) {
  if( $reuse->reuse($_POST['components_id']) ) {
    Helper::addMessage('Component reused in project.');
    header("Location: ./project-setup.php?id=".$_GET['id']);
    die();
  } else {
    Helper::addError('Failed to reuse component from project.');
    header("Location: ./project-setup.php?id=".$_GET['id']);
    die();
  }
}
$showComps = new Project($_GET['id']);
$projectSetup = new Project($_GET['id']);
    
?>

<?php include './header.layout.php'; ?>

<div class="row">
  <div class="col-md-12">
    <h2> <strong> <?php echo "$projectSetup->client_name"; ?> / <?php echo "$projectSetup->name"; ?> </strong> </h2>
  </div>
</div>


<div class="row">
  <div class="col-md-6">


    <p><strong>Add components to project</strong></p>
    <form action="./project-setup.php?id=<?php echo $showComps->id;?>" method="post" class="form-inline">
      <div class="input-group" style="width:100%;">
        <input type="text" name="search" class="form-control" placeholder="Search for components from database" />

        <div class="input-group-append">
          <button class="btn btn-dark">Search</button>
        </div>

      </div>
    </form>


    <table class="table">

      <thead>
        <tr>
          <th>
            <a href="./project-setup.php?id=<?php echo $showComps->id; ?>"><i class="fas fa-redo"></i></a>&emsp;
            Name
          </th>
          <th>Designator</th>
          <th>Top</th>
          <th>Bot</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach($components as $component) { ?>
        <tr>
          <td><?php echo "$component->name"; ?></td>
          <form action="./project-setup.php?id=<?php echo $showComps->id; ?>" method="post">
            <th>
              <input type="text" name="designator" style="width:80px;">
            </th>
            <td class="inputField">
              <input name="side" type="radio" name="checkbox" value="Top" id="1" checked />
              <label for="1"></label>
            </td>
            <td class="inputField">
              <input name="side" type="radio" name="checkbox" value="Bot" id="2" />
              <label for="2"></label>
            </td>
            <th>
              <input type="hidden" name="components_id" value="<?php echo $component->id; ?>" />
              <button name="add" class="btn btn-sm btn-outline-success" style="width:100%;">Add</button>
            </th>
          </form>
        </tr>
        <?php } ?>

      </tbody>

    </table>


  </div>
  <div class="col-md-6">
      
      <div class="row">
      <div class="col-md-12">
        <?php if($compInProjectTop){ ?>
        
        <p><strong>Top side</strong></p>
        <table class="table">

          <thead>
            <tr>
              <th>Name</th>
              <th>Designator</th>
              <th>Action</th>
              <th>Reuse</th>
              <th>Unuse</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($compInProjectTop as $compIn) { ?>
            <form action="./project-setup.php?id=<?php echo $compIn->project_id; ?>" method="post">

              <?php 
              if($compIn->deleted_at !== null ){
                echo "<tr style='background-color: #969696;'>";
              } ?>
              <td><?php echo $compIn->name; ?></td>
              <th><?php echo $compIn->designator; ?></th>
              <th>
                <input type="hidden" name="components_id" value="<?php echo $compIn->id ?>" />
                <button name="deleteFromProjectsComponents" class="btn btn-sm btn-danger" style="width:100%;"><i
                    class="far fa-trash-alt"></i></button>
              </th>
              <th>
                <button name="reuse" class="btn btn-sm btn-success" style="width:100%;"><i
                    class="fas fa-check"></i></button>
              </th>
              <th>
                <button name="unuse" class="btn btn-sm btn-dark" style="width:100%;"><i
                    class="fas fa-times"></i></button>
              </th>
              </tr>

            </form>
            <?php } ?>
          </tbody>

        </table>
        <a href="#top"><button class="btn btn-dark float-right">Back to top</button></a>
        <?php } ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <?php if($compInProjectBot){ ?>
          
        <p><strong>Bottom side</strong></p>
        <table class="table">

          <thead>
            <tr>
              <th>Name</th>
              <th>Designator</th>
              <th>Action</th>
              <th>Reuse</th>
              <th>Unuse</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($compInProjectBot as $compIn) { ?>
            <form action="./project-setup.php?id=<?php echo $compIn->project_id; ?>" method="post">
              <?php 
              if($compIn->deleted_at !== null ){
                echo "<tr style='background-color: #969696;'>";
              } ?>
              <td><?php echo $compIn->name; ?></td>
              <th><?php echo $compIn->designator; ?></th>
              <th>
                <input type="hidden" name="components_id" value="<?php echo $compIn->id ?>" />
                <button name="deleteFromProjectsComponents" class="btn btn-sm btn-danger" style="width:100%;"><i
                    class="far fa-trash-alt"></i></button>
              </th>
              <th>
                <button name="reuse" class="btn btn-sm btn-success" style="width:100%;"><i
                    class="fas fa-check"></i></button>
              </th>
              <th>
                <button name="unuse" class="btn btn-sm btn-dark" style="width:100%;"><i
                    class="fas fa-times"></i></button>
              </th>
              </tr>
            </form>
            <?php } ?>
          </tbody>
          
          </table>
          <a href="#top"><button class="btn btn-dark float-right">Back to top</button></a>
          <?php } ?>
      </div>
    </div>
  </div>
</div>










<?php include './footer.layout.php'; ?>