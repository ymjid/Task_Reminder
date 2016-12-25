<?php
if (isset($_POST)) {
	if (isset($_POST['ASMTP']) || isset($_POST['PSMTP']) || isset($_POST['ESMTP']) || isset($_POST['mailuser']) || isset($_POST['mailpwd']) || isset($_POST['site_path'])) {
		$file = fopen("../functions/options.php", "w");
		$data="<?php \n";
		$data.="/* SwiftMailer options*/";
		$value=$_POST['ASMTP'];
		$data.="\n\$mailsmtp='$value'; /* smtp */";
		$value=$_POST['PSMTP'];
		$data.="\n\$mailport='$value'; /* smtp port */";
		$value=$_POST['ESMTP'];
		$data.="\n\$mailencrypt='$value'; /* smtp crypt */";
		$value=$_POST['mailuser'];
		$data.="\n\$mailsender='$value'; /* sender mail address */";
		$value=$_POST['mailpwd'];
		$data.="\n\$mailuserpwd='$value'; /* password used to connect with mail address */";
		$data.="\n/**********************************/";
		$site_path_folder = realpath('../home.php');
		$site_path = str_replace("home.php", "", $site_path_folder);
		$value=$site_path;
		$data.="\n\$root_dir='$value'; /* root directory of the website */ ";
		$data.="\n ?>";

		fputs($file, $data);
		fclose ($file);
	}
	if (isset($_POST['sgbd']) && isset($_POST['dbhost']) && isset($_POST['dbname']) && isset($_POST['dbuser']) && isset($_POST['dbpwd'])) {

		/* On stocke dans des variables la syntaxe de la connexion à une BDD en utilisant l'extension PHP Data Objects (PDO) */
		$part1 = "<?php \$bdd = new PDO('" ;
		$part2 = ":host=" ;
		$part3 = ";dbname=" ;
		$part4 = "', '" ;
		$part5 = "', '" ;
		$part6 = "'); \n \$bdd->exec('SET CHARACTER SET utf8'); ?>" ;

		/* On ouvre le fichier bdd2.php en français à personnaliser */
		$file = fopen("../bdd/bdd2.php", "w");

		/* On personnalise le fichier avec les valeurs envoyées via le formulaire -> Concaténation */
		fputs($file, $part1.$_POST['sgbd'].$part2.$_POST['dbhost'].$part3.$_POST['dbname'].$part4.$_POST['dbuser'].$part5.$_POST['dbpwd'].$part6);

		/* On ferme le fichier */
		fclose($file);
	}
	if(isset($_POST['adminname']) && isset($_POST['adminpwd']))
	{

		/* Chemins relatifs des répertoires install. */
		$dir = "./";

		/* On stocke les données envoyées via le formulaire dans des variables. */
		$login = $_POST['adminname'];

		/* On crypte le mot de passe avec un salt. */
		$pass = crypt($_POST['adminpwd'], '4kp$zgFT*$%v');

		/* Ouverture du fichier .htpasswd relatif à la partie concernée par l'identification. */
		$file = fopen($dir.'.htpasswd','w');


		/* On écrit dans le fichier le login et le mot de passe suivant la syntaxe requise. */
		fputs($file, $login . ':' . $pass);

		/* On ferme le fichier après utilisation. */
		fclose($file);

		/*
		* On stocke le chemin absolu de ce fichier dans une variable.
		* RQ : Il est nécessaire d'avoir le chemin absolu du fichier .htpasswd de chaque zone à protéger pour que le .htaccess fonctionne.
		*/
		$path_protected_folder = realpath('install.php');


		/****** 1. Création du fichier .htaccess. ******/

		/* On remplace le nom du fichier .php en .htpasswd et on stocke le nouveau chemin absolu dans une variable. */
		$path_protected_folder_admin = str_replace("install.php",".htpasswd", $path_protected_folder);

		/* On ouvre le fichier .htaccess à personnaliser, s'il n'existe pas il sera créé.  */
		$htaccess_admin = fopen('.htaccess','w');

		/* Elements qui constituent le fichier .htaccess de base en suivant une syntaxe précise, le chemin absolu vers le .htpasswd sera personnalisable. */
		$line_1 = "AuthName \"Authentication required\"\r\n" ;
		$line_2 = "AuthType Basic\r\n";
		$line_3a = "AuthUserFile \"";
		$line_3b = $path_protected_folder_admin;
		$line_3c = "\"\r\n";
		$line_4 = "Require valid-user";

		/* Concaténation des variables dans le fichier .htaccess . */
		fputs($htaccess_admin,$line_1.$line_2.$line_3a.$line_3b.$line_3c.$line_4);

		/* On ferme le fichier après utilisation. */
		fclose($htaccess_admin);

	}
}
?>
<!DOCTYPE html>
<html class="background">
<head>
	<title>Task Reminder - Install</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="../css/website.css" />
</head>
<body>
	<div class="page" id="page">
		<div class="header">
			<div class="title"><img src="../img/TR_Logo.png"/><br>Task Reminder</div>
			<div class="subtitle">Installation</div>
		</div>
    <div id="form" class="form logged">
		</div>
		<form class="installform" action="#" method="post">
			<fieldset class="installblock">
				<legend>Configuration de Swift Mailer</legend>
				<div class="installformelem">
					<label for="ASMTP">Adresse SMTP</label>
					<input type="url" name="ASMTP" id="ASMTP" placeholder="Adresse SMTP" required/>
				</div>
				<div class="installformelem">
					<label for="PSMTP">Port SMTP</label>
					<input type="text" name="PSMTP" id="PSMTP" placeholder="Port SMTP" required/>
				</div>
				<div class="installformelem">
					<label for="ESMTP">Encodage SMTP</label>
					<input type="text" name="ESMTP" id="ESMTP" placeholder="Encodage SMTP" required/>
				</div>
				<div class="installformelem">
					<label for="mailuser">Adresse e-mail</label>
					<input type="email" name="mailuser" id="mailuser" placeholder="Adresse e-mail" required/>
				</div>
				<div class="installformelem">
					<label for="mailpwd">Mot de passe</label>
					<input type="password" name="mailpwd" id="mailpwd" placeholder="Mot de passe" required/>
				</div>
			</fieldset>
			<fieldset class="installblock">
				<legend>Configuration de la base de données</legend>
				<div class="installformelem">
					<label for="sgbd">Système de gestion de base de données</label>
					<input type="text" name="sgbd" id="sgbd" placeholder="Système de gestion de base de données"/>
				</div>
				<div class="installformelem">
					<label for="dbhost">Hôte de la base de données</label>
					<input type="text" name="dbhost" id="dbhost" placeholder="Hôte de la base de données"/>
				</div>
				<div class="installformelem">
					<label for="dbname">Nom de la base de données</label>
					<input type="text" name="dbname" id="dbname" placeholder="Nom de la base de données"/>
				</div>
				<div class="installformelem">
					<label for="dbuser">Administrateur de la base de données</label>
					<input type="text" name="dbuser" id="dbuser" placeholder="Administrateur de la base de données"/>
				</div>
				<div class="installformelem">
					<label for="dbpwd">Mot de passe de la base de données</label>
					<input type="password" name="dbpwd" id="dbpwd" placeholder="Mot de passe de la base de données"/>
				</div>
			</fieldset>
			<fieldset class="installblock">
				<legend>Configuration de l'Htaccess</legend>
				<div class="installformelem">
					<label for="adminname">Nom de l'administrateur</label>
					<input type="text" name="adminname" id="adminname" placeholder="Nom de l'administrateur" />
				</div>
				<div class="installformelem">
					<label for="adminpwd">Mot de passe de l'administrateur</label>
					<input type="text" name="adminpwd" id="adminpwd" placeholder="Mot de passe de l'administrateur" />
				</div>
			</fieldset>
			<input type="submit" value="Enregistrer les options"/>
		</form>
		<div class="footer" id="footer">
			<p>&copy; Yan 2015 - <?php echo date('Y'); ?></p>
		</div>
	</div>
</body>
</html>
