<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: members_ips.php
| Author: Nicolae Crefelean (Kneekoo)
| Converted for User Control by Philip Daly (HobbyMan)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";
require INFUSIONS."user_control/inc/functions_include.php";
add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['uc050']);

$iplist = array();
$no_avatar = "avatars/noavatar50.png";

include USER_CONT."inc/user_cont_nav.php";

if (!isset($_REQUEST['getips'])) {
 $user_ip = "";

    echo "<table cellpadding='1' class='tbl-border' width='80%' border='0' align='center'>\n<tr>\n";
    echo "<td class='tbl2'><b>".$locale['uc081']."</td>\n";
    echo "</tr>\n<tr>\n";
    echo "<td class='tbl2' align='center'>".$locale['uc081'];
    echo "<form name='ipdig' action='".FUSION_SELF.$aidlink."' method='post'>\n";
	echo "<input name='getips' class='textbox' maxlength='10' style='width:230px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl2' align='center'><input type='submit' name='ipdig' value='".$locale['uc082']."' class='button' />\n</form>\n</td>\n";
	echo "</tr>\n</table>\n";

   } elseif (isset($_POST['getips']) && is_numeric($_POST['getips'])) {
	
	$userid = $_POST['getips'];
	$totals = array(
		'posts' => 0,
		'comments' => 0,
		'shouts' => 0,
		'submissions' => 0
	);
	$getuser = dbquery("SELECT 
	                           user_name, 
	                           user_ip, 
	                           user_joined, 
	                           user_lastvisit, 
	                           user_email, 
	                           user_avatar, 
	                           user_status 
	                           FROM ".DB_USERS." 
	                           WHERE user_id='".$userid."' 
	                           ");
	if (dbrows($getuser) > 0) {
		$dbuser = dbarray($getuser);
		echo "<table cellpadding='1' class='tbl-border' align='center'>\t\n<tr>\n";
		echo "\t\t<td class='tbl2' style='padding: 4px; text-align: center; vertical-align: center'><img src='".IMAGES.(empty($dbuser['user_avatar']) ? $no_avatar : "avatars/" . $dbuser['user_avatar'])."' alt='".$dbuser['user_name']."' /></td>\n";
		echo "\t\t<td>\n";
		echo "\t\t\t[<b>ID:</b> ".$userid."] ".profile_link($userid, $dbuser['user_name'], $dbuser['user_status'])."<br />\n";
		$country_code = getCountryFromIP($dbuser['user_ip']);
		echo "\t\t\t<b>IP Address:</b> ".$dbuser['user_ip']."<br />\n";
		echo "\t\t\t<b>Country: ".$country[$country_code]."</b><br />\n";
		echo "\t\t\t<b>E-mail Address:</b> ".$dbuser['user_email']."<br />\n";
		echo "\t\t\t<b>Registered:</b> ".showdate("shortdate", $dbuser['user_joined'])."<br />\n";
		echo "\t\t\t<b>Last Visit:</b> ".($dbuser['user_lastvisit'] != 0 ? showdate("shortdate", $dbuser['user_lastvisit']) : "<em>never</em>")."<br />\n";
		echo "\t\t</td>\n";
		echo "\t</tr>\n";
		echo "</table><br />\n";
		$data = dbquery("SELECT post_datestamp date, post_ip ip FROM ".DB_POSTS." WHERE post_author='".$userid."'");
		if ($data) {
			while ($user = dbarray($data)) {
				$ip = $user['ip'];
				if (array_key_exists($ip, $iplist)) {
					$iplist[$ip]['count']++;
					$iplist[$ip]['last_used'] = $user['date'];
					$iplist[$ip]['posts']++;
					$totals['posts']++;
				} else {
					$iplist[$ip] = array();
					$iplist[$ip]['count'] = 1;
					$iplist[$ip]['first_used'] = $user['date'];
					$iplist[$ip]['last_used'] = $user['date'];
					$iplist[$ip]['posts'] = 1;
					$iplist[$ip]['comments'] = 0;
					$iplist[$ip]['shouts'] = 0;
					$totals['posts']++;
				}
			}
		}
		$data = dbquery("SELECT comment_datestamp date, comment_ip ip FROM ".DB_COMMENTS." WHERE comment_name='".$userid."'");
		if ($data) {
			while ($user = dbarray($data)) {
				$ip = $user['ip'];
				if (array_key_exists($ip, $iplist)) {
					$iplist[$ip]['count']++;
					$iplist[$ip]['last_used'] = $user['date'];
					$iplist[$ip]['comments']++;
					$totals['comments']++;
				} else {
					$iplist[$ip] = array();
					$iplist[$ip]['count'] = 1;
					$iplist[$ip]['first_used'] = $user['date'];
					$iplist[$ip]['last_used'] = $user['date'];
					$iplist[$ip]['posts'] = 0;
					$iplist[$ip]['comments'] = 1;
					$iplist[$ip]['shouts'] = 0;
					$totals['comments']++;
				}
			}
		}
		if ($shoutbox) {
		$data = dbquery("SELECT shout_datestamp date, shout_ip ip FROM ".DB_SHOUTBOX." WHERE shout_name='".$userid."'");
		if ($data) {
			while ($user = dbarray($data)) {
				$ip = $user['ip'];
				if (array_key_exists($ip, $iplist)) {
					$iplist[$ip]['count']++;
					$iplist[$ip]['last_used'] = $user['date'];
					$iplist[$ip]['shouts']++;
					$totals['shouts']++;
				} else {
					$iplist[$ip] = array();
					$iplist[$ip]['count'] = 1;
					$iplist[$ip]['first_used'] = $user['date'];
					$iplist[$ip]['last_used'] = $user['date'];
					$iplist[$ip]['posts'] = 0;
					$iplist[$ip]['comments'] = 0;
					$iplist[$ip]['shouts'] = 1;
					$totals['shouts']++;
				}
			}
		}
	}
		$data = dbarray(dbquery("SELECT COUNT(submit_id) submit FROM ".DB_SUBMISSIONS." WHERE submit_user='".$userid."'"));
		if (!empty($data['submit'])) {
			$totals['submissions'] = $data['submit'];
			$total_submissions = $data['submit'];
		} else {
			$total_submissions = 0;
		}
		if (empty($iplist)) {
			echo "<div class='tbl2' align='center'><b>".$locale['uc091'].($total_submissions > 0 ? "<br />".$locale['uc088'].": ".$total_submissions : "")."</b></div>\n";
		} else {
		
			echo "<table align='center' class='tbl-border'>\n";
			echo "\t<tr>\n";
			echo "\t\t<th class='capmain'>#</th>\n";
			echo "\t\t<th class='capmain'>".$locale['uc015']."</th>\n";
			echo "\t\t<th class='capmain'>".$locale['uc083']."</th>\n";
			echo "\t\t<th class='capmain'>".$locale['uc084']."</th>\n";
			echo "\t\t<th class='capmain'>".$locale['uc085']."</th>\n";
			echo "\t\t<th class='capmain'>".$locale['uc086']."</th>\n";
			if ($shoutbox) {
			echo "\t\t<th class='capmain'>".$locale['uc087']."</th>\n"; }
			echo "\t\t<th class='capmain'>".$locale['uc088']."</th>\n";
			echo "\t\t<th class='capmain'>".$locale['uc089']."</th>\n";
			echo "\t</tr>\n";
			$counter = 1;
			foreach ($iplist as $ip => $data) {
				$tbl = $counter % 2 ? 1 : 2;
				echo "\t<tr>\n";
				echo "\t\t<td class='tbl$tbl' align='center'>".$counter++."</td>\n";
				echo "\t\t<td class='tbl$tbl' align='right'>".$ip."</td>\n";
				echo "\t\t<td class='tbl$tbl' align='right'>".showdate("shortdate", $data['first_used'])."</td>\n";
				echo "\t\t<td class='tbl$tbl' align='right'>".showdate("shortdate", $data['last_used'])."</td>\n";
				echo "\t\t<td class='tbl$tbl' align='center'>".$data['posts']."</td>\n";
				echo "\t\t<td class='tbl$tbl' align='center'>".$data['comments']."</td>\n";
				if ($shoutbox) {
				echo "\t\t<td class='tbl$tbl' align='center'>".$data['shouts']."</td>\n"; }
				echo "\t\t<td class='tbl$tbl' align='center'>0</td>\n";
				echo "\t\t<td class='tbl$tbl' align='center'>".$data['count']."</td>\n";
				echo "\t</tr>\n";
			}
			echo "\t<tr>\n";
			echo "\t\t<td class='capmain'>".$locale['uc089']."</td>\n";
			echo "\t\t<td class='capmain'>&nbsp;</td>\n";
			echo "\t\t<td class='capmain'>&nbsp;</td>\n";
			echo "\t\t<td class='capmain'>&nbsp;</td>\n";
			echo "\t\t<td class='capmain' align='center'>".$totals['posts']."</td>\n";
			echo "\t\t<td class='capmain' align='center'>".$totals['comments']."</td>\n";
			if ($shoutbox) {
			echo "\t\t<td class='capmain' align='center'>".$totals['shouts']."</td>\n"; }
			echo "\t\t<td class='capmain' align='center'>".$totals['submissions']."</td>\n";
			echo "\t\t<td class='capmain' align='center'>".array_sum($totals)."</td>\n";
			echo "\t</tr>\n";
			echo "</table>\n";
			
			echo "<center>\n<a href='".USER_CONT."members_ips.php".$aidlink."' title='' class='button'><span>".$locale['uc020']."</span></a>\n</center>\n";
		}
	} else {
		echo "<br /><div class='tbl2' align='center'><b>".$locale['uc090']."</b></div>\n";
	}
}
require_once THEMES."templates/footer.php";
?>