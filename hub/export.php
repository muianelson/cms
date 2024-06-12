<?php

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION['MM_Username'])) {
header("Location: index.php");
}

//export.php  
$connect = mysqli_connect("localhost", "root", "", "contracts");
$output = '';
if($_POST["reporttype"]=="Valid contracts")
{
 $query = "SELECT a.contractname,a.contractdetail,a.alert_duration,a.executiondate,a.status,a.comment,a.expiry_date,b.department,a.inputter FROM contracts a , department b where a.departmentid=b.deptid and a.status='Valid' and a.departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') ";
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>Contract name</th>  
                         <th>Contract detail</th>  
                         <th>Alert duration</th>  
						  <th>Execution date</th>
						  <th>Status</th>
						  <th>Comment</th>
						  <th>Expiry date</th>
						  <th>Department</th>
						  <th>Inputter</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["contractname"].'</td>  
                         <td>'.$row["contractdetail"].'</td>  
                         <td>'.$row["alert_duration"].'</td>
						 <td>'.$row["executiondate"].'</td>  
                         <td>'.$row["status"].'</td>  
                         <td>'.$row["comment"].'</td>  
						 <td>'.$row["expiry_date"].'</td>  
                         <td>'.$row["department"].'</td>  
                         <td>'.$row["inputter"].'</td>     
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=validcontracts.xls');
  $_SESSION['exportalert']="The report was exported successfully";
  echo $output;
  
 }
 else
 {
 		$_SESSION['exportalert']="There are no valid records to export";
		 header("Location:reports.php");
 }
}

else if($_POST["reporttype"]=="All contracts")
{
 $query = "SELECT a.contractname,a.contractdetail,a.alert_duration,a.executiondate,a.status,a.comment,a.expiry_date,b.department,a.inputter FROM contracts a , department b where a.departmentid=b.deptid and a.departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') ";
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>Contract name</th>  
                         <th>Contract detail</th>  
                         <th>Alert duration</th>  
						  <th>Execution date</th>
						  <th>Status</th>
						  <th>Comment</th>
						  <th>Expiry date</th>
						  <th>Department</th>
						  <th>Inputter</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["contractname"].'</td>  
                         <td>'.$row["contractdetail"].'</td>  
                         <td>'.$row["alert_duration"].'</td>
						 <td>'.$row["executiondate"].'</td>  
                         <td>'.$row["status"].'</td>  
                         <td>'.$row["comment"].'</td>  
						 <td>'.$row["expiry_date"].'</td>  
                         <td>'.$row["department"].'</td>  
                         <td>'.$row["inputter"].'</td>     
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=allcontracts.xls');
  $_SESSION['exportalert']="The report was exported successfully";
  echo $output;
 } else
 {
 		$_SESSION['exportalert']="There are no valid records to export";
		 header("Location:reports.php");
 }
}

else if($_POST["reporttype"]=="Expired contracts")
{
 $query = "SELECT a.contractname,a.contractdetail,a.alert_duration,a.executiondate,a.status,a.comment,a.expiry_date,b.department,a.inputter FROM contracts a , department b where a.departmentid=b.deptid and a.status='Expired' and a.departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') ";
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>Contract name</th>  
                         <th>Contract detail</th>  
                         <th>Alert duration</th>  
						  <th>Execution date</th>
						  <th>Status</th>
						  <th>Comment</th>
						  <th>Expiry date</th>
						  <th>Department</th>
						  <th>Inputter</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["contractname"].'</td>  
                         <td>'.$row["contractdetail"].'</td>  
                         <td>'.$row["alert_duration"].'</td>
						 <td>'.$row["executiondate"].'</td>  
                         <td>'.$row["status"].'</td>  
                         <td>'.$row["comment"].'</td>  
						 <td>'.$row["expiry_date"].'</td>  
                         <td>'.$row["department"].'</td>  
                         <td>'.$row["inputter"].'</td>     
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=expiredcontracts.xls');
  $_SESSION['exportalert']="The report was exported successfully";
  echo $output;
 } else
 {
 		$_SESSION['exportalert']="There are no valid records to export";
		 header("Location:reports.php");
 }
}

else if($_POST["reporttype"]=="expirein")
{

$days=$_POST['specifydays'];
 $query = "SELECT a.contractname,a.contractdetail,a.alert_duration,a.executiondate,a.status,a.comment,a.expiry_date,b.department,a.inputter FROM contracts a , department b where a.departmentid=b.deptid and a.departmentid in (select deptid  from user_report_mapping where userid='$_SESSION[userid]') and datediff(expiry_date,CURDATE())>0 and datediff(expiry_date,CURDATE()) <=$days";
 
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>Contract name</th>  
                         <th>Contract detail</th>  
                         <th>Alert duration</th>  
						  <th>Execution date</th>
						  <th>Status</th>
						  <th>Comment</th>
						  <th>Expiry date</th>
						  <th>Department</th>
						  <th>Inputter</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["contractname"].'</td>  
                         <td>'.$row["contractdetail"].'</td>  
                         <td>'.$row["alert_duration"].'</td>
						 <td>'.$row["executiondate"].'</td>  
                         <td>'.$row["status"].'</td>  
                         <td>'.$row["comment"].'</td>  
						 <td>'.$row["expiry_date"].'</td>  
                         <td>'.$row["department"].'</td>  
                         <td>'.$row["inputter"].'</td>     
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=contractsexpiringin'.$days.'days.xls');
  $_SESSION['exportalert']="The report was exported successfully";
  echo $output;
 } else
 {
 		$_SESSION['exportalert']="There are no valid records to export";
		 header("Location:reports.php");
 }
}

else if($_POST["reporttype"]=="users")
{


 $query = "SELECT username 'Username',CASE WHEN enabled = 1 THEN 'Enabled' WHEN enabled = 0 THEN 'Disabled'  ELSE 'Archived' END 'User status',
CASE WHEN admin= 1 THEN 'Admin user' WHEN admin= 0 THEN 'Regular user' END 'User Type',email 'Email',nameofuser 'Name of user',lastlogindate 'Last login date',
lastpasswordchangedate 'Last password change date',createdate 'Creation date' FROM users";
 
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>Username</th>  
                         <th>User status</th>  
                         <th>User Type</th>  
						  <th>Email</th>
						  <th>Name of user</th>
						  <th>Last login date</th>
						  <th>Last password change date</th>
						  <th>Creation date</th>
						 
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["Username"].'</td>  
                         <td>'.$row["User status"].'</td>  
						 <td>'.$row["User Type"].'</td>  
                         <td>'.$row["Email"].'</td>  
                         <td>'.$row["Name of user"].'</td>  
						 <td>'.$row["Last login date"].'</td>  
                         <td>'.$row["Last password change date"].'</td>  
                         <td>'.$row["Creation date"].'</td>     
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=Userreport.xls');
  $_SESSION['exportalert']="The report was exported successfully";
  echo $output;
 } else
 {
 		$_SESSION['exportalert']="There are no valid records to export";
		 header("Location:reports.php");
 }
}



else if($_POST["reporttype"]=="user-department-view")
{


 $query = "select a.username 'Username',a.nameofuser 'Name of User',e.department 'Department' from users a,user_report_mapping c, department e where a.id=c.userid and c.deptid=e.deptid";
 
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>Username</th>  
                  		  <th>Name of User</th>
						  <th>Department</th>						 
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["Username"].'</td>  
                         <td>'.$row["Name of User"].'</td>  
						 <td>'.$row["Department"].'</td>   
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=Userdepartmentviewreport.xls');
  $_SESSION['exportalert']="The report was exported successfully";
  echo $output;
 } else
 {
 		$_SESSION['exportalert']="There are no valid records to export";
		 header("Location:reports.php");
 }
}

else if($_POST["reporttype"]=="user roles")
{


 $query = "select a.username 'Username',a.nameofuser 'Name of User',d.description Role from users a,user_roles b,pages d where a.id=b.userid and b.pageid=d.pageid";
 
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>Username</th>  
                  		  <th>Name of User</th>
						  <th>Role</th>						 
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["Username"].'</td>  
                         <td>'.$row["Name of User"].'</td>  
						 <td>'.$row["Role"].'</td>   
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=User_Roles.xls');
  $_SESSION['exportalert']="The report was exported successfully";
  echo $output;
 } else
 {
 		$_SESSION['exportalert']="There are no valid records to export";
		 header("Location:reports.php");
 }
}



else
{
	 header("Location:reports.php");
}
?>
