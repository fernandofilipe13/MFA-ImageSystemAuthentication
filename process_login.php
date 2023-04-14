

<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$response = new stdClass();

// require './includes/database.php';
// initializing variables
$username = "";
$email    = "";
$debug =false;

if (isset($_POST['signup'])) {
  // receive all input values from the form
  $username = strtolower(mysqli_real_escape_string($db, $_POST['username']));
  $email = strtolower(mysqli_real_escape_string($db, $_POST['email']));
  $password_1 = mysqli_real_escape_string($db, $_POST['password']);
  $password_2 = mysqli_real_escape_string($db, $_POST['repeatpassword']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required");return; }
  if (empty($email)) { array_push($errors, "Email is required");return; }
  if (empty($password_1)) { array_push($errors, "Password is required"); return;}
  if ($password_1 != $password_2) {array_push($errors, "The two passwords do not match");return;}

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists 
    if ($user['email'] === $email) {
      array_push($errors, "This $email already exists. Choose another one.");
      return;
    }
    if ($user['username'] === $username) {
      array_push($errors, "This $username already exists. Choose another one.");
      return;
    }
  }

  
  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    $password = encryptValue($password_1);
  	$query = "INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password');";
  	mysqli_query($db, $query);
    
    $response->title='Account created with success!';
    $response->html='Please sign in into your account!';
    $response->icon='success';
    $response->timer='60000';
    $response->redirect='./signin.php';
    
    array_push($alerts,json_encode($response));
    return;
  }
}


if (isset($_POST['signin'])) {

  $username = strtolower(mysqli_real_escape_string($db, $_POST['username'])); //AVOID SQL INJECTION
  $password = mysqli_real_escape_string($db, $_POST['password']); //AVOID SQL INJECTION

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $auth = false;
    $error = false;

    $query = "SELECT * FROM `users` WHERE `username`='$username'";
  	$results = mysqli_query($db, $query);

    while( $row = $results->fetch_array()){
      if(decryptValue($row['password']) == $password){
        $_SESSION['userId'] = $row['id'];
        $hasMFA = $row['multifactor'];

        $auth = true;
      }
    }

    if($auth==true){
      $_SESSION['authenticated']=false;
    
      if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        $userId = $_SESSION['userId'];
    
        $response->title="Welcome $username";
        $response->html='You are now logged in!';
        $response->icon='success';
        $response->timer='5000';
        
        if($hasMFA==false){
          $response->redirect='./multifactor.php';
        }else{
          $response->redirect='./authenticate.php';         

          $user_check_query = "SELECT * FROM `mfa_security` WHERE `userId`=$userId and `account_locked`=true LIMIT 1";
          $result = mysqli_query($db, $user_check_query);
          $resultsParsed = mysqli_fetch_assoc($result);

          if(isset($resultsParsed)){
            unset($response->redirect);
            unset($response->timer);

            $response->status=true;

            $datetime_1=date("Y-m-d H:i:s", strtotime(' - 1 hours'));
            $datetime_2 = $resultsParsed['locked_data_time']; 

            $start_datetime = new DateTime($datetime_1); 
            $diff = $start_datetime->diff(new DateTime($datetime_2)); 

            $total_minutes = ($diff->days * 24 * 60); 
            $total_minutes += ($diff->h * 60); 
            $total_minutes += $diff->i; 
            $hours = $total_minutes / 60;             

            if($resultsParsed['lock_before']==0){
              if($resultsParsed['lock_time']==0){
                $timeInMinutes = 120;
                $totalTime = $timeInMinutes-$total_minutes;
                $hours = floor($totalTime / 60);
                $remainingMinutes = $totalTime % 60;
                if($hours<0){
                  $hours=00;
                }
                if($remainingMinutes<0){
                  $query ="UPDATE `mfa_security` SET `last_login`=now(),`attempt`=0,`lock_before`=1,`lock_time`=0,`account_locked`=0,`ip`='$user_ip' WHERE `userid`=$userId ";
                  mysqli_query($db, $query);
                  $response->status=false;
                }else{
                  $response->moreswall = "Swal.fire({
                    html:'Your account is locked for 2 hours!<br>Remaining time: $hours <b>hours</b> and $remainingMinutes <b>minutes</b>.',
                    icon:'error',
                  })";
                }
              }
            }else{
              if($resultsParsed['lock_time']==1){
                $timeInMinutes = 720;
                $totalTime = $timeInMinutes-$total_minutes;
                $hours = floor($totalTime / 60);
                $remainingMinutes = $totalTime % 60;
                if($hours<0){
                  $hours=00;
                }

                if($remainingMinutes<0){
                  $query ="UPDATE `mfa_security` SET `last_login`=now(),`attempt`=0,`lock_before`=1,`lock_time`=0,`account_locked`=0,`ip`='$user_ip' WHERE `userid`=$userId ";
                  mysqli_query($db, $query);
                  $response->status=false;
                }else{
                  $response->moreswall = "Swal.fire({
                    html:'Your account is locked for 12 hours!<br>Remaining time: $hours <b>hours</b> and $remainingMinutes <b>minutes</b>.',
                    icon:'error',
                  })";
                }
              }

              if($resultsParsed['lock_time']==2){    
                $timeInMinutes = 1440;
                $totalTime = $timeInMinutes-$total_minutes;
                $hours = floor($totalTime / 60);
                $remainingMinutes = $totalTime % 60;
                
                if($hours<0){
                  $hours=00;
                }

                if($remainingMinutes<0){
                  $query ="UPDATE `mfa_security` SET `last_login`=now(),`attempt`=0,`lock_before`=1,`lock_time`=0,`account_locked`=0,`ip`='$user_ip' WHERE `userid`=$userId ";
                  mysqli_query($db, $query);
                  $response->status=false;
                }else{
                  $response->moreswall = "Swal.fire({
                    html:'Your account is locked for 24 hours!<br>Remaining time: $hours <b>hours</b> and $remainingMinutes <b>minutes</b>.',
                    icon:'error',
                  })";
                }        
              }

              if($resultsParsed['lock_time']==3){~
                $response->moreswall = "Swal.fire({
                  html:'Your account is locked forever, please contact the support!',
                  icon:'error',
                })";
              }
            }
            if($response->status==true){
              unset($response->timer);
              session_destroy();
              $response->footer = '<a style="color:black;" href="./simple.php">Contact Support</a>';
            }            
          }
        }
      }else{
        $error = true;
      }
    }else {
      $error = true;
    }

    if($error == true){
      $response->title="Wrong Credentials!";
      $response->html='Verify your username and your password!';
      $response->icon='error';
      $response->timer='5000';

    }
    
    array_push($alerts,json_encode($response));
  }
}


if(isset($_POST['action'])){

  $action = $_POST['action'];


if ($action == 'uploadImg'){
  
  $target_dir = "./assets/images/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      echo "File is not an image.";
      $uploadOk = 0;
    }
  }
  
  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }
  
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 2000000) {
    echo "Sorry, your file is too large.2Mb max";
    $uploadOk = 0;
  }
  
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }
  
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }

}
}

?>