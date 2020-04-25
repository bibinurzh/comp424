<?php
// core configuration
include_once "config/core.php";
include_once "config/database.php"; 
// set page title
$page_title="Main Page";
// login count
$login_attempts = 0;
$inTwoMonths =60*60*60*24+time();
setcookie('lastVisit', date("G:i - m/d/y"), $inTwoMonths);
// include login checker
$require_login=true;
include_once "login_checker.php"; 
// include page header HTML
include_once 'layout_head.php'; 
echo "<div class='col-md-12'>";
$database = new Database();
$db = $database->getConnection();
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(":id", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch();
    // to prevent undefined index notice
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    // if login was successful
    if($action=='login_success') {
	// && isset($_COOKIE['lastVisit'])){
        echo "<div class='alert alert-info'>";
            echo "<strong>Hi " . $_SESSION['firstname'] . " " . $_SESSION['lastname'].  ", welcome back!</strong>";
	    echo "<strong> Last login date: " . $_COOKIE['lastVisit'] . ". You have logged in " . $user['login_count'] .".</strong>";
	    echo"<p><strong> Successful attempts: ". $user['success'].". Failed attempts: " . $user['fail'] . ".</strong></p>";
	 echo "</div>";
    }
 
    // if user is already logged in, shown when user tries to access the login page
    else if($action=='already_logged_in'){
        echo "<div class='alert alert-info'>";
            echo "<strong>You are already logged in.</strong>";
        echo "</div>";
    }

    // content once logged in
    echo "<div class='alert alert-info'>Download <a href='{$home_url}download.php?file=company_confidential_file.txt'> file.</a></div>";
 
echo "</div>";
 
// footer HTML and JavaScript codes
include 'layout_foot.php';
?>
