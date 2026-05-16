<?php
session_start();

if(isset($_POST['login'])){
    if($_POST['username']=="umer" && $_POST['password']=="5555"){
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - FW BROTHERS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6f9;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:Arial;
        }

        .login-box{
            background:white;
            padding:35px;
            border-radius:15px;
            box-shadow:0 6px 20px rgba(0,0,0,0.08);
            width:350px;
            text-align:center;
            border-top:4px solid #0d6efd;
        }

        .logo-img{
            height:70px;
            width:70px;
            object-fit:cover;
            border-radius:12px;
            margin-bottom:10px;
            box-shadow:0 3px 10px rgba(0,0,0,0.15);
        }

        .title{
            font-size:22px;
            font-weight:bold;
            color:#0d6efd;
            margin-bottom:5px;
        }

        .subtitle{
            font-size:14px;
            color:#6c757d;
            margin-bottom:20px;
        }

        .form-control{
            border-radius:10px;
            padding:10px;
            margin-bottom:12px;
        }

        .btn-login{
            width:100%;
            border-radius:10px;
            padding:10px;
            font-weight:bold;
        }

        .error{
            background:#ffe5e5;
            color:#d63384;
            padding:8px;
            border-radius:8px;
            margin-bottom:10px;
            font-size:14px;
        }
    </style>
</head>

<body>

<div class="login-box">

    <!-- LOGO (CORRECT PLACE) -->
    <img src="logo.png" class="logo-img" alt="Logo">

    <div class="title">FW BROTHERS</div>
    <div class="subtitle">Management System Login</div>

    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post">

        <input type="text" name="username" class="form-control" placeholder="Username">

        <input type="password" name="password" class="form-control" placeholder="Password">

        <button type="submit" name="login" class="btn btn-primary btn-login">
            Login
        </button>

    </form>

</div>

</body>
</html>