<?php



require 'classes/User.php';
require 'classes/Database.php';


session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $db = new Database();
    $conn = $db->getConn();
    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {

        session_regenerate_id(true);

        $_SESSION['is_logged_in'] = true;

        echo "Your logged in:";
    } else {
        $error = "login Incorrect";
    }
}
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<?php if (!empty($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>



<section class="pb-4">
    <div class="border rounded-5">

        <section class="w-100 p-4 p-xl-5" style="background-color: #9A616D; border-radius: .5rem .5rem 0 0;">
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img1.webp" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;">
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5">

                                    <form action="" method="Post">

                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <img src="includes\images\—Pngtree—paper icon png and eps_3581509.png" alt="" height="70px" width="70px">
                                            <span class="h1 fw-bold mb-0">Login User</span>
                                        </div>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Logging in to
                                            access the page</h5>

                                        <div class="form-outline mb-4">
                                            <input type="text" name="username" id="username" class="form-control form-control-lg">
                                            <label class="form-label" for="form2Example17" style="margin-left: 0px;">Username</label>
                                            <div class="form-notch">
                                                <div class="form-notch-leading" style="width: 9px;"></div>
                                                <div class="form-notch-middle" style="width: 88.8px;"></div>
                                                <div class="form-notch-trailing"></div>
                                            </div>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input type="password" id="password" name="password" class="form-control form-control-lg">
                                            <label class="form-label" for="form2Example27" style="margin-left: 0px;">Password</label>
                                            <div class="form-notch">
                                                <div class="form-notch-leading" style="width: 9px;"></div>
                                                <div class="form-notch-middle" style="width: 64px;"></div>
                                                <div class="form-notch-trailing"></div>
                                            </div>
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block">Login</button>
                                            <a href="password-reset.php" class="btn btn-lg btn-primary">Reset
                                                password</a>
                                        </div>
                                        <div class="text-center mt-3">

                                            <!-- <button type="submit" class="btn btn-lg btn-primary">Reset password</button> -->
                                        </div>


                                        <p class="mb-5 pb-lg-2">Don't have an account? <a href="register.php">Register
                                                here</a>
                                        </p>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>