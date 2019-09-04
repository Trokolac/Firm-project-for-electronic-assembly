<?php require './admin-only.inc.php'; ?>

<?php 
require_once './Components.class.php';
require_once './Helper.class.php';

if( isset($_POST['create']) ) {
    $c = new Component();
    $c->name = $_POST['name'];
    if( $c->insert() ) {
      Helper::addMessage("Component created successfully.");
      header('Location: ./add-components.php');
      die();
    } else {
      header('Location: ./add-components.php');
      die();
    }
  }

  $comp = new Component();

  if ( isset($_POST['search']) ) {
    $components = $comp->search($_POST['search']);
  } else {
    $components = $comp->allComponents();
  }

if( isset($_POST['delete']) ) {
  $componentToDelete = new Component($_POST['component_id']);
  if( $componentToDelete->delete() ) {
  Helper::addMessage("Component deleted successfully.");
  header('Location: ./add-components.php');
  die();
  } else {
  Helper::addError("Failed to delete component.");
  header('Location: ./add-components.php');
  die();
  } 
}

?>

<?php include './header.layout.php'; ?>
<div class="row">
  <div class="col-md-12">
    <h4>Add Component</h4>
  </div>
</div>


<form class="mt-4 clearfix" action="./add-components.php" method="post">
  <div class="form-row">
    <div class="form-group col-md-12">
      <label for="inputComponentName">Component name</label>
      <input type="text" class="form-control" id="inputComponentName" placeholder="Enter component name and value here"
        name="name" />
    </div>

  </div>
  <button name="create" class="btn btn-outline-dark float-right">Create component</button>

</form>

<div class="row">
  <div class="col-md-6">
    <form action="./add-components.php" method="post">
      <div class="input-group" style="width:100%;">


        <input type="text" name="search" class="form-control" placeholder="Search for components from database . . ." />

        <div class="input-group-append">
          <button class="btn btn-outline-dark">Search</button>
        </div>
    </form>
  </div>

</div>
<div class="col-md-6"></div>
</div>


<table class="table mt-5">

  <thead>
    <tr>
      <th>
      <a href="./add-components.php"><i class="fas fa-redo"></i></a>&emsp;
      Name 
      </th>
      <th>ID</th>
      <th>Action</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach($components as $component) { ?>
    <tr>
      <td><?php echo "$component->name"; ?></td>
      <th><?php echo "$component->id"; ?></th>
      <td>
        <form action="./add-components.php" method="post">
          <input type="hidden" name="component_id" value="<?php echo $component->id; ?>" />
          <button name="delete" class="btn btn-sm btn-outline-danger mt-1" style="width:60%;"><i
              class="far fa-trash-alt"></i> Delete</button>
        </form>
      </td>
    </tr>
    <?php } ?>

  </tbody>

</table>

</div>
<div class="row">
  <div class="col-md-12 mt-3 mb-3">
  <a href="#top"><button class="btn btn-dark float-right">Back to top</button></a>
  </div>
</div>
</div>



<?php include './footer.layout.php'; ?>