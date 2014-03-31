<?php
 
// NOTE: it's best practice to CLEAN YOUR INPUTS
// http://www.acunetix.com/websitesecurity/php-security-1.htm
 
function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
$file = $path.$filename;
$file_size = filesize($file);
$handle = fopen($file, "r");
$content = fread($handle, $file_size);
fclose($handle);
$content = chunk_split(base64_encode($content));
$uid = md5(uniqid(time()));
$header = "From: ".$from_name." <".$from_mail.">\r\n";
$header .= "Reply-To: ".$replyto."\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
$header .= "This is a multi-part message in MIME format.\r\n";
$header .= "--".$uid."\r\n";
$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$header .= $message."\r\n\r\n";
$header .= "--".$uid."\r\n";
$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
$header .= "Content-Transfer-Encoding: base64\r\n";
$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
$header .= $content."\r\n\r\n";
$header .= "--".$uid."--";
// Messages for testing only, nobody will see them unless this script URL is visited manually
if (mail($mailto, $subject, "", $header)) {
echo "Message sent!";
} else {
echo "ERROR sending message.";
}
}

// EDIT FROM HERE DOWN TO
// CUSTOMIZE EMAIL
// File to attach
$my_file = "1_gt_02062014_2.xml";
$my_path = 'https://chicagoconnection.kulacart.net/uploads/orderxml/'; // $_SERVER['DOCUMENT_ROOT']."/your_path_here/";
// Who email is FROM
$my_name = "surender";
$my_mail = "surendraindiajoin@gmail.com";
$my_replyto = "test@gmail.com";
// Whe email is going TO
$to_email = "surendraindiajoin@gmail.com"; // Comes from Wufoo WebHook
// Subject line of email
$my_subject = "Your file has arrived!";
// Content of email message (Text only)
$requester ="surendraindiajoin@gmail.com"; // Comes from Wufoo WebHook
$message = "Hey $requester,
Your custom email message
goes here";
// Call function to send email
mail_attachment($my_file, $my_path, $to_email, $my_mail, $my_name, $my_replyto, $my_subject, $message);
 
?>