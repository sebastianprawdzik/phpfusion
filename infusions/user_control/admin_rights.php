<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2012 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin_rights.php
| Author: Yxos
| Adapted for User Control
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
require INFUSIONS."user_control/inc/functions_include.php";
include LOCALE.LOCALESET."admin/admins.php";
add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['uc079']);

if (!iSUPERADMIN || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

include USER_CONT."inc/user_cont_nav.php";
opentable($locale['420']);

	$result = dbquery("SELECT 
	                          user_id, 
	                          user_name, 
	                          user_level, 
	                          user_rights 
	                          FROM ".DB_USERS." 
	                          WHERE user_level > '101' 
	                          ORDER BY 
	                          user_level 
	                          DESC, user_id
	                          ");
	if (dbrows($result)) {
		$list = "<tr><th class='tbl2'><strong>".$locale['422']."</strong></th>
		             <th class='tbl2'><strong>".$locale['413']."</strong></th>
		             <th class='tbl2'><strong>".$locale['uc079']."</strong></th>
		             </tr>";
		while ($data = dbarray($result)) {
		$user_rights = explode(".", $data['user_rights']);
			$link = "<a href='".ADMIN."administrators.php".$aidlink."&amp;edit=".$data['user_id']."' target='_blank'>".$data['user_name']."</a>";
			$col = ($i % 2 == 0 ? "tbl2" : "tbl1"); $i++;
			$list .= "<tr>";
			$list .= "<td class='$col'>".getuserlevel($data['user_level'])."</td>";
			$list .= "<td class='$col'><strong>".($data['user_level'] == 102 ? (checkrights("AD") ? $link : $data['user_name']) : $data['user_name'])."</strong></td>";
			$rights = $data['user_rights']."'".(in_array($data['user_id'], $user_rights));	
			$list .= "<td class='$col' align='center'><small>".($data['user_rights'] == '' ? $locale['iu002'] : preg_replace("/[^a-zA-Z0-9\s]/", " ", $rights))."</small></td></tr>";

		}
	}
	echo "<table align='center' cellpadding='0' cellspacing='1' width='80%' class='tbl-border'>".$list."</table>";
closetable();

$admin_page = array($locale['441'], $locale['442'], $locale['443'], $locale['449'], $locale['444']);
$idxpage = 1;
while ($idxpage <= 5):
	$adminresult = dbquery("SELECT 
	                               admin_rights, 
	                               admin_title 
	                               FROM ".DB_ADMIN." 
	                               WHERE admin_page='".$idxpage."' 
	                               ORDER BY 
	                               admin_title
	                               ");
	if (dbrows($adminresult)) {
		opentable($admin_page[$idxpage-1]);
			$list = "<tr>";
			$columns = 2; $counter = 0;
			while ($admindata = dbarray($adminresult)) {
				if ($counter != 0 && ($counter % $columns == 0)) { $list .= "</tr>\n<tr>\n"; $col = ($i % 2 == 0 ? "tbl2" : "tbl1"); $i++;}
				$list .= "<td class='$col' width='20%'>".$admindata['admin_title']."<td/>";
				$list .= "<td class='$col' width='30%'>";

				$userresult = dbquery("SELECT 
				                              user_id,
				                              user_name,
				                              user_rights, 
				                              user_level 
				                              FROM ".DB_USERS." 
				                              WHERE user_level > '101' 
				                              ORDER BY 
				                              user_name 
				                              ASC
				                              ");
				if (dbrows($userresult)) {
					while ($userdata = dbarray($userresult)) {
					    if ($userdata['user_level'] == '102') {
						$link = "<a href='".ADMIN."administrators.php".$aidlink."&amp;edit=".$userdata['user_id']."' target='_blank'>".$userdata['user_name']."</a>";
				    	} else { $link = $userdata['user_name']; }
						if (in_array($admindata['admin_rights'], explode(".", $userdata['user_rights']))) {
							$list .= (checkrights("AD") ? $link : $userdata['user_name'])."<br />";
						}
					}
				}
				$list .= "<td/>";
				$counter++;
			}
			if ($counter % $columns != 0) { $list .="<td class='$col' width='20%'><td/><td class='$col' width='30%'><td/>"; }
			$list .= "<tr/>";

			echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>".$list."</table>";
		closetable();
	}
	$idxpage++;
endwhile;

require_once THEMES."templates/footer.php";
?>