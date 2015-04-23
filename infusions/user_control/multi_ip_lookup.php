<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: multi_ip_lookup.php
| Author: Philip Daly (HobbyMan)
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
add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['uc029']);

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['uc112'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['global_182'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

if (!isset($_REQUEST['user_ip'])) {
 $user_ip = "";

if (isset($_GET['user_ip']) && is_numeric($_GET['user_ip'])) { $user_ip = $_POST['user_ip']; }
include USER_CONT."inc/user_cont_nav.php";
    echo "<form name='ip_lookup' method='post' action='".FUSION_SELF.$aidlink."'>\n";
    echo "<table cellpadding='1' width='80%' border='0' align='center'>\n<tr>\n";
    echo "<td class='tbl2'><b>".$locale['uc015']." ".$locale['uc024']."</b></td>\n";
    echo "</tr><tr>\n";
    echo "<td class='tbl2' align='center'>".$locale['uc075'].$locale['uc026'].": <label><input type='textbox' name='user_ip' class='textbox' maxlength='15' style='width:230px;' /></label></td>\n";
    echo "</tr><tr>\n";
    echo "<td class='tbl2' align='center'><input type='submit' name='ip_lookup' value='Lookup' class='button' /></td>\n";
    echo "</tr>\n</table>\n";
    echo "</form>\n";

 } else {

$query_ip = ($_REQUEST['user_ip']);

if (preg_match( "/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $query_ip)) {

$ip_query = dbarray(dbquery("SELECT blacklist_ip, blacklist_email FROM ".DB_BLACKLIST." WHERE blacklist_ip = '".$query_ip."'"));
$ip_count = (dbcount("(user_ip)", DB_USERS, "user_ip = '".$query_ip."'"));

if (isset($_POST['cancel'])) {
	redirect(FUSION_SELF.$aidlink);
}

if (isset($_POST['banbl'])) {
$error = 0;

if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {

$update_access = dbquery("UPDATE ".DB_UC_SETTINGS." SET uc_access='".stripinput($userdata['user_id'])."', uc_access_date='".stripinput($time)."'");
if(isset($_POST['user_ban'])) {
        $Si['user_ban'] = stripinput_fix($_POST['user_ban']);
        foreach($Si['user_ban'] as $key) {
                $update = dbquery("UPDATE ".DB_USERS." SET user_status='1' WHERE user_id='".$key."'");
                $result = dbquery("INSERT INTO ".DB_SUSPENDS." (suspended_user, suspending_admin, suspend_ip, suspend_date, suspend_reason, suspend_type) VALUES ('".$key."', '".$userdata['user_id']."', '".$userdata['user_ip']."', '".time()."', '".$locale['uc043']."', '1')");      
        }
     }
if(isset($_POST['user_del'])) {
        $Si['user_del'] = stripinput_fix($_POST['user_del']);
        foreach($Si['user_del'] as $key) {
                $update = dbquery("DELETE FROM ".DB_USERS." WHERE user_id='".$key."'");        
                }
             }
if (isset($_POST['bl_this_ip'])) {
        $blacklist_reason = stripinput($_POST['blacklist_reason']);
        $result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_user_id, blacklist_ip, blacklist_reason, blacklist_datestamp) VALUES ('".$userdata['user_id']."', '".$query_ip."', '".$blacklist_reason."', '".time()."')");
     } else { 
	 $bl_this_ip = ''; 
  }
         } else {
		redirect(FUSION_SELF.$aidlink."&amp;user_ip=".$query_ip."&error=1");
     }
	 redirect(FUSION_SELF.$aidlink."&amp;user_ip=".$query_ip."&error=0");
  }
 unset($key);

 $ip_check = dbarray(dbquery("SELECT blacklist_ip FROM ".DB_BLACKLIST." WHERE blacklist_ip = '".$query_ip."'"));
 if ($ip_check['blacklist_ip'] == $query_ip) { $bl_status = $locale['uc035']; $ipstatus = $img_ip_blacklisted; } else { $bl_status = $locale['uc036']; $ipstatus = $img_ip_clear; }
include USER_CONT."inc/user_cont_nav.php";
$country_code = getCountryFromIP($query_ip);
    echo "<form name='banbl' method='post' action='".FUSION_SELF.$aidlink."&amp;user_ip=".$query_ip."'>\n";
    echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n";
    echo "<td class='tbl2' align='center'>".$ipstatus."</td>\n";
    echo "<td class='tbl2' align='center'><a href=\"#\" onclick=\"Popup=window.open('http://www.stopforumspam.com/api?ip=".$query_ip."','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no, width=500,height=100,left=430,top=120'); return false;\">".$img_sfs."</a></td>\n";
    echo "<td class='tbl2'><b>".$query_ip." = ".$country[$country_code]."</b> <img src='".USER_IMGS."user_flags/".strtolower($country_code).".png' alt='' style='border:0;vertical-align:middle' /></td>\n";
    echo "<td class='tbl2'><b>".sprintf($locale['uc034'], $ip_count).$bl_status.$locale['uc032']."</b></td>\n";
    echo "</tr>\n</table>\n";
    
    $no_blacklist = dbquery("SELECT user_ip FROM ".DB_USERS." WHERE user_level > '101' && user_ip = '".$query_ip."'");
    if (dbrows($no_blacklist)) { $check_admin_ip = true; } else { $check_admin_ip = false; }
    
    if ($check_admin_ip) { echo "<div align='center' class='admin-message'><span style='color:red'><b>".$locale['uc046']."</b></span></div>\n"; }
    	    
    echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n";
    echo "<th class='tbl2'><b>".$locale['uc030']."</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc031']."</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc009']."</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc012']."</b></th>\n";
    echo "<th class='tbl2'><b>SFS</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc014']."</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc010']."</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc011']."</b></th>\n";
    echo "<th class='tbl2'><b>P</b></th>\n";
    if ($shoutbox) {
    echo "<th class='tbl2'><b>S</b></th>\n"; }
    echo "<th class='tbl2'><b>C</b></th>\n";
    echo "<th class='tbl2'><b>Sb</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc016']."</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc017']."</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc037']."</b></th>\n";
    echo "<th class='tbl2'><b>".$locale['uc024']."</b></th>\n";
    echo "</tr>\n<tr>\n";

$result = dbquery("SELECT 
						 user_id, 
						 user_name,
						 user_email,
						 user_hide_email, 
						 user_posts, 
						 user_joined, 
						 user_lastvisit,  
						 user_ip, 
						 user_level, 
						 user_status, 
						 user_web, 
						 user_sig 
						 FROM ".DB_USERS." 
						 WHERE 
						 user_ip = '".$query_ip."' 
						 ORDER BY 
						 user_status, 
						 user_joined 
						 ASC
						 ");
						 
		if (dbrows($result) != 0) {
		$no_show = true;
		while ($data = dbarray($result)) {
		if ($shoutbox) {
		$shout_count = (dbcount("(shout_name)", DB_SHOUTBOX, "shout_name = '".$data['user_id']."'")); }
		$comment_count = (dbcount("(comment_name)", DB_COMMENTS, "comment_name = '".$data['user_id']."'"));
		$submission_count = (dbcount("(submit_id)", DB_SUBMISSIONS, "submit_user = '".$data['user_id']."'"));
		$spam_rating = spam_rating($data['user_sig']);
	    $spam_rating += spam_rating($data['user_web']);

		$em_query = dbarray(dbquery("SELECT blacklist_email FROM ".DB_BLACKLIST." WHERE blacklist_email = '".$data['user_email']."'"));
		$rowcolor = $i% 2==0?"tbl1":"tbl2";
	
	echo "<td class='".$rowcolor."' align='center'>".$data['user_id']."</td>\n";
	echo "<td align='center' class='".$rowcolor."'>".($data['user_status'] != 0 ? $img_user_banned : $img_user_clear)."</td>\n";
    echo "<td class='".$rowcolor."'>".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</td>\n";
    if (!strstr($data['user_web'], "http://") && !strstr($data['user_web'], "https://")) {
			$urlprefix = "http://";
		} else {
			$urlprefix = "";
		}
	echo "<td class='".$rowcolor."'>";
		if ($data['user_web']) { echo "<a href='".$urlprefix.$data['user_web']."' title='".$urlprefix.$data['user_web']."' target='_blank'>".$img_user_web."</a>"; }
		echo "</td>\n";
	echo "<td class='".$rowcolor."' align='center'><a href=\"#\" onclick=\"Popup=window.open('http://www.stopforumspam.com/api?email=".$data['user_email']."','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no, width=500,height=100,left=430,top=120'); return false;\">".$img_sfs."</a></td>\n";
    echo "<td class='".$rowcolor." small'>".$data['user_email']."<span style='float:right;'>".($em_query['blacklist_email'] != '' ? $img_email_blacklisted : $img_email_clear)."</span></td>\n";
    echo "<td class='".$rowcolor." small'>".strftime('%d/%m/%Y %H:%M', $data['user_joined']+($settings['timeoffset']*3600))."</td>\n";
    echo "<td class='".$rowcolor." small'>";
    
    if ($data['user_lastvisit'] - $data['user_joined'] < 86400) {
		$lastvisit = "<span style='color:red'>".strftime('%d/%m/%Y %H:%M', $data['user_lastvisit']+($settings['timeoffset']*3600))."</span>";
		} else {
		$lastvisit = strftime('%d/%m/%Y %H:%M', $data['user_lastvisit']+($settings['timeoffset']*3600));
		}
		 if ($data['user_lastvisit'] !='0') {
        echo $lastvisit;
        } else { echo "Not Visited"; }
    echo "</td>\n";
    echo "<td class='".$rowcolor."' align='center'>".$data['user_posts']."</td>\n";
    if ($shoutbox) {
    echo "<td class='".$rowcolor."' align='center'>".$shout_count."</td>\n"; }
    echo "<td class='".$rowcolor."' align='center'>".$comment_count."</td>\n";
    echo "<td class='".$rowcolor."' align='center'>".$submission_count."</td>\n";
    if ($check_admin_ip) {
    echo "<td colspan='2' class='".$rowcolor."' align='center'><i>".$locale['uc099']."</i></td>\n";
    } else {
    if ($data['user_status'] ==0) {
    echo "<td class='".$rowcolor."' align='center'><label><input type='checkbox' name='user_ban[]' value='".$data['user_id']."' /></label></td>\n";
    } else { 
    echo "<td class='".$rowcolor."' align='center'></td>\n";
   }
    echo "<td class='".$rowcolor."' align='center'><label><input type='checkbox' name='user_del[]' value='".$data['user_id']."' /></label><br /></td>\n";
  }
    echo "<td class='".$rowcolor."' align='center'>".$spam_rating."</td>\n";
    echo "<td class='".$rowcolor."' align='center'><a href='".USER_CONT."user_lookup.php".$aidlink."&amp;user_id=".$data['user_id']."' title='".$locale['uc037']."'>".$img_member_view."</a></td>\n";
   $i++;
    
    echo "</tr>\n";
       }
    } else { 
    echo "<tr>\n<td class='admin-message' align='center' colspan='14'><br /><br />".$locale['uc055']."<br /><br /><br /></td>\n</tr>\n";
    $no_show = false;
  }
    echo "</table>\n";
    echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n";
    if (!$check_admin_ip && $no_show) {
    if ($ip_check['blacklist_ip'] != $query_ip) {
    echo "<td class='tbl1' align='center' valign='top'>".$locale['uc070']."<input type='checkbox' name='bl_this_ip' value='1' class='textbox' /></label><br />\n
    ".$locale['uc071']."<textarea name='blacklist_reason' cols='46' rows='3' class='textbox' />".$locale['uc040']."</textarea><br /></td>\n</tr>\n";
       }
	}

    if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	echo "<tr>\n<td class='tbl' align='center'>".$locale['uc111']." <input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n</tr>\n";
   }
    echo "<tr>\n<td class='tbl1' align='center'><input type='submit' name='banbl' onclick=\"return ProcessUsers();\" value='".$locale['uc018']."' class='button' />\n 
    <input type='submit' name='cancel' value='Reset' class='button' /></td>\n";
    echo "</tr>\n</table>\n</form>\n";
       } else { 
    echo "<br /><div align='center'>".$locale['uc078']."</div><br />\n";
    }
  }

require_once THEMES."templates/footer.php";
?>