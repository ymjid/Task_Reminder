<?php
class Task{
	private $_id; /* identifiant de la tâche */
	private $_title; /* titre de la tâche  */
	private $_description;  /* description de la tâche */
	private $_state; /* état de la tâche */
	private $_img; /* illustrations de la tâche */
	private $_list;	/* liste qui contient la tâche */
	
	 public function __construct($title="", $description="", ToDoList $list) { /* Constructeur */
		$this->setTitle($title); /* Initialisation du titre.*/
		$this->setDescrip($description); /* Initialisation de la description.*/
		$this->_img = array(); /* Initialisation de l'image.*/
		$this->_state = 'active'; /* Initialisation de l'état à "active" */
		$this->setList($list); /* Initialisation de la liste contenant la tâche.*/
		$this->_id = 0; /* Initialisation de l'id à 0.*/
  	}
  	
  	public function getTitle(){ /* renvoie le titre */
		return $this->_title;
	}
	
	public function getDescrip(){ /* renvoie la description */
		return $this->_description;
	}
	
	public function getId(){ /* renvoie l'identifiant de la liste */
		return $this->_id;
	}
	
	public function getImg(){ /* renvoie le lien de l'image */
		return $this->_img;
	}
	
	public function getState(){ /* renvoie l'état de la tâche */
		return $this->_state;
	}
	
	public function getList(){ /* renvoie la liste qui contient la tâche*/
		return $this->_list;
	}
	
	public function setState($state){ /* définit l'état de la tâche */
		$this->_state = $state;
	}
	
	public function setTitle($title){ /* définit le titre */
		$this->_title = $title;
	}
	
	public function setDescrip($description){ /* définit la description */
		$this->_description = $description;
	}
	
	public function setId($id){ /* définit l'identifiant de la tâche */
		$this->_id = $id;
	}

	public function setImg(array $img){ /* définit le lien de l'image */
		$this->_img = $img;
	}
	
	public function setList(ToDoList $list){ /* définit la liste qui contient la tâche */
		$this->_list = $list;
	}
	
	public function showInfo(){
		include ('./bdd/bdd.php');
			$action1="true";
			$action2="true";
			$action3="true";
			echo '<div class="taskitem '.$this->getList()->getId().'" id="task_'.$this->getId().'" style="display:none">';
			if ($this->getState()=="active"){
				echo '<div id= "taskstate_'.$this->getId().'" class="taskinfo waititem">';
				$action2="false";
			}
			if ($this->getState()=="inactive"){
				echo '<div id= "taskstate_'.$this->getId().'" class="taskinfo cancelitem">';
				$action3="false";
			}
			if ($this->getState()=="done"){
				echo '<div id= "taskstate_'.$this->getId().'" class="taskinfo doneitem">';
				$action1="false";
			}
				echo '<div class="taskoption">';
					if ($this->getList()->getUser()->getState()=="active") {
						echo '<img class="option" src="./img/suppr.png" alt="Supprimer tâche" title="supprimer tâche" onClick="eraseTask('.$this->getList()->getId().', '.$this->getId().')"/>';
						echo '<form id="edittaskform'.$this->getId().'" hidden class="editbuttonform" method="POST" action="edittask.php">';
							echo '<input hidden type="text" name="Listid" value="'.$this->getList()->getId().'"/>';
							echo '<input hidden type="text" name="Taskid" value="'.$this->getId().'"/>';
							echo '<INPUT hidden class="editbuttonform" border=0 src="./img/edit.png" type=image Value="submit" align="middle" alt="Editer tâche" title="editer tâche"/>';
						echo '</form>';
						$formid="submiteditform('edittaskform".$this->getId()."')";
						echo '<img class="option" src="./img/edit.png" alt="Editer tâche" title="editer tâche" onClick="'.$formid.'"/>';
						if ($action1!="false"){
							echo '<img class="minioption" id="changestate1_'.$this->getId().'" src="./img/activated.png" alt="Terminer tâche" title="terminer tâche" onClick="switchStateTask('.$this->getList()->getId().', '.$this->getId().', 1)" style="display: "/>';
						}
						else {
							echo '<img class="minioption" id="changestate1_'.$this->getId().'" src="./img/activated.png" alt="Terminer tâche" title="terminer tâche" onClick="switchStateTask('.$this->getList()->getId().', '.$this->getId().', 1)" style="display:none"/>';
						}
						if ($action2!="false"){
							echo '<img class="minioption" id="changestate2_'.$this->getId().'" src="./img/progress.png" alt="Activer tâche" title="activer tâche" onClick="switchStateTask('.$this->getList()->getId().', '.$this->getId().', 2)" style="display: "/>';
						}
						else {
							echo '<img class="minioption" id="changestate2_'.$this->getId().'" src="./img/progress.png" alt="Activer tâche" title="activer tâche" onClick="switchStateTask('.$this->getList()->getId().', '.$this->getId().', 2)" style="display:none"/>';
						}
						if ($action3!="false"){
							echo '<img class="minioption" id="changestate3_'.$this->getId().'" src="./img/desactivated.png" alt="Suspendre tâche" title="suspendre tâche" onClick="switchStateTask('.$this->getList()->getId().', '.$this->getId().', 3)" style="display: "/>';
						}
						else {
							echo '<img class="minioption" id="changestate3_'.$this->getId().'" src="./img/desactivated.png" alt="Suspendre tâche" title="suspendre tâche" onClick="switchStateTask('.$this->getList()->getId().', '.$this->getId().', 3)" style="display:none"/>';
						}
					}
				echo '</div>';
				echo '<div id="taskinfo'.$this->getId().'">';
					echo '<div class="tasktitle" id="tasktitle'.$this->getId().'">';
						echo $this->getTitle();
					echo '</div>';
					echo '<div class="taskdescrip" id="taskdescrip'.$this->getId().'">';
						echo $this->getDescrip();
					echo '</div>';	
					echo '<div class="taskimgs" id="taskimg'.$this->getId().'">';
						$test = $bdd->query('SELECT * FROM task WHERE id="'.$this->getId().'"');
						while ($donnees = $test->fetch()) {
							if ($donnees['img']==true){
								$resultat = $bdd->query('SELECT * FROM img WHERE numtask="'.$this->getId().'" && state="active" LIMIT 1');
								while ($donnees = $resultat->fetch()) {
									$url=$donnees['url'];
									echo '<a class="fancybox" rel="taskgallery'.$this->getId().'" title="'.$donnees['description'].'" href="./img/taskimg/'.$donnees['url'].'"><img class="option" src="./img/imgs.png" alt="Voir les images" title="Voir les images"/></a>';		
								}
								if (isset($url)){	
									$this->ImgGallery($url);
								}
							}
					}
					echo '</div>';
				echo '</div>';

    			
				echo '</div>';
			echo '</div>';
	}
	
	public function ImgGallery($link){
		include ('./bdd/bdd.php');
		echo '<div style="display:none">';
			$resultat = $bdd->query('SELECT * FROM img WHERE numtask="'.$this->getId().'" && url !="'.$link.'"');
			while ($donnees = $resultat->fetch()) {
   				if ($donnees['state']=="active"){
   					echo '<a class="fancybox" rel="taskgallery'.$this->getId().'" title="'.$donnees['description'].'" href="./img/taskimg/'.$donnees['url'].'"><img src="./img/taskimg/'.$donnees['url'].'" alt=""/></a>';
				}
			}
			echo '</div>';
	}
	
	public function addImg(array $task, $url, $description){ /* ajoute une image à la tâche */
		$new_img= new Img ($description, $url, $this);
		$updatetab=array_push($task, $new_img);
		$this->setImg($task);
		
		
		return $new_img;
	}
	
	public function eraseImg(array $imglist, $supprimg, $ajax="false"){ /* supprime une image de la tâche */
			$tmplist= array();
			foreach($imglist as $img){
				if ($img->getId()!=$supprimg){
					array_push($tmplist, $img);
				}
				else{
					$img->erase($ajax);	
				}
			}
			$this->setImg($tmplist);
	}
	
	public function editImg(array $imglist, $editimg, $description="", $url="", $ajax="false"){ /* edite une image à la tâche */
			foreach($imglist as $img){
				if ($img->getId()==$editimg){
					$img->edit($description, $url, $ajax);
				}
			}
	}
	
	public function showListImgs(array $imglist){ /* affiche toutes les image de la tâche */
		foreach($imglist as $img){
			$img->showInfo();
		}

	}
	
	public function testimgs(array $imglist){ /* affiche toutes les image de la tâche */
		foreach($imglist as $img){
			echo 'image: '.$img->getId().' info<br>';
			echo 'image url: '.$img->getUrl().'<br>';
			echo 'image description: '.$img->getDescrip().'<br>';
			echo 'image state: '.$img->getState().'<br>';
			echo '-------------------------------------------------<br>';
		}

	}
	
	public function showImgbutton(array $imglist){ /* affiche toutes les image de la tâche */
		echo '<div id="imgmenu" class="imgmenu"/>';
		foreach($imglist as $img){
			if ($img->getState() == "active") {
				echo '<img class="option" id="imgbutton_'.$img->getId().'" title="Changer image" alt="changer image" src="./img/imgs.png" onClick="GetImgid('.$img->getId().')">';
			}
		}
			echo '<img class="option" id="imgnextbutton" title="Ajouter image" alt="ajouter image" src="./img/add.png" onClick="Opennewimg()">';
		echo '</div>';
	}
	
	public function saveImgs(array $imglist){
		foreach($imglist as $img){
				$img->save();
			}
	}
	
	public function save($ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$addData=$bdd->prepare('INSERT INTO task (title, description, numlist ,state) VALUES (:title, :description, :list, :state)');
		$addData->execute(array(
				'title' => $this->getTitle(),
				'description' => $this->getDescrip(),
				'list' => $this->getList()->getId(),
				'state' => 'active'
				));
		echo '<p>Task saved</p>';
		
		$resultat = $bdd->query('SELECT * FROM task WHERE title="'.$this->getTitle().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setId($donnees['id']);
		}
		$this->saveImgs($this->getImg());
	}
	
	public function erase($ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$updateData = $bdd -> prepare('UPDATE task SET state = :state WHERE id="'.$this->getId().'"');
		$updateData->execute(array(
						'state' => "erase"
						));
						
		echo '<p>Task data erased</p>';
		
		unset($this);

	}
	
	public function edit($title="", $description="", $ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$changetitle=($title !=$this->getTitle() && $title !="");
		$changedescription=($description !=$this->getDescrip() && $description !="");
		
				
		if ($changetitle==true){
				$updateData = $bdd -> prepare('UPDATE task SET title = :title WHERE id="'.$this->getId().'"');
				$updateData->execute(array(
						'title' => $title
						));
				echo '<p>Task title updated</p>';
		}
		if ($changedescription==true){
				$updateData = $bdd -> prepare('UPDATE task SET description = :description WHERE id="'.$this->getId().'"');
				$updateData->execute(array(
						'description' => $description
						));
				echo '<p>Task description updated</p>';
		}
		
		$resultat = $bdd->query('SELECT * FROM task WHERE id="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setTitle($donnees['title']);
			$this->setDescrip($donnees['description']);
		}
	}
	
	public function editState($state, $ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$updateData = $bdd -> prepare('UPDATE task SET state = :state WHERE id="'.$this->getId().'"');
				$updateData->execute(array(
						'state' => $state
						));

		$resultat = $bdd->query('SELECT * FROM task WHERE id="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setState($donnees['state']);
		}
	}
	
	public function load(ToDoList $list, $id, $ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$resultat = $bdd->query('SELECT * FROM task WHERE id="'.$id.'" && numlist="'.$list->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setTitle($donnees['title']);
			$this->setDescrip($donnees['description']);
			$this->setList($list);
			$this->setId($donnees['id']);
			$this->setState($donnees['state']);
		}
		
		$tabimg=array();
		$resultat = $bdd->query('SELECT * FROM img WHERE numtask="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$img= new Img ("", "", $this);
			if ($ajax=="false"){
				$img->load($this, $donnees['id']);			}
			else {
				$img->load($this, $donnees['id'], "true");
			}
			array_push($tabimg, $img);
		}
		$this->setImg($tabimg);
	}	




public function showInfo2(){
		include ('./bdd/bdd.php');
		echo '<div class="listitem" id="tasklist'.$this->getId().'">';
		echo '<div class="taskoption">';
		echo '</div>';
		echo '<div id="listinfo'.$this->getId().'">';
		echo '<div id="listtitle'.$this->getId().'" class="listtitle">';
		echo $this->getTitle();
		echo '</div>';
		echo '<div id="listdescrip'.$this->getId().'" class="listdescrip">';
		echo $this->getDescrip();
		echo '</div>';
		echo '</div>';	
		 
		/*echo '<div class="taskitem '.$this->getList()->getId().'" id="task_'.$this->getId().'" style="display:block">';
			echo '<div class="taskoption shift">';
			echo '<img class="option" src="./img/suppr.png" alt="Supprimer tâche" title="supprimer tâche" onClick="eraseTask('.$this->getList()->getId().', '.$this->getId().')"/>';
			echo '<img id="edittaskbutton'.$this->getId().'" class="option" src="./img/edit.png" alt="Editer tâche" title="editer tâche" onClick="editTaskButton('.$this->getId().')" />';*/
			echo '</div>';
			echo '<div class="taskitem '.$this->getList()->getId().'" id="task_'.$this->getId().'" style="display:block">';
			echo '<div class="taskoption">';
			echo '</div>';
			echo '<div class="actionname">Informations de '.$this->getTitle().'</div>';
			echo '<div id="edittitletask'.$this->getId().'" class="tasktitle" style="display:block">';
			echo '<input type="text" name="edittitletask'.$this->getId().'" placeholder="Nouveau nom"/>';
			echo '</div>';
			echo '<div id="editdescriptask'.$this->getId().'" class="taskdescrip" style="display:block">';
			echo '<input type="text" name="editdescriptask'.$this->getId().'" placeholder="Nouvelle description"/>';
			echo '</div>';
			echo '</div>';
			echo '<div class="taskimgsedit '.$this->getList()->getId().'">';
			echo '<div class="taskoptionedit">';
			echo '</div>';
			echo '<div style="margin:0 auto auto auto; width:85%">';
			echo '<div class="actionname">'.$this->getTitle().' Image Gallery</div>';
					
					echo '<div class="editimgdiv" id="editimg'.$this->getId().'" style="display:block">';
					$this->showImgbutton($this->getImg());
					echo '</div>';
					echo '</div>';
			echo '</div>';
			
					$this->showListImgs($this->getImg());
					
					
			
					echo '<div id="img_next_div" class="itemtask '.$this->getList()->getId().'" style="display: none; border-bottom: 1px solid blue;">';
							echo '<div id="img_next_shift" class="" style="border-right: 1px solid red; display: table-cell; width: 214px; min-width: 92px;">';
							echo '</div>';
							echo '<div id="img_next" style="display: table-cell; padding: 5px; box-sizing:padding-box; width: 85%;">';
							echo 'Uploader l\'image : <form action="./functions/upload.php" class="dropzone" id="taskaddimgfile"></form>';
							echo '<br>';
							echo 'Ajouter Image via Url : <input type="url" id="task'.$this->getId().'addimglink" pattern="https?://.+" style="min-width:100%" >';
							echo '<br>';
							echo '<div id="editconfirmtask'.$this->getId().'" class="confirmedittask" style="display:block">';
							echo '<input type="button" value="Ajouter Image" onClick="addlinkimg('.$this->getId().')">';
							echo '</div>';
							echo '<br>';
							echo '</div>'; 
		
					echo '</div>';
			
			/*echo '</div>';*/
						
			echo '<div class="taskitem '.$this->getList()->getId().'" style="display:block">';
			echo '<div class="taskoption">';
			echo '</div>';
			echo '<div id="editconfirmtask'.$this->getId().'" class="confirmedittask" style="display:block">';
			echo '<input type="button" value="Editer tâche" onClick="editTask('.$this->getList()->getId().', '.$this->getId().')"/>';
			echo '</div>';    		
			echo '</div>';		
	}
	
	
	
}	
?>