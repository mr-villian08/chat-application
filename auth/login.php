<?php

include('../config/DB.php');
include('../utils/AccessorAndMutators.php');
include('../controllers/AuthController.php');
include("../Middleware/Validations.php");

$auth = new AuthController(Database::connect());

if (isset($_POST['login'])) {
    $email = AccessorsAndMutators::setEmail($_POST['email']);
    $password = AccessorsAndMutators::setPassword($_POST['password']);

    $login = $auth->login($email, $password);
    if ($login['status']) {
        $_SESSION['userLoggedIn'] = ['id' => $login['data']->id, 'name' => $login['data']->name, 'username' => $login['data']->username, 'email' => $login['data']->email, 'isLogin' => $login['data']->is_login];
        header("location: ../index.php");
    }
}

function getInputValue($name)
{
    if (isset($_POST[$name])) {
        return $_POST[$name];
    }
}

if (isset($_SESSION['userLoggedIn'])) {
    header("location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col col-md-4 mt-5">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">

                        <form method="post" id="login-form">

                            <div class="form-group">
                                <?= $auth->getError("login")  ?>
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" class="form-control" value="<?= getInputValue('email') ?>" required />
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required />
                            </div>

                            <div class="form-group text-center mt-4">
                                <input type="submit" name="login" class="btn btn-primary" value="Login" />
                            </div>

                        </form>

                        <div class="justify-content-md-end align-items-end">
                            Not have an account <a href="./register.php" class="text-decoration-none">Click here</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>