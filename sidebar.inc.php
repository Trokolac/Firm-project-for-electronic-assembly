<?php require_once './Projects.class.php'; ?>
<?php require_once './User.class.php'; ?>

<?php
  $pro = new Project();
  $projects = $pro->all();
?>


<div class="list-group sidebar">

<?php foreach($projects as $project) { ?>

    <a href="./projects.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
      <h5> <strong> Projects </strong> </h5>
      <span class="badge badge-pill badge-dark" style="padding: 8px 10px;">
        <?php echo $project->number_of_projects; ?>
      </span>
    </a>
    <a href="./components.php" class="list-group-item list-group-item-action">
      <h5> <strong> Component Database </strong> </h5>
    </a>

    <?php require_once './User.class.php'; ?>

                        <?php if( User::isLoggedIn() ) { ?>

                        <?php
                            $loggedInUser = new User();
                            $loggedInUser->loadLoggedInUser();
                        ?>
    <?php if($loggedInUser->acc_type == 'admin') { ?>
      <h6 class="dropdown-header mt-4">Admin only</h6>
    <a href="./add-projects.php" class="list-group-item list-group-item-action">
      <h5> <strong> Add projects </strong> </h5>
    </a>
    <a href="./add-components.php" class="list-group-item list-group-item-action">
      <h5> <strong> Manage components </strong> </h5>
    </a>
    <?php } ?>

<?php } ?>
<?php } ?>

</div>
