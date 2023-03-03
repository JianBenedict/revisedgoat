<?php
include '../classes/class-employee.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action){
	case 'new':
        create_new_employee();
	break;
    case 'update':
        update_employee();
	break;
    case 'deactivate':
        deactivate_employee();
	break;
}

function create_new_employee(){
	$employee = new employee();
    $firstname = ucwords($_POST['firstname']);
    $lastname = ucwords($_POST['lastname']);
    $contactnumber = $_POST['contactnumber'];
    $email = $_POST['email'];

    
    $result = $employee->new_employee($firstname,$lastname,$contactnumber,$email);
    if($result){
        
        header('location: ../index.php?page=settings&subpage=list');
    }
}

function update_employee(){
	$user = new User();
    $email = $_POST['email'];
    $lastname = ucwords($_POST['lastname']);
    $firstname = ucwords($_POST['firstname']);
    $password = $_POST['password'];
    $contactnumber = $_POST['contactnumber'];
    
    $result = $admin->new_admin($email,$password,$lastname,$firstname,$contactnumber);
    if($result){
        header('location: ../index.php?page=settings&subpage=users&action=profile&id='.$user_id);
    }
}

function deactivate_user(){
	$user = new User();
    $user_id = $_POST['userid']; 
    $result = $user->deactivate_user($user_id);
    if($result){
        header('location: ../index.php?page=settings&subpage=users&action=profile&id='.$user_id);
    }
}