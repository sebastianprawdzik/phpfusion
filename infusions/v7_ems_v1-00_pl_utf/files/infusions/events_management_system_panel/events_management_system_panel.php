<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Infusion: Event Management System
| Filename: events_management_system_panel.php
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
include_once EMSDIR."infusion_db.php";
include_once EMSDIR."includes/functions.php";

// CSS
add_to_head("<link rel='stylesheet' href='".EMSDIR."includes/panel-styles.css' type='text/css' media='all' />");
add_to_head("<style type='text/css'>
.panel-date, .panel-date a
{
	background-color: #".$emssettings['ems_bgcolor_dates'].";
	color: #".$emssettings['ems_color_dates'].";
}
.panel-daynames
{
	background-color: #".$emssettings['ems_bgcolor_days'].";
	color: #".$emssettings['ems_color_days'].";
}
</style>");

// To Remove Duplicate Adding of Header files on page calendar.php
if (FUSION_SELF != "calendar.php")
{
add_to_head("<script src='".EMSDIR."includes/jQuery.bubbletip-1.0.6.js' type='text/javascript'></script>
<link href='".EMSDIR."includes/bubbletip/bubbletip.css' rel='stylesheet' type='text/css' />
<!--[if IE]>
<link href='".EMSDIR."includes/bubbletip/bubbletip-IE.css' rel='stylesheet' type='text/css' />
<![endif]-->");

// Background Colors for Dates
add_to_head("<style type='text/css'>
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
}

// Panel Title
opentable($locale['calendar_title']);

// Set the Month and Year to Current Time as Default
$month = date("n");
$year = date("Y");

// If User Requests for specific Month and Year, then Set values for it.
if (isset($_POST['prev_month']) && isNum($_POST['curr_month']) && ($_POST['curr_month'] <= 12))
{
	$month = $_POST['curr_month']-1;
	$year = $_POST['curr_year'];
}
if (isset($_POST['next_month']) && isNum($_POST['curr_month']) && ($_POST['curr_month'] <= 12))
{
	$month = $_POST['curr_month']+1;
	$year = $_POST['curr_year'];
}

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

// Title and Month changer Links
$month_num = date("m", $start);
$month_locale = "month_".$month_num;
$month_locale = $locale[$month_locale]; 
echo "<div class='panel-month' align='center' style='clear: both; display: block;'>\n";
echo "<form name='switch-calendar' method='post' action='".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>\n";
echo "<input type='hidden' name='curr_month' value='".$month."' />\n";
echo "<input type='hidden' name='curr_year' value='".$year."' />\n";
echo "<input type='submit' name='prev_month' value='&#171;' class='button' />\n";
echo $month_locale."&nbsp;".$year."\n";
echo "<input type='submit' name='next_month' value='&#187;' class='button' />\n";
echo "</form>\n";
echo "</div>\n<br />\n";

echo "<table cellpadding='0' cellspacing='0' width='100%' align='center'>\n";

echo "<tr>\n";
echo "\t<td class='panel-daynames'>".substr($locale['day_sun'], 0, 3)."</td>\n";
echo "\t<td class='panel-daynames'>".substr($locale['day_mon'], 0, 3)."</td>\n";
echo "\t<td class='panel-daynames'>".substr($locale['day_tue'], 0, 3)."</td>\n";
echo "\t<td class='panel-daynames'>".substr($locale['day_wed'], 0, 3)."</td>\n";
echo "\t<td class='panel-daynames'>".substr($locale['day_thu'], 0, 3)."</td>\n";
echo "\t<td class='panel-daynames'>".substr($locale['day_fri'], 0, 3)."</td>\n";
echo "\t<td class='panel-daynames'>".substr($locale['day_sat'], 0, 3)."</td>\n";
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
			echo "\t<td class='panel-dates panel-date'></td>\n";
		}
	}
	else
	{
		// If Today's Date comes in the Calendar, then Highlight it by applying Style
		$selected = ((($this_month == $month) && ($this_year == $year) && ($this_date == $count)) ? " current-day" : " panel-date");
		$rightborder = (($i%7==6)||($i == ($days_in_month+$day_of_week-1)) ? " panel-rightborder" : "");
		// Find if there is any Event/Birthday on this Date or Not
		$has_events = ((array_key_exists($count, $allevents) || (array_key_exists($count, $birthdays_cache))) ? " has-events" : "");

		// If the Day counter has started, then print the Dates
		echo "\t<td valign='middle' class='panel-dates".$selected.$rightborder.$has_events."'><a id='panel_day_".$count."' href='".EMSDIR."calendar.php?month=".$month."&amp;year=".$year."&amp;day=".$count."'>".$count."</a></td>\n";
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
		echo "<div class='panel-event-bubble-tip' id='panel_daytip_".$i."' style='display:none;'>\n";
		if (array_key_exists($i, $allevents))	{
			foreach ($allevents[$i] as $key=>$val)
			{
				echo "<a href='".EMSDIR."events.php?event=".$key."'><img src='".EMSDIR."images/calendar_icon_small.png' alt='' border='0' class='calendar-icon-small' /> ".$val."</a><br />\n";
			}
		}
		if (array_key_exists($i, $birthdays_cache))	{
			foreach ($birthdays_cache[$i] as $key=>$val)
			{
				echo "<a href='".BASEDIR."profile.php?lookup=".$key."'><img src='".EMSDIR."images/birthday.png' alt='' border='0' class='birthday-icon-small' /> ".$val."</a><br />\n";
			}
		}
		echo "</div>\n";
		$js_code_to_add .= "$('#panel_day_".$i."').bubbletip($('#panel_daytip_".$i."'), { deltaDirection: '".$emssettings['ems_panel_tip_direction']."' });\n";
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

}	// End of $infused_or_not condition

?>
