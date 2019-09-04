<?php require './user-only.inc.php'; ?>
<?php require_once './User.class.php'; ?>
<?php require_once './Helper.class.php'; ?>

<?php
  $loggedInUser = new User();
  $loggedInUser->loadLoggedInUser();

  if( isset($_POST['update']) ) {
    $loggedInUser->name = $_POST['name'];
    $loggedInUser->email = $_POST['email'];
    $loggedInUser->new_password = $_POST['new_password'];
    $loggedInUser->password_repeat = $_POST['password_repeat'];
    if( $loggedInUser->update() ) {
      Helper::addMessage('Profile updated successfully.');
      header('Location: ./update-profile.php');
      die();
    }
  }

?>
<?php include './header.layout.php'; ?>

<h4>Update profile</h4>

<form class="mt-5 clearfix" action="./update-profile.php" method="post">
  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="inputName">Name</label>
      <input
        type="text"
        class="form-control"
        id="inputName"
        placeholder="Your name"
        value="<?php echo $loggedInUser->name; ?>"
        name="name" />
    </div>

    <div class="form-group col-md-6">
      <label for="inputEmail">Email</label>
      <input
        type="email"
        class="form-control"
        id="inputEmail"
        placeholder="Email"
        value="<?php echo $loggedInUser->email; ?>"
        name="email" />
    </div>

  </div>

  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="inputPassword">Password</label>
      <input
        type="password"
        class="form-control"
        id="inputPassword"
        placeholder="Choose password"
        name="new_password" />
    </div>

    <div class="form-group col-md-6">
      <label for="inputPasswordRepeat">Password repeat</label>
      <input
        type="password"
        class="form-control"
        id="inputPasswordRepeat"
        placeholder="Enter password again"
        name="password_repeat" />
    </div>

  </div>

  <button name="update" class="btn btn-outline-dark float-right">
    Update profile
  </button>
</form>


<?php include './footer.layout.php'; ?>