<?php require_once('../Connections/conn.php'); 
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}

?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  //$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  //$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE users SET email=%s,username=%s,admin=%s,nameofuser=%s   WHERE  id=$_POST[uid]",
                       GetSQLValueString($_POST['email'], "text"),
					   GetSQLValueString($_POST['username'], "text"),
					    GetSQLValueString($_POST['usertype'], "text"),
                       GetSQLValueString($_POST['nameofuser'], "text"));

  mysqli_select_db($conn,$database_conn);
  $Result1 = mysqli_query($conn,$updateSQL) or die(mysqli_error($conn));
  
  						if($Result1)
						  {
						  
							$_SESSION['userupdate']='The user '.$_POST['nameofuser'].' has been updated successfully';
						  }
						  else
						  {
						  
							 $_SESSION['userupdate']='The user '.$_POST['nameofuser'].' has not been updated successfully. Contact administrator.';
						  }		
		
		
		
		//mysqli_select_db($conn,$database_conn);
		$sqldel="delete from user_roles where userid=$_POST[uid]";						
									 
		mysqli_query($conn,$sqldel) or die(mysqli_error($conn)); 	

						
		
		foreach($_POST['role'] as $pageid)
		{						
								
			// insert $id						
			$sql="INSERT INTO user_roles (userid,pageid) VALUES ($_POST[uid], $pageid)";						
									 
			mysqli_query($conn,$sql) or die(mysqli_error($conn)); 			   			 
									
		}												
																		  
	   //mysqli_select_db($conn,$database_conn);
		$sqldel2="delete from user_report_mapping where userid=$_POST[uid]";						
									 
		mysqli_query($conn,$sqldel2) or die(mysqli_error($conn)); 
						
		
		foreach($_POST['dept'] as $deptid)
		{
			// insert $id
			$sql2="INSERT INTO user_report_mapping (userid,deptid) VALUES ($_POST[uid], $deptid)";
								 
			mysqli_query($conn,$sql2) or die(mysqli_error($conn)); 
								
		}
	
		$integcheck="SELECT * FROM user_roles a, user_report_mapping b where a.userid=b.userid and a.userid=$_POST[uid] and a.pageid=4";
 		$Rd = mysqli_query($conn,$integcheck) or die(mysqli_error($conn));
		$numrows = mysqli_num_rows($Rd);
		//echo $numrows ;
		//exit;
		if($numrows==0)
		{
		
		
		$sqldel3="delete from user_report_mapping where userid=$_POST[uid]";						
									 
		mysqli_query($conn,$sqldel3) or die(mysqli_error($conn)); 
		
		
		$sqldel3="delete from user_roles where pageid=4 and userid=$_POST[uid]";						
									 
		mysqli_query($conn,$sqldel3) or die(mysqli_error($conn)); 
		//echo 'hi' ;
		//exit;
		$_SESSION['userupdate']='The user  department contract mapping for '.$_POST['nameofuser'].' has not been updated since the role active contracts was not selected.';
		}
		

  $updateGoTo = "users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysqli_select_db($conn,$database_conn);
$query_Recordset1 = "SELECT * FROM users where id='$_GET[id]'";
$Recordset1 = mysqli_query($conn,$query_Recordset1) or die(mysqli_error($conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

mysqli_select_db($conn,$database_conn);
/*$query_Recordset4 = "SELECT * FROM pages where description not like '%expired%'";*/
$query_Recordset4 = "SELECT * FROM pages";

$Recordset4 = mysqli_query($conn,$query_Recordset4) or die(mysqli_error($conn));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);

mysqli_select_db($conn,$database_conn);
$query_Recordset5 = "SELECT * FROM department";
$Recordset5 = mysqli_query($conn,$query_Recordset5) or die(mysqli_error($conn));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);

mysqli_select_db($conn,$database_conn);
/*$query_Recordset6 = "SELECT a.pageid,a.description FROM pages a, user_roles b where b.pageid=a.pageid and b.userid='$_GET[id]' and a.description not like '%expired%'";*/
$query_Recordset6 = "SELECT a.pageid,a.description FROM pages a, user_roles b where b.pageid=a.pageid and b.userid='$_GET[id]'";

$Recordset6 = mysqli_query($conn,$query_Recordset6) or die(mysqli_error($conn));
while($row_Recordset6 = mysqli_fetch_assoc($Recordset6))
{

$checkbox_array[]= $row_Recordset6['description'];

}


mysqli_select_db($conn,$database_conn);
$query_Recordset7 = "SELECT a.deptid,a.department FROM department a, user_report_mapping b where b.deptid=a.deptid and b.userid='$_GET[id]'";
$Recordset7 = mysqli_query($conn,$query_Recordset7) or die(mysqli_error($conn));
while($row_Recordset7 = mysqli_fetch_assoc($Recordset7))
{

$checkbox_array2[]= $row_Recordset7['department'];

}


//echo $checkbox_array;

//exit;

?>

<!--
=========================================================
Material Dashboard - v2.1.2
=========================================================

Product Page: https://www.creative-tim.com/product/material-dashboard
Copyright 2020 Creative Tim (https://www.creative-tim.com)
Coded by Creative Tim

=========================================================
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Contract Manager - Update User</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="css/Materialicons.css" />
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" media="all" href="jsdatepick-calendar/jsDatePick_ltr.min.css" />
  <script type="text/javascript">


function ContractsViewFunction() {
  // Get the checkbox
  var checkBox = document.getElementById("view_contracts");
  // Get the output text
  var text = document.getElementById("contractmapping");

  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
    text.style.display = "none";
  }
}

</script>
</head>

<body class="">
  <div class="wrapper ">
          <?php include("links.php");?>
    <div class="main-panel">
      <!-- Navbar -->
      <?php include("navbar.php");?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">User Details</h4>
                  <p class="card-category"> This window allows you to update user details.</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
				  
				   <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
				  
				   <p>
                  <div class="row">
             
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">username</label>
                          <input type="text" name="username" value="<?php echo htmlentities($row_Recordset1['username'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" required>
                        </div>
                      </div>
					  
                        <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Name of user</label>
                          <input type="text" name="nameofuser" value="<?php echo $row_Recordset1['nameofuser']; ?>" class="form-control" required>
                        </div>
                      </div>
					  
					</div>
					  
                     <div class="row">
             
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Email</label>
                          <input type="text" name="email" value="<?php echo htmlentities($row_Recordset1['email'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" required>
                        </div>
                      </div>
					  
					    <div class="col-md-4 mb-3">
						<label for="status">User Type</label>
						<select class="custom-select d-block w-100" id="usertype" name="usertype" required>
						  <option value="">Choose...</option>
						  
						   <?php 
						
					
						  if($row_Recordset1['admin']=='1')
						  {?>
						  	<option value="<?php echo $row_Recordset1['admin']?>" selected="selected">Administrator</option>
							 <option value="0">Normal</option>
						        
						  <?php
						   }
						   else if($row_Recordset1['admin']=='0')
						   {
						  ?>
						  		<option value="<?php echo $row_Recordset1['admin']?>" selected="selected">Normal</option>
						  		 <option value="1">Administrator</option>
						   <?php 
						   }?>
						
						 
						  
						  
						  
						</select>
						<div class="invalid-feedback">
						  Please provide a valid  user type.
						</div>
					  </div>
					  
					  
					  
					 </div>
					
                  	<div class="row">
             
                  	   <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating"><b>Choose User Role</b></label><P>
                          
							 
							 <div class="table-responsive">
            <table class="table table-striped table-sm">
				
				  <tbody>   
	
				  <?php
				  while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4))
				  { 		  
						?>	
							
						
						<tr>
						
						
						<?php 
						if (isset($checkbox_array)&&in_array($row_Recordset4['description'],$checkbox_array))
						{
						 	if(in_array('Active Contracts',$checkbox_array))
							{?>
						
						<td><?php echo $row_Recordset4['description']; ?></td>
						<td><input type="checkbox" name="role[]" value="<?php echo $row_Recordset4['pageid']; ?>" id="view_contracts" onClick="ContractsViewFunction()" checked class="form-control"></td>
						
							<?php 
							}
							else
							{ ?>
						<td><?php echo $row_Recordset4['description']; ?></td>
						
						<td><input type="checkbox" name="role[]" value="<?php echo $row_Recordset4['pageid']; ?>" checked class="form-control"></td>
							<?php
							 } 
						}
						else
						{
						?> 	
						<td><?php echo $row_Recordset4['description']; ?></td>
						<?php if($row_Recordset4['description']=='Active Contracts'){?>			
						<td><input type="checkbox" name="role[]" value="<?php echo $row_Recordset4['pageid']; ?>" id="view_contracts" onClick="ContractsViewFunction()" class="form-control"></td>
						<?php 
						}
						else {?>
						<td><input type="checkbox" name="role[]" value="<?php echo $row_Recordset4['pageid']; ?>"  class="form-control"></td>
						<?php
						} }
						?>         
						</tr>
						 <?php 
						 }  
						 ?>
				</tbody>
			</table>
					</div>
					
                        </div>
						</div>
						
						<div class="col-md-6" id="contractmapping">
                        <div class="form-group">
                          <label class="bmd-label-floating"><b>User - Department Contract Mapping</b> </label><P>
                          
							 
							 <div class="table-responsive">
            <table class="table table-striped table-sm">
				
				  <tbody>   
				  <?php do { 		  
							
					
						?>
						
								<tr>
						<td><?php echo $row_Recordset5['department']; ?></td>
						<?php if (isset($checkbox_array2)&&in_array($row_Recordset5['department'],$checkbox_array2))
							{?>
						<td><input type="checkbox" name="dept[]" value="<?php echo $row_Recordset5['deptid']; ?>"  checked class="form-control"></td>  
						
						<?php }
						else{?>
						   <td><input type="checkbox" name="dept[]" value="<?php echo $row_Recordset5['deptid']; ?>" class="form-control"></td> 
						   <?php }?>
						</tr>
							
						
											
						
							
				
						  <?php } while ($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
						  </tbody>
					</table>
					</div>
					
                        </div>
						</div>
						
						
                      </div>  
						
			
                    <div class="row">
						  <div class="col-md-6">
							<div class="form-group">
								<button type="submit" class="btn btn-primary pull-right">Update User</button>
								<div class="clearfix"></div>
					     		 <input type="hidden" name="MM_update" value="form1" />
                                  <input type="hidden" name="uid" value="<?php echo $row_Recordset1['id']; ?>" />
								  </form>
							</div>
						  </div>
					               
                     </div>
					
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php include("footer.php");?>
    </div>
  </div>
   <?php include("sidebar.php");?>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="../assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="../assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="../assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="../assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="../assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="../assets/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="../assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="../assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="../assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="../assets/js/plugins/arrive.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chartist JS -->
  <script src="../assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="../assets/demo/demo.js"></script>
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
</body>

</html>