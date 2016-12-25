<?php   
/*function chargerClasse($classe){
	require './class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.
*/
/*session_start();
if (!isset($_SESSION['state']) || $_SESSION['state']==false){
	$user = new User();
}*/
/*include ('./bdd/bdd.php');
$resultat = $bdd->query('SELECT * FROM user WHERE name="'.$this->getName().'"');
		while ($donnees = $resultat->fetch()) {
			if ($donnees['lost_id']!= NULL) {
				$u=$donnees['lost_id'];				
			}
		}*/
/*$user = new User();

$user->load($user->getEmail());*/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<head>
        

        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <title>Task Reminder - Bienvenue</title>
        
        <meta name="viewport" content="width=device-width; initial-scale=1.0">
        
    <style type="text/css">
         body {
         		background-color :#DCDBDB;
				width: 100%;  	
         }
         h1 {
         	font-size:50px;
         }
         li {
         	list-style-type:none;
         	 border-bottom: 1px solid black;
  			  border-top: 1px solid black;
         }
         ul {
         	padding-left:0px;	
         }
         /* unvisited link */
		a.white:link {
    		color: white;
    		text-decoration:none;
		}

		/* visited link */
		a.white:visited {
    		color: #BDBDBD;
    		text-decoration:none;
		}

		/* mouse over link */
		a.white:hover {
    		text-decoration: underline;
		}

		/* selected link */
		a.white:active {
    		color: white;
    		text-decoration:none;
		}
		p.moreList {
			cursor: pointer;	
		}
</style>
<!-- Add jQuery library -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="<?php echo $root_dir.'/js/website.js';?>" type="text/javascript" charset="utf-8"></script>
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo $root_dir.'/js/jquery/jquery.mousewheel-3.0.6.pack.js';?>"></script>
  </head> 
    <body>
    <table align="center" border="0" cellpadding="0" cellspacing="0" id="page" width="720" bgcolor="#ffffff" style="background-color: #ffffff; font-family: 'Helvetica Neue', Helvetica, Arial, Sans-serif; font-size: 1em; line-height: 1.5; margin: 0 auto 1.5em; padding: 0; width: 720px; text-align: center; -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); -ms-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); -o-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);">
    <tbody align="left">
          <tr>
                    <!-- Mastheader -->
                    <td class="header" style="margin: 0 auto; padding: 3em 0; text-align: center; width: 100%;">
                    	<span class="header" style="margin: 0 auto; padding: 3em 0; text-align: center; width: 100%;">
                    		<img src="<?php echo $root_dir.'/img/TR_Logo.png';?>" />
                    			<div>
                  				  <h2>Task Reminder</h2>
                  			    </div>
                   		 </span>
                    </td>
           </tr>
           <tr>
           			<td class="content" style="font-size: 1em; line-height: 1.5; margin: 0 auto; padding: 1.5em 0; text-align: center; width: 100%;" valign="top">
					<p border="0" cellpadding="0" cellspacing="0" style="background-color: blue; border: solid blue; border-width: 1px 0; margin: auto; padding: 0.75em 0; text-align: center; margin-bottom:15px; color:white;"> 	&#9733; Bienvenue &#9733;</p>
					<p>Bonjour <b><?php echo $this->getName(); ?></b>,
						<p>L'équipe de Task Reminder vous souhaite la bienvenue sur le site.</p>
						<p>Le but du site est de répertorier les tâches que vous avez à effectuer. Vous pouvez triez vos différentes tâches dans des listes ce qui peut vous aidez à vous y retrouver suivant vos projets.</p>
						<p><a href=<?php echo $root_dir.'/home.php';?> style="">Connectez-vous</a> dès maintenant pour créer votre première liste de tâches.</p>
					</p>
					<table style="width:100%">
						<tbody>
           <tr>
           		<td>					
           		<!-- <p border="0" cellpadding="0" cellspacing="0" style="background-color: blue; margin: auto; padding: 0.75em 0; text-align: center; color:white"> &#8505; Information &#8505; <br> Si vous n'avez pas effectuer cette demande, il vous suffit de l'ignorer.</p> -->
           		</td>
           </tr>
           <tr>
           			<td class="footer" style= "padding: 15px;">
           			&copy; Yan 2015
           			</td>
           </tr>
    </tbody>
    </table>
    </body> </html>
