<?php require './admin-only.inc.php'; ?>

<?php 
require_once './Projects.class.php';
require_once './Helper.class.php'; 

if( isset($_POST['create']) ) {
    $p = new Project();
    $p->name = $_POST['name'];
    $p->client_name = $_POST['client_name'];
    $p->ident = $_POST['project_ID'];
    if( $p->insert() ) {
      Helper::addMessage("Project created successfully.");
      header('Location: ./add-projects.php');
      die();
    } else {
      header('Location: ./add-projects.php');
      die();
    }
  }

?>

<?php include './header.layout.php'; ?>

<h4>Add projects</h4>

<form class="mt-4 clearfix" action="./add-projects.php" method="post">
    <div class="form-row">
        <div class="form-group col-md-4">
        <label for="inputProjectID">Project ID</label>
        <input
            type="number"
            class="form-control"
            id="inpuProjectID"
            placeholder="Enter project ID here"
            name="project_ID" />
        </div>

        <div class="form-group col-md-4">
        <label for="inputProjectName">Project name</label>
        <input
            type="text"
            class="form-control"
            id="inputProjectName"
            placeholder="Enter project title here"
            name="name" />
        </div>

        <div class="form-group col-md-4">
        <label for="inputClientName">Client name</label>
        <input
            type="text"
            class="form-control"
            id="inpuClientName"
            placeholder="Enter client name here"
            name="client_name" />
        </div>
    </div>

  <button name="create" class="btn btn-outline-dark float-right">
    Create project
  </button>
</form>



<?php include './footer.layout.php'; ?>