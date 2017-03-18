<?php
	/* Assumes that add_stock.php and remove_stock.php populates the transactions table. Two additional fields - Type (Addition or Removal - Set to 'Addition' by default) and Transaction_Date (name="transaction_date")
	 * The table shows all fields in add_stock (with 'Date being renamed to Purchase_Date') plus Type field and Date field ( which will be the Transaction_Date in the database).
	*/
	include ('../lib/configure.php');
	session_start();
	if(isset($_SESSION['login_type']))
	{
	if ($_SESSION['login_type']=="Doctor")
	{
		header("location: ../doctor_home.php");
	}
}
else {
	header("location: ../index.php");
}
	$count=0;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Monthly Report</title>

<!--CSS-->
<link href="../css/report.css" rel="stylesheet" type="text/css">
<link rel="icon" href="../images/cross.png" type="image/gif" sizes="16x16">
<!-- Datepicker -->
<link href="../jQueryAssets/datepicker/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/datepicker/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/datepicker/jquery.ui.datepicker.min.css" rel="stylesheet" type="text/css">
<script src="../jQueryAssets/datepicker/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="../jQueryAssets/datepicker/jquery-ui-1.9.2.datepicker.custom.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$( ".Datepicker" ).datepicker({ changeMonth: true, changeYear: true, showOtherMonths: true, selectOtherMonths: true, dateFormat:"dd-mm-yy"});
});
</script>

<!-- dataTable -->
<link href="../jQueryAssets/datatables/css/jquery.dataTables.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../jQueryAssets/datatables/css/shCore.css">
<link rel="stylesheet" type="text/css" href="../jQueryAssets/datatables/css/demo.css">
<style type="text/css" class="init">
</style>
<script type="text/javascript" language="javascript" src="../jQueryAssets/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../jQueryAssets/datatables/js/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../jQueryAssets/datatables/js/demo.js"></script>
<script type="text/javascript" language="javascript" class="init">


$(document).ready(function() {
	$('#data').DataTable();
} );


	</script>

<script>
	function reset()
	{
		<?php $creditCount = 0; $debitCount = 0;?>;
	}
</script>

</head>

<body>
<input type="button" class="home" value="" onClick="location.href='../home.php'">
<input type="button" class="logout" value="logout" onClick="location.href='../lib/logout.php'">
<div id="table1">
Monthly Report
</div>

<div id="from_to">
	<form action="" method="POST">
        Medicine Name:
        <select name="med_name">
            <?php
                $med_list = mysqli_query($conn, "SELECT MedicineName FROM medicine_stock GROUP BY MedicineName;");
                while($row = mysqli_fetch_array($med_list)){ ?>
                    <option value="<?php echo $row['MedicineName'];?>"><?php echo $row['MedicineName'];?></option>
        <?php } ?>
        </select>
		<input type="submit" name="go" value="Go" class="button">
	</form>
</div>



<!-- Necessary part to be edited -->
<?php
if (isset($_POST['med_name'])){?>
    <div id="Stock_Values">
        <span>Medicine: <?php echo $_POST['med_name'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="balance">Balance: 0</span>

    </div>

<div id="datatable3">
<table id="data" class="display">
	<thead>
		<tr id="datatable2">
			<th>Year</th>
            <th>Month</th>
			<th>Added</th>
			<th>Removed</th>
		</tr>
	</thead>
	<tbody>

		<?php
    			$result = mysqli_query($conn, "SELECT Month(Date), Year(Date) from Transactions WHERE MedicineName = '".$_POST['med_name']."' GROUP BY Month(Date), Year(Date);");
                //$creditCount = 0; $debitCount = 0;
                while($row = mysqli_fetch_array($result))
    			{
                    $m = $row['Month(Date)'];
                    $y = $row['Year(Date)'];
                    $credit = mysqli_query($conn, "SELECT SUM(Qty) FROM Transactions WHERE Type = 'Addition' AND MedicineName = '".$_POST['med_name']."' AND MONTH(Date) = '".$m."' AND YEAR(Date) = '".$y."';");
                    $credit = mysqli_fetch_array($credit);
                    $debit = mysqli_query($conn, "SELECT SUM(Qty) FROM Transactions WHERE Type = 'Removal' AND MedicineName = '".$_POST['med_name']."' AND MONTH(Date) = '".$m."' AND YEAR(Date) = '".$y."';");
                    $debit = mysqli_fetch_array($debit);
                    $dateObj   = DateTime::createFromFormat('!m', $m);
                    $monthName = $dateObj->format('F');
                    $creditCount = $creditCount+$credit[0];
                    $debitCount = $debitCount+$debit[0];
    		?>
    		<tr>
    			<form action="" method="post">
    				<td><center><?php echo $row['Year(Date)'];?></center></td>
    				<td><center><?php echo $monthName;?></center></td>
    				<td><center><?php echo $credit[0];?></center></td>
    				<td><center><?php echo $debit[0]?></center></td>
    			</form>
    		</tr>
    		<?php }
            $creditCount = $creditCount - $debitCount;
            //echo $creditCount;
            }?>


<!--Original part -->
    <script>
        document.getElementById("balance").innerHTML = "Balance: <?php echo $creditCount; ?>";
    </script>



	</tbody>
</div>
</body>
</html>
