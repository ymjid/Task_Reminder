<?php	
function chargerClasse($classe){
	require '../../class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

function download_remote_file($file_url, $save_to){
		$content = file_get_contents($file_url);
		file_put_contents($save_to, $content);
}
	
spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

session_start();

	//echo $_POST['action'];
	if ($_POST['action']=="add"){
		//echo $_POST['tasknum'].' '.$_POST['link'].' '.$_POST['txt'];//
		$filename=basename($_POST['link']);
		download_remote_file($_POST['link'], realpath("../../img/taskimg") . '/'.$filename.'');
		$tmplist=$_SESSION['user']->getTabLists();
			foreach($tmplist as $list){
					$tmptasks=$list->getListTask();
					foreach($tmptasks as $task){
						if ($task->getId()==$_POST['tasknum']){
							$img=$task->addImg($task->getImg(), $filename, $_POST['txt']);
							$img->save("true");
							$tmpimgs = $task->getImg();
							foreach($tmpimgs as $img) {
								if ($img->getUrl()==$filename && $img->getDescrip()==$_POST['txt']) {
									$newimgid = $img->getId();
									echo $newimgid;
								}
							}
						}
					}
			}
	}
	if ($_POST['action']=="edit"){
		//echo $_POST['numimg'].' '.$_POST['tasknum'].' '.$_POST['link'].' '.$_POST['txt'];//
		$filename=basename($_POST['link']);
		download_remote_file($_POST['link'], realpath("../../img/taskimg") . '/'.$filename.'');
		$tmplist=$_SESSION['user']->getTabLists();
			foreach($tmplist as $list){
					$tmptasks=$list->getListTask();
					foreach($tmptasks as $task){
						if ($task->getId()==$_POST['tasknum']){
							$img=$task->editImg($task->getImg(), $_POST['numimg'], $_POST['txt'], $filename, "true");
						}
					}
			}
	}
	
	/*echo $_POST['imgaction'];
	if ($_POST['action']=="add"){
		echo $_POST['imgname'].' '.$_POST['descripimg'].' '.$_POST['task'];
	}
	if ($_POST['action']=="erase"){
		echo $_POST['listitem'].' '.$_POST['taskitem'];
	}
	if ($_POST['action']=="edit"){
		echo $_POST['listitem'].' '.$_POST['titleitem'].' '.$_POST['descripitem'].' '.$_POST['taskitem'];
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
	}*/

?>