<?php
/*---------------------------------------------------+
| PHP-Fusion 7 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2012 Nick Jones
| http://www.php-fusion.co.uk/
|----------------------------------------------------+
| Filename: infusion_db.php
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

if (!defined("DB_UC_SETTINGS")) {
	define("DB_UC_SETTINGS", DB_PREFIX."uc_settings");
}

if (!defined("DB_SHOUTBOX")) {
	define("DB_SHOUTBOX", DB_PREFIX."shoutbox");
}
?>