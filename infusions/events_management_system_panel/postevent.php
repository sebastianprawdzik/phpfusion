<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright C 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Infusion: Event Management System
| Filename: postevent.php
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
require_once THEMES."templates/header.php";

if (!defined("IN_FUSION")) { die("Access Denied"); }

// Check If the Infusion is Infused or Not. Done to Minimize Error Logs
$infused_or_not = dbquery("SELECT * FROM ".DB_INFUSIONS. " WHERE inf_folder='events_management_system_panel'");

if (dbrows($infused_or_not) > 0) {

// Including for Infusion Functions
include_once INCLUDES."infusions_include.php";
// BB Codes
require_once INCLUDES."bbcode_include.php";

// Database Settings
// Fetching Database Settings
$emssettings = array();
$emssettings = get_settings("events_management_system_panel");

if ((($emssettings['ems_member_post_allow'] == 1) && iMEMBER) || (checkrights("EMS") || iSUPERADMIN))
{

// Defining a Constant for the Infusion directory
if (!defined("EMSDIR")) {
define("EMSDIR", INFUSIONS."events_management_system_panel/");
}

// CSS
add_to_head("<link rel='stylesheet' href='".EMSDIR."includes/styles.css' type='text/css' media='all' />");

// Including Locales
if (file_exists(EMSDIR."locale/".$settings['locale'].".php")) {
	include EMSDIR."locale/".$settings['locale'].".php";
} else {
	include EMSDIR."locale/English.php";
}
include EMSDIR."infusion_db.php";
include_once EMSDIR."includes/functions.php";

// Page Title
set_title($locale['ems_title_newevent']);

// Creating Info for Errors
if (isset($_GET['action'])) {
	if ($_GET['action'] == "add") {
		$message = $locale['ems_action_add'];
	}
	if ($_GET['action'] == "error") {
		$message = $locale['ems_action_error'];
	}
	if (isset($message)) {
		// Redirect to events.php after 3 seconds
		add_to_head("<meta http-equiv='refresh' content='3; url=events.php' />");
		opentable($locale['ems_title_newevent']);
		echo "<br />\n<center>".$message."<bt /><br /><br />\n<a href='events.php'>".$locale['ems_action_redirect']."</a></center><br />\n";
		closetable(); 
	}
}

// Adding / Editing an Event
if (isset($_POST['preview']) || isset($_POST['postevent']))
{
	$event_title = (isset($_POST['event_title']) ? stripinput($_POST['event_title']) : "");
	$event_desc = (isset($_POST['event_desc']) ? stripinput($_POST['event_desc']) : "");
	$event_start = array(
		"mday" => isnum($_POST['event_start']['mday']) ? $_POST['event_start']['mday'] : "--",
		"mon" => isnum($_POST['event_start']['mon']) ? $_POST['event_start']['mon'] : "--",
		"year" => isnum($_POST['event_start']['year']) ? $_POST['event_start']['year'] : "----",
		"hours" => isnum($_POST['event_start']['hours']) ? $_POST['event_start']['hours'] : "0",
		"minutes" => isnum($_POST['event_start']['minutes']) ? $_POST['event_start']['minutes'] : "0",
	);
	$event_end = array(
		"mday" => isnum($_POST['event_end']['mday']) ? $_POST['event_end']['mday'] : "--",
		"mon" => isnum($_POST['event_end']['mon']) ? $_POST['event_end']['mon'] : "--",
		"year" => isnum($_POST['event_end']['year']) ? $_POST['event_end']['year'] : "----",
		"hours" => isnum($_POST['event_end']['hours']) ? $_POST['event_end']['hours'] : "0",
		"minutes" => isnum($_POST['event_end']['minutes']) ? $_POST['event_end']['minutes'] : "0",
	);
	$event_visibility = ((isset($_POST['event_visibility']) && isNum($_POST['event_visibility'])) ? $_POST['event_visibility'] : 0);
	$event_allow_comments = ((isset($_POST['event_allow_comments']) && isNum($_POST['event_allow_comments'])) ? $_POST['event_allow_comments'] : 1);
	$event_allow_ratings = ((isset($_POST['event_allow_ratings']) && isNum($_POST['event_allow_ratings'])) ? $_POST['event_allow_ratings'] : 1);
	$event_start_date = time();
	$event_end_date = time();
	if ($event_start['mday']!="--" && $event_start['mon']!="--" && $event_start['year']!="----")
	{
		$event_start_date = mktime($event_start['hours'],$event_start['minutes'],0,$event_start['mon'],$event_start['mday'],$event_start['year']);
	}
	if ($event_end['mday']!="--" && $event_end['mon']!="--" && $event_end['year']!="----")
	{
		$event_end_date = mktime($event_end['hours'],$event_end['minutes'],0,$event_end['mon'],$event_end['mday'],$event_end['year']);
	}

	// PREVIEW
	opentable($locale['ems_title_preview']);

	echo "<h2>".$event_title."</h2>\n";
	echo "<br />".nl2br(parseubb(parsesmileys($event_desc)));

	closetable();

	// If user Uploaded the Image
	$event_large_image = "";
	$event_thumb_image = "";
	if (isset($_POST['postevent']) && isset($_FILES['event_image']) && $_FILES['event_image']['name'] != "")
	{
		$event_image = upload_image("event_image", "", EMSDIR."images/uploads/", $emssettings['ems_image_max_width'], $emssettings['ems_image_max_height'], $emssettings['ems_image_max_size'], false, true, false, 0, EMSDIR."images/uploads/", "_t1", $emssettings['ems_thumb_max_width'], $emssettings['ems_thumb_max_height']);
		if ($event_image['image'])		{
			$event_large_image = $event_image['image_name'];
		}
		if ($event_image['thumb1'])		{
			$event_thumb_image = $event_image['thumb1_name'];
		}
	}

	if (isset($_POST['postevent']))
	{
		$event_hidden = 0;
		if (checkrights("EMS") || iSUPERADMIN)		{
			$event_hidden = 0;
		}
		else	{
			$event_hidden = ($emssettings['ems_post_admin_moderate'] == 1 ? 1 : 0);
		}
		$result = dbquery("INSERT INTO ".DB_EVENTS." (event_id, event_title, event_author, event_startdate, event_enddate, event_text, event_visibility, event_allow_comments, event_allow_ratings, event_hidden, event_image, event_thumb_image) VALUES ('', '".$event_title."', '".$userdata['user_id']."', '".$event_start_date."', '".$event_end_date."', '".$event_desc."', '".$event_visibility."', '".$event_allow_comments."', '".$event_allow_ratings."', '".$event_hidden."', '".$event_large_image."', '".$event_thumb_image."')");
		if (!$result)	{	redirect(FUSION_SELF."?action=error");	}
		else	{	redirect(FUSION_SELF."?action=add");	}
	}
}

// Posting a New Event
if (!isset($_GET['action']))
{
	opentable($locale['ems_title_newevent']);

	// New Event Form
	echo "<form name='addevent' method='post' action='".FUSION_SELF."' enctype='multipart/form-data'>\n";
	echo "<table cellpadding='5' cellspacing='0' align='center'>\n";

	// Title
	echo "<tr>\n";
	echo "<td width='150'><strong>".$locale['ems_post_title']." :</strong></td>\n";
	echo "<td><input type='text' name='event_title' value='".(isset($event_title) ? $event_title : "")."' maxlength='100' class='textbox' size='50' /></td>\n";
	echo "</tr>\n";

	// Image
	echo "<tr>\n";
	echo "<td width='150'><strong>".$locale['ems_post_image']." :</strong></td>\n";
	echo "<td><input type='file' name='event_image' />".sprintf($locale['ems_image_details'], parsebytesize($emssettings['ems_image_max_size']), $emssettings['ems_image_max_width'], $emssettings['ems_image_max_height'])."</td>\n";
	echo "</tr>\n";

	// Description
	echo "<tr>\n";
	echo "<td width='150'><strong>".$locale['ems_post_desc']." :</strong></td>\n";
	echo "<td><textarea rows='5' name='event_desc' class='textbox' cols='80'>".(isset($event_desc) ? $event_desc : "")."</textarea>\n";
	echo display_bbcodes("400px", "event_desc", "addevent")."\n</td>\n";
	echo "</tr>\n";

	// By Default, set the Starting Date to Current time
	$event_start = (isset($event_start) ? $event_start : getdate(time()));
	$event_end = (isset($event_end) ? $event_end : getdate(time()));
	

	// Start Date
	echo "<tr>\n";
	
	echo "<td width='150'><strong>".$locale['ems_post_event_start']." :</strong></td>\n";
	echo "<td>\nDzień:<select name='event_start[mday]' class='textbox'>\n<option>--</option>\n";
	$i=0;
	for ($i=1;$i<=31;$i++) echo "<option".(isset($event_start['mday']) && $event_start['mday'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select>\nMiesiąc:<select name='event_start[mon]' class='textbox'>\n<option>--</option>\n";
	for ($i=1;$i<=12;$i++) echo "<option".(isset($event_start['mon']) && $event_start['mon'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select>\nRok:<select name='event_start[year]' class='textbox'>\n<option>----</option>\n";
	for ($i=(isset($event_start['year']) && $event_start['year'] != "----" ? $event_start['year'] : date('Y')); $i<=date("Y", strtotime('+10 years')); $i++) echo "<option".(isset($event_start['year']) && $event_start['year'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select>\n/\n<select name='event_start[hours]' class='textbox'>\n";
	for ($i=0;$i<=24;$i++) echo "<option".(isset($event_start['hours']) && $event_start['hours'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select> Godzina : <select name='event_start[minutes]' class='textbox'>\n";
	for ($i=0;$i<=60;$i++) echo "<option".(isset($event_start['minutes']) && $event_start['minutes'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select> Minuty\n</td>\n";
	echo "</tr>\n";

	// End Date
	echo "<tr>\n";
	echo "<td width='150'><strong>".$locale['ems_post_event_end']." :</strong></td>\n";
	echo "<td>\nDzień:<select name='event_end[mday]' class='textbox'>\n<option>--</option>\n";
	$i=0;
	for ($i=1;$i<=31;$i++) echo "<option".(isset($event_end['mday']) && $event_end['mday'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select>\nMiesiąc:<select name='event_end[mon]' class='textbox'>\n<option>--</option>\n";
	for ($i=1;$i<=12;$i++) echo "<option".(isset($event_end['mon']) && $event_end['mon'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select>\nRok:<select name='event_end[year]' class='textbox'>\n<option>----</option>\n";
	for ($i=(isset($event_end['year']) && $event_end['year'] != "----" ? $event_end['year'] : date('Y')); $i<=date("Y", strtotime('+10 years')); $i++) echo "<option".(isset($event_end['year']) && $event_end['year'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select>\n/\n<select name='event_end[hours]' class='textbox'>\n";
	for ($i=0;$i<=24;$i++) echo "<option".(isset($event_end['hours']) && $event_end['hours'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select> Godzina : <select name='event_end[minutes]' class='textbox'>\n";
	for ($i=0;$i<=60;$i++) echo "<option".(isset($event_end['minutes']) && $event_end['minutes'] == $i ? " selected='selected'" : "").">".$i."</option>\n";
	echo "</select> Minuty\n</td>\n";
	echo "</tr>\n";

	// Visibility
	echo "<tr>\n";
	echo "<td width='150'><strong>".$locale['ems_post_visibility']." :</strong></td>\n";
	echo "<td><select name='event_visibility' class='textbox'>\n
<option value='0'".((isset($event_visibility) && ($event_visibility == 0)) ? " selected='selected'" : "").">".$locale['user0']."</option>\n
<option value='101'".((isset($event_visibility) && ($event_visibility == 101)) ? " selected='selected'" : "").">".$locale['user1']."</option>\n
<option value='102'".((isset($event_visibility) && ($event_visibility == 102)) ? " selected='selected'" : "").">".$locale['user2']."</option>\n
<option value='103'".((isset($event_visibility) && ($event_visibility == 103)) ? " selected='selected'" : "").">".$locale['user3']."</option>\n
</select></td>\n";
	echo "</tr>\n";

	// Allow Comments
	echo "<tr>\n";
	echo "<td width='150'><strong>".$locale['ems_post_comments']." :</strong></td>\n";
	echo "<td><select name='event_allow_comments' class='textbox'>\n
<option value='1'".((isset($event_allow_comments) && ($event_allow_comments == 1)) ? " selected='selected'" : "").">".$locale['ems_option_yes']."</option>\n
<option value='0'".((isset($event_allow_comments) && ($event_allow_comments == 0)) ? " selected='selected'" : "").">".$locale['ems_option_no']."</option>\n
</select></td>\n";
	echo "</tr>\n";

	// Allow Ratings
	echo "<tr>\n";
	echo "<td width='150'><strong>".$locale['ems_post_ratings']." :</strong></td>\n";
	echo "<td><select name='event_allow_ratings' class='textbox'>\n
<option value='1'".((isset($event_allow_ratings) && ($event_allow_ratings == 1)) ? " selected='selected'" : "").">".$locale['ems_option_yes']."</option>\n
<option value='0'".((isset($event_allow_ratings) && ($event_allow_ratings == 0)) ? " selected='selected'" : "").">".$locale['ems_option_no']."</option>\n
</select></td>\n";
	echo "</tr>\n";

	// Submit
	echo "<tr>\n";
	echo "<td width='150'></td>\n";
	echo "<td><input type='submit' name='preview' value='".$locale['ems_post_preview']."' class='button' />&nbsp;<input type='submit' name='postevent' value='".$locale['ems_post_save']."' class='button' /></td>\n";
	echo "</tr>\n";

	echo "</table>\n</form>\n";

	closetable();
}
}
else
{
	redirect("events.php");
}
}	// End of $infused_or_not condition

require_once THEMES."templates/footer.php";
?>
