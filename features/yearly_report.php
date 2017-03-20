<?php
	include ('../lib/configure.php');
	session_start();
	if(isset($_SESSION['login_user'])){
		if ($_SESSION['login_user']=="doctor") {
			header("location: ../doctor_home.php");
		}
	}
	else {
		header("location: ../index.php");
	}
	if (date("M")>=4)
	{
		$curr_year = date("Y");
	}
	else
	{
		$curr_year = date("Y")-1;
	}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Yearly Report</title>
<link rel="icon" href="../images/Logo---307x275.png" type="image/gif" sizes="16x16">
<!--CSS-->
<link href="../css/yearly_report.css" rel="stylesheet" type="text/css">

<!--JavaScript-->
<script>
	function validate()
	{
		element = document.getElementById("input_year");
		if ((element.value < '2011') || (element.value > <?php echo date("Y") ;?>))
		{
				window.alert("Invalid Year !!!");
				element.value="<?php echo $curr_year; ?>";
		}
	}
</script>
<style type="text/css" class="init">
.body{
    display: flex;
    padding-left: 20px;
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
    margin-top: 30px;
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
	    <div class="body">
			<div class="images_re">
		            <a href="../features/add_stock.php">
		                <img src="../images/add stock.png" style="width:80%;"><br>
		            </a>
		            <a href="../features/remove_stock.php">
		                <img src="../images/remove stock.png" style="width:80%;"><br>
		            </a>
		            <a href="../features/view_stock.php">
		                <img src="../images/view stock.png" style="width:80%;"><br>
		            </a>
		    </div>
			<div id="table1">
			Yearly Report
			<form action="" method="post" id="year">
				<input id="input_year" type=text name="year" value=<?php if (!isset($_POST['year'])) echo $curr_year; else echo $_POST['year']; ?> size="4"/>
				<input type=submit value="Go" size="5" onclick="validate()">
			</form>
			<a id="pdf_link" href="generate_pdf.php" target="_blank">Print</a>
			</div>
<?php
	/* Make sure that 2011 0 and 0 is in the Yearly Report table, otherwise there will be an error. 2011 because it is set as such
	 * in the validate function. This initial data must be there in the database.
	 */
	$result = mysqli_query($conn, "SELECT * From Yearly_Report");
	while (	$row = mysqli_fetch_array($result) )
	{
		$prev_year = $row['Year'];
		$found= false;
		if ($row['Year'] == $_POST['year'])
		{
			$opbal = $row['OpeningBal'];
			$pur = $row['Purchase'];
			$consumption = $row['Consumption'];
			$clbal = $row['ClosingBal'];
			$found = true;
			break;
		}
	}
	if (! $found) //If the values are not in the database, calculate till date and store in the database
	{
			$yr = $prev_year + 1;
			while ($yr <= $curr_year + 1)
			{

				$p_yr = $yr-1;
				$result = mysqli_query($conn, "SELECT * From Yearly_Report WHERE (Year = '$p_yr');");
				$row = mysqli_fetch_array($result);
				$opbal = $row['ClosingBal'];
				$from = $p_yr."/04/01";
				$to = $yr."/03/31";
				$result = mysqli_query($conn, "SELECT SUM(Cost) AS Total from Transactions WHERE ((Date >= '$from') AND (Date <= '$to') AND (Type='Addition'));");
				$row = mysqli_fetch_array($result);
				$pur = $row['Total'];
				$result = mysqli_query($conn, "SELECT SUM(Cost) AS Total from Transactions WHERE ((Date >= '$from') AND (Date <= '$to') AND (Type='Removal'));");
				$row = mysqli_fetch_array($result);
				$consumption = $row['Total'];
				$clbal = $opbal+$pur-$consumption;

				$result = mysqli_query($conn, "INSERT INTO Yearly_Report VALUES ('{$yr}','{$opbal}','{$pur}','{$consumption}','{$clbal}');");

				$yr = $yr + 1;
			}
			$result = mysqli_query($conn, "SELECT * From Yearly_Report");
			while (	$row = mysqli_fetch_array($result) )
			{
				if ($row['Year'] == $_POST['year'])
				{
					$opbal = $row['OpeningBal'];
					$pur = $row['Purchase'];
					$consumption = $row['Consumption'];
					$clbal = $row['ClosingBal'];
					break;
				}
			}
	}

?>



	<div id="stmt">
		<h1>Statement as on &nbsp; <span id="close_date" >31-03-<?php if(!isset($_POST['year'])) echo $curr_year; else echo $_POST['year']; ?></span></h1>
		<table id="report_table">
			<tr>
				<td>Opening Balance </td>
				<td><?php setlocale(LC_MONETARY, 'en_IN');echo money_format('%i', $opbal); ?> </td>
			</tr>
			<tr>
				<td>Total Purchase </td>
				<td><?php setlocale(LC_MONETARY, 'en_IN');echo money_format('%i', $pur); ?> </td>
			</tr>
			<tr>
				<td>Consumption </td>
				<td><?php setlocale(LC_MONETARY, 'en_IN');echo money_format('%i',$consumption); ?></td>
			</tr>
			<tr>
				<td>Closing Balance </td>
				<td><?php setlocale(LC_MONETARY, 'en_IN');echo money_format('%i',$clbal); ?> </td>
			</tr>
		</table>
	</div>
	<?php
		$_SESSION['year']=$_POST['year'];
		$_SESSION['opbal']=$opbal;
		$_SESSION['pur']=$pur;
		$_SESSION['consumption']=$consumption;
		$_SESSION['clbal']=$clbal;
	?>
	</div>
	</div>
</body>
</html>
