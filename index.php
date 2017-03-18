<!--
* File name: index.php
* The first page in the website. The login page.
-->

<?php

//error_reporting(E_ERROR);
include("lib/configure.php");
session_start();
if(isset($_SESSION['login_type'])){
    if($_SESSION['login_type']=="admin")
    {
        header("location: home.php");
    }
    else if($_SESSION['login_type']=="Doctor")
    {
        header("location: doctor_home.php");
    }
    else if($_SESSION['login_type']=="lab_admin")
    {
        header("location: lab_admin_home.php");
    }
}

$error="";

if(isset($_POST['submit'])) {
    session_start();

    // Define $myusername and $mypassword
    $myusername=$_POST['username'];
    $mypassword=$_POST['password'];

    echo $myusername;
    echo $mypassword;
    $myusername = stripslashes($myusername);
    $mypassword = stripslashes($mypassword);
    $myusername = mysqli_real_escape_string($conn,$myusername);
    $mypassword = sha1(mysqli_real_escape_string($conn,$mypassword));

    $sql="SELECT * FROM users WHERE UserName='$myusername' and Password='$mypassword'";
    echo $sql;
    $result=mysqli_query($conn, $sql);
    //echo $result;

    // Mysql_num_row is counting table row
    $count=mysqli_num_rows($result);

    // If result matched $myusername and $mypassword, table row must be 1 row

    if($count==1)
    {
        $row = mysqli_fetch_array($result);
        $_SESSION['login_user']=$myusername;
        $_SESSION['login_type']=$row['Type'];
        if($_SESSION['login_type']=="admin")
        {
            header("location: home.php");
        }
        else if($_SESSION['login_type']=="Doctor")
        {
            header("location: doctor_home.php");
        }
        else
        {
            header("location: lab_admin_home.php");
        }
    }
    else {
        $error = "Invalid Username or Password.";
    }
}

?>
  <!doctype html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="images/cross.png" type="image/x-icon" property="og:image" />
    <title>NITC Health Centre</title>
    <!--<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">-->
    <link href="css/materialize.min.css" type="text/css" rel="stylesheet" />
    <link href="css/login.css" type="text/css" rel="stylesheet" />
  </head>

  <body>
    <div class="card">
      <div class="top">
        <h3>NITC Health Centre </h3>
        <span class="form_error"><?php echo $error; ?></span>
        <div class="content">
          <form action="" method="post">
            <div class="input-container">
              <li>
                <input type="text" name='username' class="text" autocomplete="on" placeholder="User Name" />
              </li>
              <li>
                <input name='password' type="password" placeholder="Password" />
              </li>
              <li>
                <a href="features/forgot_password.php" id="forgot_pw">Forgot Password</a>
              </li>
            </div>
            <input class="bottom-content" type="submit" name="submit" value="Sign In" />
          </form>

        </div>
      </div>
    </div>
  </body>

  </html>
