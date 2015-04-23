<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Infusion: Event Management System
| Filename: calendar.php
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

// Including for Infusion Functions
include_once INCLUDES."infusions_include.php";
// Custom Functions
include_once EMSDIR."includes/functions.php";

// Database Settings
// Fetching Database Settings
$emssettings = array();
$emssettings = get_settings("events_management_system_panel");

// CSS
add_to_head("<link rel='stylesheet' href='".EMSDIR."includes/styles.css' type='text/css' media='all' />");
add_to_head("<script src='".EMSDIR."includes/jQuery.bubbletip-1.0.6.js' type='text/javascript'></script>
<link href='".EMSDIR."includes/bubbletip/bubbletip.css' rel='stylesheet' type='text/css' />
<!--[if IE]>
<link href='".EMSDIR."includes/bubbletip/bubbletip-IE.css' rel='stylesheet' type='text/css' />
<![endif]-->");

// Background Colors for Dates
add_to_head("<style type='text/css'>
.c-date, .c-date a
{
	background-color: #".$emssettings['ems_bgcolor_dates'].";
	color: #".$emssettings['ems_color_dates'].";
}
.current-day, .current-day a
{
	background-color: #".$emssettings['ems_bgcolor_currentdate'].";
	color: #".$emssettings['ems_color_currentdate'].";
}
.has-events, .has-events a
{
	background-color: #".$emssettings['ems_bgcolor_eventdate'].";
	color: #".$emssettings['ems_color_eventdate'].";
}
.c-daynames
{
	background-color: #".$emssettings['ems_bgcolor_days'].";
	color: #".$emssettings['ems_color_days'].";
}
</style>");

if (dbrows($infused_or_not) > 0)
{
// Set the Month and Year to Current Time as Default
$month = date("n");
$year = date("Y");

// If User Requests for specific Month and Year, then Set values for it.
if (isset($_GET["month"]) && isNum($_GET["month"]) && ($_GET["month"] <= 12))
{
	$month = $_GET["month"];
}
if (isset($_GET["year"]) && isNum($_GET["year"]))
{
	$year = $_GET["year"];
}

if (!isset($_GET['day']))
{
opentable($locale['calendar_title']);

// Variables for Previous and Next Link
// Set to Current Year by Default
$previous_year = $year;
$next_year = $year;

// Variables for Previous and Next Month
$previous_month = $month-1;
$next_month = $month+1;

// If Current Month is 1, i.e, January, then Previous month is 0.
// So we must set it to 12, i.e, December of Previous year
if ($previous_month == 0)
{
	$previous_month = 12;
	$previous_year = $year-1;
}

// If Current Month is 12, i.e, December, then Next month is 13.
// So we must set it to 1, i.e, January of Next year
if ($next_month == 13)
{
	$next_month = 1;
	$next_year = $year+1;
}

// Variables for Current Date, Month and Year
$this_date = date("j");	// Today's Date in Numeric
$this_month = date("m");	// This Month
$this_year = date("Y");	// This Year

$start = mktime(0, 0, 0, $month, 1, $year);	// Make Date of the Requested month and year
$mon = date("F", $start);	// Month
$year = date("Y", $start);	// Year
$day_of_week = date("N", $start);	// Day of Week : Monday=1, Tuesday=2, etc
$days_in_month = date("t", $start);	// Total Number of Days in Month
$month_num = date("m", $start);
$month_locale = "month_".$month_num;
$month_locale = $locale[$month_locale];

// Sub-Header
echo "<div class='event-subheader'>\n";
echo "<a href='events.php'><img src='".EMSDIR."images/events.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_view']."</a>\n";
echo "<a href='calendar.php'><img src='".EMSDIR."images/view_calendar.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_calendar']."</a>\n";
if ((($emssettings['ems_member_post_allow'] == 1) && iMEMBER) || (checkrights("EMS") || iSUPERADMIN))	{
	echo "<a href='postevent.php'><img src='".EMSDIR."images/add_event.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_post']."</a>\n";
}
echo "</div>\n";

// Title and Month changer Links
echo "<div align='center' class='event-title' style='clear: both; display: block; font-size: 28px;'>\n";
echo "<a href='".FUSION_SELF."?month=".$previous_month."&amp;year=".$previous_year."'><img src='".IMAGES."go_previous.png' alt='' hspace='20' style='vertical-align: middle;' border='0' /></a>\n";
echo "<img src='".EMSDIR."images/view_calendar.png' alt='' hspace='20' style='vertical-align: middle;' />".$locale['calendar_title']." : ".$month_locale." ".$year."\n";
echo "<a href='".FUSION_SELF."?month=".$next_month."&amp;year=".$next_year."'><img src='".IMAGES."go_next.png' alt='' hspace='20' style='vertical-align: middle;' border='0' /></a>\n";
echo "</div>\n";

echo "<table cellpadding='10' cellspacing='0' width='80%' align='center'>\n";

echo "<tr>\n";
echo "\t<td class='c-daynames'>".$locale['day_sun']."</td>\n";
echo "\t<td class='c-daynames'>".$locale['day_mon']."</td>\n";
echo "\t<td class='c-daynames'>".$locale['day_tue']."</td>\n";
echo "\t<td class='c-daynames'>".$locale['day_wed']."</td>\n";
echo "\t<td class='c-daynames'>".$locale['day_thu']."</td>\n";
echo "\t<td class='c-daynames'>".$locale['day_fri']."</td>\n";
echo "\t<td class='c-daynames'>".$locale['day_sat']."</td>\n";
echo "</tr>\n";

// START TIME - END TIME for all the Events in a Month
$monthstart = mktime(0, 0, 0, $month, 1, $year);	// Make Date of 1'st Day
$monthend = mktime(24, 0, 0, $month, date("t", $monthstart), $year);	// Make Date of Last Day

// Retrieve all the Events in the Current Month
$monthevents = dbquery("SELECT event_id, event_title, event_startdate FROM ".DB_EVENTS." WHERE ".groupaccess('event_visibility')." AND (event_startdate>=".$monthstart." AND event_startdate<=".$monthend.") AND event_hidden='0' ORDER BY event_startdate");

// Array to Store all Events
$allevents = array();

while ($data = dbarray($monthevents))
{
	$event_date = date("j", $data['event_startdate']);
	$allevents[$event_date][$data['event_id']] = $data['event_title'];
}

// Birthdays
// Store Birthdays in a Variable
$birthdays_cache = array();

if ($emssettings['ems_show_birthdays_calendar'] == 1)
{
	// Find all birthdays in Current Month
	$birthdays = dbquery("SELECT user_name, user_id, user_birthdate FROM ".DB_USERS." WHERE user_birthdate LIKE '%-".date("m", $monthstart)."-%'");

	if (dbrows($birthdays) > 0)
	{
		while ($data = dbarray($birthdays))
		{
			$dob = explode("-", $data['user_birthdate']);
			$age = $year-$dob[0];
			$birthdays_cache[ltrim($dob[2], "0")][$data['user_id']] = $data['user_name']."(".$age.")";	// Trim is used to Remove Leading Zero
		}
	}
}

// Printing Dates on Calendar
for ($i=0; $i<($days_in_month+$day_of_week); $i++)
{
	// Actual Day Number
	$count = ($i - $day_of_week + 1);

	// First Day of the Week
	// Every week will be shown by 1 TR
	if(($i % 7) == 0)
	{
		echo "<tr>\n";
	}

	// If $i is less than Starting Day of Week, then Print Empty TDs
	if($i < $day_of_week)
	{
		// Exception: If Week doesn't starts from Sunday, then we should print Empty TDs
		if ($day_of_week != 7)
		{
			echo "\t<td class='c-dates c-date'></td>\n";
		}
	}
	else
	{
		// If Today's Date comes in the Calendar, then Highlight it by applying Style
		$selected = ((($this_month == $month) && ($this_year == $year) && ($this_date == $count)) ? " current-day" : " c-date");
		$rightborder = (($i%7==6)||($i == ($days_in_month+$day_of_week-1)) ? " c-rightborder" : "");
		// Find if there is any Event/Birthday on this Date or Not
		$has_events = ((array_key_exists($count, $allevents) || (array_key_exists($count, $birthdays_cache))) ? " has-events" : "");

		// If the Day counter has started, then print the Dates
		echo "\t<td valign='middle' class='c-dates".$selected.$rightborder.$has_events."'><a id='day_".$count."' href='".FUSION_SELF."?month=".$month."&amp;year=".$year."&amp;day=".$count."'>".$count."</a></td>\n";
	}

	// If it is the Last Day of Week, then Finish it with </TR>
	if(($i % 7) == 6)
	{
		echo "</tr>\n";
	}

	// If it is the Last Date of the month, then Finish it with </TR>
	if($i == ($days_in_month+$day_of_week-1))
	{
		echo "</tr>\n";
	}
}

echo "</table>\n";

// Javascript Code for Bubble Tip
$js_code_to_add = "";

for ($i=1; $i<=date("t", $monthstart); $i++)
{
	if (array_key_exists($i, $allevents) || array_key_exists($i, $birthdays_cache))
	{
		echo "<div class='event-bubble-tip' id='daytip_".$i."' style='display:none;'>\n";
		if (array_key_exists($i, $allevents))	{
			foreach ($allevents[$i] as $key=>$val)
			{
				echo "<a href='events.php?event=".$key."'><img src='".EMSDIR."images/calendar_icon_small.png' alt='' border='0' class='calendar-icon-small' /> ".$val."</a><br />\n";
			}
		}
		if (array_key_exists($i, $birthdays_cache))	{
			foreach ($birthdays_cache[$i] as $key=>$val)
			{
				echo "<a href='".BASEDIR."profile.php?lookup=".$key."'><img src='".EMSDIR."images/birthday.png' alt='' border='0' class='birthday-icon-small' /> ".$val."</a><br />\n";
			}
		}
		echo "</div>\n";
		$js_code_to_add .= "$('#day_".$i."').bubbletip($('#daytip_".$i."'), { deltaDirection: '".$emssettings['ems_calendar_tip_direction']."' });\n";
	}
}

// Add Javascript to Header
if ($js_code_to_add != "")
{
add_to_head("<script type='text/javascript'>
$(window).bind('load', function() {
".$js_code_to_add."});
</script>");
}

closetable();
}

// Show Events

// Setting the value of rowstart to 0 by Default
if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }

// Strip Input GET Variables
if (isset($_GET['rowstart'])) { $_GET['rowstart'] = stripinput($_GET['rowstart']); }
if (isset($_GET['day'])) { $_GET['day'] = stripinput($_GET['day']); }

	// The Range between which Events will be find
	$starttime = 0;
	$endtime = 0;

	// If User Requests Events for a Particular Day
	if (isset($_GET['day']) && isNum($_GET['day']))
	{
		// Make Date for that particular day
		$starttime = mktime(0, 0, 0, $month, (int)$_GET['day'], $year);	// Make Date of 00:00
		$endtime = mktime(24, 0, 0, $month, (int)$_GET['day'], $year);	// Make Date of 24:00
		$month_num = date("m", $starttime);
		$month_locale = "month_".$month_num;
		$month_locale = $locale[$month_locale];
		// Page Title
		set_title(sprintf($locale['calendar_page_title'], $_GET['day'], $month_locale." ".$year));
	}
	else
	{
		// Make Date for that particular Month
		$starttime = mktime(0, 0, 0, $month, 1, $year);	// Make Date of 1'st Day
		$endtime = mktime(24, 0, 0, $month, date("t", $starttime), $year);	// Make Date of Last Day
		$month_num = date("m", $starttime);
		$month_locale = "month_".$month_num;
		$month_locale = $locale[$month_locale];
		// Page Title
		set_title(sprintf($locale['calendar_page_title'], $month_locale, $year));
	}

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
	$events = dbquery("SELECT e.*, u.user_name, u.user_status FROM ".DB_EVENTS." e LEFT JOIN ".DB_USERS." u ON (e.event_author=u.user_id) WHERE ".groupaccess('e.event_visibility')." AND (e.event_startdate>=".$starttime." AND e.event_startdate<=".$endtime.") AND event_hidden='0' ORDER BY e.".$orderby." LIMIT ".$_GET['rowstart'].",20");
	$total = dbrows(dbquery("SELECT event_id FROM ".DB_EVENTS." WHERE ".groupaccess('event_visibility')." AND (event_startdate>=".$starttime." AND event_startdate<=".$endtime.") AND event_hidden='0' ORDER BY event_startdate"));

	if (dbrows($events) > 0)
	{
		opentable($locale['event_view']);

		echo "<div align='center' class='event-title' style='font-size: 28px;'>\n<img src='".EMSDIR."images/events.png' alt='' hspace='20' style='vertical-align: middle;' />".(isset($_GET['day']) ? sprintf($locale['event_in_day'], $_GET['day']." ".$month_locale." ".$year) : sprintf($locale['event_in_month'], $month_locale." ".$year))."\n</div>\n";

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
			echo "<td class='".$cell_color."'><img src='".EMSDIR."images/calendar_icon.png' alt='' class='calendar_icon' /><a href='events.php?event=".$data['event_id']."'>".$data['event_title']."</a></td>\n";
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
			echo "<div align='center' style='margin-top:5px;'>".makepagenav($_GET['rowstart'], 20, $total, 3, FUSION_SELF."?month=".$month."&amp;year=".$year."&amp;sortby=".$_GET['sortby']."&amp;")."</div>\n";
		}
		closetable();
	}
	else
	{
		opentable($locale['event_empty']);
		if (isset($_GET['day']))	{
		// Sub-Header
		echo "<div class='event-subheader'>\n";
		echo "<a href='events.php'><img src='".EMSDIR."images/events.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_view']."</a>\n";
		echo "<a href='calendar.php'><img src='".EMSDIR."images/view_calendar.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_calendar']."</a>\n";
		if ((($emssettings['ems_member_post_allow'] == 1) && iMEMBER) || (checkrights("EMS") || iSUPERADMIN))	{
			echo "<a href='postevent.php'><img src='".EMSDIR."images/add_event.png' alt='' hspace='10' style='vertical-align: middle;' border='0' /> ".$locale['event_post']."</a>\n";
		}
		echo "</div>\n";
		}
		echo "<br />".(isset($_GET['day']) ? sprintf($locale['event_day_empty'], $_GET['day']." ".$month_locale." ".$year) : sprintf($locale['event_month_empty'], $month_locale." ".$year));
		closetable();
	}

}	// End of $infused_or_not condition

require_once THEMES."templates/footer.php";
?>
