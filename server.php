
<?php include("includes/database.php");?>

<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function generateRandomString($length = 30) {
  return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@-_', ceil($length/strlen($x)) )),1,$length);
}
function generateBackup($length = 25){
  return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}


if(isset($_POST['backup'])){
	$userId = $_SESSION['userId'];
  $response = new stdClass();

	$user_check_query = "SELECT * FROM `multifactor` WHERE `userId`=$userId LIMIT 1;";
    $result = mysqli_query($db, $user_check_query);
    $resultParsed= mysqli_fetch_assoc($result);
	//  
    if(isset($resultParsed)){
		$receivedBackupCode = $_POST['backup'];
    // echo $receivedBackupCode;
		$str = str_replace(array('-', ' '), '', $receivedBackupCode);
		if($str == $resultParsed['backup_code']){
			$response->title = 'MFA reseted for this account!';
			$response->redirect = './multifactor.php';
		  $response->color = '#3085d6';


			$query = "DELETE FROM `multifactor` WHERE `userId`=$userId";
			mysqli_query($db, $query);
			$query = "DELETE FROM `mfa_authentication` WHERE `userId`=$userId";
			mysqli_query($db, $query);
			$query = "DELETE FROM `mfa_security` WHERE `userId`=$userId";
			mysqli_query($db, $query);
			$query = "UPDATE `users` SET `multifactor`=0 WHERE `id`=$userId";
			mysqli_query($db, $query);
		}else{
			$response->title = 'Wrong backup code!';
      $response->icon = 'error';
      $response->color = '#ff0000';
			$response->redirect = '.';
		}
	}

	echo json_encode($response);

}

if(isset($_POST['restartmfa'])){
	$userId = $_SESSION['userId'];
  $response = new stdClass();

	$user_check_query = "SELECT * FROM `multifactor` WHERE `userId`=$userId LIMIT 1;";
    $result = mysqli_query($db, $user_check_query);
    $resultParsed= mysqli_fetch_assoc($result);
	//  
    if(isset($resultParsed)){
		
			$response->title = 'MFA restarted for this account!';
			$response->redirect = './multifactor.php';
		  $response->color = '#3085d6';

			$query = "DELETE FROM `multifactor` WHERE `userId`=$userId";
			mysqli_query($db, $query);
			$query = "DELETE FROM `mfa_authentication` WHERE `userId`=$userId";
			mysqli_query($db, $query);
			$query = "DELETE FROM `mfa_security` WHERE `userId`=$userId";
			mysqli_query($db, $query);
			$query = "UPDATE `users` SET `multifactor`=0 WHERE `id`=$userId";
			mysqli_query($db, $query);
		
	}

	echo json_encode($response);

}


$username = "";
$email    = "";
$errors = array(); 

if(isset($_POST['def50200b09dc5f62ae5c2f74a73ec8d2d82de427ba3de55480b155e242c6dae0cbc560c0ec9e8e13939cf0e1b4c35b74047b9603514768b97266637003baf1739ededbc84d662d1ce59b66057e940a0837f57236b8c64dffb2f'])){
  $action = $_POST['def50200b09dc5f62ae5c2f74a73ec8d2d82de427ba3de55480b155e242c6dae0cbc560c0ec9e8e13939cf0e1b4c35b74047b9603514768b97266637003baf1739ededbc84d662d1ce59b66057e940a0837f57236b8c64dffb2f'];
  
  //GENERATE NEW IMAGES
  if($action == 'def50200de2a934ac74e094ae30171198f2cb32d761749f081ff54edc78358870cefb77734e608b11c1e9f0e93372af02f5abb3320e03ba1e7658c30098522585a54ce7360d4f7f4e3ee26789d2124d4b195355d03bff9fce5e1ec5b86ae7cc9b070d352d5'){
    //GENERATE NEW IMAGES
    //
    // echo 'WORKING HERE';
    // var_dump($photos);
    // $path = './assets/images/';
    $count4=0;
    $newDivImagesList ='';
    for ($i=0; $i < 8; $i++) {
      if($i==0 || ($i+4)==$count4){
        $newDivImagesList .= "<div class='row'>";
      }
      while($i<$count4 && $i < 8){
          if($i<$count4 && $i < 8){
              // $photos = getNewPhotos(); // LIMITED API
              // $urlPhoto = $photos->{$i}['urls']['regular'];
              $urlPhoto = file_get_contents('https://source.unsplash.com/random/400x400/');
              var_dump($urlPhoto);
              $newDivImagesList .=  "<div class='column'>
                      <img src='$urlPhoto' alt='image$i' class='imageListDisplay' >
                  </div>";
              $i++;
          }
      }
      $newDivImagesList .=  '</div>';
      $i--;
      $count4 +=4;
    }
    $newDivImagesList .= "Do you prefer to use your own image? <a  target='popup' onclick='window.open('./upload.php','name','width=600,height=800')'>Upload it! </a>
    <p> Generate new images?<button class='btn btn-secondary btn-generate-confirm'> Generate</button></p>";
    echo $newDivImagesList;
    // copy($photo['urls']['regular'],$path);
  }

  //[STEP 1] CREATION MFA- IMAGE SELECTION
  if($action == 'def50200fcb9e3de98b8ff0d0259a4ee66e41ccb0e2fabf56cd884983098127e5c4497f482bfe75b1ca4de5e78dcae7e82862624386d6144de02e2fffa86052c517e4672eb3857ab612a966a64728bc8bc0dad50e6e2a10c654ac95d34a0b6'){
    // MFA CHOOSE PICTURE

    $response = new stdClass();

    if(isset($_SESSION['userId']) && isset($_SESSION['username'])){
    
      $response->status = true;

      $username = $_SESSION['username'];
      $userId = $_SESSION['userId'];

      $query = "INSERT INTO `multifactor`(`userid`, `username`, `active`, `setup_step`) values($userId,'$username',1,1);";
      mysqli_query($db, $query);

      $imageUserChoice = $_POST['def50200203a3becbe420f8b5d704fc94974fa1289b1e3f42b29bf802ffb03994bd7ad71c83178afef4e4aaffb63face13e05d537ee4e61f32f5b2672a00101504340f669319893794e33f488aaac3769a80c58e5fc0d1e10c'];
      $cipherUserId = encryptValue($userId);
      $newFile = generateRandomString(); //GENERATE NEW IMAGE NAME
      
      //GET USERCHOICE IMAGE NAME
      $arrayUserChoice =explode("/",$imageUserChoice);
      $saveUserImageName= end($arrayUserChoice);

      //CREATE USER FOLDER
      $user_check_query = "SELECT * FROM `multifactor` WHERE `userId`=$userId LIMIT 1;";
      $result = mysqli_query($db, $user_check_query);
      $resultParsed = mysqli_fetch_assoc($result);
      $folderValueSet = false;

      if(isset($resultParsed)){
        if(isset($resultParsed['folder_value']) && $resultParsed['folder_value']!=''){
          echo 'here';
          $userFolder = $resultParsed['folder_value'];
          $destImage = "./assets/imgs/$userFolder/";
          if(decryptValue($userFolder) != $userId){
            $folderValueSet =true;
            rrmdir("./assets/imgs/$destImage"); //DELETE OLD ASSOCIATE FOLDER 
            $destImage = "./assets/imgs/$cipherUserId/"; // CREATE A NEW ASSOCIATE FOLDER
            $query ="UPDATE `multifactor` SET `active`=1,`lastlogin`= now(),`folder_value`='$cipherUserId' WHERE `userId`=$userId and `username`='$username'";
            mysqli_query($db, $query);
          }
        }
       
      }
      if($folderValueSet==false){
        $destImage = "./assets/imgs/$cipherUserId/"; // CREATE A NEW ASSOCIATE FOLDER
        $query ="UPDATE `multifactor` SET `folder_value`='$cipherUserId' WHERE `userId`=$userId and `username`='$username'";
        mysqli_query($db, $query);
        $userFolder=$cipherUserId;
      }

      if (!is_dir($destImage)) {
        mkdir($destImage, 0777, true);
      }
    
      $arrayListImages = $_SESSION['ArrayImagesDisplay'];

      foreach ($arrayListImages as $key => $value) {
        $valuePieces = explode("/", $value);
          
        $NdestImage = $destImage . end($valuePieces);
        $srcImage = './'.$value;

        copy($srcImage,$NdestImage);
      }

      $images = glob($destImage."*.jpg");
      foreach ($images as $value) {
        $arrayValuePieces =explode("/",$value);
        $savePiecesImageName= end($arrayValuePieces);

        ///WORKING
        $src_img = imagecreatefromjpeg($value);

        $src_width = imagesx($src_img);
        $src_height = imagesy($src_img);

        $dst_width = 1080;
        $dst_height = 1080;

        $newFile = generateRandomString(); //GENERATE NEW IMAGE NAME
        $resizedImagePath = $destImage . $newFile.".jpg";

        if($savePiecesImageName == $saveUserImageName){
          $saveUserImageName = $newFile;
          $pathUserChoice = $destImage.$newFile.".jpg";
        }

        $dst_img = imagecreatetruecolor($dst_width, $dst_height);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

        imagejpeg($dst_img, $resizedImagePath);
      
        imagedestroy($src_img);
        imagedestroy($dst_img);
      
        if (file_exists($value)) {
          unlink($value);
        }
      
      }



      $src_img = imagecreatefromjpeg($pathUserChoice);

      $src_width = imagesx($src_img);
      $src_height = imagesy($src_img);
      
      $grid_size_x = $src_width / 3;
      $grid_size_y = $src_height / 3;
      $pathCropped = $destImage."cropped/";

      if (!is_dir($pathCropped)) {
          mkdir($pathCropped, 0777, true);
      }
    
      for ($i = 0; $i < 3; $i++) {
          for ($j = 0; $j < 3; $j++) {
              $dst_img = imagecreatetruecolor($grid_size_x, $grid_size_y);
              imagecopy($dst_img, $src_img, 0, 0, $i * $grid_size_x, $j * $grid_size_y, $grid_size_x, $grid_size_y);
          
              $newFile = generateRandomString(); //GENERATE NEW IMAGE NAME
              $cipherImageName = encryptValue($newFile);
              imagejpeg($dst_img, $pathCropped .$cipherImageName.".jpg");
              imagedestroy($dst_img);
          }
      }
      
      imagedestroy($src_img);
     
      
      $user_check_query = "SELECT * FROM `multifactor` WHERE `userId`=$userId LIMIT 1;";
      $result = mysqli_query($db, $user_check_query);
      $user = mysqli_fetch_assoc($result);


    
      if ($user) {
        $query ="UPDATE `multifactor` SET `active`=1,`lastlogin`= now(),`image_selected`='$saveUserImageName',`setup_step`=2 WHERE `userId`=$userId and `username`='$username'";
        mysqli_query($db, $query);
      }else{
        $query = "INSERT INTO `multifactor`(`userid`, `username`, `active`, `lastlogin`, `folder_value`, `image_selected`,`setup_step`) values($userId,'$username',1,now(),'$cipherUserId','$saveUserImageName',2);";
        mysqli_query($db, $query);
      }
      
      
      //SEND SECOND STEP [2] TO THE USER
      
      $response->divElement = " 
      <div class='center image--center'>";
      $path = "./assets/imgs/$userFolder/cropped/";
      $imageGrid = 9;
      $countId = 1;
      if (is_dir($path)) {
          $images = glob($path."*.jpg");
          if(count($images)>0){

            $response->divElement .="<div class='imageGrid parent' data-value='0'>";

            shuffle($images);
              for ($i=0; $i < $imageGrid ; $i++) {
                  $secretImageName = str_replace(".jpg","",$images[$i]);//REMOVE THE PROPERTY FROM IMAGE NAME
                  $secretImageName = str_replace($path,"",$secretImageName);  //REMOVE THE LOCATION FROM IMAGE NAME
                  $cipherImageName = encryptValue($secretImageName);
                  // $decryptedValue = decryptValue($cipherImageName);
                  
                  $response->divElement .= "<div><input type='image' class='image' src='$images[$i]' data-value='$cipherImageName' id='item-$countId'></div>";
                  $countId++;
              }
              $response->divElement .= "</div>";
          }else{
            $response->divElement ="";
            $response->error = "No Images";
          }
      }else{
          $response->divElement ='';
          $response->error = 'Not a repository';
      }

      $response->divElement .="</div>
      <div class='error errorSelectection hide' style='padding:10px'><p>You have to select at least 4 images and up to 5!</p></div>
      <button class='btn btn-secondary btn-confirm' disabled > Confirm </button>";


    }else{
      $response->status = false;
    }
    
    echo json_encode($response);

  
  }

  //[STEP 2] MFA CHOOSE PIECES OF IMAGES
  if($action == 'def5020013e1e6c8236a4eef112528d5ac014d3a32963ad0ded5b5df15b760cb7f72a6e9b4979d97cb752467942be350520ea7e32f30c9d58cb8ea8e523b27f760360426674e9a1395ca7aa05bf681c7b8846ea39da3cfd5268120e70e3079b34b730e'){
    //MFA CHOOSE PIECES OF IMAGES
    
    $response = new stdClass();
    $response->status=true;

    //ENCRYPTED IMAGES SELECTED
    $arrayReceived = $_POST['def5020091438761f79acaa78b56cf5061dc38e1f38d36c7ec1cc9bb6787733421291568b072e9895884662ca209bfe778bbbbfe5625df3d2472d0ec2b3626e68618d71572f43b43c7b3a6f94c64f4c0f28403ded4e3f2a89a'];
    if(!isset($arrayReceived)){$arrayReceived=array();}

    if(count($arrayReceived) == 4 || count($arrayReceived) ==5){
      $response->status=false;
          

      //ORDER OF MFA
      $arrayOrderReceived = $_POST['def502000c356afad961652d2bedf0ec3d9dcfb0f3d837c5c2639bccb76831cdac1a8edebb7c4bca3c0f8cbd8665239cde55b765ece0a7715c32a07e675f73ee2c90d67818b49ae3212e4373b27a4a5ec31e59b6dd8bf2322913bf6f210c'];
      
      //ALL ENCRYPTED IMAGES IN OBJECT WITH ID and VALUE OBJECT
      $arrayImageArrange = $_POST['def502002f67e4a4eb1114046d026a84595bf3751448e0d89168ec35d834993b00c4f2779952dbf91a5b8fb663e143ca7be46fd0e7cae0876b0ef0e56a8778d55a0a919ba2355b936cf1c58b4505b84130510501e632c5032d8ddca76f041e22ff2708'];
      
      $userId = $_SESSION['userId'];

      $user_check_query = "SELECT `folder_value` FROM `multifactor` WHERE `userId`=$userId LIMIT 1";
      $result = mysqli_query($db, $user_check_query);

      $user = mysqli_fetch_assoc($result);
    
      if ($user) {
        $encryptedPath = $user['folder_value'];
        $path = "./assets/imgs/$encryptedPath/cropped/";
      }else{
        $getIP = $_SERVER['REMOTE_ADDR'];
        $query = "INSERT INTO `banned_ips`(`userid`, `date`, `reason`, `performing`, `ip`, `other_info`) VALUES('$userId', now(), 'MFA FOLDER COMPROMISED OR USER ID COMPROMISED','automatic','$getIP','IP BANNED')";
        mysqli_query($db, $query);

        //MFA FOLDER COMPROMISED OR USER ID COMPROMISED
        session_destroy();
        echo "<script>window.location.replace('https://google.com/');</script>";
      }

      $isTruth = true;
    
      if (is_dir($path)) {

        $images = glob($path."*.jpg"); //GET ALL FILES WITH .JPG IN THE PATH

        if(isset($images)){
          if(count($images)!=9){
            $response->error = 'ERROR - PROCESSING IMAGES';
            die;
          }

          $picturesList = new stdClass();
          for ($i=0; $i < 9 ; $i++) {
            $images[$i] = str_replace(".jpg","",$images[$i]);//REMOVE THE PROPERTY FROM IMAGE NAME
            $images[$i] = str_replace($path,"",$images[$i]);  //REMOVE THE LOCATION FROM IMAGE NAME
            $secretImageName = $images[$i]; // ONLY THE IMAGE NAME
              $newAuth = generateRandomString();
              $picturesList->$secretImageName = $newAuth;
          }

          $newArray = [];
          $count=0;

          if(isset($arrayOrderReceived )){
            foreach ($arrayOrderReceived as $element ) {
              for ($i=0; $i < 9 ; $i++) {
                if ($element == $arrayImageArrange[$i]['id'] ) {
                  $valueDescrypted = decryptValue($arrayImageArrange[$i]['value']); //DECRYPT THE SELECTED IMAGES
                  $truth = in_array($valueDescrypted,$images); // ARE THE IMAGES SELECTED IN THE FOLDER?
                  if($truth){ // IF YES
                    array_push($newArray,$arrayImageArrange[$i]['value']);
                    $count++;
                  }else{ // IF NO - CAUSED BY AN ERROR OR ATTEMPT TO BYPASS
                    $isTruth = false;
                    $response->error = 'ERROR - PIECE OF IMAGE NOT FOUND';
                    return;
                  } 
                }
              }
            }
          }
          $response->status=false;
          $myJSON = json_encode($newArray);        
          $query = "INSERT INTO `mfa_authentication`(`userid`, `passphrase`, `grid`) VALUES ($userId,'$myJSON',3)";
          mysqli_query($db, $query);
          
          $query ="UPDATE `multifactor` SET `setup_step`=3 WHERE `userId`=$userId";
          mysqli_query($db, $query);

          $response->divElement="
          <div>
            <h4>This is the real example of how you have to connect in your account. </h4>
            <p>The same list of images that were presented to you when you setted up your authentication will be presented to you in order to certify if it is really you entering in your account. <b>Only you should know which image you selected before.</b></p>
          </div>
          <div id='imageTryAuth'>";
          $user_check_query = "SELECT `folder_value` FROM `multifactor` WHERE `userId`=$userId LIMIT 1";
          $result = mysqli_query($db, $user_check_query);
          $user = mysqli_fetch_assoc($result);
          
          $userFolder = $user['folder_value'];
          $dirname = "./assets/imgs/$userFolder/";
          $images = glob($dirname."*.jpg");
          shuffle($images);
          $displayedImages =[];
          $count4=0;
          $arrayImagesDisplay = array();
          for ($i=0; $i < 8; $i++) {
              if($i==0 || ($i+4)==$count4){
                $response->divElement .= "<div class='row'>";
              }
              while($i<$count4 && $i < 8){
                  if($i<$count4 && $i < 8){
                      array_push($arrayImagesDisplay,$images[$i]);
                      $response->divElement.= "<div class='column'>
                              <img class='imageAuth' src='$images[$i]' alt='image$i' >
                          </div>";
                      $i++;
                  }
              }
              $response->divElement .= '</div>';
              $i--;
              $count4 +=4;
          }

          $response->divElement .=" </div>
          </div>
          ";
        
          if($isTruth===false){ // IF NO - CAUSED BY AN ERROR OR ATTEMPT TO BYPASS
            $response->status=false;
            $response->error = 'ERROR - ONE SELECTED PIECE OF IMAGE WAS NOT FOUND';
          }else{
            $response->status=true;
          }
        }else{ // IF NO - CAUSED BY AN ERROR OR ATTEMPT TO BYPASS
          $response->status=false;
          $response->error = 'ERROR - IMAGES NOT FOUND';
        }
      }else{
        $response->status=false;
        $response->error = 'ERROR - Not a DIR';
      }
    }
    echo json_encode($response);

  }

  //[STEP 3.1] VERIFY MFA LOGINS - IMAGE SELECTION
  // THIS FUNCTION IS THE SAME AS TRY MFA LOGIN
  if($action == 'def502009f4c92834d11c9fabb69a0ebfcdec11d3e3917f115be56c1372b349547eadda07f6ebb9d8ba3d10c7e3a4dca8b36b7dda7bedd398ffc0a5af1551b51cd8b44d4fac5fca789e6148807d6b1bbafd2cc396fe2431e57c14cdf84ead1fc23398a2be3f374b9'){
    $response = new stdClass();

    //USER SELECT IMAGE - TRY MFA - LOGIN PART
    $imageSelectedLogin = $_POST['def50200d589d8a621d04936986390bd05acb2082febfa59e71621a1226809e2aa7207ac5112db2edfebf9c6e1c3096784cbc651c333636e7d938bcaf03c0c0adee7de3ada3e9a790dd976baa1f4d04edd99743fee76a814b61ce18b7749d53cc8d6dfe39de8'];
    $userId = $_SESSION['userId'];

    //GET USERCHOICE IMAGE NAME
    $arrayUserChoiceLogin =explode("/",$imageSelectedLogin);
    $userSelectionImageName= end($arrayUserChoiceLogin);
   
    $user_check_query = "SELECT * FROM `multifactor` WHERE `userId`=$userId LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $resultParsed= mysqli_fetch_assoc($result);
 

    // var_dump($responses);
    if(isset($resultParsed)){
      $userImageAuth = $resultParsed['image_selected'].'.jpg';
      $response->ImageAuth = $userImageAuth;
      $response->Selected = $userSelectionImageName;

      if($userImageAuth==$userSelectionImageName){
        $response->status = true;
        $response->append = '<b>CONFIRM</b>';

      }else{
        $response->status = false;
      }
    }

    if($response->status == true){
      $path = $userImageAuth = "./assets/imgs/".$resultParsed['folder_value'].'/cropped/';
      $imageGrid = 9;
      $countId = 1;
      $response->divElement='';
      if (is_dir($path)) {
          $images = glob($path."*.jpg");
          shuffle($images);
          if(count($images)>0){


            $response->divElement .= "<div class='image--center imageGrid parent' data-value='0'>";
              shuffle($images);
              for ($i=0; $i <$imageGrid ; $i++) {
                  $secretImageName = str_replace(".jpg","",$images[$i]);//REMOVE THE PROPERTY FROM IMAGE NAME
                  $secretImageName = str_replace($path,"",$secretImageName);  //REMOVE THE LOCATION FROM IMAGE NAME

                  $cipherImageName = encryptValue($secretImageName);
                  $response->divElement .= "<div><input id='item-$countId' type='image' class='image' src='$images[$i]' data-value='$cipherImageName' ></div>";
                  $countId++;
              }
              $response->divElement .= "</div>";
              $response->divElement .="<button class='btn btn-secondary btn-try-confirm'> Confirm </button>";

          }else{
            $response->divElement .="";
            $response->error = "No Images";
          }
      }else{
          $response->divElement .='';
          $response->error = 'Not a repository';
      }
    }else{
       ///REGISTER ATTEMPT
      $user_check_query = "SELECT * FROM `mfa_security` WHERE `userId`=$userId LIMIT 1";
      $result = mysqli_query($db, $user_check_query);
      $resultsParsed = mysqli_fetch_assoc($result);
      $user_ip = $_SERVER['REMOTE_ADDR']; // get user's IP address

      if (!isset($resultsParsed)) {
        $query = "INSERT INTO `mfa_security`(`userid`, `last_login`, `attempt`, `lock_before`, `lock_time`, `account_locked`, `ip`) VALUES ($userId,now(),1,0,0,0,'$user_ip')";
        mysqli_query($db, $query);
      }
      
        if(!isset($resultsParsed['attempt'])){
          $attemptLogin=0;
        }else{
          $attemptLogin = $resultsParsed['attempt'];
        }
        $attemptLogin =$attemptLogin +1;
        if($attemptLogin >= 10){
          
          ///LOCK ACCOUNT
          $user_check_query = "SELECT * FROM `mfa_security` WHERE `userId`=$userId LIMIT 1";
          $result = mysqli_query($db, $user_check_query);
          $resultsParsed = mysqli_fetch_assoc($result);

          $query ="UPDATE `mfa_security` SET `last_login`=now(),`attempt`=$attemptLogin,`lock_before`=0,`lock_time`=0,`account_locked`=1,`ip`='$user_ip' WHERE `userid`=$userId ";
          mysqli_query($db, $query);
          $response->title ='Wrong Images or Sequence Selected';

        }else{
          $query ="UPDATE `mfa_security` SET `last_login`=now(),`attempt`=$attemptLogin,`lock_before`=0,`lock_time`=0,`account_locked`=0,`ip`='$user_ip' WHERE `userid`=$userId ";
          mysqli_query($db, $query);
          $response->title ='Account Locked! -1';

        }
    
      
      if($attemptLogin>0){
        $leftAttempts = 10 - $attemptLogin;
      }else{
        $leftAttempts=0;
      }

      $response->leftAttempts = $leftAttempts;
    }

    echo json_encode($response);


  }

  //[STEP 3.2] VERIFY MFA LOGINS
  // THIS FUNCTION IS THE SAME AS TRY MFA LOGIN
  if($action=='def50200b297b50622aa712faf56dd950665c05564d159530b757d3a0693871d7a7f549cf13ca9e4e422cdeef99d47d2210c026e7a9cad5e02cd24cab19cb67c1b0e4cd7eee0cf7f3361241e13674dd5265f99eefb6cc37238c383e591a07ffe3715'){
    // VERIFY MFA LOGINS
    // THIS FUNCTION IS THE SAME AS TRY MFA LOGIN
    $response = new stdClass();

    //ENCRYPTED IMAGES SELECTED
    if(isset($_POST['def5020091438761f79acaa78b56cf5061dc38e1f38d36c7ec1cc9bb6787733421291568b072e9895884662ca209bfe778bbbbfe5625df3d2472d0ec2b3626e68618d71572f43b43c7b3a6f94c64f4c0f28403ded4e3f2a89a'])){
      $arrayReceived = $_POST['def5020091438761f79acaa78b56cf5061dc38e1f38d36c7ec1cc9bb6787733421291568b072e9895884662ca209bfe778bbbbfe5625df3d2472d0ec2b3626e68618d71572f43b43c7b3a6f94c64f4c0f28403ded4e3f2a89a'];
    }else{
      $response->Error = "Not defined";
    }

    //ORDER OF MFA
    if(isset($_POST['def502000c356afad961652d2bedf0ec3d9dcfb0f3d837c5c2639bccb76831cdac1a8edebb7c4bca3c0f8cbd8665239cde55b765ece0a7715c32a07e675f73ee2c90d67818b49ae3212e4373b27a4a5ec31e59b6dd8bf2322913bf6f210c'])){
      $arrayOrderReceived = $_POST['def502000c356afad961652d2bedf0ec3d9dcfb0f3d837c5c2639bccb76831cdac1a8edebb7c4bca3c0f8cbd8665239cde55b765ece0a7715c32a07e675f73ee2c90d67818b49ae3212e4373b27a4a5ec31e59b6dd8bf2322913bf6f210c'];
    }else{
      $response->Error = "Not defined";
    }
    
    //ALL ENCRYPTED IMAGES IN OBJECT WITH ID and VALUE OBJECT
    if(isset($_POST['def502002f67e4a4eb1114046d026a84595bf3751448e0d89168ec35d834993b00c4f2779952dbf91a5b8fb663e143ca7be46fd0e7cae0876b0ef0e56a8778d55a0a919ba2355b936cf1c58b4505b84130510501e632c5032d8ddca76f041e22ff2708'])){
      $arrayImageArrange = $_POST['def502002f67e4a4eb1114046d026a84595bf3751448e0d89168ec35d834993b00c4f2779952dbf91a5b8fb663e143ca7be46fd0e7cae0876b0ef0e56a8778d55a0a919ba2355b936cf1c58b4505b84130510501e632c5032d8ddca76f041e22ff2708'];
    }else{
      $response->Error = "Not defined";
    }
    
    if(isset($_SESSION['userId'])){
      $userId = $_SESSION['userId'];
    }else{
      $response->Error = "Not defined";
    }
    if(isset($response->Error)){
      if($response->Error!=''){
          $response->title = 'Error'; 
        }
    } 
    $user_check_query = "SELECT `folder_value` FROM `multifactor` WHERE `userId`=$userId LIMIT 1";
    $result = mysqli_query($db, $user_check_query);

    $user = mysqli_fetch_assoc($result);
  
    if ($user) {
      $encryptedPath = $user['folder_value'];
      $path = "./assets/imgs/$encryptedPath/cropped/";
    }else{
      $getIP = $_SERVER['REMOTE_ADDR'];
      $query = "INSERT INTO `banned_ips`(`userid`, `date`, `reason`, `performing`, `ip`, `other_info`) VALUES('$userId', now(), 'MFA FOLDER COMPROMISED OR USER ID COMPROMISED','automatic','$getIP','IP BANNED')";
      mysqli_query($db, $query);

      // MFA FOLDER COMPROMISED OR USER ID COMPROMISED
      session_destroy();
      echo "<script>window.location.replace('https://google.com/');</script>";
    }
    if(!isset($arrayOrderReceived)){
      $response->status=false;
      $response->permission =false;
    }else{
      $isTruth = true;
      if(count($arrayOrderReceived) == 4 || count($arrayOrderReceived)==5){
        if (is_dir($path)) {

          $images = glob($path."*.jpg"); //GET ALL FILES WITH .JPG IN THE PATH

          if(isset($images)){
            if(count($images)!=9){
              echo 'ERROR - PROCESSING IMAGES';
              die;
            }

            $picturesList = new stdClass();
            for ($i=0; $i < 9 ; $i++) {
              $images[$i] = str_replace(".jpg","",$images[$i]);//REMOVE THE PROPERTY FROM IMAGE NAME
              $images[$i] = str_replace($path,"",$images[$i]);  //REMOVE THE LOCATION FROM IMAGE NAME
              $secretImageName = $images[$i]; // ONLY THE IMAGE NAME
              $newAuth = generateRandomString();
              $picturesList->$secretImageName = $newAuth;
            }

            $newArray = [];
            $count=0;

            foreach ($arrayOrderReceived as $element ) {
              for ($i=0; $i < 9 ; $i++) {
                if ($element == $arrayImageArrange[$i]['id'] ) {
                  $valueDescrypted = decryptValue($arrayImageArrange[$i]['value']); //DECRYPT THE SELECTED IMAGES
                  $truth = in_array($valueDescrypted,$images); // ARE THE IMAGES SELECTED IN THE FOLDER?
                  if($truth){ // IF YES
                    array_push($newArray,$valueDescrypted);
                    $count++;
                  }else{ // IF NO - CAUSED BY AN ERROR OR ATTEMPT TO BYPASS
                    $isTruth = false;
                    $response->error = 'ERROR - PIECE OF IMAGE NOT FOUND';
                    return;
                  } 
                }
              }
            }

            $response->status=true;

            $user_check_query = "SELECT * FROM `mfa_authentication` WHERE `userId`=$userId LIMIT 1"; // Get passphare stored
            $result = mysqli_query($db, $user_check_query);
            $resultParsed = mysqli_fetch_assoc($result);
            
            $arrayPassphare = json_decode($resultParsed['passphrase']);
            $response->permission=false;

            $hasPermission = true;
  
            if(count($newArray) == count($arrayPassphare)){ //  First verification: Verify if they have the same length
              for ($i=0; $i < count($arrayPassphare) ; $i++) { 
                if(decryptValue($arrayPassphare[$i]) != $newArray[$i]){
                  $hasPermission = false; // If one of the element is not equal
                }
              }  
            }else{
              $hasPermission =false;
            }

            if ($hasPermission==true) {
              $response->permission = true; // Everything fine
            }else{$response->permission = false;$response->status = false;}

        
            if($isTruth===false){ // IF NO - CAUSED BY AN ERROR OR ATTEMPT TO BYPASS
              $response->status=false;
              $response->error = 'ERROR - ONE SELECTED PIECE OF IMAGE WAS NOT FOUND';
            }
          }else{ // IF NO - CAUSED BY AN ERROR OR ATTEMPT TO BYPASS
            $response->status=false;
            $response->permission =false;
            $response->error = 'ERROR - IMAGES NOT FOUND';
          }
        }else{
          $response->status=false;
          $response->permission =false;
          $response->error = 'ERROR - Not a DIR';
        }
      }else{
        $response->status=false;
        $response->permission =false;
      }
    }
    
    ///REGISTER ATTEMPT
    $user_check_query = "SELECT * FROM `mfa_security` WHERE `userId`=$userId LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $resultsParsed = mysqli_fetch_assoc($result);
    $user_ip = $_SERVER['REMOTE_ADDR']; // get user's IP address

    if (!isset($resultsParsed)) {
      $query = "INSERT INTO `mfa_security`(`userid`, `last_login`, `attempt`, `lock_before`, `lock_time`, `account_locked`, `ip`) VALUES ($userId,now(),1,0,0,0,'$user_ip')";
      mysqli_query($db, $query);
    }
    
    if(!isset($resultsParsed['attempt'])){
      $attemptLogin=0;
    }else{
      $attemptLogin = $resultsParsed['attempt'];
    }

    $attemptLogin = $attemptLogin +1;

    if($attemptLogin >= 10){
      
      ///LOCK ACCOUNT
      $user_check_query = "SELECT * FROM `mfa_security` WHERE `userId`=$userId LIMIT 1";
      $result = mysqli_query($db, $user_check_query);
      $resultsParsed = mysqli_fetch_assoc($result);

      $query ="UPDATE `mfa_security` SET `last_login`=now(),`attempt`=$attemptLogin,`lock_before`=0,`lock_time`=0,`account_locked`=1,`ip`='$user_ip' WHERE `userid`=$userId ";
      mysqli_query($db, $query);
      $response->title ='Account Locked!';

    }else{
      $query ="UPDATE `mfa_security` SET `last_login`=now(),`attempt`=$attemptLogin,`lock_before`=0,`lock_time`=0,`account_locked`=0,`ip`='$user_ip' WHERE `userid`=$userId ";
      mysqli_query($db, $query);
      $response->title ='Wrong Images or Sequence Selected';

    }

    $user_check_query = "SELECT * FROM `multifactor` WHERE `userId`=$userId LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $resultParsed = mysqli_fetch_assoc($result);

    if(!isset($resultParsed['backup_code'])){
      $response->hasBackup = false;

      $backupCode = generateBackup();
      $backupPiecesCodes = str_split($backupCode, 5);
      $sendBackup= $backupPiecesCodes[0] .' - '. $backupPiecesCodes[1] .' - '.$backupPiecesCodes[2] .' - '.$backupPiecesCodes[3] .' - '. $backupPiecesCodes[4];
      
      $query ="UPDATE `multifactor` SET `backup_code`='$backupCode' WHERE `userid`=$userId;";
      mysqli_query($db, $query);
      
      $response->backup =$sendBackup;

    }else{
      $response->hasBackup = true;
    }
    $query ="UPDATE `mfa_security` SET `last_login`=now(),`attempt`=0,`lock_before`=0,`lock_time`=0,`account_locked`=0,`ip`='$user_ip' WHERE `userid`=$userId ";
    mysqli_query($db, $query);
    $response->title ='Welcome!';
    $leftAttempts=10;
    $attemptLogin=1;
    $_SESSION['authenticated']=true;

    $query ="UPDATE `users` SET `multifactor`=1 WHERE `id`=$userId ";
    mysqli_query($db, $query);
    

    if($attemptLogin>0){
      $leftAttempts = 10 - $attemptLogin;
    }

    $response->leftAttempts = $leftAttempts;

    echo json_encode($response);
  }

}
?>