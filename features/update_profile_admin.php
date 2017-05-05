<?php
include ('../lib/configure.php');
session_start();
if(isset($_SESSION['login_type']))
{
	if ($_SESSION['login_type']=="Doctor")
	{
		header("location: ../doctor_home.php");
	}
}
else 
{
	header("location: ../index.php");
}

$user = $_SESSION['login_user'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE (UserName='$user');");
$row = mysqli_fetch_array($result);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Update Admin Profile</title>
<link rel="icon" href="../images/Logo---307x275.png" type="image/gif" sizes="16x16">
<link href="../css/update_profile.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
	function validate(form)
	{
		
		if (form.username.value == "")
		{
			alert("Enter a username to save changes");
			form.username.focus();
			window.scrollByLines(-8);
			form.username.value = "";
			return false;
			
		}
		if (form.new_pw.value != form.retype.value)
		{
			alert("Passwords don't match");
			form.new_pw.focus();
			form.new_pw.value = "";
			form.retype.value="";
			return false;
		}
		if (form.pw.value == "")
		{
			alert("You must enter your current password to update changes");
			form.pw.focus();
			return false;
		} 

		return true;
	}
	
	<?php if(isset($_GET['q']))
	{
	?>
		alert("<?php echo $_GET['q'] ?>");
	<?php
	}
	?>
</script>
<style>
.body{
	display: flex;
	margin-left: 20px;
}
.images_re{
	padding-top: 10px;
}
.head{
    width: 100%;
    display: flex;
    height: 110px;
    font-family: "Lato",sans-serif;
}
.image1{
    margin-left: 30px;
    width: 120px;
}
.sub-head1{
    width: 40%;
    font-size: 40px;
    margin-right: 240px;
    margin-top: 45px;
}
.sub-head2{
    color: black;
    font-size: 20px;
    width: 10%;
    margin-left: 20px;
    margin-top: 45px;
}
a:link{
    text-decoration: blink;
}
</style>

</head>

<body>
<div>
	<div class="head">
	    <img type="button" class="image1" src="../images/Logo---307x275.png" value=""  onClick="location.href='../home.php'">
	    <div class="sub-head1">
	        NITC HEALTH CENTER
	    </div>
	    <a class="sub-head2" href="yearly_report.php">REPORTS</a>
            <a class="sub-head2" href="update_profile_admin.php">PROFILE</a>
	    <a class="sub-head2" href="../lib/logout.php">LOGOUT</a>
	</div>
<div id="table1">
		Update Profile
		<form action="update.php" method="post" name="fields" onsubmit="return validate(this);">
		<div class="input_area">
			<span>Name : </span><input name="name" type="text" class ="input_class_med" autocomplete="on" value="<?php echo $row['Name']; ?>" autofocus onfocus="var val=this.value; this.value=''; this.value= val;"> <br/>
			<span>Username : </span><input name="username" type="text" class ="input_class_med" autocomplete="on" value="<?php echo $row['UserName']; ?>"><br>
		</div>
		<p>Change Password</p>
		<div class="input_area">
			<span>New : </span><input name="new_pw" type="password" class ="input_class_med" autocomplete='off'><br/>
			<span>Retype :</span><input name="retype" type="password" class ="input_class_med"  autocomplete='off'><br>
		</div>
	
		<p>Change Security Questions</p>
		<div class="input_area">
			<span>Security Qn1 :</span><textarea name="sec_qn_1" cols="37" rows="13" onfocus="var val=this.value; this.value=''; this.value= val;"><?php echo $row['SecQn1']; ?></textarea><br>
			<span>Ans1 :</span><input name="ans1" type="password" class ="input_class_med" value="" autocomplete='off'><br>
			<span style="color:#f5f4f3;">Enter Password to save changes <sup style="color:red">*</sup> </span>
			<br/>
			<input name="pw" type="password" class ="input_class_med" autocomplete='off'>
		</div>
		<input type="submit" name="submit" value="Save">
		<br/>
		<br/>
	</form>

</div>	
</div>
</body>
</html>

