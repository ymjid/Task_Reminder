<?php    
//function chargerClasse($classe){
//	require './class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
//}
require './class/User.php';
require './class/ToDoList.php';
require './class/Task.php';
require './class/Img.php';
//spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.
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

$time=$user->erasecountdown();

?>
<html class="background">
<head>
<title>Task Reminder  - Home</title>
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

<!-- <link rel="stylesheet" href="./js/dropzone/dist/dropzone.css" />-->
<!-- <script type="text/javascript" src="./js/dropzone/dist/dropzone.js"></script>-->
</head>
<body onload='timeRemain("<?php echo $time; ?>")'>
<div class="page" id="page">
<div class="header">
<div class="title"><img src="./img/TR_Logo.png"/><br>Task Reminder</div>
<div class="subtitle"></div>
</div>
<?php
$page="home";
if (isset($_POST['login']) && isset($_POST['pwd'])){
		$log=$user->login($_POST['login'], $_POST['pwd']);
		switch ($log){
			case "NULL":
			echo '<div class="errormsg"><p>This user don\'t exist anymore</p></div>';
			break;
			case "true" :
			echo '<div class="succesmsg"><p>User connected</p></div>';
			break;
			case "false" :
			echo '<div class="errormsg"><p>The username don\'t exist or the password don\'t match</p></div>';
			break;
		}
		
}

if (isset($_POST['Clogin']) && isset($_POST['Cmail']) && isset($_POST['Cpwd'])){
		$creation=$user->createAccount($_POST['Clogin'], $_POST['Cmail'], $_POST['Cpwd'], $_POST['Cconfirmpwd']);
		if ($creation!="0"){
			echo '<div class="errormsg" id="errormsg">';
			if ($creation=="1"){
				echo '<p>Le Pseudo est déjà utilisé par un autre utilisateur</p>';
			}
			if ($creation=="3"){
				echo '<p>L\'email est déjà utilisé par un autre utilisateur</p>';
			}
			if ($creation=="2"){
				echo '<p>Le Mot de passe et la confirmation sont différent</p>';
			}
			echo '</div>';
		}
		else {
			echo '<div class="succesmsg" id="msg">';				
			echo '<p>Le compte a été crée avec succès</p>';
			echo '</div>';
		}
}

if (isset($_POST['lostmail'])){
	$send=$user->lostpwd($_POST['lostmail']);
	if ($send==true) {
		echo '<div class="succesmsg" id="msg">';				
		echo '<p>Un mail a été envoyé à l\'adresse e-mail indiquée</p>';
		echo '</div>';
	}
	else {
		echo '<div class="errormsg" id="msg">';				
		echo '<p>L\'adresse e-mail indiquée n\'a pas été trouvé dans notre base de données</p>';
		echo '</div>';
	}
	
}

include ('./menu.php');
	
if ($user->getState()!="unknown"){
			echo '<div class="listitem" id="addlist_" style="display:none">';
			echo '<div class="listoption" id="cancellist_" style="display:none">';
			echo '<img class="option" title="annuler action" src="./img/cancel.png" alt="Annuler action" onClick="cancelAddList('.$user->getId().')" />';
			echo '</div>';
			echo '<div class="actionname">';
			echo 'Ajout d\'une liste<br>';
			/*echo '<div class="listtitle wait">';*/
			echo '<input type="text" name="addtitlelist" placeholder="Nom de la liste"/><br>';
			/*echo '</div>';*/
			/*echo '<div class="listdescrip">';*/
			echo '<input type="text" name="newdescriplist" placeholder="Description de la liste"/><br>';
			/*echo '<div>';*/	
			echo '<input type="button" value="Ajouter liste" onClick="addList()"/>';
			/*echo '</div>';*/
			/*echo '</div>';*/
			echo '</div>';
			echo '</div>';
	$user->showUserLists($user->getTabLists());
}
else {
	echo '<div class="" style="border-bottom: 1px solid blue;" >';
	echo '<div class="" style="border-right: 1px solid red; display: table-cell; width: 14%; min-width: 92px;">';
	echo '</div>';
	echo '<div class="" style="display: table-cell; padding: 5px;">';
	/*echo '<p>';*/
	echo '<p>Bienvenue sur Task Reminder</p>';
	/*echo '</p>';*/
	/*echo '<p>';*/
	echo '<p>Le site Task Reminder permet à ses utilisateurs de créer des listes de tâches et de faire un suivi des tâches restantes à effectuer.</p>';
	/*echo '</p>';*/
	/*echo '<p>';*/
	echo '<p>Il est possible d\'activer une alerte mail pour que vos listes soit envoyer à votre adresse e-mail mais il faudra se connecter au site pour ajouter/éditer/supprimer des tâches et/ou listes.</p>';
	echo '</div>';
	/*echo '</p>';*/
	/*echo '</div>';*/
	/*echo '</div>';*/
	
	echo '</div>';
}
	
?>
<div class="footer" id="footer">
	<p>&copy; Yan 2015 - <?php echo date('Y'); ?></p>
</div>
</div>	
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>	
</body>
</html>