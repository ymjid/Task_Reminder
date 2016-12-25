<?php
function customcrypt($user, $time) { /* function generate the restauration code */
	$data=$user->getId().rand(0,99999).$time;
	$data=sha1($data);
	
	return $data;
}

/*function setupmail() {
		include ('./functions/options.php');
		$transport = Swift_SmtpTransport::newInstance($mailsmtp, $mailport, $mailencrypt)
  			->setUsername($mailsender)
  			->setPassword($mailuserpwd)
  			;
  		return $transport;	
}*/
?>