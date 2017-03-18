<?php
	include '../lib/configure.php';
	session_start();
	if(isset($_SESSION['login_user'])){
		if ($_SESSION['login_user']=="doctor") {
			header("location: ../doctor_home.php");
		}
	}
	else {
		header("location: ../index.php");
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Stock</title>
<link rel="icon" href="../images/Logo---307x275.png" type="image/gif" sizes="16x16">
<!--CSS-->
<link href="../css/record_tables.css" rel="stylesheet" type="text/css"> 

<!-- dataTable -->
<link href="../jQueryAssets/datatables/css/jquery.dataTables.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../jQueryAssets/datatables/css/shCore.css">
<link rel="stylesheet" type="text/css" href="../jQueryAssets/datatables/css/demo.css">
<style type="text/css" class="init">
</style>
<script type="text/javascript" language="javascript" src="../jQueryAssets/datatables/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../jQueryAssets/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../jQueryAssets/datatables/js/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../jQueryAssets/datatables/js/demo.js"></script>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
	$('#data').DataTable();	
} );


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
            <a class="sub-head2" href="../features/report.php">REPORTS</a>
            <a class="sub-head2" href="profile.php">PROFILE</a>
            <a class="sub-head2" href="../lib/logout.php">LOGOUT</a>
        </div>
	    <div class="body">
			<div class="images_re">
		            <a href="../features/add_stock.php">
		                <img src="../images/add stock.png" style="width:85%;"><br>
		            </a>
		            <a href="../features/remove_stock.php">
		                <img src="../images/remove stock.png" style="width:85%;"><br>
		            </a>
		            <a href="../features/view_stock.php">
		                <img src="../images/view stock.png" style="width:85%;"><br>
			        </a>
			</div>
		<div id="datatable3">
		<table id="data" class="display">
			<thead>
				<tr id="datatable2">		
					<th>Date</th>
					<th>Bill No</th>
					<th>Received From</th>
					<th>Medicine Name</th>
					<th>Batch No</th>
					<th>Expiry</th>
					<th>Qty</th>
					<th>Cost</th>
				</tr>
			</thead>	
			<tbody>
			
				<?php
					if (isset($_GET['q']) && $_GET['q'] != NULL)
					{
						$med_name = $_GET['q'];
						$result = mysqli_query($conn, "SELECT * from medicine_stock where (MedicineName='$med_name')");
					}
				else {
						$result = mysqli_query($conn, "SELECT * from medicine_stock");
					}
					while($row = mysqli_fetch_array($result)) {
				?>
				<tr>
					<form action="" method="post">
						<td><center><?php echo date("d/m/Y",strtotime($row['Date']));?></center></td>
						<td><center><?php echo $row['BillNo'];?></center></td>
						<td><center><?php echo $row['RecievedFrom'];?></center></td>
						<td><center><?php echo $row['MedicineName'];?></center></td>
						<td><center><?php echo $row['BatchNo'];?></center></td>
						<td><center><?php echo date("d/m/Y",strtotime($row['Expiry']));?></center></td>
						<td><center><?php echo $row['Qty'];?></center></td>
						<td><center><?php echo $row['Cost'];?></center></td>
					</form>
				</tr>
				<?php } ?>
				
			</tbody>
		</table>
		</div>
		</div>
	</div>
</table>
</body>
</html>
