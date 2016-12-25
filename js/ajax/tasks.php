<?php	
function chargerClasse($classe){
	require '../../class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

session_start();

	echo $_POST['action'];
	if ($_POST['action']=="add"){
		echo $_POST['listitem'].' '.$_POST['titleitem'].' '.$_POST['descripitem'];
	}
	if ($_POST['action']=="erase"){
		echo $_POST['listitem'].' '.$_POST['taskitem'];
	}
	if ($_POST['action']=="edit"){
		echo $_POST['listitem'].' '.$_POST['titleitem'].' '.$_POST['descripitem'].' '.$_POST['taskitem'];
	}
	if ($_POST['action']=="changeState"){
		echo $_POST['listitem'].' '.$_POST['modifState'].' '.$_POST['taskitem'];
	}
	switch ($_POST['action']){
		case "add" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
			if ($list->getId()==$_POST['listitem']){
				$task=$list->addTask($list->getListTask(), $_POST['titleitem'], $_POST['descripitem']);
				$task->save("true");
			}
		}
		break;
		case "erase" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
			if ($list->getId()==$_POST['listitem']){
				$task=$list->eraseTask($list->getListTask(), $_POST['taskitem'], "true");
			}
		}
		break;
		case "edit" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
			if ($list->getId()==$_POST['listitem']){
				$task=$list->editTask($list->getListTask(), $_POST['taskitem'], $_POST['titleitem'], $_POST['descripitem'], "none","true");			
			}
		}
		break;
		case "changeState" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
			if ($list->getId()==$_POST['listitem']){
				$task=$list->findTask($list->getListTask(), $_POST['taskitem']);
				$task->editState($_POST['modifState'], "true");		
			}
		}
	}

?>