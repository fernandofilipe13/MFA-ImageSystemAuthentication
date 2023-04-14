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
<?php include("process_login.php");?>


<div class="container" id="main-content">
	<?php  if (isset($_SESSION['username'])) : ?>
		<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
	<?php endif ?>
	
	<h2>Welcome to my website!</h2>
	
	<p>This site is just aesthetic and some features have not been implemented.</p>

	<p>You are now inside the website! Thank you for participating please fill the questionnaire bellow. <strong><a style='color:#007bff' href='https://coventry.onlinesurveys.ac.uk/mfaproject' >Click here</a></strong></p>

	<p>To test your MFA please sign OUT and sign IN!</p>
	<div style='margin-top:5%'>
		<h4>System Security</h4>
			<style>
				li {
					margin-top:10px;
					display: list-item;
					margin-left: 1em;
				}
			</style>
			<ul>
				<li>10 attempts allowed</li>
				<li>In the event of 10 failed attempts, the account will be locked for 2 hours</li>
				<li>After the 2-hour lock:
					<ul>
					<li>3 attempts allowed</li>
					<li>In the event of 3 failed attempts, the account will be locked for 12 hours</li>
					<li>The user must change the security code upon successful login</li>
					</ul>
				</li>
				<li>After the 12-hour lock:
					<ul>
					<li>3 attempts allowed</li>
					<li>In the event of 3 failed attempts, the account will be locked for 24 hours</li>
					<li>The user must change the image upon successful login</li>
					</ul>
				</li>
				<li>After the 24-hour lock:
					<ul>
					<li>3 attempts allowed</li>
					<li>In the event of 3 failed attempts, the account will be locked forever</li>
					</ul>
				</li>
			</ul>
		<p>Every time the account gets locked the owner of the account can chat with the support and use the backup code to unlock the account. The backup code is one-time use and a new code will be generated when it is used.</p>
	</div>
	
	<iframe style='margin-top:30px;margin-bottom:30px;' width="100%" height="500px" src="https://coventry.onlinesurveys.ac.uk/mfaproject" title="Questionnaire!" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


	<div class="content">
		<!-- notification message -->
		
	<?php if (isset($_SESSION['success'])) : ?>
		<div class="error success" >
			<h3 style='text-transform: uppercase;'>
			<?php 
				echo $_SESSION['success'] .' '. $_SESSION['username']; 
			?>
			</h3>
		</div>
	<?php endif ?>
		<!-- logged in user information -->
	
	</div>
</div>

<?php include("includes/footer.php");?>

</body>
</html>

