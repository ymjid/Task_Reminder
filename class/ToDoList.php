<?php
class ToDoList{
	private $_title; /* titre de la liste*/
	private $_description; /* description de la liste */
	private $_user; /* utilisateur qui a crée la liste */
	private $_state; /* état de la liste */
	private $_id; /* identifiant de la liste */
	private $_tasks; /* liste de tâches */
	
	public function __construct(User $user, $title="No Title", $description="No description") { /* Constructeur */
		$this->setTitle($title); /* Initialisation du titre.*/
		$this->setDescrip($description); /* Initialisation de la description.*/
		$this->setUser($user);  /* Initialisation de l'utilisateur.*/
		$this->_state = "active"; /* Initialisation de l'état à "active" */
		$this->_id = 0; /* Initialisation de l'id à 0.*/
		$this->_tasks = array(); /* Initialisation de la liste de tâches.*/
  	}
  	
	public function getTitle(){ /* renvoie le titre */
		return $this->_title;
	}
	
	public function getDescrip(){ /* renvoie la description */
		return $this->_description;
	}
	
	public function getUser(){  /* renvoie le créateur de la liste */
		return $this->_user;
	}
	
	public function getState(){ /* renvoie l'état de la liste */
		return $this->_state;
	}
	
	public function getId(){ /* renvoie l'identifiant de la liste */
		return $this->_id;
	}
	
	public function getListTask(){ /* renvoie les tâches de la liste */
		return $this->_tasks;
	}
	
	public function setTitle($title){ /* définit le titre */
		$this->_title = $title;
	}
	
	public function setDescrip($description){ /* définit la description */
		$this->_description = $description;
	}
	
	public function setUser(User $user){ /* définit le créateur de la liste */
		$this->_user = $user;
	}
	
	public function setState($state){ /* définit l'état de la liste */
		$this->_state = $state;
	}
	
	public function setId($id){ /* définit l'identifiant de la liste */
		$this->_id = $id;
	}
	
	public function setListTask(array $list){ /* définit les tâches de la liste */
		$this->_tasks = $list;
	}
	
	public function addTask(array $list, $title, $description){ /* ajoute une tâche à la liste */
		$new_task= new Task ($title, $description, $this);
		$updatetab=array_push($list, $new_task);
		$this->setListTask($list);
		
		
		return $new_task;
	}
	
	public function eraseTask(array $list, $supprtask, $ajax="false"){ /* supprime une tâche de la liste */
			$tmplist= array();
			foreach($list as $task){
				if ($task->getId()!=$supprtask){
					array_push($tmplist, $task);
				}
				else{
					$task->erase($ajax);	
				}
			}
			$this->setListTask($tmplist);
	}
	
	public function editTask(array $list, $edittask, $title="", $description="", $img="", $ajax="false"){ /* edite une tâche à la liste */
			foreach($list as $task){
				if ($task->getId()==$edittask){
					$task->edit($title, $description, $img, $ajax);
				}
			}
	}
	
	public function showListTasks(array $list){ /* affiche toutes les tâches de la liste */
		foreach($list as $task){
			if ($task->getState()!="erase"){
				$task->showInfo();
			}
		}

	}
	
	public function testtasks(array $list){ /* affiche toutes les tâches de la liste */
		foreach($list as $task){
			echo 'task '.$task->getId().' info <br>';
			echo 'task title: '.$task->getTitle().'<br>';
			echo 'task description: '.$task->getDescrip().'<br>';
			echo 'task state: '.$task->getState().'<br>';
			echo 'user state: '. $task->getList()->getUser()->getState().'<br>';
			echo '-------------------------------------------------<br>';
			$task->testimgs($task->getImg());
		}

	}
	
	public function findTask(array $list, $num){ /* affiche la tâche $num de la liste */
		foreach($list as $task){
			if ($task->getId()==$num){
				return $task;
			}
		}

	}
	
	public function purgeList(array $list) { /* enleve les tâches effacées de la liste */
		$tmplist=array();
		foreach($list as $task){
			if ($task->getState()!="erase"){
				array_push($tmplist, $task);
			}
		}
		return $tmplist;
	}
	public function countTask(array $list) {
		$tmplist=array();
		$activetask=0;
		$finishtask=0;
		foreach($list as $task){
			if ($task->getState()!="erase"){
				if ($task->getState()!="inactive") {
					$activetask++;
				}
				if ($task->getState()=="done") {
					$finishtask++;
				}
			}
		}
		
		return array($activetask, $finishtask);
	}
	
	public function showInfo(){
		$havetask="false";
		$purged=$this->purgeList($this->getListTask());
		if ($purged!=array()) {
			$listclass= "listitem";
			$havetask="true";
			$nbtasks=$this->countTask($purged);
			$progress=($nbtasks[1]*100)/$nbtasks[0];
		}
		else {
			$listclass= "listitem notaskitem";
		}
		echo '<div style="z-index:0;position:relative;" class="'.$listclass.'" id="tasklist'.$this->getId().'">';
		
		if ($havetask=="true") {
			echo '<div id="listprogress_'.$this->getId().'" style="width: '.$progress.'%; min-height: 105px; position: absolute; background-color: #78EE5E; z-index: 1;opacity:0.4;"></div>';
		}
		
		echo '<div style="z-index:1;position:relative;">';
		
		echo '<div id="listoption_'.$this->getId().'" class="listoption">';
		if ($this->getUser()->getState()=="active"){
			echo '<img class="option" src="./img/suppr.png" alt="Supprimer liste" title="supprimer liste" onClick="supprItem('.$this->getId().')" />';
			echo '<img class="option" id="editlistbutton'.$this->getId().'" src="./img/edit.png" alt="Editer liste" title="editer liste" onClick="editListButton('.$this->getId().')"/>';
			echo '<img class="option" id="addicon_'.$this->getId().'" src="./img/add.png" alt="Ajouter tâche" onClick="addTaskButton('.$this->getId().')" title="ajouter tâche" />';
		}
		if ($havetask=="true") {
			echo '<img class="option" id="showlist_'.$this->getId().'" src="./img/tasks.png" alt="Afficher les tâches" onClick="showTask('.$this->getId().')"  title="Afficher les tâches" />';
		}
		echo '</div>';
		echo '<div id="listinfo'.$this->getId().'" class="listinfos">';
		echo '<div id="listtitle'.$this->getId().'" class="listtitle">';
		echo $this->getTitle();
		echo '</div>';
		echo '<div id="listdescrip'.$this->getId().'" class="listdescrip">';
		echo $this->getDescrip();
		echo '</div>';
		echo '</div>';	
		

		
			echo '<div id="edittitlelist'.$this->getId().'" class="listinfos" style="display:none">';
			echo '<div class="actionname">';
			echo 'Edition d\'une liste<br>';
			echo '<input type="text" style="background:transparent;" id="edittitlelist'.$this->getId().'" name="edittitlelist'.$this->getId().'" placeholder="Nouveau nom"/><br>';
			/*echo '</div>';*/
			/*echo '<div id="editdescriplist'.$this->getId().'" class="listdescrip" style="display:none">';*/
			echo '<input type="text" style="background:transparent;" id="editdescriplist'.$this->getId().'"name="editdescriplist'.$this->getId().'" placeholder="Nouvelle description"/><br>';
			/*echo '</div>';*/
			/*echo '<div id="editconfirmlist'.$this->getId().'" class="confirmeditlist" style="display:none">';*/
			echo '<input type="button" id="editconfirmlist'.$this->getId().'" value="Editer liste" onClick="editList('.$this->getId().')"/><br>';
			echo '</div>';
			echo '</div>';
		echo '</div>';
		
		echo '</div>';
				
			echo '<div class="taskitem" id="addtask_'.$this->getId().'" style="display:none">';
			echo '<div class="taskoption" id="canceltask_'.$this->getId().'" style="display:none">';
			echo '<img class="option" title="annuler action" src="./img/cancel.png" alt="Annuler action" onClick="cancelAddTask('.$this->getId().')" />';
			echo '</div>';
			echo '<div class="actionname">';
			echo 'Ajout d\'une tâche<br>';
			/*echo '<div class="tasktitle">';*/
			echo '<input type="text" id="addtitletask'.$this->getId().'" name="addtitletask'.$this->getId().'" placeholder="Nom de la tâche"/><br>';
			/*echo '</div>';*/
			/*echo '<div class="taskdescrip">';*/
			echo '<input type="text" id="adddescriptask'.$this->getId().'" name="adddescriptask'.$this->getId().'" placeholder="Description de la tâche"/><br>';
			/*echo '<div>';*/	
			echo '<input type="button" value="Ajouter tâche" onClick="addTask('.$this->getId().')"/>';
			    /*echo '</div>';*/
			/*echo '</div>';*/
			echo '</div>';
			echo '</div>';
			
		$this->showListTasks($this->getListTask());
		echo '<div id="endlist'.$this->getId().'" style="display:none">End List '.$this->getId().'</div>';
	}
	
	public function save($ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$addData=$bdd->prepare('INSERT INTO list (title, description, numuser, state) VALUES (:title, :description, :user, :state)');
		$addData->execute(array(
				'title' => $this->getTitle(),
				'description' => $this->getDescrip(),
				'user' => $this->getUser()->getId(),
				'state' => 'active'
				));
		echo '<p>List saved</p>';
		
		$resultat = $bdd->query('SELECT * FROM list WHERE title="'.$this->getTitle().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setId($donnees['id']);
		}
		
		$this->saveTasks($this->getListTask());
		
	}
	
	public function erase($ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$updateData = $bdd -> prepare('UPDATE list SET state = :state WHERE id="'.$this->getId().'"');
		$updateData->execute(array(
						'state' => "erase"
						));
						
		echo '<p>List data erased</p>';
		
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
				$updateData = $bdd -> prepare('UPDATE list SET title = :title WHERE id="'.$this->getId().'"');
				$updateData->execute(array(
						'title' => $title
						));
				echo '<p>List title updated</p>';
		}
		if ($changedescription==true){
				$updateData = $bdd -> prepare('UPDATE list SET description = :description WHERE id="'.$this->getId().'"');
				$updateData->execute(array(
						'description' => $description
						));
				echo '<p>List description updated</p>';
		}
		
		$resultat = $bdd->query('SELECT * FROM list WHERE id="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setTitle($donnees['title']);
			$this->setDescrip($donnees['description']);
		}
	}
	
	public function load(User $user, $id, $ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$resultat = $bdd->query('SELECT * FROM list WHERE id="'.$id.'" && numuser="'.$user->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setTitle($donnees['title']);
			$this->setDescrip($donnees['description']);
			$this->setUser($user);
			$this->setState($donnees['state']);
			$this->setId($donnees['id']);
		}
		$tasklist=array();
		$resultat = $bdd->query('SELECT * FROM task WHERE numlist="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$task=new Task ("", "", $this);
			if ($ajax=="false"){
				$task->load($this, $donnees['id']);			
			}
			else {
				$task->load($this, $donnees['id'], "true");
			}
			array_push($tasklist, $task);
		}
		$this->setListTask($tasklist);
	}
	
	public function saveTasks(array $list){
		foreach($list as $task){
				$task->save();
			}
	}
}
?>