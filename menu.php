<?php 
if ($user->getState()!="unknown") {
	if ($user->getState()!="active") {
		echo '<div class="errormsg" id="countdown">';
		echo '</div>';	
	}
	echo '<div id="form" class="form itemcenter logged">';
	echo '<p id="username" class="itemcenter">'.$user->getName().'</p>';
	echo '<form class="log" id="log" method="POST" action="home.php">';
	if ($page=="home"){
		if ($user->getState()=="active"){
			echo '<img id="addicon_" class="option" onclick="addListButton()" src="./img/add.png" alt="Ajouter liste" title="ajouter liste" />';
		}
	}
	else {
		echo '<a href="home.php"><img class="option" src="./img/home.png" alt="Accueil" title="accueil" /></a>';
	}
	if ($page!="config"){
		echo '<a href="config.php"><img class="option" src="./img/config.png" alt="Configuration" title="configuration" /></a>';
	}
	echo '<input hidden type="text" class="size" name="logout" value="1" />';
	echo '<input class="option" type="image" border=0 src="./img/logout.png" type=image value="submit" alt="Déconnexion" title="deconnexion"/>';
	echo '</form>';
	echo '</div>';
}
else {
	
	echo '<div id="form" class="form logged">';
	echo '<p id="username" class="itemcenter">Invité</p>';
	echo '<form class="itemcenter log" id="log" method="POST" action="home.php">';
	echo '<img id="login" class="option" onclick="showform1()" src="./img/login.png" alt="Connexion button" title="connexion" />';	/* connexion button */
	echo '<img id="register" class="option" onclick="showform2()" src="./img/register.png" alt="Register button" title="register" />';    /* register button */
	echo '</form>';
	echo '</div>';
	
	echo '<div style="display:none;" id="form2">';
	echo '<form class="customform itemcenter log" id="register" method="POST" action="#">';
	echo '<p>Création de compte</p>';
	echo '<div class="infocontainer">';
	echo '<div class="infoicon"><i class="fa fa-user" aria-hidden="true"></i></div>';
	echo '<div class="infodata"><input type="text" class="column" name="Clogin" placeholder="Identifiant" /></div>';
	echo '</div>';
	echo '<div class="infocontainer">';
	echo '<div class="infoicon"><i class="fa fa-envelope" aria-hidden="true"></i></div>';
	echo '<div class="infodata"><input type="email" class="column" name="Cmail" placeholder="E-mail" /></div>';
	echo '</div>';
	echo '<div class="infocontainer">';
	echo '<div class="infoicon"><i class="fa fa-lock" aria-hidden="true"></i></div>';
	echo '<div class="infodata"><input type="password" class="column" name="Cpwd" placeholder="Mot de passe" /></div>';
	echo '</div>';
	echo '<div class="infocontainer">';
	echo '<div class="infoicon"><i class="fa fa-lock" aria-hidden="true"></i></div>';
	echo '<div class="infodata"><input type="password" class="column" name="Cconfirmpwd" placeholder="Confirmation Mot de passe" /></div>';
	echo '</div>';
	echo '<input type="submit" value="Créer le compte"/>';
	echo '</form>';
	echo '</div>';
	
	echo '<div class="customform" style="display:none;" id="form1">';
	echo '<form class="itemcenter log" id="logout" method="POST" action="#">';
	echo '<p>Connexion</p>';
	echo '<div class="infocontainer">';
	echo '<div class="infoicon"><i class="fa fa-user" aria-hidden="true"></i></div>';
	echo '<div class="infodata"><input type="text" class="column" name="login" placeholder="Identifiant" /></div>';
	echo '</div>';
	echo '<div class="infocontainer">';
	echo '<div class="infoicon"><i class="fa fa-unlock-alt" aria-hidden="true"></i></div>';
	echo '<div class="infodata"><input type="password" class="column" name="pwd" placeholder="Mot de passe" /></div>';
	echo '</div>';
	echo '<input type="submit" value="Connexion"/>';
	echo '<div class="itemcenter">';
	echo '<img class="option" id="key" title="mot de passe oublié?" src="./img/forget.png" alt="Mot de passe oublié?" onClick="showkey()" />';
	echo '</div>';
	echo '</form>';
	echo '<div class="itemcenter" id="lostdiv" style="display:none; border-top: 1px solid blue">';
	echo '<p>Récupération de mot de passe</p>';
	echo '<form class="itemcenter log" id="lostpwd" method="POST" action="#">';
	echo '<div class="infocontainer">';
	echo '<div class="infoicon"><i class="fa fa-envelope" aria-hidden="true"></i></div>';
	echo '<div class="infodata"><input class="column" type="email" name="lostmail" placeholder="E-mail"/></div>';
	echo '</div>';
	echo '<input type="submit" name="lostconfirm" value="Réinitialiser mot de passe"/> ';
	echo '</form>';
	echo '</div>';
	echo '</div>';


}

?>