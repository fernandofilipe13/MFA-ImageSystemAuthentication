<?php
	switch ($_SERVER["SCRIPT_NAME"]) {
		case "/private.php":
			$CURRENT_PAGE = "Private"; 
			$PAGE_TITLE = "Private";
			break;
		case "/contact.php":
			$CURRENT_PAGE = "Contact"; 
			$PAGE_TITLE = "Contact Us";
			break;
		case "/signin.php":
			$CURRENT_PAGE = "Sign-In"; 
			$PAGE_TITLE = "Sign-In";
			break;
		case "/private.php":
			$CURRENT_PAGE = "Private"; 
			$PAGE_TITLE = "Private";
			break;
		case "/consent.php":
			$CURRENT_PAGE = "consent"; 
			$PAGE_TITLE = "Consent";
			break;
		case "/simple.php":
			$CURRENT_PAGE = "simple"; 
			$PAGE_TITLE = "Simple Page";
			break;
		default:
			$CURRENT_PAGE = "Index";
			$PAGE_TITLE = "Image System Authentication";
	}
?>