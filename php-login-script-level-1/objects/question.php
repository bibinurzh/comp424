<?php
// 'user' object
class Question{
 
    // database connection and table name
    private $conn;
    private $table_name = "security_question";
 
    // object properties
    public $sec_q1;
    public $sec_q2;
    public $sec_q3;
    public $sec_q4;
    public $sec_q5;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
    function readQuestion() {
    	$query="SELECT sec_q FROM " . $this->table_name . "";
	$stmt = $this->conn->prepare( $query );
	$stmt->execute();
	}
}
