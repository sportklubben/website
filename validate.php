<?php 
	$allowed = array('capgemini.com', 'sogeti.com');
	// Make sure the address is valid
	if (filter_var($UserEmail, FILTER_VALIDATE_EMAIL))
	{
	    $explodedEmail = explode('@', $UserEmail);
	    $domain = array_pop($explodedEmail);

	    if ( ! in_array($domain, $allowed))
	    {
	        echo 'not allowed'; // Not allowed
	    }

	}
?>