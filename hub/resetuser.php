<?php

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();


}

require_once('../Connections/conn.php');

function rand_string( $length ) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);

}
mysqli_select_db($conn,$database_conn);
  $query_Recordset2 = "SELECT * FROM sysconfig";
  $Recordset2 = mysqli_query($conn,$query_Recordset2) or die( mysqli_error($conn));
  $row_Recordset2 = mysqli_fetch_array($Recordset2);

  
  
if(isset($_POST['username']))
{
  $username=$_POST['username'];
 
  $query_Recordset1 = "SELECT email FROM users where username ='$username'";
  $Recordset1 = mysqli_query($conn,$query_Recordset1) or die( mysqli_error($conn));
  $row_Recordset1 = mysqli_fetch_array($Recordset1);


    $smtpip = $row_Recordset2['smtp_ip'];
  $smtpport = $row_Recordset2['smtp_port'];
  $url = $row_Recordset2['url'];
  $fromemailaddress = $row_Recordset2['fromemailaddress'];
  $passwordhistory = $row_Recordset2['passwordhistory'];
  $passwordexpiry = $row_Recordset2['passwordexpiry'];
  $passwordlength = $row_Recordset2['passwordlength'];
 
 
   ini_set('SMTP',$smtpip);
   ini_set('smtp_port',$smtpport);
   
   if(is_null($row_Recordset1))
   {
                    
    $_SESSION['resetmsg']="The username specified does not exist.";
                                           
						    
    } 
    else
    {   

   $to=$row_Recordset1['email'];
  $pass1=rand_string(8);
  $pass2=md5($pass1);
  $updateSQL = "update  users  set password='$pass2',resetflag='1' where username='$username'";
  $Result1 = mysqli_query($conn,$updateSQL) or die( mysqli_error($conn));
  
  $subject = 'Contract Management System - Password Reset';
  ///$message = 'Your temporary password is :'.$pass1;
  $message = 'Your username is :'.$_POST['username']." \n " .'Temporary password is '.' '.$pass1.'  '." \n " .'Link: '.$url;	
  $messagex = 'Your username is :'.$_POST['username']." \n " .'Temporary password is '.' '.md5($pass1).'  '." \n " .'Link: '.$url;	
							 
  $headers = 'From:'.$fromemailaddress.' . '."\r\n" ;
	
  $outboundemail='muianelson@gmail.com';
  $outboundemailpass='@Hazy198700';
    $sendmail=mail($to, $subject, $message, $headers);
    $time = time();
  
   if($sendmail)
   { 
	 $sqlstmt="INSERT INTO  emaillog (fromadd,sendto,subject,message,datetime) VALUES ('$fromemailaddress','$to', '$subject','$messagex','$time')";
	   mysqli_query($conn,$sqlstmt) or die(mysqli_error($conn)); 
   		$_SESSION['resetmsg']='Your account has been reset and the new password has been sent to your email address';
   }
   else
   {
   		$_SESSION['resetmsg']='There is an issue with email delivery. Please contact administrator';
   
   }
  }				
}?>

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
				
				<span class="login100-form-title p-b-41"><img src='<?php echo $row_Recordset2['companylogo'];?>'>
					 <br>
					Contract Manager <br>  
				</span>
				<form class="login100-form validate-form p-b-33 p-t-5" ACTION="resetuser.php" name="reset" method="POST">

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="Enter Your User name">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					
					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn">
							Reset Password
						</button>
						
						<div class="container-login100-form-btn m-t-32">
						
							<a href="index.php">Login</a>
					
					</div>
						
						<?php 
					if(isset($_SESSION['resetmsg']))
					{
					?>
					<div id='hideMe' style="color:#FF0000" class="container-login100-form-btn m-t-32"><span class="container-login100-form-btn m-t-32" style="color:#FF0000"><?php echo $_SESSION['resetmsg'];?></span></div>
					<?php 
					unset($_SESSION['resetmsg']);
					}?>
					</div>
							

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