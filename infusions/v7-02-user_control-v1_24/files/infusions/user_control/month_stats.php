<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2012 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: month_stats.php
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
require_once INCLUDES."infusions_include.php";
require INFUSIONS."user_control/inc/functions_include.php";
add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['uc113']);
include USER_CONT."inc/user_cont_nav.php";

$u1_data = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='1'"));
$month = date('m', $u1_data['user_joined']);
$year = date('Y', $u1_data['user_joined']);

$launch_date = dbarray(dbquery("SELECT user_joined FROM ".DB_USERS." WHERE user_id = '1'"));

   echo "<br />\n<table align='center' width='100%' cellpadding='1' cellspacing='1' class='tbl-border'>\n<tr>\n";
   echo "<td align='center' class='tbl1'><b>".$locale['ci34']."</b> ".strftime('%d/%m/%Y %H:%M', $launch_date['user_joined']+($settings['timeoffset']*3600))."</td>\n";
   echo "</tr>\n</table>\n";

     echo "<br />\n<table align='center' width='100%' cellpadding='1' cellspacing='1' class='tbl-border'>\n<tr>\n";
     echo "<th><b>".$locale['ci31']."</b></th>\n";
     echo "<th><b>".$locale['ci14']."</b></th>\n";
     echo "<th><b>".$locale['ci18']."</b></th>\n";
     echo "<th><b>".$locale['ci35']."</b></th>\n</tr>\n";
$count = 0;
for($i=0; ;$i++)
{
  if(date("U", mktime (0, 0, 0,  ($month+$i), 1, $year))>date("U")) break;
  $rows = dbrows(dbquery("SELECT 
                               user_joined, 
                               user_status 
                               FROM ".DB_USERS." 
                               WHERE user_joined>='".date("U", mktime (0, 0, 0,  ($month+$i), 1, $year))."' 
                               AND user_joined<'".date("U", mktime (0, 0, 0,  ($month+($i+1)), 1, $year))."'
                               "));
  $count = $count+$rows;
  echo "<tr>\n<td class='tbl1' align='center'>".date("m / Y", mktime (0, 0, 0,  ($month+$i), 1, $year))."</td>\n";
  echo "<td class='tbl1' align='center'>".$rows." ".($rows==1 ? $locale['ci32'] : $locale['ci33'])."</td>\n";
  echo "<td class='tbl1' align='center'>".$count." ".($count==1 ? $locale['ci32'] : $locale['ci33'])."</td>\n";
  echo "<td class='tbl1' align='center'>";
  if ($rows > '30') { echo round($rows/30); } else { echo "<small>".$locale['ci36']."</small>"; }
  echo "</td>\n";
  echo "</tr>";
}
echo "</table>\n<br />";
      
require_once THEMES."templates/footer.php";
?>