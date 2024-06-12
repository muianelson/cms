<?php require_once('../Connections/conn.php'); 
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}


function datediff($date1,$date2)
{
return round(abs(strtotime($date2)-strtotime($date1))/86400);
//Remember date2 is the latest date and date 1 is older date to get +ve result
}


mysqli_select_db($conn,$database_conn);

$query_Recordset2 = "SELECT * FROM sysconfig";
  $Recordset2 = mysqli_query($conn,$query_Recordset2) or die( mysqli_error($conn));
  $row_Recordset2 = mysqli_fetch_array($Recordset2);
  $fromadd = $row_Recordset2['fromemailaddress'];
  $smtpip = $row_Recordset2['smtp_ip'];
  $url = $row_Recordset2['url'];
  $smtpport = $row_Recordset2['smtp_port'];
  $globalalert = $row_Recordset2['alertdays'];
     ini_set('SMTP',$smtpip);
   ini_set('smtp_port',$smtpport);
$time = time();
if(isset($_POST["routine"]))
{
	if ($_POST["routine"]=='renewcontracts')
	 {
	 
	 	$query_Recordsetxy = "SELECT a.id,a.alert_duration,a.contractname,a.contractdetail,a.executiondate,a.inputter,a.status,a.comment,a.departmentid,
			a.expiry_date,b.department,b.email FROM contracts a ,department b where a.departmentid=b.deptid and renewal='on'";
		$Recordsetxy = mysqli_query($conn,$query_Recordsetxy) or die(mysqli_error($conn));
		$row_Recordsetxy = mysqli_fetch_assoc($Recordsetxy);
 		
		do { 												
                                 if(is_null($row_Recordsetxy)){
                                     $_SESSION['routinealert']='There are no contracts to auto renew';
                                 } else{
                    	
				if(date("Y-m-d")>=$row_Recordsetxy['expiry_date'])
				{
					$renew= "update contracts set expiry_date= DATE_ADD(expiry_date, INTERVAL 365 DAY) where renewal='on'";
					$Recordsetrenew = mysqli_query($conn,$renew) or die(mysqli_error($conn));
					
					if($Recordsetrenew)
					{
						$_SESSION['routinealert']='The renew contracts routine was executed successfully';
					}
					else{
					
						$_SESSION['routinealert']='The renew contracts routine execution failed. Contact the system administrator';
					}
			}	}}while ($row_Recordsetxy = mysqli_fetch_assoc($Recordsetxy)); 
	}
	
	
	if ($_POST["routine"]=='dailyexpiryalert')
	 {
		
	
		$query_Recordsetx = "SELECT a.id,a.alert_duration,a.contractname,a.contractdetail,a.executiondate,a.inputter,a.status,a.comment,a.departmentid,
			a.expiry_date,b.department,b.email FROM contracts a ,department b where a.departmentid=b.deptid";
		$Recordsetx = mysqli_query($conn,$query_Recordsetx) or die(mysqli_error($conn));
		$row_Recordsetx = mysqli_fetch_assoc($Recordsetx);
 		

		$i=0;
		do { 												
                                                        if(is_null($row_Recordsetx)){
                                                                $_SESSION['routinealert']='There are no active contracts to send expiry alert';
                                                        } else{
								if($row_Recordsetx['expiry_date']>=date("Y-m-d"))
								{
									if(datediff( date("Y-m-d"),$row_Recordsetx['expiry_date'])==$row_Recordsetx['alert_duration'])
									{	
										$to =$row_Recordsetx['email'];							
										
										
										$subject = 'Contract Expiry Alert -'.$row_Recordsetx['contractname'];
										$message = 'The contract'.' '.$row_Recordsetx['contractname'].
										' '.'will expire on '.' '.date("d-m-Y", strtotime($row_Recordsetx['expiry_date'])).'.'.$row_Recordsetx['comment'].'.'.' You can login to the contract management system to get more details. The link is : '.$url;
										 
										$headers = 'From:'.$fromadd."\r\n" ;
										
										$senddaily=mail($to, $subject, $message, $headers);
										   
										  
										if($senddaily)
										{
										 $sqlstmt="INSERT INTO  emaillog (fromadd,sendto,subject,message,datetime) VALUES ('$fromadd','$to', '$subject','$message','$time')";
										  mysqli_query($conn,$sqlstmt) or die(mysqli_error($conn)); 
										
										$_SESSION['routinealert']='The daily expiry alert routine was executed successfully';
										
										}	
										
									}
									else
										{
										
											$_SESSION['routinealert']='The daily expiry alert did not run for some contracts due to alert before expiry settings. Check the log for more details.';
												
										}

								}
								
								
								if($row_Recordsetx['expiry_date']<date("Y-m-d"))
								{
								
								  $updateSQL = sprintf("UPDATE contracts SET status='Expired' where id='$row_Recordsetx[id]'");
													 
							
								  $Result1 = mysqli_query($conn,$updateSQL) or die(mysqli_error($conn));
								 echo $i."".  date("Y-m-d")."<br>";
								  
							
								}
								if($row_Recordsetx['expiry_date']>=date("Y-m-d"))
								{
								  $updateSQL = sprintf("UPDATE contracts SET status='Valid' where id='$row_Recordsetx[id]'");
													 
								 
								  $Result1 = mysqli_query($conn,$updateSQL) or die(mysqli_error($conn));
								  }
								
								$i=$i+1;
								
                }} while ($row_Recordsetx = mysqli_fetch_assoc($Recordsetx)); 
								//echo "Count:".$i; 
			
				
			
		}
	
	
	if ($_POST["routine"]=='globalexpiryalert')
	 {
		
		mysqli_select_db($conn,$database_conn);
		$query_Recordset1 = "SELECT a.id,a.alert_duration,a.contractname,a.contractdetail,a.executiondate,a.inputter,a.status,a.comment,a.departmentid,
			a.expiry_date,b.department,b.email FROM contracts a ,department b where a.departmentid=b.deptid and datediff(expiry_date,CURDATE())>0 and datediff(CURDATE(),expiry_date)<='$globalalert'";
		$Recordset1 = mysqli_query($conn,$query_Recordset1) or die(mysqli_error($conn));
		$row_Recordset1 = mysqli_fetch_assoc($Recordset1);


		$i=0;
		do { 												
								
                                                        if(is_null($row_Recordset1)){
                                                             $_SESSION['routinealert']='There are no active contracts to send expiry alert';
                                                        } else{
										$to =$row_Recordset1['email'];							
										
										
										$subject = 'Contract Expiry Alert -'.$row_Recordset1['contractname'];
										$message = 'The contract'.' '.$row_Recordset1['contractname'].
										' '.'will expire on '.' '.date("d-m-Y", strtotime($row_Recordset1['expiry_date'])).'.'.$row_Recordset1['comment'].'.'.' You can login to the contract management system to get more details. The link is : '.$url;
										 
								
										$headers = 'From:'.$fromadd."\r\n" ;		
										$senddaily=mail($to, $subject, $message, $headers);
										
										 
										   
										if($senddaily)
										{   
											$sqlstmt="INSERT INTO  emaillog (fromadd,sendto,subject,message,datetime) VALUES ('$fromadd','$to', '$subject','$message','$time')";
											mysqli_query($conn,$sqlstmt) or die(mysqli_error($conn)); 
											$_SESSION['routinealert']='The global expiry alert routine was executed successfully';
										}
										else{
										
											$_SESSION['routinealert']='The global expiry alert routine execution failed. Contact the system administrator';
										}
							
								
								
								$i=$i+1;
								
                }} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); 
						
			
		}
}

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
  <title>Contract Manager - System Maintenance</title>
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
                  <h4 class="card-title ">  System Maintenance </h4>
                  <p class="card-category"> This window allows you to perform system maintenance activities.</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <form action="" method="post" name="form1" id="form1">
				<p>
               <div class="row">
             
                
					  
                			  
					   <div class="col-md-4 mb-3">
						<label for="status">Routine to execute</label>
						<select class="custom-select d-block w-100" name="routine" id="routine" required>
						   <option value="">Choose...</option>
						  <option value="renewcontracts">Renew contracts</option>
						  <option value="dailyexpiryalert">Send daily contract expiry email alert</option>
						   <option value="globalexpiryalert">Send global expiry alert</option>
						</select>
						<div class="invalid-feedback">
						  Please provide a valid state.
						</div>
					  </div>
			  
                    </div>
						
                    <button type="submit" class="btn btn-primary pull-right">Run</button>
                    <div class="clearfix"></div>
                  </form>
					
					 
				 <div style="color:#FF0000;margin-bottom:10px">
						<?php 
						
						if(isset($_SESSION['routinealert']))
						{
						    echo $_SESSION['routinealert'];
							unset($_SESSION['routinealert']);
							
						}
						
						
						?>
			</div>
					<div style="padding-top:150px"></div>
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