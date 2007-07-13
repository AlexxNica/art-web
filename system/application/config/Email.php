<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['charset'] = 'utf8';
$config['wordwrap'] = TRUE;
$config['protocol'] = 'mail';


$config['email_template']['activation'] = array(
		'subject' => 'art.gnome.org - Account Activation',
		'body' => '

Dear %s,

Thank you for registering at the art.gnome.org. Before we can activate your account one last step must be taken to complete your registration.

Please note - you must complete this last step to become a registered member. You will only need to visit this url once to activate your account.

To complete your registration, please visit this url: 
%s

Please do not reply to this email as this is an automatically generated message.

Thanks,

art.gnome.org
'
	);
	
$config['email_template']['lost_password'] = array(
		'subject' => 'art.gnome.org - Lost Password ',
		
		'body' => '
		
Hi,

Someone has requested a password reset for the art.gnome.org account associated with this email address.

If it was you, you can choose a new password using the link bellow:

User name: %s
Password reset link for this account (link expires in 12 hours):
%s

For support, visit us at:
%s

Please do not reply to this email as this is an automatically generated message.

Thanks,

art.gnome.org'
		
	);

?>