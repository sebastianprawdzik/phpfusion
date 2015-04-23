<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_lookup.php
| Author: Philip Daly (HobbyMan)
| Spam Rate by Nicolae Crefelean (Kneekoo)
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
add_to_title($locale['global_200'].$locale['uctitle'].$locale['global_200'].$locale['uc030'].$locale['uc024']);

add_to_head("<script type='text/javascript'>
checked=false;
function checkedAll (cont_del) {
	var aa= document.getElementById('cont_del');
	 if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 aa.elements[i].checked = checked;
	}
      }
	function ProcessDeletions() {
		return confirm('".$locale['uc060']."');
	}
</script>");

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['uc112'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['global_182'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}
  
if (!isset($_REQUEST['user_id'])) {
 $user_id = "";
include USER_CONT."inc/user_cont_nav.php";
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) { $user_id = $_POST['user_id']; }

    echo "<form name='cont_del' id='cont_del' method='post' action='".FUSION_SELF.$aidlink."'>\n";
    echo "<table cellpadding='1' align='center'>\n<tr>\n";
    echo "<td class='tbl2'><b>".$locale['uc030']." ".$locale['uc024']."</b></td>\n";
    echo "</tr><tr>\n";
    echo "<td class='tbl2' align='center'>".$locale['uc075'].$locale['uc030'].":<label><input type='textbox' name='user_id' class='textbox' maxlength='15' style='width:230px;' /></label></td>\n";
    echo "</tr><tr>\n";
    echo "<td class='tbl2' align='center'><input type='submit' name='id_lookup' value='".$locale['uc024']."' class='button' /></td>\n";
    echo "</tr>\n</table>\n";
    echo "</form>\n";

 } else {
 
 $query_srate = ($_REQUEST['user_id']);

if (isset($_GET['rowstart']) && isnum($_GET['rowstart'])) {
		$rowstart = $_GET['rowstart'];
	    } else {
		$rowstart = 0;
	}

if (isset($_POST['cont_del'])) {
$error = 0;
if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
$update_access = dbquery("UPDATE ".DB_UC_SETTINGS." SET uc_access='".stripinput($userdata['user_id'])."', uc_access_date='".stripinput($time)."'");
if(isset($_POST['post_del'])) {
        $Si['post_del'] = stripinput_fix($_POST['post_del']);
        foreach($Si['post_del'] as $key) {
                $result = dbquery("DELETE FROM ".DB_POSTS." WHERE post_id='".$key."'"); 
                $update = dbquery("UPDATE ".DB_USERS." SET user_posts='0' WHERE user_id='".$_GET['user_id']."'"); 
                $update = dbquery("UPDATE ".DB_THREADS." SET thread_lastpostid=thread_lastpostid-1, thread_lastuser='0' WHERE thread_author='".$_GET['user_id']."'"); 
        }
     }
if (isset($_POST['attach_del'])) {
        $Si['attach_del'] = stripinput_fix($_POST['attach_del']);
        foreach($Si['attach_del'] as $key) {
		$result = dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS." WHERE attach_id='".$key."'");
			  }
           }
if (isset($_POST['attach_delserv'])) {
        $Si['attach_delserv'] = stripinput_fix($_POST['attach_delserv']);
        foreach($Si['attach_delserv'] as $key) {
		$result = dbquery("SELECT attach_name FROM ".DB_FORUM_ATTACHMENTS." WHERE attach_id='".$key."'");
		if (dbrows($result) != 0) {
			while ($attach = dbarray($result)) {
				unlink(FORUM."attachments/".$attach['attach_name']);
		$result = dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS." WHERE attach_id='".$key."'");
			  }
           }   
        }
     }
if(isset($_POST['submit_del'])) {
        $Si['submit_del'] = stripinput_fix($_POST['submit_del']);
        foreach($Si['submit_del'] as $key) {
                $result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$key."'"); 
        }
     }
if(isset($_POST['comm_del'])) {
        $Si['comm_del'] = stripinput_fix($_POST['comm_del']);
        foreach($Si['comm_del'] as $key) {
                $result = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_id='".$key."'"); 
        }
     }
if (isset($_POST['shout_del'])) {
        $Si['shout_del'] = stripinput_fix($_POST['shout_del']);
        foreach($Si['shout_del'] as $key) {
        $result = dbquery("DELETE FROM ".DB_SHOUTBOX." WHERE shout_id='".$key."'");
       }   
    }
if (isset($_POST['pm_del'])) {
        $Si['pm_del'] = stripinput_fix($_POST['pm_del']);
        foreach($Si['pm_del'] as $key) {
        $result = dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_id='".$key."'");
       }   
    }
  } else {
		redirect(FUSION_SELF.$aidlink."&amp;user_id=".$query_srate."&error=1");
       }
	  redirect(FUSION_SELF.$aidlink."&amp;user_id=".$query_srate."&error=0");
 } unset($key);

   $user_query = dbarray(dbquery("SELECT 
                                         user_id, 
                                         user_name, 
                                         user_email, 
                                         user_hide_email, 
                                         user_ip,  
                                         user_web, 
                                         user_sig,
                                         user_posts,
                                         user_status   
                                         FROM ".DB_USERS." 
                                         WHERE user_id = '".$query_srate."'
                                         "));
include USER_CONT."inc/user_cont_nav.php";
  if ($user_query) {
     echo "<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='100%'>\n<tr>\n";
     echo "<th class='tbl2'>".$locale['uc030']."</th>\n";
     echo "<th class='tbl2'>".$locale['uc009']."</th>\n";
     echo "<th class='tbl2'>".$locale['uc013']."</th>\n";
     echo "<th class='tbl2'>".$locale['uc012']."</th>\n";
     echo "<th class='tbl2'>".$locale['uc052']."</th>\n";
     echo "<th class='tbl2'>".$locale['uc037']."</th>\n";
     echo "<th class='tbl2'>".$locale['uc024']."</th>\n";
     echo "</tr>\n";
     $tbl = 2;
     
     if (!strstr($user_query['user_web'], "http://") && !strstr($user_query['user_web'], "https://")) {
			$urlprefix = "http://";
		} else {
			$urlprefix = "";
		}

	$spam_rating = spam_rating($user_query['user_sig']);
	$spam_rating += spam_rating($user_query['user_web']);
	
	echo "<tr>\n";
	echo "<td class='tbl1' align='center'>".($user_query['user_status'] == 1 ? $img_user_banned : $img_user_clear)."</td>\n";
	echo "<td class='tbl1'>".profile_link($user_query['user_id'], $user_query['user_name'], $user_query['user_status'])."</td>\n";
	echo "<td class='tbl1'>".$user_query['user_email']." <span style='float:right;'>".($user_query['user_hide_email'] == 1 ? $img_hidden : $img_public)."</span></td>\n";
	echo "<td class='tbl1'>";
	if ($user_query['user_web']) {
	echo "<a href='".$urlprefix.$user_query['user_web']."' title='".$urlprefix.$user_query['user_web']."' target='_blank'>".$user_query['user_web']."</a>\n"; }
	echo "</td>\n";
	echo "<td class='tbl1'><a href='".ADMIN."members.php".$aidlink."&amp;step=edit&amp;user_id=".$query_srate."'>".$locale['global_120']."</a></td>\n";
	echo "<td class='tbl1' align='center'>".$spam_rating."</td>\n";
	echo "<td class='tbl1' align='center'><a href='".USER_CONT."multi_ip_lookup.php".$aidlink."&amp;user_ip=".$user_query['user_ip']."' title='IP lookup'>".$img_lookup."</a></td>\n";
	echo "</tr>\n</table>\n";
	echo "<br />\n";

	if ($user_query['user_sig']) {
	echo "<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='100%'>\n";
	echo "<th class='tbl2'>".$locale['uc027']." ".$locale['uc054']."</th>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'>".$user_query['user_sig']."</td>\n";
	echo "</tr>\n";
	echo "</table><br />\n"; }

// Form
    echo "<form id='cont_del' name='cont_del' method='post' action='".FUSION_SELF.$aidlink."&amp;user_id=".$query_srate."'>\n";
     
// List Posts 
     $post_count = (dbcount("(post_id)", DB_POSTS, "post_author = '".$query_srate."'"));
     $post_query = dbquery("SELECT 
                                   thread_id, 
                                   forum_id, 
                                   post_id, 
                                   post_message, 
                                   post_datestamp, 
                                   post_author  
                                   FROM ".DB_POSTS." 
                                   WHERE post_author = '".$query_srate."' 
                                   ORDER BY 
                                   post_datestamp 
                                   DESC LIMIT 0, $post_threshold
                                   ");
                                   
     if (dbrows($post_query) != 0) {
     echo "<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='100%'>\n<tr>\n";
     echo "<th class='tbl2'>".sprintf($locale['uc057'], $user_query['user_posts']).$user_query['user_name']."</th>\n";
     
     while ($data = dbarray($post_query)) {
     $tbl = $tbl == 1 ? 2 : 1;
     echo "<tr><td class='tbl'><b>".$locale['global_049'].": ".strftime('%d/%m/%Y %H:%M', $data['post_datestamp']+($settings['timeoffset']*3600))."</b>";
     echo "<span style='float:right;'>".$locale['uc061']."<label><input type='checkbox' name='post_del[]' value='".$data['post_id']."' /></label></span></td>\n</tr>\n";
     echo "<tr>\n<td class='tbl'>".$data['post_message']."</td>\n</tr>";
     echo "<tr>\n<td class='tbl'><span style='float:right;'><a href='".BASEDIR."forum/viewthread.php?thread_id=".$data['thread_id']."#post_".$data['post_id']."'>".$locale['global_140']."</a></span></td>\n</tr>\n";
     }
     echo "<tr>\n<td class='tbl1'>".$locale['ul003']."</td></tr>\n</table>\n";
     
   } else { echo "<div class='tbl2' align='center'>".sprintf($locale['uc057'], $user_query['user_posts']).$user_query['user_name']."<br />";
   if ($post_count != $user_query['user_posts']) { echo sprintf($locale['ul002'], $user_query['user_posts']); 
   echo "[".$post_count."]";
   }
   echo "</div>\n";
 }
 
 
// List Submissions
     $submit_count = (dbcount("(submit_id)", DB_SUBMISSIONS, "submit_user = '".$query_srate."'"));
     $submit_query = dbquery("SELECT 
                                   submit_id, 
                                   submit_type, 
                                   submit_user, 
                                   submit_datestamp  
                                   FROM ".DB_SUBMISSIONS." 
                                   WHERE submit_user = '".$query_srate."' 
                                   ORDER BY 
                                   submit_datestamp 
                                   DESC LIMIT 0, $post_threshold
                                   ");
                                   
     if (dbrows($submit_query) != 0) {
     echo "<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='100%'>\n<tr>\n";
     echo "<th class='tbl2'>".sprintf($locale['uc048'], $submit_count).$user_query['user_name']."</th>\n";
     
     while ($data = dbarray($submit_query)) {
     $tbl = $tbl == 1 ? 2 : 1;
     echo "<tr><td class='tbl1'><b>".$locale['uc045'].": ".strftime('%d/%m/%Y %H:%M', $data['submit_datestamp']+($settings['timeoffset']*3600))."</b>";
     echo "<span style='float:right;'>".$locale['uc061']."<label><input type='checkbox' name='submit_del[]' value='".$data['submit_id']."' /></label></span></td>\n</tr>\n";
     echo "<tr>\n<td class='tbl1'>".$submit_type[$data['submit_type']];
     echo "<span style='float:right;'><a href='".ADMIN."submissions.php".$aidlink."'>".$locale['global_140']."</a></span></td>\n</tr>\n";
     }
     echo "</table>\n";
     
   } else { echo "<div class='tbl2' align='center'><br />".sprintf($locale['uc048'], $submit_count).$user_query['user_name']."<br /></div>\n";
 }
 
 
//List Forum Attachments
     $attach_query = dbquery("SELECT 
                                   po.thread_id, 
                                   po.post_id,  
                                   po.post_datestamp, 
                                   po.post_author,
                                   fa.attach_id,
                                   fa.attach_name, 
                                   fa.post_id, 
                                   fa.attach_ext 
                                   FROM ".DB_POSTS." po 
                                   LEFT JOIN ".DB_FORUM_ATTACHMENTS." fa USING(post_id)  
                                   WHERE po.post_author = '".$query_srate."' 
                                   AND fa.post_id = po.post_id 
                                   ORDER BY 
                                   po.post_datestamp 
                                   DESC LIMIT 0, $post_threshold
                                   ");
                                   
     if (dbrows($attach_query) != 0) {
     require_once INCLUDES."forum_include.php";
     echo "<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='100%'>\n<tr>\n";
     echo "<th class='tbl2'>".$user_query['user_name'].$locale['ul004']."</th>\n";
     
      while ($dataq = dbarray($attach_query)) {
     $tbl = $tbl == 1 ? 2 : 1;
     echo "<tr>\n<td class='tbl1'>";
     if ($dataq['attach_id'] && $dataq['attach_name'] && file_exists(FORUM."attachments/".$dataq['attach_name'])) {
			if (in_array($dataq['attach_ext'], $imagetypes) && @getimagesize(FORUM."attachments/".$dataq['attach_name'])) {
				echo "\n<hr />\n".$user_query['user_name'].$locale['ul005']."<br /><br />\n".display_image($dataq['attach_name'])."<br />[".parsebytesize(filesize(FORUM."attachments/".$dataq['attach_name']))."]\n";
			} else {
				echo "\n<hr />\n".$user_query['user_name'].$locale['ul006']."<br />\n<a href='".FORUM."viewthread.php?thread_id=".$dataq['thread_id']."&amp;getfile=".$dataq['post_id']."'>".$dataq['attach_name']."</a>";
			}
		}
     echo "</td><td class='tbl1' align='right' valign='bottom'>
     ".$locale['ul008']."<label><input type='checkbox' name='attach_del[]' value='".$dataq['attach_id']."' /></label><br />
     ".$locale['ul009']."<label><input type='checkbox' name='attach_delserv[]' value='".$dataq['attach_id']."' /></label></span></td>\n</tr>\n";
    echo "</td>\n</tr>";
     }
     echo "</table>\n";
    } else { echo "<div class='tbl2' align='center'>".$user_query['user_name'].$locale['ul007']."<br /></div>\n";
  }
 
// List Comments
     $comment_count = (dbcount("(comment_id)", DB_COMMENTS, "comment_name = '".$query_srate."'"));
     $comm_query = dbquery("SELECT 
                                   comment_id, 
                                   comment_type, 
                                   comment_name, 
                                   comment_message, 
                                   comment_datestamp, 
                                   comment_ip 
                                   FROM ".DB_COMMENTS." 
                                   WHERE comment_name = '".$query_srate."' 
                                   ORDER BY 
                                   comment_datestamp 
                                   DESC LIMIT 0,$post_threshold
                                   ");
                                   
     if (dbrows($comm_query) != 0) {
     include INCLUDES."comments_include.php";
     
     echo "<br />\n<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='100%'>\n<tr>\n";
     echo "<th class='tbl2'>".sprintf($locale['uc058'], $comment_count).$user_query['user_name']."</th>\n";
     
     while ($data = dbarray($comm_query)) {
     $tbl = $tbl == 1 ? 2 : 1;
     echo "<tr><td class='tbl1'><b>".$locale['global_049'].": ".strftime('%d/%m/%Y %H:%M', $data['comment_datestamp']+($settings['timeoffset']*3600))."</b>";
     echo "<span style='float:right;'>".$locale['uc061']."<label><input type='checkbox' name='comm_del[]' value='".$data['comment_id']."' /></label></span></td>\n</tr>\n";
     echo "</td>\n</tr>\n";
     echo "<tr><td class='tbl1'>".$data['comment_message']."</td>\n</tr>";
     }
     echo "</table>\n";
     
   } else { echo "<div class='tbl2' align='center'>".sprintf($locale['uc058'], $comment_count).$user_query['user_name']."<br /></div>\n";
 }
 
if ($shoutbox) {
// List Shouts
     $shout_count = (dbcount("(shout_id)", DB_SHOUTBOX, "shout_name = '".$query_srate."'"));
     $shout_query = dbquery("SELECT 
                                   shout_id, 
                                   shout_message, 
                                   shout_name, 
                                   shout_datestamp
                                   FROM ".DB_SHOUTBOX." 
                                   WHERE shout_name = '".$query_srate."' 
                                   ORDER BY 
                                   shout_datestamp 
                                   DESC LIMIT 0, $post_threshold
                                   ");
                                   
     if (dbrows($shout_query) != 0) {
     echo "<br />\n<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='100%'>\n<tr>\n";
     echo "<th class='tbl2'>".sprintf($locale['uc059'], $shout_count).$user_query['user_name']."</th>\n";
     
     while ($data = dbarray($shout_query)) {
     $tbl = $tbl == 1 ? 2 : 1;
     echo "<tr><td class='tbl1'><b>".$locale['global_049'].": ".strftime('%d/%m/%Y %H:%M', $data['shout_datestamp']+($settings['timeoffset']*3600))."</b>";
     echo "<span style='float:right;'>".$locale['uc061']."<label><input type='checkbox' name='shout_del[]' value='".$data['shout_id']."' /></label></span></td>\n</tr>\n";
     echo "</td>\n</tr>\n";
     echo "<tr><td class='tbl1'>".$data['shout_message']."</td>\n</tr>";
     }
     echo "</table>\n";
     
   } else { echo "<div class='tbl2' align='center'>".sprintf($locale['uc059'], $shout_count).$user_query['user_name']."</div>\n";
        }
      }
        
// List Private Messages
     $pm_count = (dbcount("(message_id)", DB_MESSAGES, "message_from = '".$query_srate."'"));
     $pm_query = dbquery("SELECT 
                                   message_id, 
                                   message_to, 
                                   message_from, 
                                   message_subject, 
                                   message_message, 
                                   message_datestamp
                                   FROM ".DB_MESSAGES." 
                                   WHERE message_from = '".$query_srate."' 
                                   AND message_to != '".$query_srate."'
                                   ORDER BY 
                                   message_datestamp 
                                   DESC LIMIT 0, $post_threshold
                                   ");
                                   
     if (dbrows($pm_query) != 0) {
     echo "<br />\n<table class='tbl-border' cellspacing='1' cellpadding='4' align='center' width='100%'>\n<tr>\n";
     echo "<th class='tbl2'>".sprintf($user_query['user_name'].$locale['ul010'], $pm_count)."</th>\n";
     
     while ($datapm = dbarray($pm_query)) {
     $tbl = $tbl == 1 ? 2 : 1;
     $pm_to = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id = '".$datapm['message_to']."'"));
     $spam_rating = spam_rating($datapm['message_message']);
     echo "<tr><td class='tbl1'><b>".$locale['ul011'].profile_link($datapm['message_to'], $pm_to['user_name'], $user_query['user_status'])." ".$locale['global_071'].strftime('%d/%m/%Y %H:%M', $datapm['message_datestamp']+($settings['timeoffset']*3600)).sprintf($locale['ul012'], $spam_rating)."</b>";
     echo "<span style='float:right;'>".$locale['uc061']."<label><input type='checkbox' name='pm_del[]' value='".$datapm['message_id']."' /></label></span></td>\n</tr>\n";
     echo "</td>\n</tr>\n";
     }
     echo "</table>\n";
     
   } else { echo "<div class='tbl2' align='center'>".sprintf($user_query['user_name'].$locale['ul010'], $pm_count)."<br /></div>\n";
        }   
     echo "<table cellpadding='1' align='center' width='100%'>\n<tr>\n";
	 echo "<td align='right' class='tbl2'>".$locale['iu014']."<label><input type='checkbox' name='checkall' onclick='checkedAll(cont_del);'></span></td>\n</tr>";
	 
	if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	echo "<tr>\n<td class='tbl' align='center'>".$locale['uc111']." <input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n</tr>\n";
} 
 
     echo "<tr>\n<td class='tbl2' align='center'>\n";
     $admin_check = dbarray(dbquery("SELECT user_level FROM ".DB_USERS." WHERE user_id = '".$query_srate."'"));
     
     if ($admin_check['user_level'] > "101") { echo "<span style='color:red'><b>".$locale['uc099']."!</b></span>\n";
     } else {
     echo "<input type='submit' name='cont_del' onclick=\"return ProcessDeletions();\" value='".$locale['ul001']."' class='button' />\n";
     echo "<input type='submit' name='cancel' value='".$locale['uc020']."' class='button' />\n";
     }
     echo "</td>\n";
     echo "</tr>\n</table>\n</form>";
	 
      } else { 
      echo "<div class='tbl2' align='center'>".sprintf($locale['uc069'], $query_srate)."</div>\n";
    }
 }
require_once THEMES."templates/footer.php";
?>