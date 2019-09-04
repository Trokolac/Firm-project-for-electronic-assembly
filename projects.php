<?php require_once './Projects.class.php'; ?>
<?php require './user-only.inc.php'; ?>


<?php
    if( isset($_POST['delete']) ) {
      $projectToDelete = new Project($_POST['project_id']);
      if( $projectToDelete->delete() ) {
      Helper::addMessage("Project deleted successfully.");
      header('Location: ./projects.php');
      die();
      } else {
      Helper::addError("Failed to delete project.");
      } 
    }

    $projectObject = new Project();

    if( isset($_POST['update_quantity']) ) {
        if( $projectObject->updateQuantity($_POST['cart_id'], (int)$_POST['new_quantity']) ) {
          Helper::addMessage('Quantity updated successfully.');
          header('Location: ./projects.php');
          die();
        } else {
          Helper::addError('Failed to update quantity.');
        }
      }

    $projectList = new Project();
    $projectsList = $projectList->allProjects();
?>


<?php include './header.layout.php'; ?>

<h4>Projects</h4>

<table class="table mt-5">

  <thead>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Client</th>
      <th>Quantity</th>
      <th>Actions</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach($projectsList as $project) { ?>
    <tr>
      <th><?php echo "$project->ident"; ?></th>
      <td><?php echo "$project->name"; ?></td>
      <td><?php echo "$project->client_name" ?></td>
      <?php if($loggedInUser->acc_type == 'user') { ?>
      <?php if($project->quantity === null){
                  echo "<td><strong> 0 </strong></td>";
                } else{
                    echo "<td><strong> $project->quantity </strong></td>"; 
                } ?>
      <?php } ?>
      <?php if($loggedInUser->acc_type == 'admin') { ?>
      <td>
        <form action="./projects.php" method="post">
          
          <div class="input-group input-group-sm">
            <input type="hidden" name="cart_id" value="<?php echo $project->id; ?>" />
            <input type="number" name="new_quantity" class="" style="width: 50%;"
              value="<?php echo $project->quantity; ?>" placeholder="<?php if($project->quantity === null){
                  echo "0";
                } else{
                    echo $project->quantity; 
                } ?>" />

            <div class="input-group-append">
              <button name="update_quantity" class="btn btn-outline-dark form-control">Update</button>
            </div>
          </div>
          
        </form>
      </td>
      <?php } ?>
        <?php if($loggedInUser->acc_type == 'user') { ?>
          <td>
          <a href="./project-details.php?id=<?php echo $project->id; ?>">
            <button name="remove_from_cart" class="btn btn-sm btn-outline-dark"><i class="far fa-window-restore"></i>
              View
            </button>
          </a>
          </td>
        <?php } ?>
      
      
      <?php if($loggedInUser->acc_type == 'admin') { ?>
      <td>
      <div class="dropdown" >
        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          SetUp
        </button>
          <div class="dropdown-menu dropdown-menu-right drp mt-3 text-center" aria-labelledby="dropdownMenu2">
              <a href="./project-details.php?id=<?php echo $project->id; ?>">
                <button name="remove_from_cart" class="btn btn-sm btn-outline-dark" style="width:95%;"><i class="far fa-window-restore"></i> View/Update</button>
              </a>
              <a href="./project-setup.php?id=<?php echo $project->id; ?>"><button class="btn btn-sm btn-outline-dark mt-1 mb-2" style="width:95%;"><i class="fas fa-cogs"></i> Add Components</button></a>
              <div class="dropdown-divider"></div>
              <form action="./projects.php" method="post">
                <input type="hidden" name="project_id" value="<?php echo $project->id; ?>" />
                <button name="delete" class="btn btn-sm btn-outline-danger mt-2" style="width:95%;"><i class="far fa-trash-alt"></i> Delete</button>
              </form>
        </div>
      </div>
      </td>
      <?php } ?>
  
    </tr>
    <?php } ?>
  </tbody>

</table>

<div class="row">
  <div class="col-md-12 mt-3 mb-3">
  <a href="#top"><button class="btn btn-dark float-right">Back to top</button></a>
  </div>
</div>

<?php include './footer.layout.php'; ?>