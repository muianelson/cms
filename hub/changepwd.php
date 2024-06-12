<?php

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}



if(isset($_GET['uid']))
{
$_SESSION['uid']=$_GET['uid'];

}

//echo $_SESSION['uid'];
require_once('../Connections/conn.php'); 
mysqli_select_db($conn,$database_conn);
  $query_Recordset2 = "SELECT * FROM sysconfig";
  $Recordset2 = mysqli_query($conn,$query_Recordset2) or die( mysqli_error($conn));
  $row_Recordset2 = mysqli_fetch_array($Recordset2);
if(isset($_POST['username']))
{


  $username=$_POST['username'];
  $password1 = $_POST['password1'];
  $password2 = $_POST['password2'];
 	
  

  
  $query_Recordset1 = "SELECT * FROM users where username ='$username'";
  $Recordset1 = mysqli_query($conn,$query_Recordset1) or die( mysqli_error($conn));
  $row_Recordset1 = mysqli_fetch_array($Recordset1);
  
  
  $uid=$row_Recordset1['id'];
  $curdate=date("Y/m/d");
  $passwordhistory = $row_Recordset2['passwordhistory'];
  $lastpasswordchangedate=$row_Recordset1['lastpasswordchangedate'];
  $passwordlength = $row_Recordset2['passwordlength'];
  
  // Validate password strength
	$uppercase = preg_match('@[A-Z]@', $password1);
	$lowercase = preg_match('@[a-z]@', $password1);
	$number    = preg_match('@[0-'.$passwordlength.']@', $password1);
	$specialChars = preg_match('@[^\w]@', $password1);
	

  $error=0;
  if($password1!=$password2)
  {
  		$_SESSION['pwdchangemsg']='The first password does not match with the second';
		$error=1;
  
  }

  	
  if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password1) < $passwordlength) {
	
		$_SESSION['pwdchangemsg']='Password should be at least '. $passwordlength.' characters in length and should include at least one upper case letter, one number, and one special character.';
		$error=1;
		
	} 

  
  $query_Recordset3 = "SELECT * FROM passwordhistory where userid='$uid' order by id desc limit ". $passwordhistory;
  $Recordset3 = mysqli_query($conn,$query_Recordset3) or die( mysqli_error($conn));
  $row_Recordset3 = mysqli_fetch_array($Recordset3);
	
  $password1=md5($password1);
	 
	do 
	{
            if(is_null($row_Recordset3)){}
            else{ 

		if($row_Recordset3['password']==$password1)
		{
			
			$_SESSION['pwdchangemsg']='You cannot reuse your last '. $passwordhistory.' passwords' ;
			$error=1;
		}
			
	}}	
   while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3));
  $userid=$row_Recordset1['id'];
  if($error!=1)
  {
  
  	 
	  $updateSQL = "update  users  set password='$password1',lastpasswordchangedate='$curdate',resetflag=0 where username='$username'";
	  $updateSQL2 = "insert into  passwordhistory(userid,password,PostingDate)  values('$userid','$password1','$curdate')";
	  $Result4 = mysqli_query($conn,$updateSQL) or die( mysqli_error($conn));
	  $Result5 = mysqli_query($conn,$updateSQL2) or die( mysqli_error($conn));
	   if($Result4)
	   {
			$_SESSION['pwdchangemsg1']='Your account has been updated successfully. Proceed to login';
	   }
	   else
	   {
			$_SESSION['pwdchangemsg']='There was an issue with password change. Please contact administrator';
	   
	   }
  
  		header("Location: index.php");
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
					Contract Manager <br>  Change Password
				</span>
				<form class="login100-form validate-form p-b-33 p-t-5" ACTION="changepwd.php" name="reset" method="POST">

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" value="<?php if(isset($_SESSION['uid'])){ echo $_SESSION['uid'];} else{ echo $_POST['username']; }?>" placeholder="Enter Your User name">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

						<div class="wrap-input100 validate-input" data-validate = "Enter password">
						<input class="input100" type="password" name="password1" placeholder="Enter your new password">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>
					
						<div class="wrap-input100 validate-input" data-validate = "Enter password">
						<input class="input100" type="password" name="password2" placeholder="Confirm your new password">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>
					
					
					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn">
							Submit
						</button>
						
				
						
						<?php 
					if(isset($_SESSION['pwdchangemsg']))
					{
					?>
					<div id='hideMe' style="color:#FF0000" class="container-login100-form-btn m-t-32"> <?php echo $_SESSION['pwdchangemsg'];?></div>
					<?php 
					
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