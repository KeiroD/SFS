function SpammerCheck()
{
	global $txt, $boarddir, $context, $settings, $modSettings, $user_info, $sourcedir;
	
	$isSpammer=0;
	$emailSpam=$_POST['email'];
	$ipSpam=$user_info['ip'];
	$usernameSpam=$_POST['user'];
	$response='';
	$url='http://www.stopforumspam.com/api?email=' . $emailSpam;
	
	require_once($sourcedir . '/subs/Package.subs.php');
	
	$response = fetch_web_data($url);
	
	//Is Email Spammer??
	if (strpos($response, "<appears>yes</appears>") > 0) $isSpammer=1;
	
	if (!$isSpammer && !empty($modSettings['sfs_ipcheck'])) { //If Not Spammer check the IP
		//Check IP Spammer
		$url='http://www.stopforumspam.com/api?ip=' . $ipSpam;
		
		$response = fetch_web_data($url);
		//Is IP Spammer??
		if (strpos($response, "<appears>yes</appears>") > 0) $isSpammer=1;
	}
	
	if (!$isSpammer && !empty($modSettings['sfs_usernamecheck'])) { //If Not Spammer check the username
		//Check Username Spammer
		$url='http://www.stopforumspam.com/api?username=' . $usernameSpam;
		
		$response = fetch_web_data($url);
		//Is IP Spammer??
		if (strpos($response, "<appears>yes</appears>") > 0) $isSpammer=1;
	}
	
	return $isSpammer;
}
