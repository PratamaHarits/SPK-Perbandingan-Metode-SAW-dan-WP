<?php

session_start();

include '../tools/connection.php';

// LOGIN USER

if (isset($_SESSION["login_user"])) {
    header("location: ../home/home.php");
    exit();
}

if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->query("SELECT * FROM ta_user WHERE user_nama = '$username'");

    //cek username
    if (mysqli_num_rows($query) === 1) {

        //cek password
        $row = mysqli_fetch_assoc($query);
        if ($password === $row["user_password"]) {

            // set session
            $_SESSION["login_user"] = true;

            header("location: ../home/home.php");
            exit();
        }
    }
    $error = true;
}

// LOGIN ADMIN

if (isset($_SESSION["login_admin"])) {
    header("location: ../admin/admin.php");
    exit();
}

if (isset($_POST['login_admin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->query("SELECT * FROM ta_admin WHERE admin_nama = '$username'");

    //cek username
    if (mysqli_num_rows($query) === 1) {

        //cek password
        $row = mysqli_fetch_assoc($query);
        if ($password === $row["admin_password"]) {

            // set session
            $_SESSION["login_admin"] = true;

            header("location: ../admin/admin.php");
            exit();
        }
    }
    $error = true;
}


?>

<?php include '../blade/header.php' ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-info">
            <?php include '../blade/namaProgram.php'; ?>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-4 shadow py-3">

                    <p class="text-center fw-bold">Login Sistem</p>
                    <hr>

                    <form action="" method="post">
                        <?php if (isset($error)) : ?>
                            <p style="color: red; font-weight: bold;">DATA INPUT SALAH !!</p>
                        <?php endif; ?>
                        <div class="row mb-3">
                            <label for="username" class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="username" autocomplete="off" autofocus required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="col-sm-4 col-form-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="d-grid gap-3 text-center">
                            <p>Login Sebagai</p>
                        </div>
                        <div class="d-grid gap-3 text-center">
                            <button type="submit" class="btn btn-info" name="login_user">User</button>
                            <button type="submit" class="btn btn-primary" name="login_admin">Admin</button>
                        </div>
                    </form>

                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>
    </div>
</div>