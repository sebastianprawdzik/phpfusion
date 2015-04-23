<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Infusion: Event Management System
| Filename: functions.php
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

// Function to Calculate Time Left
function calctimeleft($starttime, $endtime)
{
	global $locale;

	$res = "";

	if (is_event_finished($endtime))
	{
		$res .= $locale['event_status_finished'];
	}
	else if (is_event_started($starttime))
	{
		$res .= $locale['event_status_started'];
	}
	else if (!is_event_started($starttime) && !is_event_finished($endtime))
	{
		$total = ($starttime-time());
		$days = (int)($total/86400);
		$hours = (int)($total/3600);
		$mins = (int)($total/60);
		$secs = (int)($total % 60);

		if ($days != 0)
		{
			$res .= sprintf($locale['time_left_days'], $days);
		}
		else if ($hours != 0)
		{
			$res .= sprintf($locale['time_left_hours'], $hours);
		}
		else if ($mins != 0)
		{
			$res .= sprintf($locale['time_left_mins'], $mins);
		}
		else if ($secs != 0)
		{
			$res .= sprintf($locale['time_left_secs'], $secs);
		}
	}
	return $res;
}

function is_event_finished($endtime)
{
	if ($endtime == 0)
	{
		return false;
	}
	else if (time() > $endtime)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function is_event_started($starttime)
{
	if ($starttime == 0)
	{
		return true;
	}
	else if (time() > $starttime)
	{
		return true;
	}
	else
	{
		return false;
	}
}

?>