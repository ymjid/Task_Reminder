<?php	
function chargerClasse($classe){
	require '../../class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

session_start();

	//echo $_POST['type'], $_POST['list'], $_POST['title'], $_POST['descrip'];
	switch ($_POST['type']){
		case "task" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
			if ($list->getId()==$_POST['list']){
				$tmptasks=$list->getListTask();
				foreach($tmptasks as $task){
					if ($task->getTitle()==$_POST['title'] && $task->getDescrip()==$_POST['descrip']) {
						$itemid = $task->getId();
					}
				}
			}
		}
		break;
		case "img" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
				$tmptasks=$list->getListTask();
				foreach($tmptasks as $task){
					if ($task->getId()==$_POST['list']){
						$tmpimgs=$task->getImg();
						foreach($tmpimgs as $img){
							if ($img->getUrl()==$_POST['title'] && $img->getDescrip()==$_POST['descrip']) {
								$itemid = $img->getId();
							}
						}
					}
				}
		}
		break;
		case "list" :
		$tmplist=$_SESSION['user']->getTabLists();
		foreach($tmplist as $list){
			if ($list->getTitle()==$_POST['title'] && $list->getDescrip()==$_POST['descrip']) {
				$itemid = $list->getId();
			}
		}
		break;
	}
echo $itemid; 

?>