<?php
$message = __("Hi", "ultimate-blogroll").",<br /><br />".__("Somebody added a new link in", "ultimate-blogroll")." Wordpress Ultimate Blogroll<br />";
$message .= "<table>";
$message .= "<tr><td style=\"width: 250px;\">".__("Website owner's name", "ultimate-blogroll").":</td><td>".$linkpartner["your_name"]."</td></tr>";
$message .= "<tr><td>".__("Website owner's email", "ultimate-blogroll").":</td><td>".$linkpartner["your_email"]."</td></tr>";
$message .= "<tr><td><br /></td></tr>";
$message .= "<tr><td>".__("Website url", "ultimate-blogroll").":</td><td>".$linkpartner["website_url"]."</td></tr>";
$message .= "<tr><td>".__("Website title", "ultimate-blogroll").":</td><td>".$linkpartner["website_title"]."</td></tr>";
$message .= "<tr><td>".__("Website description", "ultimate-blogroll").":</td><td>".$linkpartner["website_description"]."</td></tr>";
$message .= "<tr><td><br /></td></tr>";
$message .= "<tr><td>".__("Website reciprocal", "ultimate-blogroll").":</td><td>".$linkpartner["website_reciprocal"]."</td></tr>";
$message .= "<tr><td>".__("Website image", "ultimate-blogroll").":</td><td>".$linkpartner["website_image"]."</td></tr>";
$message .= "</table>Do you want to <a href=\"".get_admin_url()."admin.php?".http_build_query(array("page" => "ultimate-blogroll-overview", "action" => "edit", "id" => $id ))."#edit\">View details</a> | <a href=\"".get_admin_url()."admin.php?".http_build_query(array("page" => "ultimate-blogroll-overview", "additional" => "mail", "linkpartner[]" => $id))."\">Approve</a> | <a href=\"".get_admin_url()."admin.php?".http_build_query(array("page" => "ultimate-blogroll-overview", "additional" => "mail", "linkpartner[]" => $id))."\">Delete</a>";
echo $message;
?>