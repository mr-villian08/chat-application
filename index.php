<?php


include "./config/DB.php";
include "./Controllers/AuthController.php";
include "./Controllers/ChatController.php";
include "./Controllers/UserController.php";

$auth = new AuthController(Database::connect());

if (!isset($_SESSION['userLoggedIn'])) {
    header("location: ./auth/register.php");
}

$chatController = new ChatController(Database::connect());
$userController = new UserController(Database::connect());

$chats = $chatController->show();
$users = $userController->show();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>

    <link rel="stylesheet" href="/assets/styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- ************************************************************************************** My code ******************************************************************************************** -->
    <div class="container">
        <div>
            <?= $auth->getError('logout') ?>
            <form method="post" action="<?php $auth->logOut(); ?>">
                <button type="submit" class="btn btn-success" name="logout"> Logout </button>
            </form>
        </div>
        <br />
        <h3 class="text-align-center">Welcome to chat room</h3>
        <br />
        <div class="row">

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Chat Room</h3>
                    </div>
                    <div class="card-body" id="messages_area">
                        <?php
                        if (empty($chats)) {
                        ?> No Chats found
                            <?php
                        } else {
                            foreach ($chats as $chat) {
                                if (isset($_SESSION["userLoggedIn"])) {
                                    $from = "Me";
                                    $rowClass = "row justify-content-start";
                                    $backgroundClass = "text-dark alert-right";
                                } else {
                                    $from = $chat['username'];
                                    $rowClass = "row justify-content-end";
                                    $backgroundClass = "alert-success";
                                }
                            ?>

                                <div class="<?= $rowClass ?>">
                                    <div class="col-sm-10">
                                        <div class="shadow-sm alert <?= $backgroundClass ?>">
                                            <b> <?= $from  ?> </b> <?= $chat['message']  ?> <br>
                                            <div class="text-right">
                                                <small><i><?= $chat["created_at"] ?></i></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php  }
                        } ?>
                    </div>
                </div>

                <form method="post" id="chat_form" data-parsley-errors-container="#validation_error">
                    <div class="input-group mb-3">
                        <textarea class="form-control" id="chat_message" name="chat_message" placeholder="Type Message Here" data-parsley-maxlength="1000" data-parsley-pattern="/^[a-zA-Z0-9\s]+$/" required></textarea>
                        <div class="input-group-append">
                            <button type="submit" name="send" id="send" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4">

                <div class="card mt-3">
                    <div class="card-header">User List</div>
                    <div class="card-body" id="user_list">
                        <div class="list-group list-group-flush">
                            <?php
                            foreach ($users as $key => $user) {
                                $icon = '<i class="fa fa-circle text-danger"></i>';

                                if ($user['is_login']) {
                                    $icon = '<i class="fa fa-circle text-success"></i>';
                                }

                                if ($user["id"] != $_SESSION["userLoggedIn"]['id']) {
                            ?>
                                    <a class="list-group-item list-group-item-action">
                                        <span class="ml-1"><strong><?= $user["username"] ?></strong></span>
                                        <span class="mt-2 float-right"><?= $icon ?></span>
                                    </a>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./assets/socket.js"></script>
</body>

</html>