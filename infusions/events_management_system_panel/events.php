<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Infusion: Event Management System
| Filename: events.php
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

// Database Settings
// Fetching Database Settings
$emssettings = array();
$emssettings = get_settings("events_management_system_panel");

// Defining a Constant for the Infusion directory
if (!defined("EMSDIR")) {
define("EMSDIR", INFUSIONS."events_management_system_panel/");
}

// Including Locales
if (file_exists(EMSDIR."locale/".$settings['locale'].".php")) {
	include EMSDIR."locale/".$settings['locale'].".php";
} else {
	include EMSDIR."locale/English.php";
}
include EMSDIR."infusion_db.php";
include_once EMSDIR."includes/functions.php";

// CSS
add_to_head("<link rel='stylesheet' href='".EMSDIR."includes/styles.css' type='text/css' media='all' />");
add_to_head("<link rel='stylesheet' href='".INCLUDES."jquery/colorbox/colorbox.css' type='text/css' media='screen' />");
add_to_head("<script type='text/javascript' src='".INCLUDES."jquery/colorbox/jquery.colorbox.js'></script>");
add_to_footer("<script type='text/javascript'>
/*<![CDATA[*/
jQuery(document).ready(function(){
jQuery('a[rel^=\"attach\"]').colorbox({width:'80%',height:'80%'});
});
/*]]>*/</script>");

// Setting the value of rowstart to 0 by Default
if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }

// Strip Input GET Variables
if (isset($_GET['rowstart'])) { $_GET['rowstart'] = stripinput($_GET['rowstart']); }
if (isset($_GET['event'])) { $_GET['event'] = stripinput($_GET['event']); }

// Viewing an Event
if(isset($_GET['event']) && isNum($_GET['event']))
{
	// Retrieve the requested Event
	$event = dbquery("SELECT e.*, u.user_id, u.user_name, u.user_status FROM ".DB_EVENTS." e LEFT JOIN ".DB_USERS." u ON e.event_author=u.user_id WHERE ".groupaccess('event_visibility').((checkrights("EMS") || iSUPERADMIN) ? "" : " AND event_hidden='0'")." AND e.event_id=".stripinput($_GET['event']));

	opentable($locale['event_view']);

	// Sub-Header
	echo "<div class='event-subheader'>\n";
	echo "<a href='events.php'><img src='".EMSDIR."images/events.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_view']."</a>\n";
	echo "<a href='calendar.php'><img src='".EMSDIR."images/view_calendar.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_calendar']."</a>\n";
	if ((($emssettings['ems_member_post_allow'] == 1) && iMEMBER) || (checkrights("EMS") || iSUPERADMIN))	{
		echo "<a href='postevent.php'><img src='".EMSDIR."images/add_event.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_post']."</a>\n";
	}
	if (checkrights("EMS") || iSUPERADMIN)	{
		echo "<a href='editevent.php?edit=".$_GET['event']."'><img src='".EMSDIR."images/edit_event.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_edit']."</a>\n";
		}
	echo "</div>\n";

	if (dbrows($event) > 0)
	{
		$data = dbarray($event);

		// Page Title
		set_title($data['event_title']);

		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";

		echo "<tr>\n<td class='event-text' valign='top'>\n";
		// Title
		echo "<h1 class='event-title'>".$data['event_title']."\n</h1>\n";
		echo "<br />".nl2br(parseubb(parsesmileys($data['event_text'])))."\n</td>\n<td class='event-details' valign='top'>\n";
		// Image
		if ($data['event_image'] != "")
		{
			echo "<div class='event-image'>\n<a href='".EMSDIR."images/uploads/".$data['event_image']."' rel='attach'><img src='".EMSDIR."images/uploads/".$data['event_thumb_image']."' alt='' border='0' /></a>\n</div>\n";
		}
		// Details
		echo "<table cellpadding='5' cellspacing='0' style='border: 1px solid #ccc;' width='100%'>\n";
		echo "<tr>\n";
		echo "<td class='event-head'><center><strong>".$locale['event_sidebar_title']."</strong></center></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td><strong>".$locale['event_status']." : </strong>".calctimeleft($data['event_startdate'],$data['event_enddate'])."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td><strong>".$locale['event_sidebar_author']." : </strong>".profile_link($data['event_author'], $data['user_name'], $data['user_status'])."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td><strong>".$locale['event_sidebar_start']." : </strong>".showdate("longdate", $data['event_startdate'])."</td>\n";
		echo "</tr>\n";
		if ($data['event_enddate'] != 0)	{
			echo "<tr>\n";
			echo "<td><strong>".$locale['event_sidebar_finish']." : </strong>".showdate("longdate", $data['event_enddate'])."</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "</td>\n";

		echo "</tr>\n</table>";

		include INCLUDES."comments_include.php";
		include INCLUDES."ratings_include.php";
		if ($data['event_allow_comments'] == 1)	{
		showcomments("EM", DB_EVENTS, "event_id", $_GET['event'], FUSION_SELF."?event=".$_GET['event']);
		}
		if ($data['event_allow_ratings'] == 1)	{
		showratings("E", $_GET['event'], FUSION_SELF."?event=".$_GET['event']);
		}
	}
	else
	{
		redirect("events.php");
	}
	closetable();
}
// Showing List of all Events
else
{
	// Page Title
	set_title($locale['event_view']);

	opentable($locale['event_view']);

	echo "<div align='center' class='event-title' style='font-size: 28px;'>\n<img src='".EMSDIR."images/events.png' alt='' hspace='20' style='vertical-align: middle;' /> ".$locale['event_view']."\n</div>\n";

	echo "<div class='event-subheader'>\n";
	echo "<a href='calendar.php'><img src='".EMSDIR."images/view_calendar.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_calendar']."</a>\n";
	if ((($emssettings['ems_member_post_allow'] == 1) && iMEMBER) || (checkrights("EMS") || iSUPERADMIN))	{
		echo "<a href='postevent.php'><img src='".EMSDIR."images/add_event.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_post']."</a>\n";
	}
	echo "</div>\n";

	if (!isset($_GET['sortby']) || !ctype_alnum($_GET['sortby'])) { $_GET['sortby'] = "event"; }
	$orderby = "event_startdate";
	if ($_GET['sortby'] == "event")	{
		$orderby = "event_title";
	}
	if ($_GET['sortby'] == "date")	{
		$orderby = "event_startdate";
	}
	if ($_GET['sortby'] == "author")	{
		$orderby = "event_author";
	}

	// Retrieve all Events
	$events = dbquery("SELECT e.*, u.user_name, u.user_status FROM ".DB_EVENTS." e LEFT JOIN ".DB_USERS." u ON (e.event_author=u.user_id) WHERE ".groupaccess('event_visibility')." AND event_hidden='0' ORDER BY e.".$orderby." LIMIT ".$_GET['rowstart'].",20");
	$total = dbrows(dbquery("SELECT event_id FROM ".DB_EVENTS." WHERE ".groupaccess('event_visibility')." AND event_hidden='0'"));

	if (dbrows($events) > 0)
	{
		$i=0;

		echo "<table cellpadding='5' cellspacing='0' style='border: 1px solid #ccc;' width='100%'>\n";

		echo "<tr>\n";
		echo "<td class='event-head'><strong><a href='".FUSION_SELF."?sortby=event'>".$locale['event_title']."</a></strong></td>\n";
		echo "<td class='event-head'><strong><a href='".FUSION_SELF."?sortby=author'>".$locale['event_author']."</a></strong></td>\n";
		echo "<td class='event-head'><strong><a href='".FUSION_SELF."?sortby=date'>".$locale['event_time']."</a></strong></td>\n";
		echo "<td class='event-head'><strong>".$locale['event_status']."</strong></td>\n";
		if (checkrights("EMS") || iSUPERADMIN)	{
			echo "<td class='event-head'><strong>".$locale['event_actions']."</strong></td>\n";
		}
		echo "</tr>\n";

		while ($data = dbarray($events))
		{
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
			echo "<tr>\n";
			echo "<td class='".$cell_color."'><img src='".EMSDIR."images/calendar_icon.png' alt='' class='calendar_icon' /><a href='".FUSION_SELF."?event=".$data['event_id']."'>".$data['event_title']."</a></td>\n";
			echo "<td class='".$cell_color."'>".profile_link($data['event_author'], $data['user_name'], $data['user_status'])."</td>\n";
			echo "<td class='".$cell_color."'>".showdate("longdate", $data['event_startdate'])."</td>\n";
			echo "<td class='".$cell_color."'>".calctimeleft($data['event_startdate'],$data['event_enddate'])."</td>\n";
			if (checkrights("EMS") || iSUPERADMIN)	{
			echo "<td class='".$cell_color."'><a href='editevent.php?edit=".$data['event_id']."'>".$locale['event_action_001']."</a> | <a href='editevent.php?edit=".$data['event_id']."&amp;delete=".$data['event_id']."' onclick=\"return confirm('".$locale['event_action_002_ask']."');\">".$locale['event_action_002']."</a></td>\n";
		}
			echo "</tr>\n";
		}

		echo "</table>";

		if ($total > 20)
		{
			echo "<div align='center' style='margin-top:5px;'>".makepagenav($_GET['rowstart'], 20, $total, 3, FUSION_SELF."?sortby=".$_GET['sortby']."&amp;")."</div>\n";
		}
	}
	else
	{
		echo $locale['event_empty'];
	}
	closetable();

// Display Un Published Events to Admin
if (checkrights("EMS") || iSUPERADMIN)
{
	// Un-Published Events
	$unevents = dbquery("SELECT e.*, u.user_name, u.user_status FROM ".DB_EVENTS." e LEFT JOIN ".DB_USERS." u ON (e.event_author=u.user_id) WHERE ".groupaccess('event_visibility')." AND event_hidden='1' ORDER BY event_startdate LIMIT ".$_GET['rowstart'].",20");
	$untotal = dbrows(dbquery("SELECT event_id FROM ".DB_EVENTS." WHERE ".groupaccess('event_visibility')." AND event_hidden='1'"));

	if (dbrows($unevents) > 0)
	{
		opentable($locale['event_unpublished']);

		$i=0;

		echo "<table cellpadding='5' cellspacing='0' style='border: 1px solid #ccc;' width='100%'>\n";

		echo "<tr>\n";
		echo "<td class='event-head'><strong>".$locale['event_title']."</strong></td>\n";
		echo "<td class='event-head'><strong>".$locale['event_author']."</strong></td>\n";
		echo "<td class='event-head'><strong>".$locale['event_time']."</strong></td>\n";
		echo "<td class='event-head'><strong>".$locale['event_status']."</strong></td>\n";
		if (checkrights("EMS") || iSUPERADMIN)	{
			echo "<td class='event-head'><strong>".$locale['event_actions']."</strong></td>\n";
		}
		echo "</tr>\n";

		while ($data = dbarray($unevents))
		{
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
			echo "<tr>\n";
			echo "<td class='".$cell_color."'><img src='".EMSDIR."images/calendar_icon.png' alt='' class='calendar_icon' /><a href='".FUSION_SELF."?event=".$data['event_id']."'>".$data['event_title']."</a></td>\n";
			echo "<td class='".$cell_color."'>".profile_link($data['event_author'], $data['user_name'], $data['user_status'])."</td>\n";
			echo "<td class='".$cell_color."'>".showdate("longdate", $data['event_startdate'])."</td>\n";
			echo "<td class='".$cell_color."'>".calctimeleft($data['event_startdate'],$data['event_enddate'])."</td>\n";
			if (checkrights("EMS") || iSUPERADMIN)	{
			echo "<td class='".$cell_color."'><a href='editevent.php?edit=".$data['event_id']."'>".$locale['event_action_001']."</a> | <a href='editevent.php?edit=".$data['event_id']."&amp;delete=".$data['event_id']."' onclick=\"return confirm('".$locale['event_action_002_ask']."');\">".$locale['event_action_002']."</a></td>\n";
		}
			echo "</tr>\n";
		}

		echo "</table>";

		if ($untotal > 20)
		{
			echo "<div align='center' style='margin-top:5px;'>".makepagenav($_GET['rowstart'], 20, $untotal, 3, FUSION_SELF."?")."</div>\n";
		}
		closetable();
	}
}	// Unpublished Events condition ends here
}

}	// End of $infused_or_not condition

require_once THEMES."templates/footer.php";
?>