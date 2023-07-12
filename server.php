<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$fname ="";
$acn="";
$category="";
$errors = array(); 

// connect to the database
$database = mysqli_connect('localhost', 'root', '', 'registration');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($database, $_POST['username']);
  $email = mysqli_real_escape_string($database, $_POST['email']);
  $fname = mysqli_real_escape_string($database, $_POST['fname']);
  $acn = mysqli_real_escape_string($database, $_POST['acn']);
  $category = mysqli_real_escape_string($database, $_POST['category']);
  $password_1 = mysqli_real_escape_string($database, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($database, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($database, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, fname, acn, category,  password) 
  			  VALUES('$username', '$email', '$fname', '$acn', '$category', '$password')";
  	mysqli_query($database, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

// ... 
// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($database, $_POST['username']);
    $password = mysqli_real_escape_string($database, $_POST['password']);
  
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $results = mysqli_query($database, $query);
        $category1="SELECT* category FROM users WHERE username='$username'";
       
        // if (mysqli_num_rows($results) == 1) {
        //   $_SESSION['username'] = $username;
        //   $_SESSION['success'] = "You are now logged in";
          // header('location: index.php');}  
        if (mysqli_num_rows($results) == 1) {
           if($category1='advocate'){
             header('location: advocate.html');
           }
           elseif($category1='patient'){
             header('location: patient.html');
           }
           elseif($category1='seniorcitizen'){
             header('location: seniorcitizen.html');
           }
           elseif($category1='homemaker'){
             header('location: homemaker.html');
           }
        }
         
         else {
            array_push($errors, "Wrong username/password combination");
        }
    }
  }
  
  ?>


<!-- if (mysqli_num_rows($results) == 1) {
          header('location: home.html')
} -->