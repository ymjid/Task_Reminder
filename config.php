<?php   
function chargerClasse($classe){
	require './class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

session_start();
if (isset($_POST['logout'])){
	session_destroy();
	$_SESSION['user']="";
	unset($_SESSION);

}

if (!isset($_SESSION['user'])){
	$user = new User();
}
else {
	$user = $_SESSION['user'];	
}

if ($user->getState()=="unknown"){
	header('Location: home.php');
}
$time=$user->erasecountdown();
?>
<html class="background">
<head>
<title>Task Reminder  - Config Profile</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="./css/website.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

<!-- Add jQuery library -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="./js/website.js" type="text/javascript" charset="utf-8"></script>
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="./js/jquery/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="./js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="./js/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="./js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="./js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="./js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

<link rel="stylesheet" href="./js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="./js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<link rel="stylesheet" href="./js/dropzone/dist/dropzone.css" />
<script type="text/javascript" src="./js/dropzone/dist/dropzone.js"></script>
</head>
<?php
$page="config";
?>
<body onload='timeRemain("<?php echo $time ?>")'>
<div id="page" class="page">
<div class="header">
<div class="title"><img src="./img/TR_Logo.png"/><br>Task Reminder</div>
<div class="subtitle">Configuration profile</div>
</div>
<?php
	include ('./menu.php');
	echo '<div class="" style="border-bottom: 1px solid blue;">';
	echo '<div class="" style="border-right: 1px solid red; display: table-cell; width: 214px; min-width: 92px;">';
	echo '</div>';
	echo '<div class="" style="display: table-cell; padding: 5px;width: 85%;">';
	echo '<h1>Informations personnelles</h1>';
	echo '<div>Pseudo : <input type="text" id="pseudo" name="pseudo" value="'.$user->getName().'" /></div>';
	echo '<div>E-mail : <input type="email" id="e-mail" name="e-mail" value="'.$user->getEmail().'" /></div>';
	echo '<div><span title="Ne remplissez pas ce champ si vous ne souhaitez pas modifier votre mot de passe.">Nouveau mot de passe :</span> <input type="password" id="password" name="password" placeholder="mot de passe" value="" /></div>';
	echo '<div id="confirmpwdcontainer">Confirmer mot de passe : <input type="password" id="confirmpwd" name="confirmpwd" placeholder="confirmer mot de passe" value="" /></div>';
	if ($user->getState()=="active") {
		echo '<div><input type="button" onclick="userState()" name="stateaccount" id="stateaccount" value="Supprimer compte" title="Le compte sera désactivé en attendant sa suppression, vous ne pourrez plus modifier vos listes/tâches." /></div>';
	}
	else {
		echo '<div><input type="button" onclick="userState()" name="stateaccount" id="stateaccount" value="Réactiver compte" title ="La réactivation du compte vous permettra de modifier vos listes/tâches."/></div>';
	}
	echo '</div>';
	echo '</div>';
	

?>
<div class="" style="border-bottom: 1px solid blue;">
<div class="" style="border-right: 1px solid red; display: table-cell; min-width: 92px; width: 214px;">
</div>
<div style="display: table-cell; padding: 5px;width: 85%;">
<div class="confirmedittask" style="display:block">
<input type="button" name="save" id="save" onClick="saveProfil();" value="Enregistrer Modification" />
</div>
</div>
</div>
<div class="footer">
	<p>&copy; Yan 2015 - <?php echo date('Y'); ?></p>
</div>
</body>
</html>