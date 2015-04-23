<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings.php
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

add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['uc080']);
include USER_CONT."inc/user_cont_nav.php";
$time = time();
if (isset($_POST['savesettings'])) {

$update = dbquery("UPDATE ".DB_UC_SETTINGS." SET 
                   uc_show_icons='".stripinput($_POST['uc_show_icons'])."',
                   uc_view='".stripinput($_POST['uc_view'])."',
                   uc_spamrate='".stripinput($_POST['uc_spamrate'])."',
                   uc_post_num='".stripinput($_POST['uc_post_num'])."',
                   uc_access='".stripinput($userdata['user_id'])."',
                   uc_access_date='".stripinput($time)."',
                   uc_update='".stripinput($userdata['user_id'])."',
                   uc_update_date='".stripinput($time)."'
                   ");             
                   
	redirect(FUSION_SELF.$aidlink);
}

$get_user = dbarray(dbquery("SELECT user_id, user_name, user_status FROM ".DB_USERS." WHERE user_id = '".$uc_globalsettings['uc_update']."'"));

opentable($locale['uctitle'].$locale['global_200'].$locale['uc080']);

echo "<div align='center' class='tbl'>".$locale['uc102'].profile_link($uc_globalsettings['uc_update'], $get_user['user_name'], $get_user['user_status']).$locale['uc103'].strftime('%d/%m/%Y %H:%M', $uc_globalsettings['uc_update_date']+($settings['timeoffset']*3600))."</div>\n";

  echo "<form name='savesettings' method='post' action='".FUSION_SELF.$aidlink."'>\n";
  echo "<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
  echo "<th class='tbl2' colspan='3'><b>".$locale['uc080']."</b></th>\n";
  echo "</tr>\n<tr>\n";
  // Show Icons
  echo "<td class='tbl1' align='right' valign='top'><b>".$locale['uc114']."</b></td>";
  echo "<td class='tbl1' valign='top'>";
  echo "<label><select name='uc_show_icons' class='textbox'>\n";
  echo "<option value='0'(".($uc_globalsettings['uc_show_icons'] == '0' ? " selected" : "").">".$locale['uc116']."</option>\n";
  echo "<option value='1'(".($uc_globalsettings['uc_show_icons'] == '1' ? " selected" : "").">".$locale['uc115']."</option>\n";
  echo "</select></label></td>\n";
  echo "</tr>\n<tr>\n";
  // View Users by... 
  echo "<td class='tbl1' align='right' valign='top'><b>".$locale['uc110']."</b></td>";
  echo "<td class='tbl1' valign='top'>";
  echo "<label><select name='uc_view' class='textbox'>\n";
  echo "<option value='0'(".($uc_globalsettings['uc_view'] == '0' ? " selected" : "").">".$locale['uc107']."</option>\n";
  echo "<option value='1'(".($uc_globalsettings['uc_view'] == '1' ? " selected" : "").">".$locale['uc108']."</option>\n";
  echo "<option value='2'(".($uc_globalsettings['uc_view'] == '2' ? " selected" : "").">".$locale['uc109']."</option>\n";
  echo "</select></label></td>\n";
  echo "</tr>\n<tr>\n";
  // Spam Rate
  echo "<td class='tbl1' align='right' valign='top'><b>".$locale['uc037'].":</b></td>";
  echo "<td class='tbl1' valign='top'>";
  echo "<label><input type='text' name='uc_spamrate' value='".$uc_globalsettings['uc_spamrate']."' maxlength='5' class='textbox' style='width:40px;' /></label></td>\n";
  echo "</tr>\n<tr>\n";
  // Post Threshold
  echo "<td class='tbl1' align='right' valign='top'><b>".$locale['uc104'].":</b></td>";
  echo "<td class='tbl1' valign='top'>";
  echo "<label><input type='text' name='uc_post_num' value='".$uc_globalsettings['uc_post_num']."' maxlength='5' class='textbox' style='width:40px;' /></label></td>\n";
  echo "</tr>\n<tr>\n";
  echo "<td colspan='3' align='center'>";
  echo "<br /><input type='submit' name='savesettings' value='".$locale['uc101']."' class='button'>";
  echo "</td>\n</tr>\n</table>\n</form>\n";

closetable();
echo $uc_footer;
require_once THEMES."templates/footer.php";
?>