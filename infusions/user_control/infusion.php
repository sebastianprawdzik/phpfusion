<?php
/*---------------------------------------------------+
| PHP-Fusion 7 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
|----------------------------------------------------+
| Infusion: User Control Settings
| Filename: infusion.php
| Author: Philip Daly (HobbyMan)
+----------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+----------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (file_exists(INFUSIONS."user_control/locale/".$settings['locale'].".php")) {
	include INFUSIONS."user_control/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."user_control/locale/English.php"; }

include INFUSIONS."user_control/infusion_db.php";

$inf_title = $locale['uctitle'];
$inf_description = $locale['ucdesc'];
$inf_version = "1.24";
$inf_developer = "HobbyMan";
$inf_email = "";
$inf_weburl = "http://www.hobbysites.net/";

$inf_folder = "user_control";

$inf_newtable[1] = DB_UC_SETTINGS." (
uc_view VARCHAR(200) NOT NULL DEFAULT '',
uc_show_icons TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
uc_spamrate MEDIUMINT(5) UNSIGNED DEFAULT '0' NOT NULL,
uc_post_num MEDIUMINT(5) UNSIGNED DEFAULT '0' NOT NULL,
uc_access MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
uc_access_date INT(10) UNSIGNED DEFAULT '0' NOT NULL,
uc_update MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
uc_update_date INT(10) UNSIGNED DEFAULT '0' NOT NULL,
PRIMARY KEY (uc_view)
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_UC_SETTINGS." (uc_view, uc_show_icons, uc_spamrate, uc_post_num, uc_access, uc_access_date, uc_update, uc_update_date) 
VALUES ('0', '1', '20', '20', '".$userdata['user_id']."', '".time()."', '".$userdata['user_id']."', '".time()."')";
$inf_droptable[1] = DB_UC_SETTINGS;

$inf_droptable[1] = DB_UC_SETTINGS;

$inf_adminpanel[1] = array(
	"title" => $locale['uctitle'],
	"image" => "user_control.gif",
	"panel" => "control_index.php",
	"rights" => "USCT"
);

?>