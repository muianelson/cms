<?php require_once('../Connections/conn.php'); 
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION['MM_Username'])) {
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


if ((isset($_POST["contractname"])) ) {



$fileName=$_FILES["fileToUpload"]["name"];
$fileSize=$_FILES["fileToUpload"]["size"]/1024;
$fileType=$_FILES["fileToUpload"]["type"];
$fileTmpName=$_FILES["fileToUpload"]["tmp_name"];  

if($fileType=="application/pdf"){
if($fileSize<=20000){

//New file name
$random=rand(1111,9999);
$newFileName=$random.$fileName;

//File upload path
$uploadPath="uploads/".$newFileName;

//function for upload file
if(move_uploaded_file($fileTmpName,$uploadPath)){
  //echo "Successful<BR>"; 
  //echo "File Name :".$newFileName."<BR>"; 
  //echo "File Size :".$fileSize." kb"."<BR>"; 
  //echo "File Type :".$fileType."<BR>"; 
}
}
else{
  //echo "Maximum upload file size limit is 200 kb";
}
}
else{
  //echo "You can only upload a pdf file.";
}  

    

 $time = time();
  $insertSQL = sprintf("INSERT INTO contracts (contractname, contractdetail, executiondate, inputter,date_time, status, `comment`, alert_duration,departmentid, expiry_date,value,renewal,upload_file) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s)",
                       GetSQLValueString($_POST['contractname'], "text"),
                       GetSQLValueString($_POST['contractdetail'], "text"),
                       GetSQLValueString($_POST['executiondate'], "date"),               
                       GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString($time, "text"),					    
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['frequency'], "text"),
                       GetSQLValueString($_POST['departmentid'], "int"),					   

                       GetSQLValueString($_POST['expiry_date'], "date"),
					   GetSQLValueString($_POST['contvalue'], "text"),
					   GetSQLValueString($_POST['renewal'], "text"),
					    GetSQLValueString($uploadPath, "text"));
						
						  $insertSQL2 = sprintf("INSERT INTO contractsaudit (contractname, contractdetail, executiondate, inputter,date_time, status, `comment`, alert_duration,departmentid, expiry_date,value,action,renewal,upload_file) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($_POST['contractname'], "text"),
                       GetSQLValueString($_POST['contractdetail'], "text"),
                       GetSQLValueString($_POST['executiondate'], "date"),               
                       GetSQLValueString($_SESSION['MM_Username'], "text"),
					   GetSQLValueString($time, "text"),					    
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['comment'], "text"),
					   GetSQLValueString($_POST['frequency'], "text"),
                       GetSQLValueString($_POST['departmentid'], "int"),	   
                       GetSQLValueString($_POST['expiry_date'], "date"),
					   GetSQLValueString($_POST['contvalue'], "text"),
					   GetSQLValueString('New contract', "text"),
					   GetSQLValueString($_POST['renewal'], "text"),
					    GetSQLValueString($uploadPath, "text"));


  mysqli_select_db($conn,$database_conn);
  $Result1 = mysqli_query($conn,$insertSQL) or die(mysqli_error($conn));
  $Result2 = mysqli_query($conn,$insertSQL2) or die(mysqli_error($conn));
  
 
  if($Result1)
  {
  
    $_SESSION['contractupdate']='The contract '.$_POST['contractname'].' has been added successfully';
  }
  else
  {
  
  	 $_SESSION['contractupdate']='The contract '.$_POST['contractname'].' has not been added successfully. Contact administrator.';
  }

  $insertGoTo = "contracts.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysqli_select_db($conn,$database_conn);
$query_Recordset1 = "SELECT * FROM DEPARTMENT  where deptid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]')";
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
  <title>Contract Manager - Add Contract</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="css/Materialicons.css" />
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
 
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
                  <h4 class="card-title "> Add Contract</h4>
                  <p class="card-category"> This window allows you to create a new contract.</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
				  
				   <form action="" method="post" name="form1" id="form1" enctype="multipart/form-data">
				<p>
               <div class="row">
             
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Contract name</label>
                          <input type="text" name="contractname" class="form-control" required>
                        </div>
                      </div>
					  
                			  
					   <div class="col-md-4 mb-3">
						<label for="status">Contract Status</label>
						<select class="custom-select d-block w-100" name="status" id="status" required>
						  <option value="">Choose...</option>
						  <option value="Valid">Valid</option>
						  <option value="Expired">Expired</option>
						</select>
						<div class="invalid-feedback">
						  Please provide a valid state.
						</div>
					  </div>
			  
                    </div>
					
					
                    <div class="row">
                       <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Contract value (Ksh)</label>
                          <input type="text" name="contvalue"  class="form-control" required>
                        </div>
                      </div>
					  
					  <div class="col-md-4 mb-6">
						<label for="department">Department</label>
						<select class="custom-select d-block w-100" name="departmentid" id="department" required>
						  <option value="">Choose...</option>
						  <?php 
								do {  
								?>
								
						  <option value="<?php echo $row_Recordset1['deptid']?>"><?php echo $row_Recordset1['department']?></option>
						   <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
							  ?>
						</select>
						<div class="invalid-feedback">
						  Please provide a valid department.
						</div>
					  </div>
					  
					
					
                    
					  
					  
                    </div>
					
     				<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								  <div class="file-field">
									<div class="btn btn-primary btn-sm float-left">
									  <span>Upload contract file : PDF (20 mb max) </span>
									  <input type="file" name="fileToUpload" id="fileToUpload" required>
									</div>
									
								  </div>
								
							</div>
						</div>
						
						  <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Days before alert</label>
                          <input type="text"   name="frequency" class="form-control" required>
                        </div>
                      </div>
						  
					  
				
                    </div>
					
								  
					 
					  <div class="row">   
					  
					  <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Start date</label>
        					<br>
						    <input class="form-control" type="date" name="executiondate" value="" id="example-date-input" required >
                        </div>
                      </div>  
					  
					  
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">End date</label>
            					<br>	  
						  	 <input class="form-control" type="date" name="expiry_date" value="" id="example-date-input" required >
                        </div>
                      </div>
					  
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Comment</label>
						  <br>
                          <input type="text" name="comment" value="" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Contract detail</label>
                          <div class="form-group">
                            <label class="bmd-label-floating"> This is a summary of details of the contract e.g terms,exit clause e.t.c</label>
                            <textarea class="form-control" name="contractdetail" rows="5" required></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
					
					  <div class="row">
							  <div class="col-md-4">
								  <div class="custom-control custom-checkbox">
								  <input type="checkbox" name="renewal" class="custom-control-input" id="defaultChecked2">
								  <label class="custom-control-label" for="defaultChecked2">Contract auto-renews?</label>
								</div>
							</div>
					</div>
					
                    <button type="submit" class="btn btn-primary pull-right">Add Contract</button>
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