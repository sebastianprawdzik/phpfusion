<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_control.php
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

add_to_title($locale['global_200'].$locale['uctitle']);

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

if (!isset($_GET['sortby']) || !preg_match("/^[0-9A-Z]$/", $_GET['sortby'])) { $_GET['sortby'] = "$sortby"; }
$users_per_page = (isset($_REQUEST['users_per_page'])) ? $_REQUEST['users_per_page'] : "";
if (!isset($_GET['users_per_page']) || !preg_match("/^[0-9A-Z]$/", $_GET['users_per_page'])) { $_GET['users_per_page'] = "$users_per_page"; }

if (isset($_POST['cancel'])) {
	redirect(FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
}

if (isset($_GET['rowstart']) && isnum($_GET['rowstart'])) {
		$rowstart = $_GET['rowstart'];
	    } else {
		$rowstart = 0;
	}
	
if (isset($_GET['users_per_page']) && isnum($_GET['users_per_page'])) {
		$users_per_page = $_GET['users_per_page'];
	    } else {
		$users_per_page = 20;
	}

if (isset($_POST['user_cont'])) {
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
if (isset($_POST['ip_black'])) {
        $Si['ip_black'] = stripinput_fix($_POST['ip_black']);
        foreach($Si['ip_black'] as $value) {
        $result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_user_id, blacklist_ip, blacklist_reason, blacklist_datestamp) VALUES ('".$userdata['user_id']."', '".$value."', '".$locale['uc040']."', '".time()."')");
                }   
             }
           } else {
		redirect(FUSION_SELF.$aidlink."&error=1");
       }
	  redirect(FUSION_SELF.$aidlink."&error=0"); 
    }

 unset($key);
 unset($value);
 
$view_criteria = ($uc_globalsettings['uc_post_num'] + '1');
if ($sortby == '') { $sortby = "user_id"; }
$user_count = (dbcount("(user_id)", DB_USERS, "user_status = 0 && user_level = '101' && user_posts < '".$view_criteria."'"));
   
include USER_CONT."inc/user_cont_nav.php";
    echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n";
    echo "<td class='tbl2'><b>".sprintf($locale['uc007'], $uc_globalsettings['uc_post_num']).": ".$user_count."</b></td>\n";
    echo "<td class='tbl2' align='right'><b>".$locale['uc006'].":</b></td>\n";
    echo "<td class='tbl2'>\n";
    echo "<form name='users_per_page' method='post' action='".FUSION_SELF.$aidlink."'>\n";
    echo "<select name='users_per_page' class='textbox' onChange='submit()'>\n";
    echo "<option value='20'".($users_per_page == "20" ? " selected" : "").">20</option>\n";
    echo "<option value='40'".($users_per_page == "40" ? " selected" : "").">40</option>\n";
    echo "<option value='60'".($users_per_page == "60" ? " selected" : "").">60</option>\n";
    echo "<option value='80'".($users_per_page == "80" ? " selected" : "").">80</option>\n";
    echo "<option value='100'".($users_per_page == "100" ? " selected" : "").">100</option>\n";
    echo "</select>\n";
	echo "</form>\n</td>\n";
	echo "</tr>\n</table>\n";

$query = dbquery("SELECT 
						 user_id, 
						 user_name,
						 user_email,
						 user_hide_email, 
						 user_posts, 
						 user_joined, 
						 user_lastvisit,  
						 user_ip, 
						 user_status, 
						 user_web, 
						 user_sig  
						 FROM ".DB_USERS." 
						 WHERE user_status = '0'
						 AND user_level = '101'
						 AND user_posts < '".$view_criteria."'  
						 ORDER BY  
						  ".$sortby." DESC LIMIT 
						 $rowstart,$users_per_page
						 ");
						 echo "</form>\n";
if (dbrows($query) != 0) {
    echo "<form id='user_cont' name='user_cont' method='post' action='".FUSION_SELF.$aidlink."'>\n";
    echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n
    <th class='tbl1'>#</th>\n
    <th class='tbl1'><b>".$locale['uc009']."</b></th>\n
    <th class='tbl1'><b>SFS</b></th>\n
    <th class='tbl1'><b>".$locale['uc010']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc011']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc012']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc013']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc054']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc015']."</b></th>\n
    <th class='tbl1'><b>P</b></th>\n";
    if ($shoutbox) {
    echo "<th class='tbl1'><b>S</b></th>\n";
    }
    echo "<th class='tbl1'><b>C</b></th>\n
    <th class='tbl1'><b>Sm</b></th>\n
    <th class='tbl1'><b>".$locale['uc062']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc016']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc017']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc038']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc024']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc037']."</b></th>\n";
    echo "</tr>\n";
    while ($data = dbarray($query)) {
		$rowcolor = $i% 2==0?"tbl1":"tbl2";
		if (!strstr($data['user_web'], "http://") && !strstr($data['user_web'], "https://")) {
			$urlprefix = "http://";
		} else {
			$urlprefix = "";
		}
		
		$spam_rating = spam_rating($data['user_sig']);
	    $spam_rating += spam_rating($data['user_web']);

		echo "<tr>\n<td class='".$rowcolor."' align='center'>".$data['user_id']."</td>\n";
		echo "<td class='".$rowcolor."'>".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</td>\n";
		echo "<td class='".$rowcolor."' align='center'><a href=\"#\" onclick=\"Popup=window.open('http://www.stopforumspam.com/api?username=".$data['user_name']."&ip=".$data['user_ip']."&email=".$data['user_email']."','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no, width=500,height=100,left=430,top=120'); return false;\">".$img_sfs."</a></td>\n";
		echo "<td class='".$rowcolor." small' align='center'>".strftime('%d/%m/%Y %H:%M', $data['user_joined']+($settings['timeoffset']*3600))."</td>\n";
		echo "<td class='".$rowcolor." small' align='center'>";
		
		if ($data['user_lastvisit'] - $data['user_joined'] < 86400) {
		$lastvisit = "<span style='color:red'>".strftime('%d/%m/%Y %H:%M', $data['user_lastvisit']+($settings['timeoffset']*3600))."</span>";
		} else {
		$lastvisit = strftime('%d/%m/%Y %H:%M', $data['user_lastvisit']+($settings['timeoffset']*3600));
		}
		
        if ($data['user_lastvisit'] !='0') {
        echo $lastvisit;
        } else { echo $locale['uc022']; }
        
        echo "</td>\n";
		echo "<td class='".$rowcolor."' align='center'>";
		if ($data['user_web']) { echo "<a href='".$urlprefix.$data['user_web']."' title='".$urlprefix.$data['user_web']."' target='_blank'>".$img_user_web."</a>"; }
		echo "</td>\n";
		echo "<td class='".$rowcolor."' align='center'>".($data['user_hide_email'] == 1 ? $img_hidden : $img_public)."</td>\n";
		echo "<td class='".$rowcolor."' align='center'>";
		if ($data['user_sig'] !='') { echo "<a href='".USER_CONT."user_lookup.php".$aidlink."&amp;user_id=".$data['user_id']."' title='".$locale['uc054']."'>".$img_tick."</a>"; }
		echo "</td>\n";
		if ($shoutbox) {
		$shout_count = (dbcount("(shout_name)", DB_SHOUTBOX, "shout_name = '".$data['user_id']."'")); } else { $shout_count = '0'; }
		$comment_count = (dbcount("(comment_name)", DB_COMMENTS, "comment_name = '".$data['user_id']."'"));
		$submission_count = (dbcount("(submit_id)", DB_SUBMISSIONS, "submit_user = '".$data['user_id']."'"));
		$getips = dbquery("SELECT 
		                          COUNT(user_ip) 
		                          count, 
		                          user_ip ip 
		                          FROM ".DB_USERS." 
		                          WHERE user_ip = '".$data['user_ip']."'
		                          ");
		
		while ($ip = dbarray($getips)) {
		if ($ip['count'] > 1) { $ipcount = "<span class='small' style='float:right;'><font color='red'>[x".$ip['count']."]</font></span>"; } 
		else { $ipcount = ""; }
		}
		if ($spam_rating >= $uc_globalsettings['uc_spamrate']) { 
		$show_srate = "<a href='".USER_CONT."user_lookup.php".$aidlink."&amp;user_id=".$data['user_id']."' title='".$locale['uc037']."'>".$img_spam."</a>"; $check = "checked"; }
		else { $show_srate = $spam_rating; $check = "";}
		 $ip_check = dbarray(dbquery("SELECT blacklist_ip FROM ".DB_BLACKLIST." WHERE blacklist_ip = '".$data['user_ip']."'"));
// Countries
		 $country_code = getCountryFromIP($data['user_ip']);
		echo "<td class='".$rowcolor." small'><img src='".USER_IMGS."user_flags/".strtolower($country_code).".png' alt='' style='border:0;vertical-align:middle' />\n";
		echo "<a href='".USER_CONT."user_lookup.php".$aidlink."&amp;user_id=".$data['user_id']."' title='".$locale['uc024']." ".$data['user_ip']."'>".$country[$country_code]."</a> ".$ipcount."</td>\n";
	
		echo "<td class='".$rowcolor."' align='center'>".$data['user_posts']."</td>\n";
		if ($shoutbox) {
        echo "<td class='".$rowcolor."' align='center'>".$shout_count."</td>\n"; }
        echo "<td class='".$rowcolor."' align='center'>".$comment_count."</td>\n"; 
        echo "<td class='".$rowcolor."' align='center'>".$submission_count."</td>\n";
        echo "<td class='".$rowcolor."' align='center'>";
        if ($data['user_posts'] + $shout_count + $comment_count + $submission_count !='0') {
        echo "<a href='".USER_CONT."user_lookup.php".$aidlink."&amp;user_id=".$data['user_id']."' title='".$locale['uc062']."'>".$img_content."</a>"; }
        echo "</td>\n";
		echo "<td class='".$rowcolor."' align='center'><label><input type='checkbox' name='user_ban[]' value='".$data['user_id']."' ".$check." /></label><br /></td>\n";
		echo "<td class='".$rowcolor."' align='center'>";
		if ($data['user_posts'] + $shout_count + $comment_count + $submission_count =='0') {
		echo "<label><input type='checkbox' name='user_del[]' value='".$data['user_id']."' /></label>"; } else { echo $img_user_content; }
		echo "</td>\n"; 
		echo "<td class='".$rowcolor."' align='center'>";
		$ip_count = (dbcount("(user_ip)", DB_USERS, "user_ip = '".$data['user_ip']."'"));
		if($ip_check['blacklist_ip'] != $data['user_ip']) {
		if ($ip_count == '1') {
		echo "<label><input type='checkbox' name='ip_black[]' value='".$data['user_ip']."' ".$check."/></label>\n"; } else { echo $img_right; }
		} else { echo $img_ip_blacklisted; }
		echo "</td>\n";
		echo "<td class='".$rowcolor."' align='center'>";
		if ($ip_count > '1') { echo "<a href='".USER_CONT."multi_ip_lookup.php".$aidlink."&amp;user_ip=".$data['user_ip']."' title='".$locale['uc029']."'>".$img_lookup."</a>"; 
		} else { echo "<a href='".USER_CONT."multi_ip_lookup.php".$aidlink."&amp;user_ip=".$data['user_ip']."' title='".$locale['uc047']."'>".$img_member_view."</a>"; }
		echo "</td>\n";
		echo "<td class='".$rowcolor."' align='center'>".$show_srate."</td>\n";
		$i++;
     }
	 echo "</tr>\n</table>\n";

	 echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n";

$admin_password = isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "";
if (!check_admin_pass($admin_password)) {
	echo "<td class='tbl' align='center'>".$locale['uc111']." <input type='password' name='admin_password' value='".$admin_password."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n<tr>\n";
  }
     echo "<td class='tbl' align='center'><input type='submit' name='user_cont' onclick=\"return ProcessUsers();\" value='".$locale['uc018']."' class='button' />\n 
      <input type='submit' name='cancel' value='".$locale['uc020']."' class='button' /></td>\n";
     echo "</tr>\n</table>\n</form>";
  }

if ($user_count > $users_per_page)  { echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($rowstart,$users_per_page,$user_count,3,FUSION_SELF.$aidlink."&amp;sortby=".$_GET['sortby']."&amp;users_per_page=".$users_per_page."&amp;")."\n</div>\n"; }
   
require_once THEMES."templates/footer.php";
?>