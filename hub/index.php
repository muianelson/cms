<?php

require_once('../Connections/conn.php');
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

mysqli_select_db($conn,$database_conn);
$queryx = "SELECT companylogo FROM sysconfig";
$Recordsetx = mysqli_query($conn,$queryx) or die(mysqli_error($conn));
$rowx = mysqli_fetch_assoc($Recordsetx);


// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();


}



$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
	
  $_SESSION['logincount']=0;
  $loginUsername=$_POST['username'];
  $password=md5($_POST['password']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "dashboard.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
  mysqli_select_db($conn,$database_conn);
   $curdate=date("Y/m/d");
   
   
  
   	$query_Recordset2 = "SELECT * FROM sysconfig";
  	$Recordset2 = mysqli_query($conn,$query_Recordset2) or die( mysqli_error($conn));
  	$row_Recordset2 = mysqli_fetch_array($Recordset2);
	$maxfailed=$row_Recordset2['maxfailedlogins'];
	
  
  $LoginRS__query=sprintf("SELECT username, password,lastpasswordchangedate,resetflag,logincount,id,admin FROM users WHERE username=%s AND password=%s and enabled=1 and logincount<'$maxfailed'",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysqli_query($conn,$LoginRS__query) or die(mysqli_error($conn));
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    $row_Recordset1 = mysqli_fetch_array($LoginRS);
	$uname= $row_Recordset1['username'];
	$usertype= $row_Recordset1['admin'];
	
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
	$_SESSION['utype'] = $usertype;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	 
	$_SESSION['userid']=$row_Recordset1['id'];
	
	
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }

	

	
  //echo $row_Recordset1['lastpasswordchangedate'].'<br>';
  //echo datediff(date("Y/m/d"),$row_Recordset1['lastpasswordchangedate']);
  //exit;
  
    $uname= $row_Recordset1['username'];
	//echo $row_Recordset1['resetflag'];
    $login=1;
	 
	 if($row_Recordset1['resetflag']=='1')
	 {
	 
	    $login=0;
	 	header("Location: " . 'changepwd.php?uid='. $uname );
	 
	 }
	 
	  if(datediff(date("Y/m/d"),$row_Recordset1['lastpasswordchangedate'])>=$row_Recordset2['passwordexpiry'])
	  {
	  
	
		$login=0;
	    $_SESSION['passchange']='Your password has expired';
		header("Location: " . 'changepwd.php?uid='. $uname );
		
			  
	  }
	 
	 
	 
	 
	 if($login=='1')
	 {
	  
	  			
			  $updateSQLx = "update users set lastlogindate='$curdate', logincount=0 where username='$loginUsername'";
		
			  $Resultx = mysqli_query($conn,$updateSQLx) or die( mysqli_error($conn));
			  // Update the timout field with the current time.
				$_SESSION['timeout'] = time();
	  		   header("Location: " . $MM_redirectLoginSuccess );
	  
	  }
	


  }
  else {
  	 $count=1; 
	 //echo $row_Recordset2['maxfailedlogins'];
	 
	 $usr="SELECT * from users where username='$loginUsername'";
	 $Resultus = mysqli_query($conn,$usr) or die( mysqli_error($conn));
	 $row_Recordset1 = mysqli_fetch_array($Resultus);
	 //echo $row_Recordset1['logincount'];
	 //exit;

	  if($row_Recordset1['logincount']==$row_Recordset2['maxfailedlogins'])
	  {
	  
		  
		  $updateSQLdis= "update users set enabled=0 where username='$loginUsername'";
			
		 $Resultdis = mysqli_query($conn,$updateSQLdis) or die( mysqli_error($conn));
	     $_SESSION['loginmsg']='Your account has been disabled due to maximum failed logins. Contact the administrator.';
	  }
	  else
	  {
		  $logincount=$row_Recordset1['logincount']+1;
		  $updateSQLxy = "update users set logincount='$logincount' where username='$loginUsername'";
			
		 $Resultxy = mysqli_query($conn,$updateSQLxy) or die( mysqli_error($conn));
		 $_SESSION['loginmsg']='Invalid username or password. Try again';
	 } 
	 
	 
    header("Location: ". $MM_redirectLoginFailed );
  }
}
else{

$count=0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Contract Mgt System - V2.0</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
<style>
#hideMe {
    -moz-animation: cssAnimation 0s ease-in 5s forwards;
    /* Firefox */
    -webkit-animation: cssAnimation 0s ease-in 5s forwards;
    /* Safari and Chrome */
    -o-animation: cssAnimation 0s ease-in 5s forwards;
    /* Opera */
    animation: cssAnimation 0s ease-in 5s forwards;
    -webkit-animation-fill-mode: forwards;
    animation-fill-mode: forwards;
}
@keyframes cssAnimation {
    to {
        width:0;
        height:0;
        overflow:hidden;
    }
}
@-webkit-keyframes cssAnimation {
    to {
        width:0;
        height:0;
        visibility:hidden;
    }
}


</style>

</head>
<body>
	
	<div class="limiter">
	
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
		
			<div class="wrap-login100 p-t-30 p-b-50">
			
                            <span class="login100-form-title p-b-41">
                                <div style="float: left;width: 50%; "><img src='<?php echo $rowx['companylogo'];?>'></div>
                                <div style="float: left;width: 50%;font-size: 21px">Contract Manager</div><br>
				</span>
				<form class="login100-form validate-form p-b-33 p-t-5" ACTION="<?php echo $loginFormAction; ?>" name="form1" method="POST">

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="User name">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
					</div>

					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
					<div class="container-login100-form-btn m-t-32">
						
							<a href="resetuser.php">Forgot password</a>
					
					</div>
					
					<?php 
					if(isset($_SESSION['loginmsg']))
					{
					?>
					<div id='hideMe' style="color:#FF0000" class="container-login100-form-btn m-t-32"> <?php echo $_SESSION['loginmsg'];?></div>
					<?php 
					
					
					}
					
					if( $count==0)
					{
					
					unset($_SESSION['loginmsg']);
					}
					?>
					

				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>