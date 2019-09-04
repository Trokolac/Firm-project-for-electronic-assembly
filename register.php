<?php 
require_once './User.class.php';
require_once './Helper.class.php';


// dodavanje novog korisnika
if( isset($_POST['register']) ) {
    $u = new User();
    $u->name = $_POST['name'];
    $u->email = $_POST['email'];
    $u->password = $_POST['password'];
    $u->password_repeat = $_POST['password_repeat'];
    if( $u->insert() ) {
        Helper::addMessage("Account created successfully!");
        header("Location: ./index.php");
        die();
    } else {
        header("Location: ./register.php");
        die();
    }
  }
?>

<?php include './header.layout.php'; ?>

    <form action="./register.php" method="post">

        <div class="form-row">
            <div class="col-md-6"></div>
            <div class="form-group col-md-3">
                <label for="inputName">Name</label>
                <input type="text" name="name" id="inputName" class="form-control" placeholder="Enter your name" />
            </div>

            <div class="form-group col-md-3">
                <label for="inputEmail">Email adress</label>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Enter email" />
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6"></div>
            <div class="form-group col-md-3">
                <label for="inputPassword">Password</label>
                <input type="password" name="password" id="inputPassword" class="form-control"
                    placeholder="Choose a password" />
            </div>

            <div class="form-group col-md-3">
                <label for="inputPasswordRepeat">Password repeat</label>
                <input type="password" name="password_repeat" id="inputPasswordRepeat" class="form-control"
                    placeholder="Confirm password" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button name="register" class="btn btn-outline-dark float-right">Register</button>
            </div>
        </div>
    </form>

<?php include './footer.layout.php'; ?>