<?php require_once('../Connections/conn.php'); 
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}
/*mysqli_select_db($conn,$database_conn);
$query_Recordset2 = "SELECT * FROM users where username='$_SESSION[MM_Username]'";
$Recordset2 = mysqli_query($conn,$query_Recordset2) or die(mysqli_error($conn));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);*/
function datediff($date1,$date2)
{
return round(abs(strtotime($date2)-strtotime($date1))/86400);
//Remember date2 is the latest date and date 1 is older date to get +ve result
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


$cid=$_GET['id'];
mysqli_select_db($conn,$database_conn);
$query_Recordset1 = "SELECT a.id,a.alert_duration,b.deptid,a.contractname,a.contractdetail,a.executiondate,a.inputter,a.status,a.comment,a.departmentid,a.expiry_date,b.department ,a.upload_file,a.value,a.renewal FROM contracts a , department b where a.departmentid=b.deptid and a.id='$cid'";
$Recordset1 = mysqli_query($conn,$query_Recordset1) or die(mysqli_error($conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
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
  <title>Contract Manager - View / Update</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="css/Materialicons.css" />
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" media="all" href="jsdatepick-calendar/jsDatePick_ltr.min.css" />
  <script type="text/javascript" src="jsdatepick-calendar/jquery.1.4.2.js"></script>
  <script type="text/javascript" src="jsdatepick-calendar/jsDatePick.jquery.min.1.3.js"></script>
  <script type="text/javascript">
	
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"inputField",
			dateFormat:"%Y-%m-%d"
		});
		
		new JsDatePick({
			useMode:2,
			target:"inputField2",
			dateFormat:"%Y-%m-%d"
		});
		
		new JsDatePick({
			useMode:2,
			target:"inputField3",
			dateFormat:"%d-%M-%Y"
		});
		
	};		
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
                  <h4 class="card-title ">Contract Details</h4>
                  <p class="card-category"> This window lists details of the contract.</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
				  
				   <form>
				
				   <p>
               <div class="row">
             
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Contract name</label>
                          <input type="text" name="contractname" value="<?php echo $row_Recordset1['contractname']; ?>" class="form-control" disabled>
                        </div>
                      </div>
					  
                     <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Status</label>
                          <input type="text" name="contractname" value="<?php echo $row_Recordset1['status']; ?>" class="form-control" disabled>
                        </div>
                      </div>
					  
                    </div>
					
					
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Contract value</label>
                          <input type="text" name="contvalue" value="<?php echo $row_Recordset1['value']; ?>"  class="form-control" disabled>
                        </div>
                      </div>
					  
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Department</label>
                          <input type="text"  name="department" value="<?php echo $row_Recordset1['department']; ?>" class="form-control" disabled>
                        </div>
                      </div>
                    </div>
					
     				<div class="row">
					
					<div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating"></label>
                          <?php if(!empty($row_Recordset1['upload_file'])){?>
								  <a href="<?php echo $row_Recordset1['upload_file']; ?>">Download Contract</a>
								  <?php  // echo $row_Recordset1['upload_file'];
								  
								  }
								  else
								  {?>
								   
								   <div class="alert alert-info">
									<span>Contract not uploaded</span>
								  </div>
								  
								  
								   <?php
								   }
								   ?>
                        </div>
                      </div>
                    </div>
					
								  
					  <p>
					  
					  <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Addendums</label>
                          <div class="form-group">
                            <label class="bmd-label-floating"> This section displays all addendums that have been attached to the main contract in order of recency</label>
							<p>
                          <?php
								  
								  $query_Recordsetx = "SELECT * from addendums where contractid='$cid'";
									$Recordsetx= mysqli_query($conn,$query_Recordsetx) or die(mysqli_error($conn));
									$row_Recordsetx = mysqli_fetch_assoc($Recordsetx);
									$row_numx = mysqli_num_rows($Recordsetx);
									$row=1;
										 if($row_numx>0){
										do { ?>
									
										
								
										<a href="<?php echo $row_Recordsetx['addendum']; ?>"><?php echo $row.' . '.$row_Recordsetx['description'].'(Click to view)'; ?>
										</a>                
										<br>
								
										<?php	
										$row=$row+1;
										} while ($row_Recordsetx = mysqli_fetch_assoc($Recordsetx));
										}
									?>								
                          </div>
                        </div>
                      </div>
                    </div>
					
					
					  <div class="row">   
					  
					  <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Start date</label>
                          <input type="text" value="<?php echo date("d-m-Y", strtotime($row_Recordset1['executiondate'])); ?>" class="form-control" disabled>
                        </div>
                      </div>  
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">End date</label>
                          <input type="text" value="<?php echo date("d-m-Y", strtotime($row_Recordset1['expiry_date'])); ?>" class="form-control" disabled>
                        </div>
                      </div>
					  
             
                    </div>
					
					
									  <div class="row">   
					  
					  <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Contract duration</label>
                          <input type="text" value="<?php echo datediff($row_Recordset1['executiondate'],$row_Recordset1['expiry_date']); ?>" class="form-control" disabled>
                        </div>
                      </div>  
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Days before expiry </label>
                          <input type="text" value="<?php echo datediff(date("Y-m-d"),$row_Recordset1['expiry_date']); ?>" class="form-control" disabled>
                        </div>
                      </div>
					  
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Days before reminder </label>
                          <input type="text" value="<?php echo $row_Recordset1['alert_duration']; ?>" class="form-control" disabled>
                        </div>
                      </div>
                    </div>
					
					 <div class="row">
					         <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Comment</label>
                  
						   <textarea class="form-control" disabled rows="5"><?php echo $row_Recordset1['comment']; ?></textarea>
                        </div>
                      </div>
					  </div>
					
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Contract detail</label>
                          <div class="form-group">
                            <label class="bmd-label-floating"> This is a summary of details of the contract e.g terms,exit clause e.t.c</label>
                            <textarea class="form-control" disabled rows="5"><?php echo $row_Recordset1['contractdetail']; ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
					
					 <div class="row">   
					  
					  <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Input by</label>
                          <input type="text" value="<?php echo $row_Recordset1['inputter']; ?>" class="form-control" disabled>
						  </div>
                        </div>
						
						
							  <div class="col-md-4">
								  <div class="custom-control custom-checkbox">
								  <input type="checkbox" class="custom-control-input" id="defaultChecked2" 
								  <?php if($row_Recordset1['renewal']=='on'){?> checked <?php } else {  }?>>
								  <label class="custom-control-label" for="defaultChecked2">Contract auto-renews?</label>
								</div>
				
							
							
                      </div>  
              
                    <div class="clearfix"></div>
                  </form>
					
					
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