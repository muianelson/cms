<?php 
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}


require_once('C:\wamp64\www\contractmanager\Connections\conn.php'); 

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
$url = $row_Recordset2['url']; 
$smtpip = $row_Recordset2['smtp_ip'];
$smtpport = $row_Recordset2['smtp_port'];
$globalalert = $row_Recordset2['alertdays'];
ini_set('SMTP',$smtpip);
ini_set('smtp_port',$smtpport);
$time = time();

//renew auto contracts

	$query_Recordsetxy = "SELECT a.id,a.alert_duration,a.contractname,a.contractdetail,a.executiondate,a.inputter,a.status,a.comment,a.departmentid,
			a.expiry_date,b.department,b.email FROM contracts a ,department b where a.departmentid=b.deptid and renewal='on'";
		$Recordsetxy = mysqli_query($conn,$query_Recordsetxy) or die(mysqli_error($conn));
		$row_Recordsetxy = mysqli_fetch_assoc($Recordsetxy);
 		
		do { 												
					
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
			}	}while ($row_Recordsetxy = mysqli_fetch_assoc($Recordsetxy)); 
		
		
//dailyexpiryalert

$query_Recordsetx = "SELECT a.id,a.alert_duration,a.contractname,a.contractdetail,a.executiondate,a.inputter,a.status,a.comment,a.departmentid,
a.expiry_date,b.department,b.email FROM contracts a ,department b where a.departmentid=b.deptid";
$Recordsetx = mysqli_query($conn,$query_Recordsetx) or die(mysqli_error($conn));
$row_Recordsetx = mysqli_fetch_assoc($Recordsetx);
$i=0;
do { 												
								
								if($row_Recordsetx['expiry_date']>=date("Y-m-d"))
								{
									if(datediff( date("Y-m-d"),$row_Recordsetx['expiry_date'])==$row_Recordsetx['alert_duration'])
									{	
										$to =$row_Recordsetx['email'];							
										//$to      = 'nmuia@nse.co.ke';
										
										$subject = 'Contract Expiry Alert -'.$row_Recordsetx['contractname'];
										$message = 'The contract'.' '.$row_Recordsetx['contractname'].
										' '.'will expire on '.' '.date("d-m-Y", strtotime($row_Recordsetx['expiry_date'])).'.'.$row_Recordsetx['comment'].'.'.' You can login to the contract management system to get more details. The link is : '.$url;
									
											$headers = 'From:'.$fromadd."\r\n" ;	
										$senddaily=mail($to, $subject, $message, $headers);
										   
										  
										if($senddaily)
										{
										 $sqlstmt="INSERT INTO  emaillog (fromadd,sendto,subject,message,datetime) VALUES 	('$fromadd','$to', '$subject','$message','$time')";
										  mysqli_query($conn,$sqlstmt) or die(mysqli_error($conn)); 
										
										//$_SESSION['dailyexpiryalert']='The daily expiry alert routine was executed successfully';
										
										}	
										
									}
									else
									{
										
											//$_SESSION['dailyexpiryalert']='The daily expiry alert routine execution failed. Contact the system administrator';
												
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
								
} while ($row_Recordsetx = mysqli_fetch_assoc($Recordsetx)); 
								
	
//globalexpiryalert	
mysqli_select_db($conn,$database_conn);
$query_Recordset1 = "SELECT a.id,a.alert_duration,a.contractname,a.contractdetail,a.executiondate,a.inputter,a.status,a.comment,a.departmentid,
a.expiry_date,b.department,b.email FROM contracts a ,department b where a.departmentid=b.deptid and datediff(expiry_date,CURDATE())>0 and datediff(CURDATE(),expiry_date)<='$globalalert'";
$Recordset1 = mysqli_query($conn,$query_Recordset1) or die(mysqli_error($conn));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$i=0;
do { 												
								
							
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
			$_SESSION['globalexpiryalert']='The global expiry alert routine was executed successfully';
		}
		else
		{
											
			$_SESSION['globalexpiryalert']='The global expiry alert routine execution failed. Contact the system administrator';
		}
							
								
								
	$i=$i+1;
								
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); 

session_destroy();

?>





