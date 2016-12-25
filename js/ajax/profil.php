<?php	
function chargerClasse($classe){
	require '../../class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

session_start();
	
		$tab= $_SESSION['user']->edit ($_POST['Pseudo'] , $_POST['Pwd'], $_POST['Cpwd'], $_POST['Email'], $_POST['ChangeState'], "true");
		if (isset($_POST['ChangeState']) && $_POST['ChangeState']!="") {
					$string=$_SESSION['user']->checkerasetime($_POST['ChangeState']);

		}
		else {
					$string="0";
		}
		echo $tab[0].$tab[1].$tab[2].$tab[3].$string;

?>