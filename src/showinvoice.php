<!DOCTYPE html>
<?php
session_start();
if(!isset($_SESSION['username'])){
header("location:index.php");
}
$link = mysql_connect('localhost', 'root', 'mysql');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
	mysql_select_db("lms");
	$invoiceid = $_GET["invoiceid"];
	$joborderrow = mysql_fetch_array(mysql_query("SELECT * FROM job_order WHERE id='$invoiceid'"));
	$usermobilecol = mysql_fetch_array(mysql_query("SELECT a.mobile_no FROM appusers a, job_order j WHERE a.id=j.user_id and j.id='$invoiceid'"));
	$clientnamerow = mysql_fetch_array(mysql_query("SELECT c.fullname FROM clients c, job_order j WHERE c.id=j.client_id and j.id='$invoiceid'"));
	$orderdetails = mysql_query("SELECT * FROM order_details WHERE job_order_id='$invoiceid'");
	$usermobileno = $usermobilecol[0];
	$clientname = $clientnamerow[0];
	$status = $joborderrow[7];
?>
<html lang="en">
    <head>
        <title>Laundry Management System</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        <script src="js/modernizr.custom.js"></script>
        <script src="js/printThis.js"></script>
        <script>
		function printPlease()
		{
			$("#printblock").printThis({});
		}
		</script>

    </head>
    <body>
    	<div id="header"> 
        </div>
        
        <div class="container">
            <div class="content">
               <nav class="dr-menu">
						<div class="dr-trigger"><span id="dr-icon" class="fa fa-bars"></span><a class="dr-label">Menu</a></div>
						<ul>
							<li><a id="dr-icon" class="fa fa-compass"  href="home.php">Dashboard</a></li>
							<li><a id="dr-icon" class="fa fa-check-square-o" href="job_order.php">Job Order</a></li>
							<li><a id="dr-icon" class="fa fa-bar-chart-o" href="reporting.php">Reporting</a></li>
							<li><a id="dr-icon" class="fa fa-pencil" href="invoice.php">Invoices</a></li>
                            <li><a id="dr-icon" class="fa fa-phone" href="contact.php">Contact Us</a></li>
							<li><a id="dr-icon" class="fa fa-power-off" href="logout.php">Logout</a></li>
						</ul>
					</nav>
            </div><!-- content -->
            <div id="block">
                  <h4 style="float:left; width:14%; margin-left:23px;">Invoice</h4>
                  <?php  
				   if($status==0) {
                       echo '<form style="float:left; width:22%;margin-top:20px;" action="changeorderstatus.php" method="get"> <input type="hidden" name="status" value="1" /> <input type="hidden" name="invoiceid" value="'.$invoiceid.'" /><button type="submit" class="btn btn-primary">Laundry Processed</button> </form>';
				   } else if($status==1) {
					   echo '<form style="float:left; width:22%;margin-top:20px;" action="changeorderstatus.php" method="get"> <input type="hidden" name="status" value="2" /> <input type="hidden" name="invoiceid" value="'.$invoiceid.'" /> <button type="submit" class="btn btn-primary">Complete Order</button> </form>';
				   }
                  ?>
                  <button type="button" class="btn btn-default btn-lg" style="float:right; margin-top:20px; margin-right:20px;" onClick="printPlease()"><span class="fa fa-print"></span></button>
                  <br><br><br><br>
                  <div id="printblock">
                  <h5>Cash Bill</h5>
                  <h3>The Laundry Point</h3>
                  <h5>Mobile: <?php echo $usermobileno; ?></h5>
                  <span style="float:left;">Invoice Id:<?php echo $invoiceid; ?></span><span style="float:right; width:20%;">Date:<?php echo $joborderrow[3]; ?></span><span style="float:right;">Delivery Date:<?php echo $joborderrow[4]; ?></span><br>
                  <span style="float:left;">Name : <?php echo $clientname; ?></span><br>
                  <table class="table table-striped table-bordered table-hover">
                  <col width="10%">
                  <col width="40%">
                  <col width="10%">
                  <col width="20%">
                  <col width="20%">
				<thead>  
					<tr>  
						<th>Sl No.</th>  
						<th>Cloth Type</th>  
						<th>Quantity</th>  
						<th>Amount</th>
                        <th>Laundry/Dry Clean</th>   
					</tr>  
				</thead>  
                <tfoot>
   					 <tr>
   						   <td></td>
     					   <td>Total</td>
                           <?php   
						   echo "<td>$joborderrow[5]</td>";
                           echo "<td>$joborderrow[6]</td>";
						   ?>
                           <td></td>
   					 </tr>
  				</tfoot>
				<tbody>  
                	<?php
					$i=0;
					   while ($row = mysql_fetch_array($orderdetails)) {
						   $i++;
					       echo "<tr>";
						   echo "<td>$i</td>";
						  echo "<td>$row[1]</td>";
						  echo "<td>$row[2]</td>";
						  echo "<td>$row[3]</td>";
						  if($row[4] == 0)
						     echo "<td>Laundry</td>"; 
						  else
						     echo "<td>Dry Clean</td>"; 
						  echo "</tr>";	 
					   }
					?>
                </tbody>
                  </table>
                  </div>
            </div>
        </div>
        
        <div id="footer"> <p id="leftContent">Laundry Management System</p>
        </div>
        <script src="js/ytmenu.js"></script>
    </body>
</html>