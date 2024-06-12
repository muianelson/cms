 <?php require_once('../Connections/conn.php');
if (!isset($_SESSION)) {
  session_start();
}


if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}


 $currentPage =  basename($_SERVER['PHP_SELF']);
mysqli_select_db($conn,$database_conn);
$queryx = "SELECT companylogo,session_timeout FROM sysconfig";
$Recordsetx = mysqli_query($conn,$queryx) or die(mysqli_error($conn));
$rowx = mysqli_fetch_assoc($Recordsetx);

$query_Recordsetp = "select a.username,b.page,b.material_icon,b.description from users a,pages b,user_roles c where b.pageid=c.pageid and c.userid=a.id and a.enabled=1 and b.material_icon!='' and username='$_SESSION[MM_Username]' and b.page!='dashboard.php' order by 4";
$Recordsetp = mysqli_query($conn,$query_Recordsetp) or die(mysqli_error($conn));
$row_Recordsetp = mysqli_fetch_assoc($Recordsetp);

$timeout = $rowx['session_timeout']; // Number of seconds until it times out.
 
// Check if the timeout field exists.
if(isset($_SESSION['timeout'])) {
    // See if the number of seconds since the last
    // visit is larger than the timeout period.
    $duration = time() - (int)$_SESSION['timeout'];
    if($duration > $timeout) {
        // Destroy the session and restart it.
        session_destroy();
        header("Location:index.php");
    }
}


?>
<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="" class="simple-text logo-normal">
		<img src='<?php echo $rowx['companylogo'];?>'>

        </a></div>
      <div class="sidebar-wrapper">
	    <ul class="nav">
	 <li class="nav-item  <?php if ($currentPage=='dashboard.php') echo 'active'; else echo '';?>">
            <a class="nav-link" href="./dashboard.php">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
	
	    <?php do { 
						?>
      
          <li class="nav-item  <?php if ($currentPage== $row_Recordsetp['page']) echo 'active'; else echo '';?>">
            <a class="nav-link" href="<?php echo $row_Recordsetp['page'];?>">
              <i class="material-icons"><?php echo $row_Recordsetp['material_icon'];?></i>
              <p><?php echo $row_Recordsetp['description'];?></p>
            </a>
          </li>
		 		  
		   <?php } while ($row_Recordsetp = mysqli_fetch_assoc($Recordsetp)); ?>
		   
		  	  <li class="nav-item  <?php if ($currentPage=='about.php') echo 'active'; else echo '';?>">
            <a class="nav-link" href="./about.php">
              <i class="material-icons">comment</i>
              <p>About</p>
            </a>
          </li>
		  
		   <!--	  <li class="nav-item">
            <a class="nav-link" href="../dokuwiki/doku.php?id=start" target="_blank">
                <i class="material-icons">announcement</i>
              <p>Help</p>
            </a>
          </li>-->
		  
        </ul>
		
		
		 
      </div>
    </div>