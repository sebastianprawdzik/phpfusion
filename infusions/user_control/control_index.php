<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: control_index.php
| Author: Philip Daly (HobbyMan)
| Adapted Member Stats 
| From Site Stats 1.3 by digifredje
| Web: http://www.fmbel.be/
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
require_once INCLUDES."infusions_include.php";
require INFUSIONS."user_control/inc/functions_include.php";

add_to_title($locale['global_200'].$locale['uctitle']);
include USER_CONT."inc/user_cont_nav.php";

      $total_total = dbcount("(user_id)", DB_USERS);
      $total_staff = dbcount("(user_id)", DB_USERS, "user_level>'101'");
     $total_sadmin = dbcount("(user_id)", DB_USERS, "user_level='103'");
      $total_admin = dbcount("(user_id)", DB_USERS, "user_level='102'");
      $total_clear = dbcount("(user_id)", DB_USERS, "user_level='101' && user_status = '0'");
     $total_banned = dbcount("(user_id)", DB_USERS, "user_status = '1'");
$total_unactivated = dbcount("(user_id)", DB_USERS, "user_status = '2'");
  $total_suspended = dbcount("(user_id)", DB_USERS, "user_status = '3'");
  $security_banned = dbcount("(user_id)", DB_USERS, "user_status = '4'");
       $total_anon = dbcount("(user_id)", DB_USERS, "user_status = '6'");

$admin_query = dbarray(dbquery("SELECT 
                                       user_id, 
                                       user_name, 
                                       user_ip, 
                                       user_status 
                                       FROM ".DB_USERS." 
                                       WHERE user_id = '".$uc_globalsettings['uc_access']."'
                                       OR user_id = '".$uc_globalsettings['uc_update']."'
                                       "));
                                       
opentable($locale['ci00']);

    echo "<table cellpadding='1' width='100%'>\n<tr>\n";
    echo "<td class='tbl2'><b>".$locale['uc105']."</b>".profile_link($uc_globalsettings['uc_access'], $admin_query['user_name'], $admin_query['user_status']).$locale['uc103'].strftime('%d/%m/%Y %H:%M', $uc_globalsettings['uc_access_date']+($settings['timeoffset']*3600))."</td>\n";
    echo "<td class='tbl2'><b>".$locale['uc102']."</b>".profile_link($uc_globalsettings['uc_update'], $admin_query['user_name'], $admin_query['user_status']).$locale['uc103'].strftime('%d/%m/%Y %H:%M', $uc_globalsettings['uc_update_date']+($settings['timeoffset']*3600))."</td>\n";
    echo "</tr>\n</table>\n";
    
    echo "<table cellpadding='1' width='100%'>\n<tr>\n";
    echo "<td class='forum-caption' colspan='4'><strong>".$locale['ci20']."</strong></td>\n";
    echo "</tr>\n<tr>\n";
    echo "<td class='tbl1'>".$locale['ci21']."</td><td class='tbl1'>".$total_total."</td>\n";
    echo "<td class='tbl1'>".$locale['ci22']."</td><td class='tbl1'>".$total_staff."</td>\n";
    echo "</tr>\n<tr>\n";
    
    echo "<td class='tbl1'>".$locale['ci23']."</td><td class='tbl1'>".$total_sadmin."</td>\n";
    echo "<td class='tbl1'>".$locale['ci24']."</td><td class='tbl1'>".$total_admin."</td>\n";
    echo "</tr>\n<tr>\n";
    
    
    echo "<td class='tbl1'>".$locale['ci25']."</td><td class='tbl1'>".$total_clear."</td>\n";
    echo "<td class='tbl1'>".$locale['ci26']."</td><td class='tbl1'>".$total_banned."</td>\n";
    echo "</tr>\n<tr>\n";
    echo "<td class='tbl1'>".$locale['ci27']."</td><td class='tbl1'>".$total_suspended."</td>\n";
    echo "<td class='tbl1'>".$locale['ci29']."</td><td class='tbl1'>".$total_anon."</td>\n";
    
    echo "</tr>\n<tr>\n";
    echo "<td class='tbl1'>".$locale['ci28']."</td><td class='tbl1'>".$security_banned."</td>\n";
    echo "<td class='tbl1'>".$locale['ci30']."</td><td class='tbl1'>".$total_unactivated."</td>\n";

    echo "</tr>\n<tr>\n</table>\n";



// Globale Variabelen
$time = time();
$since = 0;
$launched_last_visit = $lastvisited;	
$start_today = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
$start_yesterday = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
$start_this_month = mktime(0, 0, 0, date("m")  , 1, date("Y"));
$start_last_month = mktime(0, 0, 0, date("m")-1  , 1, date("Y"));

$num_last_visit = 0;

// User Variables
$u_num_last_visit = 0;
$u_num_today = 0;
$u_num_yesterday = 0;
$u_num_this_month = 0;
$u_num_prev_month = 0;

// Visits Variables
$b_num_last_visit = 0;
$b_num_today = 0;
$b_num_yesterday = 0;
$b_num_this_month = 0;
$b_num_prev_month = 0;

if ($launched_last_visit < $start_last_month)
	{$since = $launched_last_visit;
	} else {
	$since = $start_last_month; }

// Find first registration date
$u_result_first = dbquery("SELECT MIN(user_joined) as u_first FROM ".DB_USERS);
$u_data_first = dbarray($u_result_first);
$u_first_user = $u_data_first['u_first'];

// Find total number of users
$u_result_total = dbquery("SELECT count(*) as u_total FROM ".DB_USERS);
$u_data_total = dbarray($u_result_total);
$u_total_user = $u_data_total['u_total'];


// Determine num day and months with users
if ($u_first_user == 0) {
	$u_num_day = 1;
	$u_num_months = 1;
	} else {
	$u_num_day = ceil((time() - $u_first_user)/(60*60*24));
	$u_num_months = ceil((time() - $u_first_user)/(60*60*24*30));
  }

// Calculate average registrations per day and month
$u_avg_day= round($u_total_user / $u_num_day);
$u_avg_month = round($u_total_user / $u_num_months);

// Find all users since smallest date
$u_result = dbquery("SELECT user_joined FROM ".DB_USERS." WHERE user_joined >= ".$since);

// Find all visits since smallest date
$b_result = dbquery("SELECT user_lastvisit FROM ".DB_USERS." WHERE user_lastvisit >= ".$since);


//Count the number of new members for the various dates		
if (dbrows($u_result) != 0) {
	while($u_data = dbarray($u_result)) { 
	if ($u_data['user_joined'] > $launched_last_visit) {
			$u_num_last_visit++;
		}
		if ($u_data['user_joined'] > $start_today) {
			$u_num_today++;
		}
		if ($u_data['user_joined'] > $start_this_month) {
			$u_num_this_month++;
		}
		if (($u_data['user_joined'] >= $start_last_month) && ($u_data['user_joined'] < $start_this_month)) {
			$u_num_prev_month++;
		}
		if (($u_data['user_joined'] >= $start_yesterday) && ($u_data['user_joined'] < $start_today)) {
			$u_num_yesterday++;
			}	
		}
	}

// Count the number of visits for different dates
if (dbrows($b_result) != 0)
	{
	while($b_data = dbarray($b_result))
		{
		if ($b_data['user_lastvisit'] > $launched_last_visit) {
			$b_num_last_visit++;
		}
		if ($b_data['user_lastvisit'] > $start_today) {
			$b_num_today++;
		}
		if ($b_data['user_lastvisit'] > $start_this_month) {
			$b_num_this_month++;
		}
		if (($b_data['user_lastvisit'] >= $start_last_month) && ($b_data['user_lastvisit'] < $start_this_month)) {
			$b_num_prev_month++;
		}
		if (($b_data['user_lastvisit'] >= $start_yesterday) && ($b_data['user_lastvisit'] < $start_today)) {
			$b_num_yesterday++;
			}	
		}
	}
	
// New Members
	echo "<table cellpadding='0' cellspacing='0' width='100%'><tr>\n";
	echo "<td class='forum-caption' colspan='4'><strong>".$locale['ci14']."</strong></td>\n";
	echo "</tr>\n<tr>\n";
	
	echo "<td class='tbl1'>".$locale['ci02']."</td>\n";
	echo "<td class='tbl1'>".$u_num_last_visit."</td>\n";
	echo "<td class='tbl1'></td>\n";
	echo "<td class='tbl1'></td>\n";

	echo "</tr>\n<tr>\n";
	
	echo "<td class='tbl1'>".$locale['ci03']."</td>\n";
	echo "<td class='tbl1'>".$u_num_today."</td>\n";
	echo "<td class='tbl1'>".$locale['ci05']."</td>\n";
	echo "<td class='tbl1'>".$u_num_this_month."</td>\n";
	
	echo "</tr>\n<tr>\n";
	
	echo "<td class='tbl1'>".$locale['ci04']."</td>\n";
	echo "<td class='tbl1'>".$u_num_yesterday."</td>\n";
	echo "<td class='tbl1'>".$locale['ci06']."</td>\n";
	echo "<td class='tbl1'>".$u_num_prev_month."</td>\n";
	
	echo "</tr>\n<tr>\n";
	
	echo "<td class='tbl1'>".$locale['ci12']."</td>\n";
	echo "<td class='tbl1'>".$u_avg_day."</td>\n";		
	echo "<td class='tbl1'>".$locale['ci13']."</td>\n";
	echo "<td class='tbl1'>".$u_avg_month."</td>\n";
	
	echo "</tr>\n</table>\n";
	echo "<br />\n";
	
// Unique members visit
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
	echo "<td class='forum-caption' colspan='4'><strong>".$locale['ci15']."</strong></td>\n";
	echo "</tr>\n<tr>\n";
	
	echo "<td width='30%' class='tbl1'>".$locale['ci02']."</td>\n";
	echo "<td class='tbl1'>".$b_num_last_visit."</td>";
	echo "<td class='tbl1'> </td>\n";
	echo "<td class='tbl1'> </td>\n";
	
	echo "</tr>\n<tr>\n";
	
	echo "<td  class='tbl1'>".$locale['ci03']."</td>\n";
	echo "<td class='tbl1'>".$b_num_today."</td>\n";
	echo "<td class='tbl1'>".$locale['ci05']."</td>\n";
	echo "<td class='tbl1'>".$b_num_this_month."</td>\n";
	echo "</tr>\n</table>\n";
	echo "<br />\n<div class='admin-message' align='center'><strong>".$locale['uc021']."</strong></div>\n<br />\n";	
closetable();

 echo $uc_footer;

require_once THEMES."templates/footer.php";
?>