<!DOCTYPE html>
<html>
<?php include("includes/a_config.php"); ?>

<head>
  <?php include("includes/head-tag-contents.php"); ?>
</head>
<?php

if(isset($_POST['consent'])){
    echo $_POST['consent'];
    $getIP = $_SERVER['REMOTE_ADDR'];
    $query = "INSERT INTO `history`(`ip`,  `consent`, `date`) VALUES( '$getIP',1,now())";
    mysqli_query($db, $query);
    exit;
}

if(isset($_SESSION)){
    if(!isset($_SESSION['consent'])){
        $_SESSION['consent'] = true;
    }
}
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
    $(document).ready(function(){

        Swal.fire({
        title:'Hello! Thank you for your participation!',
        text:'Please read this carefully',
        icon:'warning',
        timer: 5000,
        }).then(() => {
            Swal.fire({
                text:'First, you will need to create an account and sign-in',
                icon:'info',
                timer: 10000,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Continue'

            }).then(() => {
                Swal.fire({
                    text:'Second, you will start setting up your Multi-Factor Authentication.',
                    icon:'info',
                    timer: 10000,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Continue'
                }).then(() => {
                    Swal.fire({
                        title:'Multi-Factor Authentication',
                        html:'The system has 3 phases.<br>1. Select the image<br>2. Select the parts of the image<br>3. Try it!',
                        icon:'info',
                        timer: 10000,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Continue'
                    }).then(() => {
                        Swal.fire({
                            title:'Thank you!',
                            icon:'success',
                            timer: 10000,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Continue'
                        }).then(() => {
                            $.ajax({
                                type:'POST',
                                url:'',
                                data:{
                                    "consent":true
                                },
                                success:function(data){
                                    location.href = './signup.php'; 
                                }
                            })
                        })
                    })
                })
            })
        })
    })
</script>
