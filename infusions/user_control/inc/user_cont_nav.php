<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_cont_nav.php
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

   echo "<table class='tbl' cellspacing='0' cellpadding='0' border='0' align='center' width='100%'>\n<tr>\n";
   echo "<th class='tbl2' width='1%'><a href='".USER_CONT."control_index.php".$aidlink."' title='".$locale['uctitle']."'>".$img_home."</a></th><th class='tbl2'><strong>".$img_logo."</strong></th>\n";
   echo "</tr>\n</table>\n";

   echo "<table class='tbl' cellspacing='0' cellpadding='0' border='0' align='center' width='100%'>\n<tr>\n";
   if ($show_icons) {
   echo "<th class='tbl1'><a href='".USER_CONT."user_control.php".$aidlink."' title='".$locale['uctitle']."'>".$img_user_control."</a></th>\n";
   echo "<th class='tbl1'><a href='".USER_CONT."group_by_ip.php".$aidlink."' title='".$locale['uc028']."'>".$img_group_by_ip."</a></th>\n";
   echo "<th class='tbl1'><a href='".USER_CONT."multi_ip_lookup.php".$aidlink."' title='".$locale['uc029']."'>".$img_ip_lookup."</a></th>\n";
   echo "<th class='tbl1'><a href='".USER_CONT."members_ips.php".$aidlink."' title='".$locale['uc050']."'>".$img_members_ips."</a></th>\n";
   echo "<th class='tbl1'><a href='".USER_CONT."user_lookup.php".$aidlink."' title='".$locale['uc030']." ".$locale['uc024']."'>".$img_user_id_lookup."</a></th>\n";
   echo "<th class='tbl1'><a href='".USER_CONT."user_inactive.php".$aidlink."' title='".$locale['iu003']."'>".$img_inactive_user."</a></th>\n";
   echo "<th class='tbl1'><a href='".USER_CONT."month_stats.php".$aidlink."' title='".$locale['uc113']."'>".$img_month_stats."</a></th>\n";
   if (iSUPERADMIN) {
   echo "<th class='tbl1'><a href='".USER_CONT."admin_rights.php".$aidlink."' title='".$locale['uc079']."'>".$img_admin_rights."</a></th>\n";
   echo "<th class='tbl1'><a href='".USER_CONT."settings.php".$aidlink."' title='".$locale['uc080']."'>".$img_settings."</a></th>\n"; 
   } else {
   echo "<th class='tbl1'>".$img_admin_rights."</th>\n";
   echo "<th class='tbl1'>".$img_settings."</th>\n"; }
   echo "<th class='tbl1'><a href='".USER_CONT."legend.php".$aidlink."' title='".$locale['uc063']."'>".$img_legend."</a></th>\n";
   echo "</tr>\n<tr>\n";
   }
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."user_control.php".$aidlink."' title='".$locale['uctitle']."'>".$locale['uctitle']."</a></td>\n";
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."group_by_ip.php".$aidlink."' title='".$locale['uc028']."'>".$locale['uc028']."</a></td>\n";
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."multi_ip_lookup.php".$aidlink."' title='".$locale['uc029']."'>".$locale['uc029']."</a></td>\n";
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."members_ips.php".$aidlink."' title='".$locale['uc050']."'>".$locale['uc050']."</a></td>\n";
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."user_lookup.php".$aidlink."' title='".$locale['uc030']." ".$locale['uc024']."'>".$locale['uc030']." ".$locale['uc024']."</a></td>\n";
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."user_inactive.php".$aidlink."' title='".$locale['iu003']."'>".$locale['iu003']."</a></td>\n";
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."month_stats.php".$aidlink."' title='".$locale['uc113']."'>".$locale['uc113']."</a></td>\n";
   if (iSUPERADMIN) {
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."admin_rights.php".$aidlink."' title='".$locale['uc079']."'>".$locale['uc079']."</a></td>\n";
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."settings.php".$aidlink."' title='".$locale['uc080']."'>".$locale['uc080']."</a></td>\n"; 
   } else {
   echo "<td class='tbl1' align='center'>".$locale['uc079']."</td>\n";
   echo "<td class='tbl1' align='center'>".$locale['uc080']."</td>\n"; }   
   echo "<td class='tbl1' align='center'><a href='".USER_CONT."legend.php".$aidlink."' title='".$locale['uc063']."'>".$locale['uc063']."</a></td>\n";
   echo "</tr>\n</table>\n";

?>