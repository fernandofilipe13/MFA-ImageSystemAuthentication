<?php

$errors = array(); 
$alerts = array(); 


$db = mysqli_connect('localhost', 'root', 'password', 'mfaproject');

$query = "SELECT * FROM `banned_ips`";
$results = mysqli_query($db, $query);
$blocked_ips = array();
while( $row = $results->fetch_array() )
{
    array_push($blocked_ips,$row['ip']);
}

$user_ip = $_SERVER['REMOTE_ADDR']; // get user's IP address

if (in_array($user_ip, $blocked_ips)) {
   // display an error message or redirect the user to a different page
   die("Access denied");
}


require 'vendor/autoload.php';



use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

function loadEncryptionKeyFromConfig(){
    $keyAscii = 'def000006fee91e551bf57c0e64b8ec676f6ce4905eea72537fdff1fd379d49a0cdd1c3362326161250eaae11b7cdb8d26d6c1e7ce8c3735e4aae7a41605f0a140fd9ac3';
    return Key::loadFromAsciiSafeString($keyAscii);
}

function encryptValue($secret_data){
    $key = loadEncryptionKeyFromConfig();

    return Crypto::encrypt($secret_data, $key);
}

// echo encryptValue('verifyLoginMFA');

function decryptValue($ciphertext){
    try {
            $key = loadEncryptionKeyFromConfig();
            $secret_data = Crypto::decrypt($ciphertext, $key);
            return $secret_data;
        } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
            echo $ex;   
            // An attack! Either the wrong key was loaded, or the ciphertext has
            // changed since it was created -- either corrupted in the database or
            // intentionally modified by Eve trying to carry out an attack.
        
            // ... handle this case in a way that's suitable to your application ...
        }
}

function rrmdir($dir){
 if (is_dir($dir))
 {
  $objects = scandir($dir);

  foreach ($objects as $object)
  {
   if ($object != '.' && $object != '..')
   {
    if (filetype($dir.'/'.$object) == 'dir') {rrmdir($dir.'/'.$object);}
    else {unlink($dir.'/'.$object);}
   }
  }

  reset($objects);
  rmdir($dir);
 }
}

use Unsplash as Unsplash;
\Unsplash\HttpClient::init([
	'applicationId'	=> '9n8MidtEMu2DsuxXOkh5yP6OYAcgjbZ-MxtdX25zdNc',
	'secret'	=> 'dGbhXYFCkuPM6AUJaUZMTtyaC7fx7ZqrQoGx8x36xGc',
	'utmSource' => 'Image Authentication System'
]);

function getNewPhotos(){
    $filters = [
        'count' => 8,
        'width' => 400,
        'height' => 400,
    ];
   
    // Get a random photo with the specified filters
    $photos= Unsplash\Photo::random($filters);

    return $photos;
}


ini_set('display_errors', 1); error_reporting(E_ALL);

if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
    if($CURRENT_PAGE != "Sign-In" && $CURRENT_PAGE != "simple"){
        //SECURITY
        $user_check_query = "SELECT * FROM `mfa_security` WHERE `userId`=$userId LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $resultsParsed = mysqli_fetch_assoc($result);
        if(isset($resultsParsed)){
            // var_dump($resultsParsed);
            if($resultsParsed['account_locked']==true){
                header('location: ./signin.php');
                session_destroy();
            }
        }
    }
    
    $query = "SELECT * FROM `users` where `id`= $userId LIMIT 1;";
    $result = mysqli_query($db, $query);
    $resultParsed= mysqli_fetch_assoc($result);
    if(!isset($resultParsed)){
        session_destroy();
        header("location: signin.php");
    }

}
?>