# SFS
A StopForumSpam port to ElkArte. The original code can be found [here](http://custom.simplemachines.org/mods/index.php?mod=1519). The instructions for adding the code manually is found by hitting the `Parse` button below the download link to the right of the website. As this is the Original branch specifically for the purpose of archiving the SimpleMachines Forum SFS Mod... there will be no code commits after this one.

Any attempts to do a `PR` will be expressly rejected to the Original branch.

## File Edits

In `./Sources/Register.php`...

Find:

```
foreach ($_POST as $key => $value)
	{
		if (!is_array($_POST[$key]))
			$_POST[$key] = htmltrim__recursive(str_replace(array("\n", "\r"), '', $_POST[$key]));
	}
```

Add After:

```	
			//Check Forum Spam if enabled
	if (!empty($modSettings['sfs_enabled'])) {
		if (SpammerCheck()) {
			loadLanguage('SFS');
			fatal_error(sprintf($txt['sfs_spam_detected'],$_POST['user'],$_POST['email'],$user_info['ip']), true);
		}
	}
```

Find (at the end of the file):
`?>`

Add the following code before `?>`:

```
function SpammerCheck()
{
	global $txt, $boarddir, $context, $settings, $modSettings, $user_info, $sourcedir;
	
	$isSpammer=0;
	$emailSpam=$_POST['email'];
	$ipSpam=$user_info['ip'];
	$usernameSpam=$_POST['user'];
	$response='';
	$url='http://www.stopforumspam.com/api?email=' . $emailSpam;
	
	require_once($sourcedir . '/Subs-Package.php');
	
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
```

In `./Sources/ManageRegistration.php`
Find:

`loadLanguage('Login');`

Add After:

`loadLanguage('SFS');`

Presumably so the code looks like this:

```
loadLanguage('Login');
loadLanguage('SFS');
```
Still in `./Sources/ManageRegistration.php`...

Find:
```
	$config_vars = array(
			array('select', 'registration_method', array($txt['setting_registration_standard'], $txt['setting_registration_activate'], $txt['setting_registration_approval'], $txt['setting_registration_disabled'])),
			array('check', 'enableOpenID'),
			array('check', 'notify_new_registration'),
			array('check', 'send_welcomeEmail'),
		'',
			array('int', 'coppaAge', 'subtext' => $txt['setting_coppaAge_desc'], 'onchange' => 'checkCoppa();'),
			array('select', 'coppaType', array($txt['setting_coppaType_reject'], $txt['setting_coppaType_approval']), 'onchange' => 'checkCoppa();'),
			array('large_text', 'coppaPost', 'subtext' => $txt['setting_coppaPost_desc']),
			array('text', 'coppaFax'),
			array('text', 'coppaPhone'),
```

Add After:
```
		'',
			array('check', 'sfs_enabled', 'subtext' => $txt['setting_sfs_enabled_desc']),
			array('check', 'sfs_ipcheck', 'subtext' => $txt['setting_sfs_ipcheck_desc']),
			array('check', 'sfs_usernamecheck', 'subtext' => $txt['setting_sfs_usernamecheck_desc']),
```

In `./Themes/default/Who.template.php`...

Find:

```
		if (!$member['is_guest'])
		{
			echo '
								<span class="contact_info floatright">
									', $context['can_send_pm'] ? '<a href="' . $member['online']['href'] . '" title="' . $member['online']['label'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $member['online']['image_href'] . '" alt="' . $member['online']['text'] . '" align="bottom" />' : $member['online']['text'], $context['can_send_pm'] ? '</a>' : '', '
									', isset($context['disabled_fields']['icq']) ? '' : $member['icq']['link'] , ' ', isset($context['disabled_fields']['msn']) ? '' : $member['msn']['link'], ' ', isset($context['disabled_fields']['yim']) ? '' : $member['yim']['link'], ' ', isset($context['disabled_fields']['aim']) ? '' : $member['aim']['link'], '
								</span>';
		}
```

Add After:

```
		if ($member['is_guest'])
		{
			echo '
				<span class="floatright" style=" margin-right: 2px;">
					<a href="http://www.stopforumspam.com/search?q='. $member['ip'] .'" target="_blank"><img src="' . $settings['theme_url'] . '/../default/images/sfs_icon.png" align="middle" /></a>
				</span>';
		}
```

#### File Operations

```
Move the included file "SFS.arabic-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.arabic.php" to "./Themes/default/languages".
Move the included file "SFS.croatian-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.croatian.php" to "./Themes/default/languages".
Move the included file "SFS.english-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.english.php" to "./Themes/default/languages".
Move the included file "SFS.french-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.french.php" to "./Themes/default/languages".
Move the included file "SFS.german-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.german.php" to "./Themes/default/languages".
Move the included file "SFS.greek-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.greek.php" to "./Themes/default/languages".
Move the included file "SFS.italian-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.italian.php" to "./Themes/default/languages".
Move the included file "SFS.portuguese_pt-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.portuguese_pt.php" to "./Themes/default/languages".
Move the included file "SFS.russian-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.russian.php" to "./Themes/default/languages".
Move the included file "SFS.turkish-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.turkish.php" to "./Themes/default/languages".
Move the included file "SFS.ukrainian-utf8.php" to "./Themes/default/languages".
Move the included file "SFS.ukrainian.php" to "./Themes/default/languages".
Move the included file "sfs_icon.png" to "./Themes/default/images".
```
