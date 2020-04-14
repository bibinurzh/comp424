<?php
// 'user' object
class User{
 
    	// database connection and table name
    	private $conn;
    	private $table_name = "users";
 
    	// object properties
    	public $id;
    	public $firstname;
    	public $lastname;
    	public $email;
    	public $birthday;
    	public $contact_number;
    	public $username;
    	public $password;
    	public $access_level;
	public $access_code;
   	public $status;
    	public $created;
    	public $modified;
 	public $sec_q;
	public $sec_a;
    	// constructor
    	public function __construct($db){
        	$this->conn = $db;
    	}
    	// check if given email exist in the database
    	function emailExists(){
 
    		// query to check if email exists
    		$query = "SELECT id, firstname, lastname, access_level, password, status, username
            		FROM " . $this->table_name . "
            		WHERE email = ?
            		LIMIT 0,1";
 
    		// prepare the query
    		$stmt = $this->conn->prepare( $query );
 
    		// sanitize
    		$this->email=htmlspecialchars(strip_tags($this->email));
 
    		// bind given email value
    		$stmt->bindParam(1, $this->email);
 
    		// execute the query
    		$stmt->execute();
 
    		// get number of rows
    		$num = $stmt->rowCount();
 
    		// if email exists, assign values to object properties for easy access and use for php sessions
    		if($num>0){
 
        		// get record details / values
        		$row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        		// assign values to object properties
        		$this->id = $row['id'];
        		$this->firstname = $row['firstname'];
        		$this->lastname = $row['lastname'];
			$this->username = $row['username'];
        		$this->access_level = $row['access_level'];
        		$this->password = $row['password'];
        		$this->status = $row['status'];
 
        		// return true because email exists in the database
        		return true;
    		}
 
    		// return false if email does not exist in the database
    		return false;
	}
	// create new user record
	function create(){
 
    		// to get time stamp for 'created' field
    		$this->created=date('Y-m-d H:i:s');
 
    		// insert query
   		$query = "INSERT INTO " . $this->table_name . "
            	SET
        	firstname = :firstname,
        	lastname = :lastname,
        	email = :email,
		username = :username,
        	contact_number = :contact_number,
        	birthday = :birthday,
        	password = :password,
        	access_level = :access_level,
		access_code = :access_code,
        	status = :status,
        	created = :created,
		sec_q = :sec_q,
		sec_a = :sec_a";
 
    		// prepare the query
    		$stmt = $this->conn->prepare($query);
 
    		// sanitize
    		$this->firstname=htmlspecialchars(strip_tags($this->firstname));
    		$this->lastname=htmlspecialchars(strip_tags($this->lastname));
    		$this->email=htmlspecialchars(strip_tags($this->email));
    		$this->username=htmlspecialchars(strip_tags($this->username));;
    		$this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
    		$this->birthday=htmlspecialchars(strip_tags($this->birthday));
    		$this->password=htmlspecialchars(strip_tags($this->password));
    		$this->access_level=htmlspecialchars(strip_tags($this->access_level));
		$this->access_code=htmlspecialchars(strip_tags($this->access_code));
    		$this->status=htmlspecialchars(strip_tags($this->status));
    		$this->sec_q=htmlspecialchars(strip_tags($this->sec_q));
		$this->sec_a=htmlspecialchars(strip_tags($this->sec_a));
		// bind the values
    		$stmt->bindParam(':firstname', $this->firstname);
    		$stmt->bindParam(':lastname', $this->lastname);
    		$stmt->bindParam(':email', $this->email);
    		$stmt->bindParam(':username', $this->username);
    		$stmt->bindParam(':contact_number', $this->contact_number);
    		$stmt->bindParam(':birthday', $this->birthday);
		$stmt->bindParam(':sec_q', $this->sec_q);
		$ans_hash = password_hash($this->sec_a, PASSWORD_BCRYPT);
    		$stmt->bindParam(':sec_a', $ans_hash);
		// hash the password before saving to database
    		$password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    		$stmt->bindParam(':password', $password_hash);
 
    		$stmt->bindParam(':access_level', $this->access_level);
		$stmt->bindParam(':access_code', $this->access_code);
    		$stmt->bindParam(':status', $this->status);
    		$stmt->bindParam(':created', $this->created);
    		// execute the query, also check if query was successful
    		if($stmt->execute()){
        		return true;
    		}else{
        		$this->showError($stmt);
        		return false;
    		}
 
	}
	public function showError($stmt){
    		echo "<pre>";
        	print_r($stmt->errorInfo());
    		echo "</pre>";
	}
	// check if given access_code exist in the database
	function accessCodeExists(){
 
    		// query to check if access_code exists
    		$query = "SELECT id
            	FROM " . $this->table_name . "
            	WHERE access_code = ?
            	LIMIT 0,1";
 
    		// prepare the query
    		$stmt = $this->conn->prepare( $query );
 
    		// sanitize
    		$this->access_code=htmlspecialchars(strip_tags($this->access_code));
 
    		// bind given access_code value
    		$stmt->bindParam(1, $this->access_code);
 
    		// execute the query
    		$stmt->execute();
 
    		// get number of rows
    		$num = $stmt->rowCount();
 
    		// if access_code exists
    		if($num>0){
 
        		// return true because access_code exists in the database
        		return true;
    		}
 
    		// return false if access_code does not exist in the database
    		return false;
 
	}
	// used in email verification feature
	function updateStatusByAccessCode(){
 
    		// update query
   		$query = "UPDATE " . $this->table_name . "
            	SET status = :status
            	WHERE access_code = :access_code";
 
    		// prepare the query
    		$stmt = $this->conn->prepare($query);
 
    		// sanitize
    		$this->status=htmlspecialchars(strip_tags($this->status));
    		$this->access_code=htmlspecialchars(strip_tags($this->access_code));
 
    		// bind the values from the form
    		$stmt->bindParam(':status', $this->status);
    		$stmt->bindParam(':access_code', $this->access_code);
 
    		// execute the query
    		if($stmt->execute()){
        		return true;
    		}
 
    		return false;
	}
	// check if given email exist in the database
function usernameExists(){
 
    // query to check if email exists
    $query = "SELECT id, firstname, lastname, access_level, password, status
            FROM " . $this->table_name . "
            WHERE username = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));
 
    // bind given email value
    $stmt->bindParam(1, $this->username);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->access_level = $row['access_level'];
        $this->password = $row['password'];
        $this->status = $row['status'];
 
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}
}
