<?php	
function chargerClasse($classe){
	require '../../class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

session_start();

	echo $_POST['imgaction'];
	if ($_POST['imgaction']=="add"){
		echo $_POST['imgname'].' '.$_POST['descripimg'].' '.$_POST['task'];
	}
	if ($_POST['imgaction']=="erase"){
		echo $_POST['imageid'], $_POST['task'];
	}
	if ($_POST['imgaction']=="edit"){
		echo $_POST['imageid'].' '.$_POST['imgname'].' '.$_POST['descripimg'].' '.$_POST['task'];
	}
	switch ($_POST['imgaction']){
		case "add" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
				$tmptasks=$list->getListTask();
				foreach($tmptasks as $task){
					if ($task->getId()==$_POST['task']){
						$img=$task->addImg($task->getImg(), $_POST['imgname'], $_POST['descripimg']);
						$img->save("true");
					}
				}
		}
		break;
		case "edit" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
				$tmptasks=$list->getListTask();
				foreach($tmptasks as $task){
					if ($task->getId()==$_POST['task']){
						$img=$task->editImg($task->getImg(), $_POST['imageid'], $_POST['descripimg'], $_POST['imgname'], "true");
					}
				}
		}
		break;
		case "erase" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
				$tmptasks=$list->getListTask();
				foreach($tmptasks as $task){
					if ($task->getId()==$_POST['task']){
						$img=$task->eraseImg($task->getImg(), $_POST['imageid'], "true");
					}
				}
		}
		break;
	}

?>