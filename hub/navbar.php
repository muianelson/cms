 <?php 
if (!isset($_SESSION)) {
  session_start();
}if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}

$currentPage =  basename($_SERVER['PHP_SELF']);
mysqli_select_db($conn,$database_conn);
$queryx2 = "SELECT companyname FROM sysconfig";
$Recordsetx2 = mysqli_query($conn,$queryx2) or die(mysqli_error($conn));
$rowx2 = mysqli_fetch_assoc($Recordsetx2);


$queryx3 = "SELECT nameofuser FROM users where username='$_SESSION[MM_Username]'";
$Recordsetx3 = mysqli_query($conn,$queryx3) or die(mysqli_error($conn));
$rowx3 = mysqli_fetch_assoc($Recordsetx3);

$query_Recordset10 = "SELECT count(*) contracts FROM contracts where status ='Archived' and departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') ";
$Recordset10 = mysqli_query($conn,$query_Recordset10) or die(mysqli_error($conn));
$row_Recordset10= mysqli_fetch_assoc($Recordset10);
$Archivedcontracts=$row_Recordset10['contracts'];


?>

<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;"><b><?php echo $rowx2['companyname'];?> </b> contract management system</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <!--<form class="navbar-form">
              <div class="input-group no-border">
                <input type="text" value="" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>-->
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    Stats
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">notifications</i>
				  
				  
				    <?php 
					$notifcount=2;
					if($Archivedcontracts!=0)
				    {
				 	 $notifcount=$notifcount+1;
				   }?>
				   
                  <span class="notification"><?php echo  $notifcount;?></span>
                  <p class="d-lg-none d-md-block">
                    Some Actions
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="">Welcome <?php echo $rowx3['nameofuser'];?> </a>
				  
				  <?php if($Archivedcontracts!=0)
				  {?>
                 <a class="dropdown-item" href="archived_contracts.php"><?php echo "You have ". $Archivedcontracts;?> archived contracts </a>
           <?php }?>
		   
		    <a class="dropdown-item" href="">Click on a department on the dashboard to view its contracts  </a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                 <!-- <a class="dropdown-item" href="profile.php">Profile</a>-->
                <!--  <a class="dropdown-item" href="#">Settings</a>-->
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="logout.php">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>