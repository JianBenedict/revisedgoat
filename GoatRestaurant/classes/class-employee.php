<?php
class Employee{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='test';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
		
	}
	
	public function new_employee($firstname,$lastname,$contactnumber,$email){
		
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');

		$data = [
			[$firstname,$lastname,$contactnumber,$email],
		];
		$stmt = $this->conn->prepare("INSERT INTO employee (firstname,lastname,contactnumber,email) VALUES (?,?,?,?)");
		try {
			$this->conn->beginTransaction();
			foreach ($data as $row)
			{
				$stmt->execute($row);
			}
			$this->conn->commit();
		}catch (Exception $e){
			$this->conn->rollback();
			throw $e;
		}

		return true;

	}

	public function update_admin($email,$password,$firstname,$lastname,$contactnumber,$id){
		
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');

		$sql = "UPDATE tbl_admin SET email=:email,password=:password,firstname=:firstname,lastname=:lastname,contactnumber=:contactnumber WHERE EmpID=:EmpID";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':email'=>$email, ':password'=>$password,':firstname'=>$firstname,':lastname'=>$lastname,':EmpID'=>$id));
		return true;
	}

	public function list_employee(){
		$sql="SELECT * FROM employee";
		$q = $this->conn->query($sql) or die("failed!");
		while($r = $q->fetch(PDO::FETCH_ASSOC)){
		$data[]=$r;
		}
		if(empty($data)){
		   return false;
		}else{
			return $data;	
		}
}

	function get_EmployeeID($email){
		$sql="SELECT EmployeeID FROM employee WHERE email = :email";	
		$q = $this->conn->prepare($sql);
		$q->execute(['email' => $email]);
		$email = $q->fetchColumn();
		return $email;
	}
	function get_email($id){
		$sql="SELECT email FROM employee WHERE EmpID = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['EmployeeID' => $id]);
		$email = $q->fetchColumn();
		return $email;
	}
	function get_firstname($id){
		$sql="SELECT firstname FROM employee WHERE EmployeeID = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['EmployeeID' => $id]);
		$firstname = $q->fetchColumn();
		return $firstname;
	}
	function get_lastname($id){
		$sql="SELECT lastname FROM employee WHERE EmployeeID = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['EmployeeID' => $id]);
		$lastname = $q->fetchColumn();
		return $lastname;
	}
	function get_contactnumber($id){
		$sql="SELECT contactnumber FROM employee WHERE EmployeeID = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['EmployeeID' => $id]);
		$contactnumber = $q->fetchColumn();
		return $contactnumber;
	}
	

	function get_session(){
		if(isset($_SESSION['login']) && $_SESSION['login'] == true){
			return true;
		}else{
			return false;
		}
	}
	public function check_login($email,$password){
		
		$sql = "SELECT count(*) FROM tbl_admin WHERE email = :email AND password = :password"; 
		$q = $this->conn->prepare($sql);
		$q->execute(['email' => $email,'password' => $password ]);
		$number_of_rows = $q->fetchColumn();
		

	
		if($number_of_rows == 1){
			
			$_SESSION['login']=true;
			$_SESSION['email']=$email;
			return true;
		}else{
			return false;
		}
	}
}
