<?php
function chargerClasse($classe){
	require './class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

include ('./bdd/bdd.php');
$page="restore";
session_start();
if (!isset($_SESSION['user'])){
	$user = new User();
}
else {
	$user = $_SESSION['user'];	
}

$user->checkaskpwd($_GET['u']);

$restoreform = '<div class="customform" style="display: block";><form class="itemcenter log" method="POST" action="#"><p>Attribution du nouveau mot de passe</p><input type="text" hidden name="user" value="'.$user->getCode().'" /><div class="infocontainer"><div class="infoicon"><i class="fa fa-unlock-alt" aria-hidden="true"></i></div><div class="infodata"><input class="column" type="password" name="Rpwd" placeholder="Mot de passe" /></div></div><div class="infocontainer"><div class="infoicon"><i class="fa fa-unlock-alt" aria-hidden="true"></i></div><div class="infodata"><input class="column" type="password" name="RCpwd" placeholder="Confirmation mot de passe" /></div></div><input type="submit" value="Changer mot de passe" /></form></div>';

if (isset($_POST['Rpwd']) && isset($_POST['RCpwd'])) {
	$user->load($_POST['user']);
	if ($_POST['Rpwd']!="" && $_POST['RCpwd']!=""){
		$completeform=true;
		$edittab=$user->edit ("", $_POST['Rpwd'], $_POST['RCpwd'],"", "","false");
	}
	else {
		$completeform=false;
	}
}

?>
<html class="background">
<head>
<title>Task Reminder  - Restore password</title>
<meta charset="utf-8" />
<link rel="stylesheet" href="./css/website.css" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div class="page" id="page">
	<div class="header">
	<div class="title"><img src="./img/TR_Logo.png"/><br>Task Reminder</div>
	<div class="subtitle"></div>
	</div>
<?php
if (isset($completeform)) {
	if ($edittab[1]==1){
		$user->restoredpwd();
		$restoreform ="";
		echo '<div class="succesmsg" id="msg">';				
		echo '<p>Le mot de passe a bien été modifié</p>';
		echo '</div>';
	}
	else {
		echo '<div class="errormsg" id="msg">';				
		echo '<p>Le mot de passe et sa confirmation sont différents</p>';
		echo '</div>';
	}
}
	echo '<div id="form" class="form logged">';
	echo '<p id="username" class="itemcenter">'.$user->getName().'</p>';
	echo '</div>';
	echo $restoreform;

?>
<div class="footer" id="footer">
	<p>&copy; Yan 2015 - <?php echo date('Y'); ?></p>
</div>
</div>	
</body>
</html>