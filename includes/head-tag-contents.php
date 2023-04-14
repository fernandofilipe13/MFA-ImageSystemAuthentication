<title><?php print $PAGE_TITLE;?></title>

<?php if ($CURRENT_PAGE == "Index") { ?>
	<meta name="description" content="" />
	<meta name="keywords" content="" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
<?php }

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['username']);
	header("location: signin.php");
}
echo"<link rel='stylesheet' type='text/css' href='./assets/css/style.css'>";


require 'includes/database.php';

if($CURRENT_PAGE != "consent"){
	$getIP = $_SERVER['REMOTE_ADDR'];
	$query = "SELECT * FROM `history` where `ip`= '$getIP' LIMIT 1;";
	$result = mysqli_query($db, $query);
	$resultParsed= mysqli_fetch_assoc($result);
	if(!isset($resultParsed)){
		if($resultParsed['consent']!=1){
			header("location: consent.php");
		}
	}
	$userAgent = $_SERVER['HTTP_USER_AGENT'];

	if (strpos($userAgent, 'Mobile') !== false) {
		echo "Accessing via mobile phone is not allowed.";
		die();
	}
}
?>
<style>
	#main-content {
		margin-top:20px;
	}
	.footer {
		font-size: 14px;
		text-align: center;
	}
</style>