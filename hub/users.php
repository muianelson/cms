<?php require_once('../Connections/conn.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}


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

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
function rand_string( $length ) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);

}
   mysqli_select_db($conn,$database_conn);

  $query_Recordset2 = "SELECT * FROM sysconfig";
  $Recordset2 = mysqli_query($conn,$query_Recordset2) or die( mysqli_error($conn));
  $row_Recordset2 = mysqli_fetch_array($Recordset2);
  $fromadd = $row_Recordset2['fromemailaddress'];
   $url = $row_Recordset2['url'];
  $smtpip = $row_Recordset2['smtp_ip'];
  $smtpport = $row_Recordset2['smtp_port'];
  
   $time = time();
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {


$pass1=rand_string(8);
$pass2=md5($pass1);


  $insertSQL = sprintf("INSERT INTO users (email,username, password,nameofuser, enabled, `admin`) VALUES (%s, %s,%s, %s, %s, %s)",
                       GetSQLValueString($_POST['email'], "text"),
					   GetSQLValueString($_POST['username'], "text"),
					    GetSQLValueString($pass2, "text"),
                       GetSQLValueString($_POST['nameofuser'], "text"),
                       GetSQLValueString($_POST['enabled'], "text"),
                       GetSQLValueString($_POST['admin'], "int"));
					   
					 
    

					    mysqli_select_db($conn,$database_conn);
					   $Result1 = mysqli_query($conn,$insertSQL) or die(mysqli_error($conn));  				  
						$to      = $_POST['email'];
						$subject = 'Contract Management Password Alert';
						$message = 'Your username is :'.$_POST['username']." \n " .'Password is '.' '.$pass1.'  '." \n " .'Link: '.$url;	
					
						$query_Recordset5 = "SELECT * FROM users where email='$_POST[email]'";
						$Recordset5 = mysqli_query($conn,$query_Recordset5) or die(mysqli_error($conn));
						$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
						$userid=$row_Recordset5['id'];
						
						if(isset($_POST['role']))
						{    
						
							mysqli_select_db($conn,$database_conn);
							foreach($_POST['role'] as $pageid)
							{
								// insert $id
								$sql="INSERT INTO user_roles (userid,pageid) VALUES ($userid, $pageid)";
								 
					   			 mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
								
							}
						}		  
						
						if(isset($_POST['dept']))
						{    
						
							mysqli_select_db($conn,$database_conn);
							foreach($_POST['dept'] as $deptid)
							{
								// insert $id
								$sql="INSERT INTO user_report_mapping (userid,deptid) VALUES ($userid, $deptid)";
								 
					   			 mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
								
							}
						}	
									 
						$headers = 'From:'.$fromadd."\r\n" ;
		
						mail($to, $subject, $message, $headers);

					  $insertGoTo = "users.php";
					  if (isset($_SERVER['QUERY_STRING'])) {
						$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
						$insertGoTo .= $_SERVER['QUERY_STRING'];
					  }
					  header(sprintf("Location: %s", $insertGoTo));
}


if(isset($_GET['reset']))
{
	
	$pass1=rand_string(8);
	$pass2=md5($pass1);

    $updateSQL = "update  users  set password='$pass2',logincount=0,resetflag=1 where id=$_GET[reset]";

    mysqli_select_db($conn,$database_conn);
    mysqli_query($conn,$updateSQL) or die(mysqli_error($conn));
  
	$query_Recordset = "SELECT * FROM users where id =$_GET[reset]";
	$Recordset = mysqli_query($conn,$query_Recordset) or die(mysqli_error($conn));
	$row_Recordset = mysqli_fetch_assoc($Recordset);

	$to      = $row_Recordset['email'];
	$subject = 'Contract Management System - Password Change Alert';
	
	$message = 'Your username is :'.$row_Recordset['username']." \n " .'Password is '.' '.$pass1.'  '." \n " .'Link: '.$url;			
	//$messagex = 'Your new password is :'.md5($pass1);	
	$messagex = 'Your username is :'.$row_Recordset['username']." \n " .'Password is '.' '.md5($pass1).'  '." \n " .'Link: '.$url;					
	$headers = 'From: contractmgt@nse.co.ke' . "\r\n" ;									 
	mail($to, $subject, $message, $headers);
	 $sqlstmt="INSERT INTO  emaillog (fromadd,sendto,subject,message,datetime) VALUES ('contractmgt@nse.co.ke','$to', '$subject','$messagex','$time')";
	
	   mysqli_query($conn,$sqlstmt) or die(mysqli_error($conn)); 
   		$_SESSION['userupdate']='The user account has been reset and the new password has been sent to their email address';							

}


if(isset($_GET['disable']))
{

  $updateSQL = "update  users  set enabled=0 where id=$_GET[disable]";
  mysqli_select_db($conn,$database_conn);
  $Result1 = mysqli_query($conn,$updateSQL) or die(mysqli_error($conn));
    if($Result1)
	{
						  
		$_SESSION['userupdate']='The user has been disabled successfully';					
	 }					 
	else					  
	{					  
		$_SESSION['userupdate']='The user has not been disabled. Contact administrator';				  
							 
	 }						 	
					  
}

if(isset($_GET['archive']))
{

  $updateSQL = "update  users  set enabled=2 where id=$_GET[archive]";
  mysqli_select_db($conn,$database_conn);
  $Result1 = mysqli_query($conn,$updateSQL) or die(mysqli_error($conn));
   if($Result1)
	{
						  
		$_SESSION['userupdate']='The user has been archived successfully';					
	 }					 
	else					  
	{					  
		$_SESSION['userupdate']='The user has not been archived. Contact administrator';				  
							 
	 }		

}

if(isset($_GET['enable']))
{

  $updateSQL = "update  users  set enabled=1,logincount=0 where id=$_GET[enable]";
  mysqli_select_db($conn,$database_conn);
  $Result1 = mysqli_query($conn,$updateSQL) or die(mysqli_error($conn));
  
   if($Result1)
	{
						  
		$_SESSION['userupdate']='The user has been enabled successfully';					
	 }					 
	else					  
	{					  
		$_SESSION['userupdate']='The user has not been enabled. Contact administrator';				  
							 
	 }		

}



$maxRows_Recordset3 = 10;
$pageNum_Recordset3 = 0;
if (isset($_GET['pageNum_Recordset3'])) {
  $pageNum_Recordset3 = $_GET['pageNum_Recordset3'];
}
$startRow_Recordset3 = $pageNum_Recordset3 * $maxRows_Recordset3;

mysqli_select_db($conn,$database_conn);
$query_Recordset3 = "SELECT * FROM users where enabled in ('0','1') order by enabled desc";
$query_limit_Recordset3 = sprintf("%s LIMIT %d, %d", $query_Recordset3, $startRow_Recordset3, $maxRows_Recordset3);
$Recordset3 = mysqli_query($conn,$query_limit_Recordset3) or die(mysqli_error($conn));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);

if (isset($_GET['totalRows_Recordset3'])) {
  $totalRows_Recordset3 = $_GET['totalRows_Recordset3'];
} else {
  $all_Recordset3 = mysqli_query($conn,$query_Recordset3);
  $totalRows_Recordset3 = mysqli_num_rows($all_Recordset3);
}
$totalPages_Recordset3 = ceil($totalRows_Recordset3/$maxRows_Recordset3)-1;


$queryString_Recordset3 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset3") == false && 
        stristr($param, "totalRows_Recordset3") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset3 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset3 = sprintf("&totalRows_Recordset3=%d%s", $totalRows_Recordset3, $queryString_Recordset3);



mysqli_select_db($conn,$database_conn);
$query_Recordset4 = "SELECT * FROM pages where description not like '%expired%'";
$Recordset4 = mysqli_query($conn,$query_Recordset4) or die(mysqli_error($conn));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);

mysqli_select_db($conn,$database_conn);
$query_Recordset5 = "SELECT * FROM department";
$Recordset5 = mysqli_query($conn,$query_Recordset5) or die(mysqli_error($conn));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);


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
  <title>Contract Manager - User Management</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="css/Materialicons.css" />
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />

<script type="text/javascript">
$(document).ready(function() {
	$('a[data-confirm]').click(function(ev) {
		var href = $(this).attr('href');
		if (!$('#dataConfirmModal').length) {
			$('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Please Confirm</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button><a class="btn btn-primary" id="dataConfirmOK">OK</a></div></div>');
		} 
		$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
		$('#dataConfirmOK').attr('href', href);
		$('#dataConfirmModal').modal({show:true});
		return false;
	});
});

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
                  <h4 class="card-title ">Users</h4>
                  <p class="card-category"> This window shows a list of active and inactive users. Archived users will not be shown in this view. The user report will display all users. </p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
				  
				      <div style="color:#FF0000;margin-bottom:10px"><?php
					if(isset( $_SESSION['userupdate']))
					{
					 echo ($_SESSION['userupdate']);
					 unset($_SESSION['userupdate']);
					 
					}?>
					
					</div>
					
                    <table class="table">
                      <thead class=" text-primary">
                      <th>
                          email                         </th>
                        <th>
                          username                        </th>
                              <th>
                          user status                        </th>
						        <th>
                          user type                        </th>
						  <th>
                          last login                       </th>
                       </thead>
                      <tbody>
					    <?php do { 
								
												
								
								?>
                        <tr>
                          <td>
                             <a href="">
							 <?php echo $row_Recordset3['email']; ?></a>                   </td>
                          <td>
                           <?php echo $row_Recordset3['username']; ?>                         </td>
                          
       					    <td>
                           <?php if($row_Recordset3['enabled']==1){
											echo "Active";}
									  else {echo "Inactive";}

									  ?>                        </td>
									  
									    <td>
                          <?php  if($row_Recordset3['admin']==1){
									  echo "Administrator";}
									  else {echo "Normal";}
									  
									  
									   ?>                       </td>
									 
									  
									  <td>
                           <?php if($row_Recordset3['lastlogindate']=='')
						   { echo ' N/ A ';} else{echo date("d-m-Y", strtotime($row_Recordset3['lastlogindate']));} ?>                       </td>
			 <?php if($_SESSION['utype']=='1'){?>
			 <td><a href="users.php?reset=<?php echo $row_Recordset3['id']; ?>">Reset</a></td>
			<?php if($row_Recordset3['enabled']==1){?>
			<td><a href="users.php?disable=<?php echo $row_Recordset3['id']; ?>">Disable</a></td>
			<?php } else{ ?>
			<td><a href="users.php?enable=<?php echo $row_Recordset3['id']; ?>">Enable</a></td>	
			
			<?php }
			?>	 <td><a href="users.php?archive=<?php echo $row_Recordset3['id']; ?>">Archive</a></td>
						   <td class="td-actions text-right">
                              <button type="button" rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                                <i class="material-icons"></i><a href="edit_user.php?id=<?php echo $row_Recordset3['id']; ?>">update</a>
                              </button>
                             
                          </td>
						  <?php
						  }?>
                        </tr>
                
                      </tbody>
					   <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
					  
					       <tbody>
					      <tr><td><?php if ($pageNum_Recordset3 > 0) { // Show if not first page ?>
                                        <a href="<?php printf("%s?pageNum_Recordset3=%d%s", $currentPage, 0, $queryString_Recordset3); ?>"><img src="../First.PNG" width="47" height="33" border="0" /></a>
                                        <?php } // Show if not first page ?></td>
						   <td><?php if ($pageNum_Recordset3 > 0) { // Show if not first page ?>
                                        <a href="<?php printf("%s?pageNum_Recordset3=%d%s", $currentPage, max(0, $pageNum_Recordset3 - 1), $queryString_Recordset3); ?>"><img src="../Previous.PNG" width="42" height="31" border="0" /></a>
                                        <?php } // Show if not first page ?></td>
										<td><?php if ($pageNum_Recordset3 < $totalPages_Recordset3) { // Show if not last page ?>
                                        <a href="<?php printf("%s?pageNum_Recordset3=%d%s", $currentPage, min($totalPages_Recordset3, $pageNum_Recordset3 + 1), $queryString_Recordset3); ?>"><img src="../Next.PNG" width="38" height="30" border="0" /></a>
                                        <?php } // Show if not last page ?></td>
										<td><?php if ($pageNum_Recordset3 < $totalPages_Recordset3) { // Show if not last page ?>
                                        <a href="<?php printf("%s?pageNum_Recordset3=%d%s", $currentPage, $totalPages_Recordset3, $queryString_Recordset3); ?>"><img src="../Last.PNG" width="40" height="31" border="0" /></a>
                                        <?php } // Show if not last page ?></td>
						   </tr>
						   </tbody>
													
                    </table>
                  </div>
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