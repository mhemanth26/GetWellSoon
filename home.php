<?php
session_start();
include('lib/configure.php');
if(isset($_SESSION['login_type'])){
  if ($_SESSION['login_type']!="admin")
   {
    header("location: ../doctor_home.php");
  }
}
else {
  header("location: ../index.php");
}
$min_qty = 10;
?>

<!doctype html>
<html>
<head>
    <title>Admin Home Page</title>
    <!--<link rel="stylesheet" href="css/home.css">-->
    <link rel="icon" href="images/Logo---307x275.png">
    <link rel="stylesheet" type="text/css" href="css/materialize.css">

<!-- dataTable -->



    <style>
      
.image1{
    width: 12%;
    height: 20%;
}
.sub-head1{
    display: block;
    font-size: 40px;
    margin: 3.3%;
    font-family: "Lato",sans-serif;
    
}
.ghost-button {

  font-family: "Lato",sans-serif;
  display: inline-block;
  width: 10%;
  padding:.4cm;
  color: black;
  font-size: 20px;
  
  text-align: center;
  outline: none;
  text-decoration: none;
  transition: background-color 0.2s ease-out,
              border-color 0.2s ease-out;
}
.ghost-button:hover{ 
  background-color: rgba(0, 0, 0, 0.4);
  color:white;
  border-color: rgba(255, 255, 255, 0.4);
  transition: background-color 0.5s ease-in,
              border-color 0.5s ease-in;}
.ghost-button:active {background-color: #9363c4;
  border-color: #9363c4;
  color: #fff;
  transition: color 0.3s ease-in,
              background-color 0.3s ease-in,
              border-color 0.3s ease-in;
 }

.body1{
  background-repeat: no-repeat;
}
.body12{
    margin-top: 10px;
    padding-left: 10px;
    width: 20%;

}
.link{
  width:20%;
  margin: 0px;
  
}
.firsttable{
  width: 47%;
  display: inline-flex;
  
}
.secondtable{
  width:47%;
  display: inline-flex;
  margin-left: 0%;
  float: right;
  margin-right: 3%;
}

  .modal1 {
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
    background-color: rgba(0,0,0,0.7); /* Black w/ opacity */

    }

/* Modal Content */
.modal-content1 {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 65%;
    border-radius: 10px;
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

.ar{
  display: inline-block;
  width:80%;
  float: right;
}
    </style>
  
</head>

<body background="images/1.jpg">

<div class="container123">

<header>
   <img class="image1" src="images/Logo---307x275.png" style="float: left;">
   <p class="sub-head1" style="float: left;">NITC HEALTH CENTER</p>
   <a class="ghost-button" href="lib/logout.php" style="float:right;">LOGOUT</a>
   <a class="ghost-button" href="features/update_profile_admin.php" style="float:right;">PROFILE</a>
   <a class="ghost-button" href="features/yearly_report.php" style="float:right;">REPORTS</a>
</header>

<nav class="body12">
  <a class="link" href="features/add_stock.php"><img src="images/add stock.png" style="width:100%; padding: 10px;"><br></a>
  <a class="link" href="features/remove_stock.php"><img src="images/remove stock.png" style="width:100%; padding: 10px;"><br></a>
  <a class="link" href="features/view_stock.php"><img src="images/view stock.png" style="width:100%; padding: 10px;"><br></a>
</nav>

<article class="ar">
<div class="firsttable" style="overflow-y: scroll; height: 490px;">
    <table class="highlight" style="width: 100%; margin: 0px;">
        <thead >

          <tr id="datatable2">
            <th><center>Medicine Name</center></th>
            <th><center>Expiry</center></th>
            <th><center>Details</center></th>
          </tr>
        </thead>
        <tbody style=" overflow-y: scroll;">
          <?php
             
            $curr_date = date("Y/m/d");
            $check_date = date("Y/m/d", strtotime("+30 days"));
            $result = mysqli_query($conn, "SELECT * from medicine_stock WHERE (Expiry < '$check_date')");
            while($row = mysqli_fetch_array($result)){

          ?>

          <tr>
      
        
        <td><center><?php echo $row['MedicineName'];?></center></td>
        
        <td><center><?php echo date("d/m/Y",strtotime($row['Expiry']));?></center></td>
        
        <td><center><button id="myBtn<?php echo $row['BatchNo'];?>1" class="btn waves-effect waves-light">details</button>
          <div id="myModal<?php echo $row['BatchNo'];?>1" class="modal1">


  <div class="modal-content1">
    <span id="close<?php echo $row['BatchNo'];?>1"; class="close">&times;</span>
    
    <p style="display: inline;">MedicineName  :  </p><input type="hidden" name="MedicineName" value="<?php echo $row['MedicineName'];?>"><?php echo $row['MedicineName'];?>
    <br><br>
    <p style="display: inline;">BatchNo   :  </p><input type="hidden" name="BatchNo" value="<?php echo $row['BatchNo'];?>"><?php echo $row['BatchNo'];?>
    <br><br>
    <p style="display: inline;">Expiry Date   :  </p><?php echo date("d/m/Y",strtotime($row['Expiry']));?>
    <br><br>
    <p style="display: inline;">Quantity present   :  </p><?php echo $row['Qty'];?>
    <br><br>
   
  </div>

</div>

<script>
// Get the modal


// Get the button that opens the modal
var btn = document.getElementById("myBtn<?php echo $row['BatchNo'];?>1");
var modal;
// Get the <span> element that closes the modal
var span; //= document.getElementsByClassName("close")[0];
// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal = document.getElementById('myModal<?php echo $row['BatchNo'];?>1');
  span = document.getElementById('close<?php echo $row['BatchNo'];?>1');
    modal.style.display = "block";
    span.onclick = function() {
  

    modal.style.display = "none";
}
}


// When the user clicks on <span> (x), close the modal


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  //var modal = document.getElementById('myModal<?php echo $row['BatchNo'];?>');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script></center></td>



      
    </tr>
    <?php } ?>
        </tbody>
        
    </table>
</div>
<div class="secondtable" style=" overflow-y: scroll; height: 490px;"  >
  <table class="highlight" style="width: 100%; margin: 0px;">
        <thead>
          <tr id="datatable2">
            <th><center>Medicine Name</center></th>
            <th><center>Quantity</center></th>
            <th><center>Details</center></th>
          </tr>
        </thead>
        <tbody >
          <?php
             
            $result = mysqli_query($conn, "SELECT * from medicine_stock");
            while($row = mysqli_fetch_array($result)) {
              if($row['Qty']<$min_qty){
          ?>

          <tr>
      
        
        <td><center><?php echo $row['MedicineName'];?></center></td>
        
        <td><center><?php echo $row['Qty'];?></center></td>
        
        <td><center><button id="myBtn<?php echo $row['BatchNo'];?>2" class="btn waves-effect waves-light">details</button>

<div id="myModal<?php echo $row['BatchNo'];?>2" class="modal1">


  <div class="modal-content1">
    <span id="close<?php echo $row['BatchNo'];?>2"; class="close">&times;</span>
    
    <p style="display: inline;">MedicineName  :  </p><input type="hidden" name="MedicineName" value="<?php echo $row['MedicineName'];?>"><?php echo $row['MedicineName'];?>
    <br><br>
    <p style="display: inline;">BatchNo   :  </p><input type="hidden" name="BatchNo" value="<?php echo $row['BatchNo'];?>"><?php echo $row['BatchNo'];?>
    <br><br>
    <p style="display: inline;">Expiry Date   :  </p><?php echo date("d/m/Y",strtotime($row['Expiry']));?>
    <br><br>
    <p style="display: inline;">Quantity present   :  </p><?php echo $row['Qty'];?>
    <br><br>
    
    
  </div>

</div>

<script>
// Get the modal


// Get the button that opens the modal
var btn = document.getElementById("myBtn<?php echo $row['BatchNo'];?>2");
var modal;
// Get the <span> element that closes the modal
var span; //= document.getElementsByClassName("close")[0];
// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal = document.getElementById('myModal<?php echo $row['BatchNo'];?>2');
  span = document.getElementById('close<?php echo $row['BatchNo'];?>2');
    modal.style.display = "block";
    span.onclick = function() {
  //alert(this.id);
  //modal = document.getElementById('myModal<?php echo $row['BatchNo'];?>');

    modal.style.display = "none";
}
}


// When the user clicks on <span> (x), close the modal


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  //var modal = document.getElementById('myModal<?php echo $row['BatchNo'];?>');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
</center></td>



      
    </tr>
    <?php }} ?>
        </tbody>
  </table>
</div>
</article>

<footer></footer>

</div>

</body>