<?php require_once('../Connections/conn.php');

if (!isset($_SESSION)) {
  session_start();
}


if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}


mysqli_select_db($conn,$database_conn);
$query_Recordset1 = "SELECT sum(value) value FROM contracts  where status='Valid' and departmentid in (select deptid from user_report_mapping where userid='$_SESSION[userid]') ";
$Recordset1 = mysqli_query($conn,$query_Recordset1) or die(mysqli_error($conn));
$row_Recordset1= mysqli_fetch_assoc($Recordset1);
$contractvalue=$row_Recordset1['value'];

$query_Recordset2 = "SELECT count(*) contracts FROM contracts where upload_file=''  and departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') ";
$Recordset2 = mysqli_query($conn,$query_Recordset2) or die(mysqli_error($conn));
$row_Recordset2= mysqli_fetch_assoc($Recordset2);
$nocontractswithoutuploads=$row_Recordset2['contracts'];

$query_Recordset3 = "SELECT count(*) contracts FROM contracts  where departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') ";
$Recordset3 = mysqli_query($conn,$query_Recordset3) or die(mysqli_error($conn));
$row_Recordset3= mysqli_fetch_assoc($Recordset3);
$nocontracts=$row_Recordset3['contracts'];

$query_Recordset4 = "SELECT count(*) contracts FROM contracts where status='Expired'  and departmentid in (select deptid  from  user_report_mapping where userid='$_SESSION[userid]') ";
$Recordset4 = mysqli_query($conn,$query_Recordset4) or die(mysqli_error($conn));
$row_Recordset4= mysqli_fetch_assoc($Recordset4);
$nocontractsexpired=$row_Recordset4['contracts'];


$query_Recordset5 = "SELECT count(*) contracts FROM contracts where value =0  and departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') ";
$Recordset5 = mysqli_query($conn,$query_Recordset5) or die(mysqli_error($conn));
$row_Recordset5= mysqli_fetch_assoc($Recordset5);
$nocontractswithoutcontractvalue=$row_Recordset5['contracts'];


$query_Recordset6 = "SELECT count(*) contracts FROM contracts where status ='Valid'  and departmentid in (select deptid  from  user_report_mapping where userid='$_SESSION[userid]') ";
$Recordset6 = mysqli_query($conn,$query_Recordset6) or die(mysqli_error($conn));
$row_Recordset6= mysqli_fetch_assoc($Recordset6);
$activecontracts=$row_Recordset6['contracts'];


$query_Recordset7 = "SELECT count(*) contracts FROM contracts where datediff(expiry_date,CURDATE())>0 and datediff(CURDATE(),expiry_date)<=30  and departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') ";
$Recordset7 = mysqli_query($conn,$query_Recordset7) or die(mysqli_error($conn));
$row_Recordset7= mysqli_fetch_assoc($Recordset7);
$contractsexpiringin30=$row_Recordset7['contracts'];

$query_Recordset8 = "SELECT b.department department,count(*) countcont,b.deptid FROM contracts a , department b where a.departmentid=b.deptid  and a.status='Valid'  and a.departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]')  group by b.department";
$Recordset8 = mysqli_query($conn,$query_Recordset8) or die(mysqli_error($conn));
$row_Recordset8 = mysqli_fetch_assoc($Recordset8);

$query_Recordset9 = "SELECT b.department department,sum(value) value,b.deptid FROM contracts a , department b where a.departmentid=b.deptid  and a.status='Valid'  and a.departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]')  group by b.department";
$Recordset9 = mysqli_query($conn,$query_Recordset9) or die(mysqli_error($conn));
$row_Recordset9 = mysqli_fetch_assoc($Recordset9);


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
  <title>
    Contract Management System - Dashboard
  </title>
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
    <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
 
      <?php include("links.php");?>
    </div>
    <div class="main-panel">
   <!-- Navbar -->
      <?php include("navbar.php");?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">content_copy</i>
                  </div>
                  <p class="card-category">Expired contracts</p>
                  <h3 class="card-title"><?php echo $nocontractsexpired; ?>
                    <small></small>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                    <a href="expired_contracts.php"> Click here to renew ...</a>
                  </div>
                </div>
              </div>
            </div>
          
			
			<div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">add_alert</i>
                  </div>
                  <p class="card-category">Contracts without values</p>
                  <h3 class="card-title"><?php echo $nocontractswithoutcontractvalue;?></h3>
                </div>
               <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                    <a href="contractswithoutvalues.php"> Click here to view ...</a>
                  </div>
                </div>
              </div>
            </div>
			
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">info_outline</i>
                  </div>
                  <p class="card-category">Incomplete contracts </p>
                  <h3 class="card-title"><?php echo $nocontractswithoutuploads;?></h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                 
				   <a href="incompletecontracts.php"> Click here to view ...</a>
                  </div>
                </div>
              </div>
            </div>
            
			  <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">store</i>
                  </div>
                  <p class="card-category">Total contract value</p>
                  <h3 class="card-title"><small style="font-size:15px"><?php echo number_format($contractvalue,2); ?></small></h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
   <!--                 <i class="material-icons">date_range</i> Last 24 Hours-->
                  </div>
                </div>
              </div>
            </div>
			
          <div class="row">
            <div class="col-md-4">
              <div class="card card-chart">
                <div class="card-header card-header-success">
                  <div class="ct-chart" id="dailySalesChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Total contracts</h4>
                  <p class="card-category">
                    <span class="text-success"><!--<i class="fa fa-long-arrow-up">--></i><?php echo $nocontracts;?>  </span></p>
                </div>
                <div class="card-footer">
                  <div class="stats">
                   <!-- <i class="material-icons">access_time</i> updated 4 minutes ago-->
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-chart">
                <div class="card-header card-header-warning">
                  <div class="ct-chart" id="websiteViewsChart"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Active Contracts</h4>
                  <p class="card-category"><?php echo $activecontracts; ?></p>
                </div>
                <div class="card-footer">
                  <div class="stats">
                  <!--  <i class="material-icons">access_time</i> updated  2 days ago-->
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-chart">
                <div class="card-header card-header-danger">
                  <div class="ct-chart" id="completedTasksChart"></div>
                </div>
                <div class="card-body"> 
				<i class="material-icons text-danger">warning</i>
                    <a href="expiring_month.php"> Contracts expiring in 30 days</a>
				
                </div>
                <div class="card-footer">
                  <div class="stats">
             <!--       <i class="material-icons">access_time</i>updated 2 days ago-->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-12">
              <div class="card">
                <div class="card-header card-header-tabs card-header-primary">
                  <div class="nav-tabs-navigation">
                   Number of active contracts per department
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                    <table class="table table-hover">
                    <thead class="text-warning">
                      <th>Department</th>
                      <th>Number of contracts</th>
                    
                    </thead>
                    <tbody>
		    <?php do { 
								
												
                    if(is_null($row_Recordset9)){?>
                    
                         <tr colspan="2">
                             <td> There are no contracts</td>
							
                       
                      </tr>
                          <?php
                    }                                                							
                    else {
								?>
                      <tr>
                        <td>  <a href="departmentalsearch.php?did=<?php echo $row_Recordset8['deptid']; ?>">
							 <?php echo ucwords($row_Recordset8['department']); ?></a> </td>
                        <td><?php echo $row_Recordset8['countcont']; ?></td>
                       
                      </tr>
                      
                    <?php }} while ($row_Recordset8 = mysqli_fetch_assoc($Recordset8)); ?>
                    </tbody>
                  </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-12">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title">Value of active contracts per department</h4>
                <!--  <p class="card-category">updated as at ..</p>-->
                </div>
                <div class="card-body table-responsive">
                  <table class="table table-hover">
                    <thead class="text-warning">
                      <th>Department</th>
                      <th>Contract value (Ksh) </th>
                    
                    </thead>
                    <tbody>
					 <?php do { 
								
		    if(is_null($row_Recordset9)){?>
                    
                         <tr colspan="2">
                             <td> There are no contracts</td>
							
                       
                      </tr>
                          <?php
                    }                                         						
		    else
                    {
								?>
                      <tr>
                         <td>  <a href="departmentalsearch.php?did=<?php echo $row_Recordset9['deptid']; ?>">
							 <?php echo ucwords($row_Recordset9['department']); ?></a> </td>
                        <td><?php echo ucwords(number_format($row_Recordset9['value'], 2)); ?></td>
                       
                      </tr>
                                         <?php }} while ($row_Recordset9 = mysqli_fetch_assoc($Recordset9)); ?>
                    </tbody>
                  </table>
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
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

    });
  </script>
</body>

</html>