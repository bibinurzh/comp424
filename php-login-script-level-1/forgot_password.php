<?php
// core configuration
include_once "config/core.php";
 
// set page title
$page_title = "Forgot Password";
 
// include login checker
include_once "login_checker.php";
 
// include classes
include_once "config/database.php";
include_once 'objects/user.php';
include_once "libs/php/utils.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$user = new User($db);
$utils = new Utils();
 
// include page header HTML
include_once "layout_head.php";
 
// post code will be here
//show reset password HTML form
echo "<div class='col-md-4'></div>";
echo "<div class='col-md-4'>";    

echo "<div class='account-wall'>
        <div id='my-tab-content' class='tab-content'>
            <div class='tab-pane active' id='login'>
		<div align='center'>
		    <form>
        <div class='outerbox_forgot_username'>
           
                <div class = 'method_div'>
         
               <button type='button'><a href='method_email'>Reset By Email</a></button>
		<p></p> 
		<p><button type='button'><a href='method_security'>Answer Security Questions</a></button> 
</p>
        </div>
    </form>

            </div>
		</div>
            </div>
        </div>
    </div>";
echo "</div>";
echo "<div class='col-md-4'></div>";
 
// footer HTML and JavaScript codes
include_once "layout_foot.php";
?>
