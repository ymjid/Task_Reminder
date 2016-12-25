<?php
class Img {
	private $_id; /* identifiant de l'image */
	private $_description; /* notes à propos de l'image */
	private $_state; /* etat de l'image */
	private $_url; /* lien de l'image */
	private $_task; /* tâche qui utilise l'image */
	
	 public function __construct($description="", $url="", Task $task) { /* Constructeur */
		$this->_id = 0; /* Initialisation de l'id à 0.*/
		$this->setDescrip($description); /* Initialisation de la description.*/
		$this->_state = 'active'; /* Initialisation de l'état à "active" */
		$this->setUrl($url); /* Initialisation de l'url */
		$this->setTask($task); /* Initialisation de la tâche */

  	}
  	
	public function getId(){ /* renvoie l'identifiant */
		return $this->_id;
	}
	
	public function getDescrip(){ /* renvoie les notes */
		return $this->_description;
	}
	
	public function getState(){ /* renvoie l'etat */
		return $this->_state;
	}
	
	public function getUrl(){ /* renvoie le lien */
		return $this->_url;
	}
	
	public function getTask(){ /* renvoie la tâche */
		return $this->_task;
	}
	
	public function setId($id){ /* définit l'identifiant */
		$this->_id = $id;
	}
	
	public function setDescrip($description){ /* définit les notes */
		$this->_description = $description;
	}
	
	public function setState($state){ /* définit l'etat */
		$this->_state = $state;
	}
	
	public function setUrl($url){ /* définit le lien */
		$this->_url = $url;
	}
	
	public function setTask($task){ /* définit la tâche */
		$this->_task = $task;
	}
	
	public function showInfo(){
		if ($this->getState() != 'erase'){
			echo '<div id="img_'.$this->getId().'" style="display:none;border-bottom: 1px solid blue;" class="" >';
			echo '<div id="img_shift_'.$this->getId().'" class="" style="border-right: 1px solid red; display: table-cell; width: 214px; min-width: 92px;">';
			echo '</div>';		
			/*echo '<img class="option" id="imgbutton_'.$this->getId().'" title="Changer image" alt="changer image" src="./img/imgs.png" onClick="GetImgid('.$this->getId().')">';*/
			echo '<div style="display: table-cell; padding: 5px; box-sizing:padding-box; width: 85%;">';
			echo '<img class="option eraseoption" id="imgbutton_'.$this->getId().'" title="Supprimer image" alt="supprimer image" src="./img/suppr.png" onClick="supprimg('.$this->getId().')">';
			echo '<div id="imgpreview" class="imgpreview">';
			$imgsize=getimagesize("./img/taskimg/".$this->getUrl());
			if ($imgsize[0] > 500 || $imgsize[1] > 500) {
				$cent="false";
				if ($imgsize[0] > 500) {
					$newimgsize[0] = 100;
					$cent="true";
				}
				else {
					$newimgsize[0] = $imgsize[0];
				}
				if ($imgsize[1] > 500) {
					$newimgsize[1] = 100;
					$cent="true";
				}
				else {
					$newimgsize[1] = $imgsize[1];
				}
				if ($cent == "true") {
					echo '<img id="imgid_'.$this->getId().'" src="./img/taskimg/'.$this->getUrl().'" alt="'.$this->getUrl().'" title="'.$this->getDescrip().'" height='.$newimgsize[1].'% width='.$newimgsize[0].'% >';
				}
				else {
					echo '<img id="imgid_'.$this->getId().'" src="./img/taskimg/'.$this->getUrl().'" alt="'.$this->getUrl().'" title="'.$this->getDescrip().'" height='.$newimgsize[1].'px width='.$newimgsize[0].'px >';
				}
			}
			else {
				echo '<img id="imgid_'.$this->getId().'" src="./img/taskimg/'.$this->getUrl().'" alt="'.$this->getUrl().'" title="'.$this->getDescrip().'" >';
			}
			echo '</div>';
			echo '<div class="editimg">';
			echo 'Uploader la nouvelle image : <form action="./functions/upload.php" class="dropzone" id="taskeditimgfile"></form>';
			echo '<br>';
			echo 'Changer Image via Url : <input type="url" id="task'.$this->getTask()->getId().'editimglink'.$this->getId().'" pattern="https?://.+" style="min-width:100%" >';
			echo '<br>';
			echo '<div id="editconfirmtask'.$this->getId().'" class="confirmedittask" style="display:block">';
			echo '<input type="button" value="Modifier Image" onClick="editimg('.$this->getTask()->getId().' , '.$this->getId().')">';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}
	
	public function save($ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$addData=$bdd->prepare('INSERT INTO img (description, url, numtask ,state) VALUES (:description, :url, :task, :state)');
		$addData->execute(array(
				'description' => $this->getDescrip(),
				'url' => $this->getUrl(),
				'task' => $this->getTask()->getId(),
				'state' => 'active'
				));
		/*echo '<p>Image saved</p>';*/
		
		$resultat = $bdd->query('SELECT * FROM img WHERE url="'.$this->getUrl().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setId($donnees['id']);
		}
	}
	
	public function erase($ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$updateData = $bdd -> prepare('UPDATE img SET state = :state WHERE id="'.$this->getId().'"');
		$updateData->execute(array(
						'state' => "erase"
						));
						
		/*echo '<p>Image data erased</p>';*/
		
		unset($this);

	}
	
	public function edit($description="", $url="", $ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$changeurl=($url !=$this->getUrl() && $url !="");
		$changedescription=($description !=$this->getDescrip() && $description !="");
		
				
		if ($changeurl==true){
				$updateData = $bdd -> prepare('UPDATE img SET url = :url WHERE id="'.$this->getId().'"');
				$updateData->execute(array(
						'url' => $url
						));
				echo '<p>Image url updated</p>';
		}
		if ($changedescription==true){
				$updateData = $bdd -> prepare('UPDATE img SET description = :description WHERE id="'.$this->getId().'"');
				$updateData->execute(array(
						'description' => $description
						));
				/*echo '<p>Image description updated</p>';*/
		}
		
		$resultat = $bdd->query('SELECT * FROM img WHERE id="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setUrl($donnees['url']);
			$this->setDescrip($donnees['description']);
		}
	}
	
	public function load(Task $task, $id, $ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$resultat = $bdd->query('SELECT * FROM img WHERE id="'.$id.'" && numtask="'.$task->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setUrl($donnees['url']);
			$this->setDescrip($donnees['description']);
			$this->setTask($task);
			$this->setId($donnees['id']);
			$this->setState($donnees['state']);
		}
		
	}	
	
}
?>