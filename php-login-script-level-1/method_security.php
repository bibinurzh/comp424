<?php
// core configuration
include_once "config/core.php";
include_once "config/database.php";
include_once "objects/user.php";
include_once "libs/php/utils.php"; 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
 
// check if email is in the database
$user->email=$_POST['email'];
 
// check if email exists, also get user details using this emailExists() method
$email_exists = $user->emailExists();
 
// set page title
$page_title = "Password Retrieval";
 
// include login checker
include_once "login_checker.php";

// include page header HTML
include_once "layout_head.php";
$myFrom = false;
?>
<html>
<body>
	<center>
	   <form action="" method="POST">
	   <table class="table table-hover table-responsive table-bordered">
		<tr>
		<td>Email</td>
		<td><input type="text" name="email" class="form-control" value ="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" placeholder="Enter email"/> <br/></td>
		</tr>
	   </table>
		<input type="submit" name ="search" class="btn btn-primary" value="Continue">
	   </form>
<?php

if(isset($_POST['search'])) {
	if($email_exists) {
		$myFrom = true;
		$_SESSION['sec_a1']=$user->sec_a1;
		$_SESSION['sec_a2']=$user->sec_a2;
		$_SESSION['sec_a3']=$user->sec_a3;
		$_SESSION['access_code']=$user->access_code;
	}
	else {
    		echo "<div class='alert alert-danger'>
        		<strong>No users found.</strong>
    		</div>";
	}

}
?>
<?php
if($myFrom) {
?>
	<p>
           <form action="" method="POST">
           <table class="table table-hover table-responsive table-bordered">
     		
			<tr><td><option value ="<?php echo $user->sec_q1?>"><?php echo $user->sec_q1?></option>
			</td>
			<td><input type = "text" name ="sec_a1" class="form-control"></td>
			</tr>
			<tr><td><option value ="<?php echo $user->sec_q2?>"><?php echo $user->sec_q2?></option>
			</td>
			<td><input type = "text" name ="sec_a2" class="form-control"></td>
			</tr>
			<tr><td><option value ="<?php echo $user->sec_q3?>"><?php echo $user->sec_q3?></optio$
                        </td>
                        <td><input type = "text" name ="sec_a3" class="form-control"></td>
                        </tr>                
           </table>
		<input type="submit" name ="done" class="btn btn-primary" value="Done">
           </form>
	   </p>
<?php
}
if(isset($_POST['done'])) {
	if(password_verify($_POST['sec_a1'], $_SESSION['sec_a1']) && password_verify($_POST['sec_a2'], $_SESSION['sec_a2']) && password_verify($_POST['sec_a3'], $_SESSION['sec_a3'])) {
                header("Location: {$home_url}reset_password/?access_code={$_SESSION['access_code']}");
	}
	else {
        	echo "<div class='alert alert-danger'>
        	<strong>Security answers are wrong.</strong>
    		</div>";
	}
}
?>
</center>
</body>
</html>
<?php
include_once "layout_foot.php";
?>
