<?php 
require($_SERVER['DOCUMENT_ROOT']."/classes/attach_mailer/attach_mailer_class.php");

$test = new attach_mailer($name = "Olaf Lederer", $from = "me@mymail.com", $to = "you@gmail.com", $cc = "copy@provider.org", $bcc = "blind@copy.org", $subject = "Test mime email class", $body = "Some body text");
$test->create_attachment_part("vcss.png"); 
$test->create_attachment_part("image.gif");
$test->process_mail();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Attachment Mailer example script</title>
</head>

<body>
<h2>Attachment Mailer example</h2>
<p>This is a simple example of how to use this class. First of all this version supports textmail with multiple attachements. The files need to be uploaded first. To create an online mail application you have to build a form and for the uploads you should use the upload class on finalwebsites.com (<a href="http://www.finalwebsites.com/snippets.php?id=7">Easy PHP Upload</a>)</p>
<p><a href="http://www.finalwebsites.com/snippets.php">More PHP scripts and classes at finalwebsites.com </a></p>
<p style="color:#FF0000;"><?php echo $test->get_msg_str(); ?></p>
</body>
</html>
