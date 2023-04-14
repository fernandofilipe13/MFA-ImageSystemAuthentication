<?php include("includes/a_config.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
</head>
<?php
    $response = new stdClass();

    if (!isset($_SESSION['username'])) {
        header('location: signin.php');
    }

    $user_check_query = "SELECT * FROM `users` WHERE `id`=$userId LIMIT 1;";
    $result = mysqli_query($db, $user_check_query);
    $resultParsed= mysqli_fetch_assoc($result);
    //  
    if(isset($resultParsed)){
        if($resultParsed['multifactor'] == false){
            header('location: multifactor.php');
        }
    }else{
        header('location: multifactor.php');

    }
?>

<body>
    <div id='tryAuth' class='menuSection'>
        <?php
        echo "                    
            <div id='imageAuthDiv'>";
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
                    if ($i == 1 || ($i + 4) == $count4) {
                        echo "<div class='row'>";
                    }
                    while ($i < $count4 && $i < 8) {
                        if ($i < $count4 && $i < 8) {
                            array_push($arrayImagesDisplay, $images[$i]);
                            echo"<div class='column'>
                                    <img class='imageAuth' src='$images[$i]' alt='image$i' >
                                </div>";
                            $i++;
                        }
                    }
                    echo '</div>';
                    $i--;
                    $count4 += 4;
                }

        echo "</div>";
        ?>
    </div>
</body>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js'></script>
<script src='./assets/scripts/essentialFunctions.js'></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<?php include('./notifications/alerts.php'); include("includes/footer.php"); ?>
</html>