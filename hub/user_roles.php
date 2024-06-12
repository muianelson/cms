<?php require_once('../Connections/conn.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}



$currentPage = $_SERVER["PHP_SELF"];



$maxRows_Recordset3 = 5;
$pageNum_Recordset3 = 0;
if (isset($_GET['pageNum_Recordset3'])) {
  $pageNum_Recordset3 = $_GET['pageNum_Recordset3'];
}
$startRow_Recordset3 = $pageNum_Recordset3 * $maxRows_Recordset3;

mysqli_select_db($conn,$database_conn);
$query_Recordset3 = "select a.username,a.nameofuser,a.admin,a.enabled,b.description role from users a,pages b,user_roles c where b.pageid=c.pageid and c.userid=a.id";
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




$maxRows_Recordset4 = 5;
$pageNum_Recordset4 = 0;
if (isset($_GET['pageNum_Recordset4'])) {
  $pageNum_Recordset4 = $_GET['pageNum_Recordset4'];
}
$startRow_Recordset4 = $pageNum_Recordset4 * $maxRows_Recordset4;

mysqli_select_db($conn,$database_conn);
$query_Recordset4 = "select a.username,a.nameofuser,c.department from users a,user_report_mapping b,department c where a.id=b.userid and c.deptid=b.deptid";
$query_limit_Recordset4 = sprintf("%s LIMIT %d, %d", $query_Recordset4, $startRow_Recordset4, $maxRows_Recordset4);
$Recordset4 = mysqli_query($conn,$query_limit_Recordset4) or die(mysqli_error($conn));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);

if (isset($_GET['totalRows_Recordset4'])) {
  $totalRows_Recordset4 = $_GET['totalRows_Recordset4'];
} else {
  $all_Recordset4 = mysqli_query($conn,$query_Recordset4);
  $totalRows_Recordset4 = mysqli_num_rows($all_Recordset4);
}
$totalPages_Recordset4 = ceil($totalRows_Recordset4/$maxRows_Recordset4)-1;


$queryString_Recordset4 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset4") == false && 
        stristr($param, "totalRows_Recordset4") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset4 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset4 = sprintf("&totalRows_Recordset4=%d%s", $totalRows_Recordset4, $queryString_Recordset4);

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
  <title>Contract Manager - User roles</title>
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
                  <h4 class="card-title ">User roles </h4>
                  <p class="card-category"> This window shows a list of users and their roles. </p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    
					
					 <table class="table">
                      <thead class=" text-primary">
                      <th>
                          username                         </th>
                        <th>
                          name of user                        </th>
                              <th>
                          role                       </th>
					
                       </thead>
                      <tbody>
					    <?php do { 
								
							if(is_null($row_Recordset3)){?>
                    
                                                    <option>There are no user roles</option>
						    <?php
                                            } else{   ?>	
                        <tr>
                          <td>
                             <a href="">
							 <?php echo $row_Recordset3['username']; ?></a>                   </td>
                          <td>
                           <?php echo $row_Recordset3['nameofuser']; ?>                         </td>
                          
       					    <td>
                           <?php echo $row_Recordset3['role']; ?>                         </td>
									  
									   
			
	
						   
                        </tr>
                
                      </tbody>
                                            <?php }} while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
					  
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
					
					
				<div class="card-header card-header-primary">
                  <h4 class="card-title ">User department contract mapping </h4>
                  <p class="card-category"> This window shows a list of users and departments which they can view contracts. </p>
                </div>
                <div class="card-body">
					<table class="table">
                      <thead class=" text-primary">
                      <th>
                          username                         </th>
                        <th>
                          name of user                        </th>
                              <th>
                          User - department contracts                       </th>
					
                       </thead>
                      <tbody>
					    <?php do { 
								
												
								
								if(is_null($row_Recordset4)){?>
                    
                                                    <option>There are no departments</option>
						    <?php
                                            } else{   ?>	
                        <tr>
                          <td>
                             <a href="">
							 <?php echo $row_Recordset4['username']; ?></a>                   </td>
                          <td>
                           <?php echo $row_Recordset4['nameofuser']; ?>                         </td>
                          
       					    <td>
                           <?php echo $row_Recordset4['department']; ?>                         </td>
									  
									   
			
	
						   
                        </tr>
                
                      </tbody>
                                            <?php }} while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
					  
					       <tbody>
					      <tr><td><?php if ($pageNum_Recordset4 > 0) { // Show if not first page ?>
                                        <a href="<?php printf("%s?pageNum_Recordset4=%d%s", $currentPage, 0, $queryString_Recordset4); ?>"><img src="../First.PNG" width="47" height="33" border="0" /></a>
                                        <?php } // Show if not first page ?></td>
						   <td><?php if ($pageNum_Recordset4 > 0) { // Show if not first page ?>
                                        <a href="<?php printf("%s?pageNum_Recordset4=%d%s", $currentPage, max(0, $pageNum_Recordset4 - 1), $queryString_Recordset4); ?>"><img src="../Previous.PNG" width="42" height="31" border="0" /></a>
                                        <?php } // Show if not first page ?></td>
										<td><?php if ($pageNum_Recordset4 < $totalPages_Recordset4) { // Show if not last page ?>
                                        <a href="<?php printf("%s?pageNum_Recordset4=%d%s", $currentPage, min($totalPages_Recordset4, $pageNum_Recordset4 + 1), $queryString_Recordset4); ?>"><img src="../Next.PNG" width="38" height="30" border="0" /></a>
                                        <?php } // Show if not last page ?></td>
										<td><?php if ($pageNum_Recordset4 < $totalPages_Recordset4) { // Show if not last page ?>
                                        <a href="<?php printf("%s?pageNum_Recordset4=%d%s", $currentPage, $totalPages_Recordset4, $queryString_Recordset4); ?>"><img src="../Last.PNG" width="40" height="31" border="0" /></a>
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