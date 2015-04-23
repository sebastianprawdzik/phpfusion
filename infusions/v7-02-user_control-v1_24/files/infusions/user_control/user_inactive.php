<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_inactive.php
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
add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['iu003']);

add_to_head("<script type='text/javascript'>
checked=false;
function checkedAll (inuser_del) {
	var aa= document.getElementById('inuser_del');
	 if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 aa.elements[i].checked = checked;
	}
      }
</script>");

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

if (isset($_POST['cancel'])) {
	redirect(FUSION_SELF.$aidlink);
}

if (isset($_GET['rowstart']) && isnum($_GET['rowstart'])) {
		$rowstart = $_GET['rowstart'];
	    } else {
		$rowstart = 0;
	}

if (isset($_POST['inuser_del'])) {
$error = 0;
if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
$update_access = dbquery("UPDATE ".DB_UC_SETTINGS." SET uc_access='".stripinput($userdata['user_id'])."', uc_access_date='".stripinput($time)."'");
if(isset($_POST['user_del'])) {
        $Si['user_del'] = stripinput_fix($_POST['user_del']);
        foreach($Si['user_del'] as $key) {
                $update = dbquery("DELETE FROM ".DB_USERS." WHERE user_id='".$key."'");        
            }
         }
	   } else {
		redirect(FUSION_SELF.$aidlink."&amp;time_limit=".$_GET['time_limit']."&error=1");
    }
	  redirect(FUSION_SELF.$aidlink."&amp;time_limit=".$_GET['time_limit']."&error=0");
 } unset($key);

$total_inactive = (dbcount("(user_id)", DB_USERS, "user_status = 0 && user_level = '101' && user_posts ='0'")); 
$time_limit = (isset($_REQUEST['time_limit'])) ? $_REQUEST['time_limit'] : "";
   if (!isset($_GET['time_limit']) || !preg_match("/^[0-9A-Z]$/", $_GET['time_limit'])) { $_GET['time_limit'] = "$time_limit"; }
   
include USER_CONT."inc/user_cont_nav.php";
    echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n";
    echo "<td class='tbl2'><b>".$locale['iu001'].$total_inactive."</b></td>\n";
    echo "<td class='tbl2' align='right'><b>".$locale['iu013'].":</b></td>\n";
    echo "<td class='tbl2'>";
    echo "<form name='time_limit' method='post' action='".FUSION_SELF.$aidlink."'>\n";
    echo "<select name='time_limit' class='textbox' onChange='submit()'>\n";
    echo "                <option value='0'".($time_limit == "0" ? " selected" : "").">".$locale['uc022']."</option>\n";
    echo "        <option value='86400'".($time_limit == "86400" ? " selected" : "").">".$locale['iu004']."</option>\n";
    echo "    <option value='1209600'".($time_limit == "1209600" ? " selected" : "").">".$locale['iu005']."</option>\n";
    echo "    <option value='2592000'".($time_limit == "2592000" ? " selected" : "").">".$locale['iu006']."</option>\n";
    echo "  <option value='15552000'".($time_limit == "15552000" ? " selected" : "").">".$locale['iu007']."</option>\n";
    echo "  <option value='31536000'".($time_limit == "31536000" ? " selected" : "").">".$locale['iu008']."</option>\n";
    echo "  <option value='63072000'".($time_limit == "63072000" ? " selected" : "").">".$locale['iu009']."</option>\n";
    echo "  <option value='94608000'".($time_limit == "94608000" ? " selected" : "").">".$locale['iu010']."</option>\n";
    echo "<option value='126144000'".($time_limit == "126144000" ? " selected" : "").">".$locale['iu011']."</option>\n";
    echo "<option value='157680000'".($time_limit == "157680000" ? " selected" : "").">".$locale['iu012']."</option>\n";
    echo "</select>\n";
	echo "</form>\n</td>\n";
	echo "</tr>\n</table>\n";

if ($time_limit == "") { $time_limit = "0"; }
$user_count_locale = array(
	       0 => $locale['uc022'], 
	   86400 => $locale['iu004'], 
	 1209600 => $locale['iu016'].$locale['iu005'],
	 2592000 => $locale['iu016'].$locale['iu006'],
	15552000 => $locale['iu016'].$locale['iu007'],
	31536000 => $locale['iu016'].$locale['iu008'],
	63072000 => $locale['iu016'].$locale['iu009'],
	94608000 => $locale['iu016'].$locale['iu010'],
   126144000 => $locale['iu016'].$locale['iu011'],
   157680000 => $locale['iu016'].$locale['iu012']
);

if ($time_limit =='0') {
$where = "WHERE user_lastvisit = '0'";
$user_count = (dbcount("(user_id)", DB_USERS, "user_status = 0 && user_level = '101' && user_posts = '0' && user_lastvisit ='0'")); 
$orderby = "user_joined";
} elseif ($time_limit =='86400')  {
$where = "WHERE user_lastvisit - user_joined <= ".$time_limit."";
$user_count = (dbcount("(user_id)", DB_USERS, "user_status = 0 && user_level = '101' && user_posts = '0' && user_lastvisit - user_joined <= '86400'")); 
$orderby = "user_lastvisit";
} else {
$where = "WHERE user_lastvisit < ($time - $time_limit) AND user_lastvisit != ''"; 
$user_count = (dbcount("(user_id)", DB_USERS, "user_status = 0 && user_level = '101' && user_posts = '0' && user_lastvisit < ($time - $time_limit)")); 
}
             
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
						 ".$where."
						 AND user_status = '0'
						 AND user_level = '101' 
						 AND user_posts = '0'
						 AND 
						 user_id NOT IN (SELECT comment_name FROM ".DB_COMMENTS." WHERE comment_name = user_id)
						 ORDER BY 
						 ".$sortby." 
						 ASC LIMIT 
						 $rowstart,20
						 ");
						 echo "</form>\n";
if (dbrows($query) != 0) {
	$placing = ($rowstart+1);
    echo "<form id='inuser_del' name='inuser_del' method='post' action='".FUSION_SELF.$aidlink."&amp;time_limit=".$_GET['time_limit']."'>\n";
    echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n";
    echo "<td class='tbl2'><b>".$locale['iu002']."</b></td><td class='tbl2' align='center'><b>".$user_count_locale[$time_limit].": ".$user_count."</b></td>\n";
    echo "</tr>\n</table>\n";
    echo "<hr />\n";
    echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n
    <th class='tbl1'>#</th>\n
    <th class='tbl1'><b>".$locale['uc009']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc010']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc011']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc012']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc013']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc054']."</b></th>\n
    <th class='tbl1' colspan='2'><b>".$locale['uc015']."</b></th>\n
    <th class='tbl1'><b>".$locale['uc017']."</th>\n";
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

		echo "<tr>\n<td class='".$rowcolor."' align='center'>".$placing."</td>\n";
		echo "<td class='".$rowcolor."'>".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</td>\n";
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
		echo "<td class='".$rowcolor."'>";
		if ($data['user_web']) { echo "<a href='".$urlprefix.$data['user_web']."' title='".$urlprefix.$data['user_web']."' target='_blank'>".trimlink($urlprefix.$data['user_web'],20)."</a>"; }
		echo "</td>\n";
		echo "<td class='".$rowcolor."'><a href='mailto:".$data['user_email']."' title='".$data['user_email']."'>".trimlink($data['user_email'],18)."</a>\n";
		echo "<span style='float:right;'>".($data['user_hide_email'] == 1 ? $img_hidden : $img_public)."</span></td>\n";
		echo "<td class='".$rowcolor."' align='center'>";
		if ($data['user_sig'] !='') { echo "<a href='".USER_CONT."user_lookup.php".$aidlink."&amp;user_id=".$data['user_id']."' title='".$locale['uc054']."'>".$img_tick."</a>"; }
		echo "</td>\n";
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
		 $ip_check = dbarray(dbquery("SELECT blacklist_ip FROM ".DB_BLACKLIST." WHERE blacklist_ip = '".$data['user_ip']."'"));
		   if ($ip_check['blacklist_ip'] == $data['user_ip']) { $ipstatus = $img_ip_blacklisted; } else { $ipstatus = $img_ip_clear; }
		echo "<td class='".$rowcolor."' align='center'>".$ipstatus."</td>\n";
		 $country_code = getCountryFromIP($data['user_ip']);
		echo "<td class='".$rowcolor."'><img src='".USER_IMGS."user_flags/".strtolower($country_code).".png' alt='' style='border:0;vertical-align:middle' /> <a href='".USER_CONT."multi_ip_lookup.php".$aidlink."&amp;user_ip=".$data['user_ip']."' title='".$data['user_ip']."'>".$country[$country_code]."</a> ".$ipcount."</td>\n";
		echo "<td class='".$rowcolor."' align='center'><label><input type='checkbox' name='user_del[]' value='".$data['user_id']."' /></label></td>\n"; 
		$placing = $placing+1;
		$i++;
     }
        echo "</tr>\n</table>";
        echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n"; 
        echo "<td class='tbl1' align='right'>".$locale['iu014']."</td>";
        echo "<td class='tbl1' align='center'><input type='checkbox' name='checkall' onclick='checkedAll(inuser_del);'></td>\n";
	    echo "</tr>\n</table>\n";
	 echo "<table cellpadding='1' width='100%' border='0'>\n<tr>\n";
	 if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	 echo "<td class='tbl' align='center'>".$locale['uc111']." <input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
	 echo "</tr>\n<tr>\n";
}
     echo "<td class='tbl2' align='center'><input type='submit' name='inuser_del' onclick=\"return ProcessUsers();\" value='".$locale['uc018']."' class='button' />\n 
      <input type='submit' name='cancel' value='".$locale['uc020']."' class='button' /></td>\n";
     echo "</tr>\n</table>\n</form>";
  } else { echo "<br /><div align='center'>".$locale['iu015']."</div><br />\n";}

if ($user_count > 20) { echo "<div align='center' style=';margin-top:5px;'>\n
".makepagenav($rowstart,20,$user_count,3,FUSION_SELF.$aidlink."&amp;time_limit=".$_GET['time_limit']."&amp;users_per_page=20&amp;")."\n</div>\n"; }

require_once THEMES."templates/footer.php";
?>