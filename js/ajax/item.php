<?php	
function chargerClasse($classe){
	require '../../class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

session_start();

	echo $_POST['action'];
	if ($_POST['action']=="add"){
		echo $_POST['titleitem'].' '.$_POST['descripitem'];
	}
	if ($_POST['action']=="erase"){
		echo $_POST['Listnum'];
	}
	if ($_POST['action']=="edit"){
		echo $_POST['listitem'].' '.$_POST['titleitem'].' '.$_POST['descripitem'];
	}
	switch ($_POST['action']){
		case "add" :
		$tmplist=$_SESSION['user']->addList($_SESSION['user']->getTabLists(), $_POST['titleitem'], $_POST['titleitem']);
		$tmplist->save("true");
		break;
		case "erase" :
		$_SESSION['user']->eraseLists($_SESSION['user']->getTabLists(), $_POST['Listnum'], "true");
		break;
		case "edit" :
		$_SESSION['user']->editLists($_SESSION['user']->getTabLists(), $_POST['listitem'], $_POST['titleitem'], $_POST['descripitem'], "true");
		break;
	}

?>