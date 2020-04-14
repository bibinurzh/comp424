<?php
// core configuration
include_once "config/core.php";
 
// set page title
$page_title = "Sign Up";
 
// include login checker
include_once "login_checker.php";
 
// include classes
include_once 'config/database.php';
include_once 'objects/user.php';
include_once "libs/php/utils.php";
 
// include page header HTML
include_once "layout_head.php"; 
echo "<div class='col-md-12'>";
 
    // if form was posted
if($_POST){
 
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
 
    // initialize objects
    $user = new User($db);
    $utils = new Utils();
    // set user email to detect if it already exists
    $user->email=$_POST['email'];
 
    // check if email already exists
    if($user->emailExists()){
        echo "<div class='alert alert-danger'>";
            echo "The email you specified is already registered. Please try again or <a href='{$home_url}login'>login.</a>";
        echo "</div>";
    }
 
    else{
        // set values to object properties
$user->firstname=$_POST['firstname'];
$user->lastname=$_POST['lastname'];
$user->username=$_POST['username'];
$user->contact_number=$_POST['contact_number'];
$user->birthday=$_POST['birthday'];
$user->password=$_POST['password'];
$user->sec_q=$_POST['sec_q'];
$user->sec_a=$_POST['sec_a'];
$user->access_level='Customer';
$user->status=0;
// access code for email verification
$access_code=$utils->getToken();
$user->access_code=$access_code;
//check password requirements
if(strlen($_POST['password'])<8) {
	echo "<div class='aler alert-danger'>";
	echo "Password too short.";
	echo "</div>";
}
elseif(!preg_match("#[0-9]+#", $_POST['password'])) {
  	echo "<div class='aler alert-danger'>";
        echo "Password must have at least one number.";
        echo "</div>";

}
elseif(!preg_match("#[a-zA-Z]+#", $_POST['password'])) {
        echo "<div class='aler alert-danger'>";
        echo "Password must have at least one upper case.";
        echo "</div>";

}


elseif($_POST['password']==$_POST['confirmpassword']){
	//create the user
	if($user->create()){
		// send confimation email
   		$send_to_email=$_POST['email'];
    		$body="Hi {$send_to_email}.<br /><br />";
    		$body.="Please click the following link to verify your email and login: {$home_url}verify/?access_code={$access_code}";
    		$subject="Verification Email";
    		if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)){
        		echo "<div class='alert alert-success'>
            		Verification link was sent to your email. Click that link to login.
        		</div>";
    		}else{
        		echo "<div class='alert alert-danger'>
            		User was created but unable to send verification email. Please contact admin.
        		</div>";
    		}
	//empty posted values
	$_POST=array();
	}else{
		echo "<div class='alert alert-danger' role='alert'>Unable to register. Please try again.</div>";
	}
}else {
        echo "<div class='alert alert-danger'>
        Password does not match
	</div>";
}
}
}

?>
<form action='register.php' method='post' id='register'>
 
    <table class='table table-responsive'>
 
        <tr>
            <td class='width-30-percent'>First Name</td>
            <td><input type='text' name='firstname' class='form-control' required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>
 
        <tr>
            <td>Last Name</td>
            <td><input type='text' name='lastname' class='form-control' required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>

	 <tr>
            <td>Username</td>
            <td><input type='text' name='username' class='form-control' required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>
 
        <tr>
            <td>Contact Number</td>
            <td><input type='text' name='contact_number' class='form-control' required value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>
 
        <tr>
            <td>Birthday</td>
            <td><input type='date' name='birthday' class='form-control' required value="<?php echo isset($_POST['birthday']) ? htmlspecialchars($_POST['birthday'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>
 
        <tr>
            <td>Email</td>
            <td><input type='email' name='email' class='form-control' required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>
 
        <tr>
            <td>Password</td>
            <td><input type='password' name='password' class='form-control' required id='passwordInput'></td>
        </tr>
	    
	<tr>
	   <td>Confirm Password</td>
	   <td><input type='password' name='confirmpassword' class='form-control' required id='passwordInput'></td>
	</tr>
	
	<?php
	$mysqli=new mysqli('localhost', 'root', '04021999a', 'php_login_system');
	$resultSet=$mysqli->query("SELECT ques FROM security_questions");
	?>
	<tr>	
		<td>
		<select name='sec_q' class='form-control' required value="<?php echo isset($_POST['sec_q']) ? htmlspecialchars($_POST['sec_q'], ENT_QUOTES) : ""; ?>"/>

		<?php
		while($rows=$resultSet->fetch_assoc()){
		$ques=$rows['ques'];
		echo "<option value='$ques'>$ques</option>";
		}
		?>
		
		</select>
		</td>
		<td><input type='text' name='sec_a' class='form-control' required value ="<?php echo isset($_POST['sec_a']) ? htmlspecialchars($_POST['sec_a'], ENT_QUOTES) : ""; ?>" /></td>
        </tr>

	<tr>	
		<td>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<div class="g-recaptcha" data-sitekey="6LdOEOMUAAAAAM6VAPvw4VahJuc61mOo-IxfUD0k"></div>
		</td>
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-plus"></span> Register
                </button>
            </td>
        </tr>
 
    </table>
</form>
<?php
 
echo "</div>";
 
// include page footer HTML
include_once "layout_foot.php";
?>
