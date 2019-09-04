<div class="container-fluid" id="top">
        <div class="row">
            <div class="col-md-5">
                <h2 class="mt-1 mb-2">
                    <a href="./index.php">EMS</a>
                </h2>
            </div>
            <div class="col-md-7">
                <div class="bar">
                    <div class="dropdown mt-2 mb-2">
                        
                        <?php require_once './User.class.php'; ?>

                        <?php if( User::isLoggedIn() ) { ?>

                        <?php
                            $loggedInUser = new User();
                            $loggedInUser->loadLoggedInUser();
                        ?>

                        <button class="btn btn-outline-dark dropdown-toggle" type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="dropdownMenuLink">
                            <?php echo $loggedInUser->name; ?> 
                            (<?php if($loggedInUser->acc_type == 'admin') { echo "admin"; } else { echo "user"; }?>)
                        </button>

                        <div class="dropdown-menu dropdown-menu-right mt-3 drp" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="./update-profile.php">Update profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item dropa" href="./logout.php">Log out</a>
                        </div>
                        <?php } else { ?>

                        <a href="./login.php"><button class="btn btn-outline-dark">Log in</button></a>
                        <a href="./register.php"><button class="btn btn-outline-dark">Sign up</button></a>

                    </div> <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    </div>
    <hr>
    