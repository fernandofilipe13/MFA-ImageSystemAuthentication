<?php include("includes/a_config.php");?>
<!DOCTYPE html>

<html>
<head>
	<?php include("includes/head-tag-contents.php");
	    if (!isset($_SESSION['username'])) {
			header('location: signin.php');
		}else{
			if($_SESSION['authenticated']==false){
				header('location: signin.php');
			}
		}
	?>
</head>
<body class='align'>

<?php include("includes/navigation.php");?>

<style>
	p{
		margin-top:10px;
	}
</style>
<div class="container" id="main-content">
	<p>You are being invited to take part in research on Multi-Factor Authentication. This survey aims to gather data on user attitudes towards Multi-Factor Authentication.<p>

	<p>The research project is being conducted by Fernando Filipe at Coventry University. Your participation in the survey is entirely voluntary, and you can opt-out at any stage by closing and exiting the browser. If you are happy to take part, please answer the following questions.</p>


	<p>The research was granted ethical approval by Coventry University’s Research Ethics Committee P149039. </p>

	<p>For further information, or if you have any queries, please contact Fernando Filipe, filipebesf@uni.coventry.ac.uk</p>

	<p>Thank you for taking the time to participate in this survey. Your help is very much appreciated! You are helping to secure the internet!</p>
</div>

<?php include("includes/footer.php");?>

</body>
</html>