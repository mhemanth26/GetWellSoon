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
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Remove Stock</title>
<link rel="icon" href="../images/cross.png" type="image/gif" sizes="16x16">
<!--CSS-->
<link href="../css/record_tables.css" rel="stylesheet" type="text/css">
</style>
<style>
	.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

    }

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
<!-- dataTable -->
<link rel="stylesheet" type="text/css" href="../jQueryAssets/datatables/css/jquery.dataTables1.css">
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

</head>
<body>
<div id="datatable3">
<table id="data" class="display">
	<thead>
		<tr id="datatable2">
			<th>Purchase Date</th>
			<th>Received From</th>
			<th>Medicine Name</th>
			<th>Batch No</th>
			<th>Expiry</th>
			<th>Qty</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if(isset($_POST['remove']))
			 {
				$result = mysqli_query($conn, "SELECT `Qty`,`Cost` from medicine_stock WHERE (`BatchNo`='{$_POST['BatchNo']}' AND MedicineName='{$_POST['MedicineName']}');");       //primary key is med name+ batch no.
				$qty=mysqli_fetch_array($result);

				$cst=$qty['Cost'];
				$qty=$qty['Qty'];
				
				if($_POST['Qty'] > $qty)
				{
					?><script> alert("Invalid Quantity");</script><?php
				}
				else if (($_POST['Qty'] >= 0)and($_POST['Qty']<=1000000000)and (is_numeric($_POST['Qty'])))
				{
					//populating Transactions table
					$result=mysqli_query($conn, "SELECT * FROM medicine_stock WHERE (BatchNo= '{$_POST['BatchNo']}' AND MedicineName='{$_POST['MedicineName']}' );");
					$cur_date = date("Y-m-d");
					$row = mysqli_fetch_array($result);
					//while($row = mysqli_fetch_array($result))
					//{
					$dt = $row['Date'];
					$bno = $row['BatchNo'];
					$rcvfrm = $row['RecievedFrom'];
					$md_nm = $row['MedicineName'];
					$btch_no = $row['BatchNo'];
					$exp = date("Y-m-d",strtotime($row['Expiry']));
					$qnt = $_POST['Qty'];
					$percst=$cst/$qty;
					$newcostt=$qnt*$percst;
					$result=mysqli_query($conn, "INSERT INTO Transactions VALUES ('Removal', '{$cur_date}', '{$dt}', '{$bno}', '{$rcvfrm}', '{$md_nm}', '{$btch_no}', '{$exp}', '{$qnt}', '{$newcostt}');");
					//}
					$qty=$qty-$_POST['Qty'];
					$newcostt=$qty*$percst;

					if($qty==0) {mysqli_query($conn, "DELETE FROM medicine_stock WHERE (`BatchNo`='{$_POST['BatchNo']}'AND MedicineName='{$_POST['MedicineName']}' );");}
					else
					{
						mysqli_query($conn, "UPDATE medicine_stock SET `Qty`='{$qty}', `Cost`='{$newcostt}' WHERE (`BatchNo`='{$_POST['BatchNo']}' AND MedicineName='{$_POST['MedicineName']}' );");
					}
				}
				else
				{
					?><script> alert("Invalid Quantity");</script><?php
				}
			}
			if(isset($_GET['q']) && $_GET['q']!=NULL){
				$med_name = $_GET['q'];
				$result = mysqli_query($conn, "SELECT * from medicine_stock where (MedicineName='$med_name');");
			}
			else {
				$result = mysqli_query($conn, "SELECT * from medicine_stock");
			}
			while($row = mysqli_fetch_array($result)) {
		?>

		<tr>
			
				<td id="1<?php echo $row['RecievedFrom'];?>"><center><?php echo date("d/m/Y",strtotime($row['Date']));?></center></td>
				<td id="2<?php echo $row['RecievedFrom'];?>"><center><?php echo $row['RecievedFrom'];?></center></td>
				<td id="3<?php echo $row['RecievedFrom'];?>"><center><?php echo $row['MedicineName'];?></center></td>
				<td id="4<?php echo $row['RecievedFrom'];?>"><center><?php echo $row['BatchNo'];?></center></td>
				<td id="5<?php echo $row['RecievedFrom'];?>"><center><?php echo date("d/m/Y",strtotime($row['Expiry']));?></center></td>
				<td id="6<?php echo $row['RecievedFrom'];?>"><center><?php echo $row['Qty'];?></center></td>
				<td><center><button id="mybtn<?php echo $row['BatchNo'];?>">Delete</button>
				<div id="myModal<?php echo $row['BatchNo'];?>" class="modal">


  <div class="modal-content">
    <span class="close">&times;</span>
    <form action="" method="post">
    <p style="display: inline;">MedicineName  :  </p><input type="hidden" name="MedicineName" value="<?php echo $row['MedicineName'];?>"><?php echo $row['MedicineName'];?>
    <br><br>
    <p style="display: inline;">BatchNo   :  </p><input type="hidden" name="BatchNo" value="<?php echo $row['BatchNo'];?>"><?php echo $row['BatchNo'];?>
    <br><br>
    <p style="display: inline;">Expiry Date   :  </p><?php echo date("d/m/Y",strtotime($row['Expiry']));?>
    <br><br>
    <p style="display: inline;">Quantity present   :  </p><?php echo $row['Qty'];?>
    <br><br>
    
    <p style="display: inline;">Quantity to be removed   :  </p><input type="text" name="Qty" size="5" style="background-color: rgb(220,220,220);">
    <br><br>
    <input type="submit" name="remove" value="remove" size='14'>
    <script>
// Get the modal

	var modal = document.getElementById('myModal<?php echo $row['BatchNo'];?>');
	console.log(modal)

// Get the button that opens the modal
var btn = document.getElementById('mybtn<?php echo $row['BatchNo'];?>');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
	alert(this.id);
    // console.log(modal)
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
    </form>
  </div>

</div>

</center>
</td>
			
		</tr>
		<?php } ?>
	</tbody>
</div>
</body>
</html>
