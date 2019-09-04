<?php require_once './User.class.php';

if( isset($_POST['login']) ) {
    $u = new User();
    $u->email = $_POST['email'];
    $u->password = $_POST['password'];
    if( $u->login() ) {
      header("Location: ./index.php");
      die();
    } else {
        header("Location: ./login.php");
        die();
    }
}

?>

<?php include './header.layout.php'; ?>

    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <form action="./login.php" method="post">
                <div class="form-group">
                    <label for="inputEmail">Email address</label>
                    <input type="text" name="email" class="form-control" id="inputEmail" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Enter password">
                </div>
                <button name="login" class="btn btn-outline-dark float-right">Log in</button>
            </form>
        </div>
    </div>

<?php include './footer.layout.php'; ?>