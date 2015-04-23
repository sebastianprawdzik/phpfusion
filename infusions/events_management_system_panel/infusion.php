<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Infusion: Event Management System
| Filename: infusion.php
| Developer: Ankur Thakur, Nick Jones
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

include INFUSIONS."events_management_system_panel/infusion_db.php";

if (file_exists(INFUSIONS."events_management_system_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."events_management_system_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."events_management_system_panel/locale/English.php";
}

// Infusion general information
$inf_title = $locale['inf_title'];
$inf_description = $locale['inf_description'];
$inf_version = "1.00";
$inf_developer = "Ankur Thakur";
$inf_email = "admin@phpfusionmods.co.uk";
$inf_weburl = "http://www.phpfusionmods.co.uk";

$inf_folder = "events_management_system_panel";

$inf_newtable[1] = DB_EVENTS." (
event_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
event_title VARCHAR(100) NOT NULL DEFAULT '',
event_author MEDIUMINT(8) UNSIGNED NOT NULL,
event_startdate INT(10) UNSIGNED DEFAULT '0',
event_enddate INT(10) UNSIGNED DEFAULT '0',
event_text TEXT NOT NULL,
event_visibility INT(3) UNSIGNED DEFAULT '0',
event_image VARCHAR(100) NOT NULL DEFAULT '',
event_thumb_image VARCHAR(100) NOT NULL DEFAULT '',
event_allow_comments TINYINT(1) UNSIGNED DEFAULT '0',
event_allow_ratings TINYINT(1) UNSIGNED DEFAULT '0',
event_hidden TINYINT(1) UNSIGNED DEFAULT '0',
PRIMARY KEY (event_id)
) ENGINE=MyISAM;";

// Infusion Settings
$inf_insertdbrow[1] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_member_post_allow', '0', '".$inf_folder."')";
$inf_insertdbrow[2] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_post_admin_moderate', '1', '".$inf_folder."')";
$inf_insertdbrow[3] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_show_birthdays_calendar', '1', '".$inf_folder."')";
$inf_insertdbrow[4] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_image_max_width', '1800', '".$inf_folder."')";
$inf_insertdbrow[5] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_image_max_height', '1600', '".$inf_folder."')";
$inf_insertdbrow[6] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_thumb_max_width', '200', '".$inf_folder."')";
$inf_insertdbrow[7] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_thumb_max_height', '200', '".$inf_folder."')";
$inf_insertdbrow[8] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_image_max_size', '300000', '".$inf_folder."')";
$inf_insertdbrow[9] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_calendar_tip_direction', 'up', '".$inf_folder."')";
$inf_insertdbrow[10] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_panel_tip_direction', 'left', '".$inf_folder."')";
// Style CSS
// BG Color
$inf_insertdbrow[11] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_bgcolor_currentdate', 'FF0000', '".$inf_folder."')";
$inf_insertdbrow[12] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_bgcolor_eventdate', '00FF00', '".$inf_folder."')";
$inf_insertdbrow[13] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_bgcolor_dates', 'FFFFFF', '".$inf_folder."')";
// Text Color
$inf_insertdbrow[14] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_color_currentdate', 'FFFFFF', '".$inf_folder."')";
$inf_insertdbrow[15] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_color_eventdate', 'FF0000', '".$inf_folder."')";
$inf_insertdbrow[16] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_color_dates', '000000', '".$inf_folder."')";
// Days Colors
$inf_insertdbrow[17] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_bgcolor_days', '333333', '".$inf_folder."')";
$inf_insertdbrow[18] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('ems_color_days', 'FFFFFF', '".$inf_folder."')";

// Add Site Link - Events
$inf_sitelink[1]['title'] = $locale['event_view'];
$inf_sitelink[1]['url'] = "events.php";
$inf_sitelink[1]['visibility'] = "0";

// Delete Infusion Settings
$inf_deldbrow[1] = DB_SETTINGS_INF." WHERE settings_inf='".$inf_folder."'";

// Delete Comments on Events
$inf_deldbrow[2] = DB_COMMENTS." WHERE comment_type='EM'";

// Delete Ratings on Events
$inf_deldbrow[3] = DB_RATINGS." WHERE rating_type='E'";

// Drop Table Events
$inf_droptable[1] = DB_EVENTS;

$inf_adminpanel[1] = array(
	"title" => $locale['inf_title'],
	"image" => "ems.png",
	"panel" => "events_management_system_panel_admin.php",
	"rights" => "EMS"
);

?>