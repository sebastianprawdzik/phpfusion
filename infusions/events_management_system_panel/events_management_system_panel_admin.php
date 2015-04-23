<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Infusion: Event Management System
| Filename: events_management_system_panel_admin.php
| Developer: Ankur Thakur
| Author: PHP-Fusion Mods UK
| Version: 1.00
| Web: http://www.phpfusionmods.co.uk
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
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("EMS")||!defined("iAUTH")||$_GET['aid']!=iAUTH) { redirect("../index.php"); }

if (file_exists(INFUSIONS."events_management_system_panel/locale/".$settings['locale'].".php")) {
   include INFUSIONS."events_management_system_panel/locale/".$settings['locale'].".php";
     } else {
   include INFUSIONS."events_management_system_panel/locale/English.php"; 
}
include INFUSIONS."events_management_system_panel/infusion_db.php";
include INCLUDES."infusions_include.php";

$inf_folder = "events_management_system_panel";

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// Creating Info for Errors
if (isset($_GET['error']) && isnum($_GET['error'])) {
	if ($_GET['error'] == 1) {
		$message = $locale['901'];
	}
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// Infusion Settings Query
if (isset($_POST['saveinfusionsettings']))
{
	$error = 0;

	$result = set_setting("ems_member_post_allow", stripinput($_POST['ems_member_post_allow']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_post_admin_moderate", stripinput($_POST['ems_post_admin_moderate']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_show_birthdays_calendar", stripinput($_POST['ems_show_birthdays_calendar']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_bgcolor_currentdate", stripinput($_POST['ems_bgcolor_currentdate']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_bgcolor_eventdate", stripinput($_POST['ems_bgcolor_eventdate']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_bgcolor_dates", stripinput($_POST['ems_bgcolor_dates']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_bgcolor_days", stripinput($_POST['ems_bgcolor_days']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_color_currentdate", stripinput($_POST['ems_color_currentdate']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_color_eventdate", stripinput($_POST['ems_color_eventdate']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_color_dates", stripinput($_POST['ems_color_dates']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_color_days", stripinput($_POST['ems_color_days']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_calendar_tip_direction", stripinput($_POST['ems_calendar_tip_direction']), $inf_folder);
	if (!$result)	{	$error = 1;	}
	$result = set_setting("ems_panel_tip_direction", stripinput($_POST['ems_panel_tip_direction']), $inf_folder);
	if (!$result)	{	$error = 1;	}

	if (isNum($_POST['ems_image_max_size']))	{
		$result = set_setting("ems_image_max_size", stripinput($_POST['ems_image_max_size']), $inf_folder);
		if (!$result)	{	$error = 1;	}
	}
	if (isNum($_POST['ems_image_max_width']))	{
		$result = set_setting("ems_image_max_width", stripinput($_POST['ems_image_max_width']), $inf_folder);
		if (!$result)	{	$error = 1;	}
	}
	if (isNum($_POST['ems_image_max_height']))	{
		$result = set_setting("ems_image_max_height", stripinput($_POST['ems_image_max_height']), $inf_folder);
		if (!$result)	{	$error = 1;	}
	}
	if (isNum($_POST['ems_thumb_max_width']))	{
		$result = set_setting("ems_thumb_max_width", stripinput($_POST['ems_thumb_max_width']), $inf_folder);
		if (!$result)	{	$error = 1;	}
	}
	if (isNum($_POST['ems_thumb_max_height']))	{
		$result = set_setting("ems_thumb_max_height", stripinput($_POST['ems_thumb_max_height']), $inf_folder);
		if (!$result)	{	$error = 1;	}
	}

	if ($error == 0)	{
		redirect(FUSION_SELF.$aidlink."&error=0");
	}
	else {	redirect(FUSION_SELF.$aidlink."&error=1");	}
}
// JS Color
add_to_head("<script type='text/javascript' src='".INFUSIONS.$inf_folder."/includes/jscolor/jscolor.js'></script>");

// Infusion Settings
opentable($locale['ems_admin_title']);

// Database Settings
// Fetching Database Settings
$emssettings = array();
$emssettings = get_settings($inf_folder);

echo "<form name='infusion_settings' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' align='center'>\n";

echo "<tr>\n";
echo "<td class='tbl2'><h2>".$locale['ems_admin_set_001']."</h2></td>\n";
echo "<td class='tbl2'></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_001']." :</strong></td>\n";
echo "<td class='tbl2'>";
echo "<select name='ems_member_post_allow' class='textbox'>";
echo "<option value='0'".($emssettings['ems_member_post_allow'] == 0 ? "selected='selected'" : "").">".$locale['ems_option_no']."</option>\n";
echo "<option value='1'".($emssettings['ems_member_post_allow'] == 1 ? "selected='selected'" : "").">".$locale['ems_option_yes']."</option>\n";
echo "</select>";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_002']." :</strong></td>\n";
echo "<td class='tbl2'>";
echo "<select name='ems_post_admin_moderate' class='textbox'>";
echo "<option value='0'".($emssettings['ems_post_admin_moderate'] == 0 ? "selected='selected'" : "").">".$locale['ems_option_no']."</option>\n";
echo "<option value='1'".($emssettings['ems_post_admin_moderate'] == 1 ? "selected='selected'" : "").">".$locale['ems_option_yes']."</option>\n";
echo "</select>";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_003']." :</strong></td>\n";
echo "<td class='tbl2'>";
echo "<select name='ems_show_birthdays_calendar' class='textbox'>";
echo "<option value='0'".($emssettings['ems_show_birthdays_calendar'] == 0 ? "selected='selected'" : "").">".$locale['ems_option_no']."</option>\n";
echo "<option value='1'".($emssettings['ems_show_birthdays_calendar'] == 1 ? "selected='selected'" : "").">".$locale['ems_option_yes']."</option>\n";
echo "</select>";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_006']." :</strong></td>\n";
echo "<td class='tbl2'><input type='text' class='textbox' value='".$emssettings['ems_image_max_size']."' name='ems_image_max_size' /> - ".parsebytesize($emssettings['ems_image_max_size'])."</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_007']." :</strong></td>\n";
echo "<td class='tbl2'><input class='textbox' type='text' value='".$emssettings['ems_image_max_width']."' size='4' name='ems_image_max_width' /> x <input class='textbox' type='text' value='".$emssettings['ems_image_max_height']."' size='4' name='ems_image_max_height' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_008']." :</strong></td>\n";
echo "<td class='tbl2'><input class='textbox' type='text' value='".$emssettings['ems_thumb_max_width']."' size='4' name='ems_thumb_max_width' /> x <input class='textbox' type='text' value='".$emssettings['ems_thumb_max_height']."' size='4' name='ems_thumb_max_height' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><h2>".$locale['ems_admin_set_002']."</h2></td>\n";
echo "<td class='tbl2'></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_004a']." :</strong></td>\n";
echo "<td class='tbl2'><input class='color textbox' value='".$emssettings['ems_bgcolor_currentdate']."' maxlength='6' name='ems_bgcolor_currentdate' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_004b']." :</strong></td>\n";
echo "<td class='tbl2'><input class='color textbox' value='".$emssettings['ems_color_currentdate']."' maxlength='6' name='ems_color_currentdate' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_005a']." :</strong></td>\n";
echo "<td class='tbl2'><input class='color textbox' value='".$emssettings['ems_bgcolor_eventdate']."' maxlength='6' name='ems_bgcolor_eventdate' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_005b']." :</strong></td>\n";
echo "<td class='tbl2'><input class='color textbox' value='".$emssettings['ems_color_eventdate']."' maxlength='6' name='ems_color_eventdate' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_009a']." :</strong></td>\n";
echo "<td class='tbl2'><input class='color textbox' value='".$emssettings['ems_bgcolor_dates']."' maxlength='6' name='ems_bgcolor_dates' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_009b']." :</strong></td>\n";
echo "<td class='tbl2'><input class='color textbox' value='".$emssettings['ems_color_dates']."' maxlength='6' name='ems_color_dates' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_010a']." :</strong></td>\n";
echo "<td class='tbl2'><input class='color textbox' value='".$emssettings['ems_bgcolor_days']."' maxlength='6' name='ems_bgcolor_days' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_010b']." :</strong></td>\n";
echo "<td class='tbl2'><input class='color textbox' value='".$emssettings['ems_color_days']."' maxlength='6' name='ems_color_days' /></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><h2>".$locale['ems_admin_set_003']."</h2></td>\n";
echo "<td class='tbl2'></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_011a']." :</strong></td>\n";
echo "<td class='tbl2'>";
echo "<select name='ems_panel_tip_direction' class='textbox'>";
echo "<option value='up'".($emssettings['ems_panel_tip_direction'] == "up" ? "selected='selected'" : "").">".$locale['ems_admin_011_up']."</option>\n";
echo "<option value='down'".($emssettings['ems_panel_tip_direction'] == "down" ? "selected='selected'" : "").">".$locale['ems_admin_011_down']."</option>\n";
echo "<option value='left'".($emssettings['ems_panel_tip_direction'] == "left" ? "selected='selected'" : "").">".$locale['ems_admin_011_left']."</option>\n";
echo "<option value='right'".($emssettings['ems_panel_tip_direction'] == "right" ? "selected='selected'" : "").">".$locale['ems_admin_011_right']."</option>\n";
echo "</select>";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'><strong>".$locale['ems_admin_011b']." :</strong></td>\n";
echo "<td class='tbl2'>";
echo "<select name='ems_calendar_tip_direction' class='textbox'>";
echo "<option value='up'".($emssettings['ems_calendar_tip_direction'] == "up" ? "selected='selected'" : "").">".$locale['ems_admin_011_up']."</option>\n";
echo "<option value='down'".($emssettings['ems_calendar_tip_direction'] == "down" ? "selected='selected'" : "").">".$locale['ems_admin_011_down']."</option>\n";
echo "<option value='left'".($emssettings['ems_calendar_tip_direction'] == "left" ? "selected='selected'" : "").">".$locale['ems_admin_011_left']."</option>\n";
echo "<option value='right'".($emssettings['ems_calendar_tip_direction'] == "right" ? "selected='selected'" : "").">".$locale['ems_admin_011_right']."</option>\n";
echo "</select>";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='tbl2'></td>\n";
echo "<td class='tbl2'><input type='submit' name='saveinfusionsettings' value='".$locale['ems_admin_save']."' class='button' /></td>\n";
echo "</tr>\n";

echo "</table>\n</form>\n";

closetable();

require_once THEMES."templates/footer.php";
?>