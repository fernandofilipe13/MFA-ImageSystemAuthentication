<?php include("includes/a_config.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
</head>
<?php
    $response = new stdClass();


    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: signin.php');
    }
    $debug = false;


    if(isset($_POST['backup'])){
        $userId = $_SESSION['userId'];
      $response = new stdClass();
    
        $user_check_query = "SELECT * FROM `multifactor` WHERE `userId`=$userId LIMIT 1;";
        $result = mysqli_query($db, $user_check_query);
        $resultParsed= mysqli_fetch_assoc($result);
        if(isset($resultParsed)){
            $receivedBackupCode = $_POST['backup'];
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
?>


<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
  $(document).on("click", '#restartmfa', async function() {
	
    Swal.fire({
                title: 'Restart the process',
                html: 'To restart the process of your MFA',
                showCancelButton: false,
                confirmButtonText: 'Confirm',
                confirmButtonColor: '#3085d6',
                icon: 'info',
                timer: 10000,
            }).then(() => {
                $.ajax({
                    type:'POST',
                    url:'server.php',
                    data:{
                        "restartmfa":true
                    },
                    success:function(data){
                        var result = $.parseJSON(data);
                        Swal.fire({
                            title:result.title,
                            icon:result.icon,
                            timer: 10000,
                            confirmButtonColor:result.color, 
                            confirmButtonText: 'Ok!'
                        })
                        .then(() => {
                            location.href = result.redirect; 
                        })
                    }
                })
            })
})
</script>

<body class="align">
    <div style='text-align:center'>
        <h3>Multi-Factor Authetication Setup</h3>
        <p>It's quick and easy.</p>
    </div>

    <div class="container" id="main-content">
        <p>Welcome <strong><?php echo $_SESSION['username']; ?>!</strong><span style='float:right'>Do you need help? <a href='./faq.php'>FAQ</a></span></p>

    <?php
        $userId = $_SESSION['userId'];
        $user_check_query = "SELECT * FROM `multifactor` WHERE `userId`=$userId LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        
        $firstStepHide = 'hide';
        $secondStepHide = 'hide';
        $thirdStepHide = 'hide';
        
        if ($user) {
            if ($user['setup_step'] != 2 && $user['setup_step'] != 3) {
                $firstStepHide = '';
                echo "<div id='titleStep'><h3  class='align'><b>FIRST STEP</b></h3><p> to remember later</p></div>";
                $response->title = 'MFA - First Step';
                $response->html = 'Select an image to remember later';
                $response->icon = 'info';
                // $response->timer = '10000';
                array_push($alerts, json_encode($response));
            
            }
            if ($user['setup_step'] == 2) {
                $secondStepHide = '';
                echo "<div id='titleStep'><h3 class='align'><b>SECOND STEP</b></h3><p>Create a security code by selecting the parts of the images you want! Order matters!</p></div>";
                $response->title = 'MFA - Second Step';
                $response->html = 'Create a security code by selecting the parts of the images you want! Order matters!';
                $response->icon = 'info';
                // $response->timer = '10000';
                array_push($alerts, json_encode($response));
            }
            if ($user['setup_step'] == 3) {
                $thirdStepHide = '';
                echo "<div id='titleStep'><h3 class='align'><b>THIRD STEP</b></h3></div>";
                $response->title = 'MFA - Third Step';
                $response->html = 'Test your authentication!';
                $response->icon = 'info';
                // $response->timer = '10000';
                array_push($alerts, json_encode($response));
            }
        } else {
            $firstStepHide = '';

            echo "<div id='titleStep'><h3 class='align'><b>FIRST STEP</b></h3><p>Select an image to remember later</p></div>";

            $response->title = 'MFA - First Step';
            $response->html = 'Select an image to remember later';
            $response->icon = 'info';
            // $response->timer = '10000';
            array_push($alerts, json_encode($response));
        }

        $multifactorActive = false;
        $username = $_SESSION['username'];
        $userId = $_SESSION['userId'];

        $query = "SELECT * FROM `users` WHERE `username`='$username' AND `id`=$userId;";
        $results = mysqli_query($db, $query);

        while ($row = $results->fetch_array()) {
            $multifactorActive = $row['multifactor'];
        }

        if ($multifactorActive == false) { ?>

            <div id='imgAuth' class='menuSection <?php echo $firstStepHide; ?>'>
                <?php if ($firstStepHide != 'hide') { ?>
                    <div id='imagesList'>
                        <?php
                        $dirname = "assets/images/"; // Images Directory
                        $images = glob($dirname . "*.jpg"); // Read only files with this property
                        shuffle($images); // Shuffle images
                        $displayedImages = [];
                        $count4 = 0;
                        $arrayImagesDisplay = array();
                        for ($i = 0; $i < 8; $i++) {
                            if ($i == 0 || ($i + 4) == $count4) {
                                echo "<div class='row'>"; // Start Row
                            }
                            while ($i < $count4 && $i < 8) {
                                if ($i < $count4 && $i < 8) {
                                    // Insert 4 images
                                    array_push($arrayImagesDisplay, $images[$i]); 
                                    echo "<div class='column'>
                                            <img src='$images[$i]' alt='image$i' class='imageListDisplay'>
                                        </div>";
                                    $i++;
                                }
                            }
                            // Close row
                            echo '</div>';
                            $i--;
                            $count4 += 4;
                        }
                        $_SESSION['ArrayImagesDisplay'] = $arrayImagesDisplay;
                        echo `Do you prefer to use your own image? <a  target="popup" onclick="window.open('./upload.php','name','width=600,height=800')">Upload it! </a>
                        <p> Generate new images?<button class='btn btn-secondary btn-generate-confirm'> Generate</button></p> `;

                        ?>

                    </div>
                    <div class="container imageContainer" id='imageContainer'>
                        <div class='button--center'>
                            <button id='closeImage' class='btn btn-outline-danger' onclick="closeFunction()"> Close Image</button>
                            <button id='chooseImage' style='margin-left:10px' class='btn btn-outline-success'> Choose Image</button>
                        </div>

                        <!-- Expanded image -->
                        <div style='width:100%;'>
                            <img class='image--center' id="expandedImg" width=300 heigh=300>
                        </div>
                    </div>
                <?php } ?>
            </div>


            <div id='setupAuth' class='menuSection <?php echo $secondStepHide; ?>'>

                <?php if ($secondStepHide != 'hide') { ?>
                    <?php

                    echo "<div class='center image--center'>";

                    if (decryptValue($user['folder_value']) == $userId) {
                        $encryptedPath = $user['folder_value'];
                        $path = "./assets/imgs/$encryptedPath/cropped/";
                    } else {
                        $getIP = $_SERVER['REMOTE_ADDR'];
                        $query = "INSERT INTO `banned_ips`(`userid`, `date`, `reason`, `performing`, `ip`, `other_info`) VALUES('$userId', now(), 'MFA FOLDER COMPROMISED OR USER ID COMPROMISED','automatic','$getIP','IP BANNED')";
                        mysqli_query($db, $query);

                        // MFA FOLDER COMPROMISED OR USER ID COMPROMISED
                        session_destroy();
                        echo "<script>window.location.replace('https://google.com/');</script>";
                    }


                    $imageGrid = 9;
                    $countId = 1;
                    if (is_dir($path)) {
                        $images = glob($path . "*.jpg");
                        if (count($images) > 0) {
                            echo "<div class='imageGrid parent' data-value='0'>";
                            shuffle($images);
                            for ($i = 0; $i < $imageGrid; $i++) {
                                $secretImageName = str_replace(".jpg", "", $images[$i]); //REMOVE THE PROPERTY FROM IMAGE NAME
                                $secretImageName = str_replace($path, "", $secretImageName);  //REMOVE THE LOCATION FROM IMAGE NAME

                                $cipherImageName = encryptValue($secretImageName);
                                // $decryptedValue = decryptValue($cipherImageName);

                                echo "<div><input type='image' class='image' src='$images[$i]' data-value='$cipherImageName' id='item-$countId'></div>";
                                $countId++;
                            }
                            echo "</div>";
                        } else {
                            echo 'no images';
                        }
                    } else {
                        echo 'not a dir';
                    }
                    echo "</div>
                    <div class='error errorSelectection hide' style='margin-top:10px;margin-bottom:10px'><p>You have to select at least 4 images and up to 5!</p></div>
                    <button class='btn btn-secondary btn-confirm' disabled > Confirm </button>
                    ";
                    ?>
                <?php } ?>
            </div>


            <div id='tryAuth' class='menuSection <?php echo $thirdStepHide; ?>'>
                <?php if ($thirdStepHide != 'hide') { ?>
                    <?php
                    echo "
                        <h4>This is the real example of how you have to connect in your account. </h4>
                        <p>The same list of images that were presented to you when you setted up your authentication will be presented to you in order to certify if it is really you entering in your account. <b>Only you should know which image you selected before.</b></p>
                            
                        <div id='imageTryAuth' >";
                    $user_check_query = "SELECT `folder_value` FROM `multifactor` WHERE `userId`=$userId LIMIT 1";
                    $result = mysqli_query($db, $user_check_query);
                    $user = mysqli_fetch_assoc($result);

                    $userFolder = $user['folder_value'];
                    $dirname = "./assets/imgs/$userFolder/";
                    $images = glob($dirname . "*.jpg");
                    shuffle($images);
                    $displayedImages = [];
                    $count4 = 0;
                    $arrayImagesDisplay = array();
                    for ($i = 0; $i < 8; $i++) {
                        if ($i == 0 || ($i + 4) == $count4) {
                            echo "<div class='row'>";
                        }
                        while ($i < $count4 && $i < 8) {
                            if ($i < $count4 && $i < 8) {
                                array_push($arrayImagesDisplay, $images[$i]);
                                echo "<div class='column'>
                                        <img class='imageAuth' src='$images[$i]' alt='image$i' >
                                    </div>";
                                $i++;
                            }
                        }
                        echo '</div>';
                        $i--;
                        $count4 += 4;
                    }

                    echo " </div>
                        ";
                    ?>
                <?php } ?>
            </div>

            <div id='description' style='margin-bottom:10px;margin-top:10px;'>
                <button class='btn btn-secondary btn-tutorial' > Show Tutorial</button><button class='btn btn-secondary' style='margin-left:10px;' id='restartmfa'> Restart MFA</button>
            </div>
        <?php } else { ?>
            <div>This is only for setup MFA. You already have a MFA activated </div>
            <script>location.replace('./index.php');</script>
        <?php } ?>
    </div>



    <?php include('./notifications/alerts.php'); include("includes/footer.php"); ?>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js'></script>
    <script src='./assets/scripts/essentialFunctions.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</body>

</html>