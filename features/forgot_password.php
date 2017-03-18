<?php

error_reporting(E_ERROR);
include("../lib/configure.php");

$def_pw = "password";
$def_sha1_pw = "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8";

$error="";

if (isset($_POST['change']) && !isset($_POST['reset_pwd']))
{
    $user = $_POST['username'] ;
    $sql = "SELECT * FROM users WHERE (UserName='$user');";
    echo $sql;
    $result = mysqli_query($conn,$sql);
    $num = mysqli_num_rows($result);
    echo $num;
    if ($num == 0){
        $error = "Invalid Username provided.";
        unset($_POST['username']);
    }
    else
    {
        $row = mysqli_fetch_array($result);
        $sec1 = $row["SecQn1"];
        $sec2 = $row["SecQn2"];
        $valid = true;
    }
}

if (isset($_POST['reset_pwd']))
{
    $myans1 = sha1($_POST['ans1']);
    $myans2 = sha1($_POST['ans2']);
    $user = $_POST['username'] ;
    $sql = "SELECT * FROM users WHERE (UserName='$user');";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
    $ans1 = $row["Ans1"];
    $ans2 = $row["Ans2"];
    if (($myans1 == $ans1)&&($myans2 == $ans2))
    {
        $sql="UPDATE users SET Password='$def_sha1_pw' WHERE (UserName='$user')";
        $result=mysqli_query($conn, $sql);
        echo '<script>';
        echo 'alert("Succesful reset to - password .\nPlease change the password once you login. ");';
        echo 'window.location.href = "../index.php";';
        echo '</script>';
    }
    else
    {
        $error = "Wrong Answers given. Sorry!";
        unset($_POST['username']);
    }

}

?>
  <!doctype html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="../images/cross.png" type="image/x-icon" />
    <title>Reset Password - NITC Health Centre</title>
    <link href="../css/materialize.min.css" type="text/css" rel="stylesheet" />
    <link href="../css/forgot_password.css" type="text/css" rel="stylesheet" />
  </head>

  <body>
    <?php
if (!isset($_POST['username'])){
    ?>
      <div id="cardBefore" class="card">
        <div class="top">
          <div class="left-arrow">
            <a href="../index.php"><img src="../images/left-arrow.png" height="20" weight="20" /></a>
          </div>
          <div class="col.s8" style="display: inline;">
            <h3>Forgot Password? </h3>
          </div>
          <span class="form_error"><?php echo $error; ?></span>
          <div class="content">
            <form action="" method="post">
              <div class="input-container">
                <li>
                  <input type="text" name='username' class="text" autocomplete="on" placeholder="User Name" />
                </li>
              </div>
              <input class="bottom-content" type="submit" name="change" value="Change Password" />

            </form>
<?php
}
else{
        unset($_POST['change']);
?>
              <div id="cardAfter" class="card">
                <div class="top">
                  <div class="left-arrow">
                    <a href="../index.php"><img src="../images/left-arrow.png" height="20" weight="20" /></a>
                  </div>
                  <div class="col.s8" style="display: inline;">
                    <h3>Password change for <i><?php echo $_POST['username'] ?></i> </h3>
                  </div>
                  <span class="form_error"><?php echo $error; ?></span>
                  <div class="content">
                    <form action="" method="post">
                      <div class="input-container">
                        <li>
                          <p><b>Q1: <?php echo $sec1 ?></b></p>
                          <input name='ans1' type="password" placeholder="Answer 1 here" />
                        </li>
                        <li>
                          <p><b>Q2: <?php echo $sec2 ?></b></p>
                          <input name='ans2' type="password" placeholder="Answer 2 here" />
                        </li>
                      </div>
                      <input type="hidden" name="username" value="<?php echo $_POST['username'];?>" />
                      <input class="bottom-content" type="submit" name="reset_pwd" value="Reset Password" />
                    </form>
                  </div>
<?php
}
?>
        </div>
      </div>
    </div>
  </body>
</html>
