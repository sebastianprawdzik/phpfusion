<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: group_by_ip.php
| Author: Philip Daly (HobbyMan)
| Version: v1.1
| Using coding by Nicolae Crefelean (Kneekoo)
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
add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['uc028']);

if (isset($_GET['rowstart']) && isnum($_GET['rowstart'])) {
		$rowstart = $_GET['rowstart'];
	    } else {
		$rowstart = 0;
	}

 include USER_CONT."inc/user_cont_nav.php";

   $getips = dbquery("SELECT 
                             COUNT(user_ip) 
                             count, user_ip ip 
                             FROM ".DB_USERS." 
                             WHERE user_level = '101' 
                             GROUP BY user_ip 
                             ORDER BY count 
                             DESC LIMIT $rowstart,$post_threshold
                             ");

    echo "<table cellpadding='1' width='80%' border='0' align='center'>\n<tr>\n";
    echo "<th class='tbl2'><b>".$locale['uc028']."</b></th>\n";
	echo "</tr>\n</table>\n";
   $tbl = 1;
   $ipcount = "0";
   
   echo "<table class='tbl-border' align='center' cellspacing='1' cellpadding='4'>\n";
   echo "<tr>\n";
   echo "<th class='tbl2'>".$locale['uc098']."</th>\n";
   echo "<th class='tbl2'>".$locale['uc026']."</th>\n";
   echo "<th class='tbl2'>".$locale['uc076']."</th>\n";
   echo "<th class='tbl2'>".$locale['uc029']."</th>\n";
   echo "<th class='tbl2'>".$locale['uc031']."</th>\n";
   echo "</tr>\n";
   
   while ($ip = dbarray($getips)) {
   
   $ip_check = dbarray(dbquery("SELECT blacklist_ip FROM ".DB_BLACKLIST." WHERE blacklist_ip = '".$ip['ip']."'"));
   $country_code = getCountryFromIP($ip['ip']);
   
   if ($ip['count'] > '1') {
   echo "<tr>\n";
   echo "<td class='tbl$tbl'><img src='".USER_IMGS."user_flags/".strtolower($country_code).".png' alt='' style='border:0;vertical-align:middle' /> ".$country[$country_code]."</td>\n";
   echo "<td class='tbl$tbl'><a href=\"#\" onclick=\"Popup=window.open('http://www.stopforumspam.com/api?ip=".$ip['ip']."','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no, width=500,height=100,left=430,top=120'); return false;\">".$ip['ip']."</a></td>\n";
   echo "<td class='tbl$tbl' align='center'>".$ip['count']."</td>\n";
   echo "<td class='tbl$tbl' align='center'><a href='".USER_CONT."multi_ip_lookup.php".$aidlink."&amp;user_ip=".$ip['ip']."' title='".$locale['uc024']."'>".$locale['uc024']."</a></td>\n";
   if ($ip_check['blacklist_ip'] == $ip['ip']) { $status = $locale['uc032']; } else { $status = $locale['uc033']; }
   echo "<td class='tbl$tbl small' align='center'>".$status."</td>\n";
   echo "</tr>\n"; 
   $ipcount++;
        }
   $tbl = $tbl == 1 ? 2 : 1;
         }

echo "</table>\n<br />\n";
    if (!$ipcount) { echo "<div align='center'>".$locale['uc097']."</div>\n"; }
if ($ipcount > $post_threshold) echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($rowstart,$post_threshold,$ipcount,3,FUSION_SELF.$aidlink."&amp;post_threshold=".$post_threshold."&amp;")."\n</div>\n";
 
require_once THEMES."templates/footer.php";
?>