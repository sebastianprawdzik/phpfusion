<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2012 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: legend.php
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
include USER_CONT."inc/user_cont_nav.php";
add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['uc063']);

     opentable($locale['uc063']);
     echo "<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='80%'>\n<tr>\n";
     echo "<th class='tbl1'>".$locale['uc064']."</th>";
     echo "<th class='tbl1'>".$locale['uc065']."</th>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl2' align='center'>".$img_user_clear."</td><td class='tbl2'>".$locale['leg12']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl1' align='center'>".$img_user_banned."</td><td class='tbl1'>".$locale['leg13']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl2' align='center'>".$img_public."</td><td class='tbl2'>".$locale['leg02']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl1' align='center'>".$img_hidden."</td><td class='tbl1'>".$locale['leg03']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl2' align='center'>".$img_email_clear."</td><td class='tbl2'>".$locale['leg04']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl1' align='center'>".$img_email_blacklisted."</td><td class='tbl1'>".$locale['leg05']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl2' align='center'>".$img_ip_blacklisted."</td><td class='tbl2'>".$locale['leg06']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl1' align='center'>".$img_ip_clear."</td><td class='tbl1'>".$locale['leg07']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl2' align='center'>".$img_lookup."</td><td class='tbl2'>".$locale['uc029']." ".$locale['leg01']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl1' align='center'>".$img_spam."</td><td class='tbl1'>".$locale['leg08']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl2' align='center'>".$img_right."</td><td class='tbl2'>".$locale['leg09']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl1' align='center'>".$img_tick."</td><td class='tbl1'>".$locale['leg10']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl2' align='center'>".$img_content."</td><td class='tbl2'>".$locale['leg11']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl1' align='center'>".$img_user_content."</td><td class='tbl1'>".$locale['leg14']."</td>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl2' colspan='2' align='center'>".$locale['leg15']."</td>";
     echo "<tr>\n</tr>\n</table>\n";
     echo "<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='80%'>\n<tr>\n";
     echo "<th class='tbl2'>".$locale['leg16']."</th>";
     echo "<tr>\n</tr>\n";
     echo "<td class='tbl1'>".$locale['leg17']."</td>";
     echo "<tr>\n</tr>\n";
     echo "</table>\n";
     echo "<br />\n<div class='admin-message' align='center'><strong>".$locale['uc021']."</strong></div>\n<br />\n";	
     closetable();
     
 echo $uc_footer;
 
     $data_v = dbarray(dbquery("SELECT inf_version FROM ".DB_INFUSIONS." WHERE inf_title='".$locale['uctitle']."'"));
     $version = $data_v['inf_version'];
     echo "<!-- Version Checker 2.0.0 @ http://version.starefossen.com - Copyright Starefossen 2007-2011 -->";
     echo "<br /><center><script type='text/javascript' src='http://version.starefossen.com/infusions/version_updater/checker/js.php?ps=ucont&amp;v=".$version."'></script></center><br />\n";
     echo "<noscript><a href='http://version.starefossen.com/' target='_blank'><strong>JavaScript disabled:</strong> Check version manually!</a></noscript>";
     
require_once THEMES."templates/footer.php";
?>