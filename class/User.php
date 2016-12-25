<?php
class User {
	private $_id; /* identifiant de l'utilisateur*/
	private $_email; /* email de l'utilisateur */
	private $_name; /* nom de l'utilisateur */
	private $_pwd;  /* mot de passe de l'utilisateur */
	private $_code; /* code de restauration de mot de passe */
	private $_type; /* type d'utilisateur (inscrit, visiteur, etc.) */
	private $_state; /* état du compte utilisateur */
	private $_lists; /* tableau de listes */
	
	 public function __construct($name="Invité", $pwd="", $type="Visiteur") { /* Constructeur */
		$this->setName($name); /* Initialisation du nom.*/
		$this->setPwd($pwd); /* Initialisation du mot de passe.*/
		$this->_code = "NULL"; /* Initialisation du code */
		$this->setType($type); /* Initialisation du type.*/
		$this->_state = "unknown";
		$this->_id = 0; /* Initialisation de l'id à 0.*/
		$this->_email = "guest@email.com";
		$this->_lists = array(); /* Initialisation du tableau de listes*/

  	}
  	
	public function getId(){ /* renvoie l'identifiant */
		return $this->_id;
	}
	
	public function getName(){ /* renvoie le nom */
		return $this->_name;
	}
	
	public function getPwd(){ /* renvoie le mot de passe */
		return $this->_pwd;
	}
	
	public function getCode(){ /* renvoie le code */
		return $this->_code;
	}
	
	public function getEmail(){ /* renvoie l'email */
		return $this->_email;
	}
	
	public function getType(){ /* renvoie le type */
		return $this->_type;
	}
	
	public function getState(){ /* renvoie l'état du compte */
		return $this->_state;
	}
	
	public function getTabLists(){ /* renvoie les listes de l'utilisateur */
		return $this->_lists;
	}
	
	public function setId($id){ /* définit l'identifiant */
		$this->_id = $id;
	}
	
	public function setName($name){ /* définit le nom */
		$this->_name = $name;
	}
	
	public function setPwd($pwd){ /* définit le mot de passe */
		$this->_pwd = $pwd;
	}
	
	public function setCode($code){ /* définit le code */
		$this->_code = $code;
	}
	
	public function setEmail($email){ /* définit l'email */
		$this->_email = $email;
	}
	
	public function setType($type){ /* définit le type */
		$this->_type = $type;
	}
	
	public function setState($state){ /* définit l'état du compte */
		$this->_state = $state;
	}
	
	public function setTabLists(array $tab){ /* définit les listes de l'utilisateur */
		$this->_lists = $tab;
	}
	
	public function addList(array $tab, $title, $description){ /* ajoute une liste pour l'utilisateur */
		$new_list= new ToDoList ($this, $title, $description);
		$updatetab=array_push($tab, $new_list);
		$this->setTabLists($tab);
		
		
		return $new_list;
	}
	
	
	public function showUserLists(array $tab){ /* affiche toutes les listes de l'utilisateur */
		$activelist=0;
		foreach($tab as $list){
			if ($list->getState()!="erase"){
				$list->showInfo();
				$activelist++;
			}
		}
		if ($tab==array() || $activelist==0) {
			echo '<div class="listitem" id="nolist">';
				echo '<div class="listoption">';
				echo '</div>';
				echo '<div>';
					echo '<div class="listtitle">';
					echo 'Aucune liste de tâches n\'existe.';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}

	}
	
	public function testlists(array $tab, $purge="off"){ /* affiche toutes les listes de l'utilisateur */
		foreach($tab as $list){
			$customlist=$list->getListTask();
			echo '<div style="display:table-cell">';
			echo 'list '.$list->getId().' info<br>';
			echo 'list title: '.$list->getTitle().'<br>';
			echo 'list description: '.$list->getDescrip().'<br>';
			echo 'list state: '.$list->getState().'<br>';
			echo 'user state : '.$list->getUser()->getState().'<br>';
			echo '-------------------------------------------------<br>';
			if ($purge=="on") {
				$customlist=$list->purgeList($list->getListTask());
			}
			$list->testtasks($customlist);
			echo '</div>';
		}

	}
	
	public function findList(array $tab, $num){ /* affiche la liste $num de l'utilisateur */
		foreach($tab as $list){
			if ($list->getId()==$num){
				return $list;
			}
		}

	}
	
	public function createAccount($userid, $usermail, $userpwd, $usercpwd) {
		include ('./bdd/bdd.php');
		$error = "0";
		$resultat = $bdd->query('SELECT * FROM user');
		while ($donnees = $resultat->fetch()) {
			if ($userid==$donnees['name']){
				$error="1";
			}
			if ($usercpwd!=$userpwd){
				$error="2";
			}
			if ($usermail==$donnees['email']){
				$error="3";
			}
		}

		if ($error=="0"){
			$this->setName($userid);
			$this->setEmail($usermail);
			$this->setPwd($userpwd);
			$this->setState("active");
			$this->save ("false");
			$this->sendemail("welcome");
		}
		
		return $error;
	}
	
	public function login ($userid, $userpwd) {
		include ('./bdd/bdd.php');
		$connexion="false";
		$resultat = $bdd->query('SELECT * FROM user');
		while ($donnees = $resultat->fetch()) {
			if (($userid==$donnees['name']) && (md5($userpwd)==$donnees['pwd'])){
				if ($donnees['state']!="erase") {
					$this->load($userid);
					$_SESSION['user']=$this;
					$connexion="true";
				}
				else {
					$connexion="NULL";
				}
			}
		}
		
		return $connexion;
		
	}
	
	public function lostpwd($mail) {
		include ('./functions/functions.php');
		include ('./bdd/bdd.php');
		$userexist=false;
		$resultat = $bdd->query('SELECT email FROM user');
		while ($donnees = $resultat->fetch()) {
			if ($mail==$donnees['email']){
				$userexist=true;
			}
		}
		if ($userexist==true){
			$user=new User();
			$user->load($mail);
			$time=date("Y-m-d H:i:s", time());
			$code=customcrypt($user, $time);
			$user->setCode($code);
			$updateData = $bdd->prepare('UPDATE user SET lost = :nopwd, lost_time = :time, lost_id = :code WHERE email="'.$mail.'"');
			$updateData->execute(array(
								'nopwd' => 'Y',
								'time' => $time,
								'code' => $code
			));

			$user->sendemail("lost_pwd");
		
		}
	
		return $userexist;	
	}
	
	public function checkaskpwd($code){
		$time=time();
		if ($this->getCode()!="NULL" &&  $this->getCode()!=$code){
	 		header('Location: home.php');
		}
		else {
			include ('./bdd/bdd.php');
			$redirect=true;
			$this->load($code);
			$resultat = $bdd->query('SELECT * FROM user WHERE lost_id="'.$this->getCode().'"');
				while ($donnees = $resultat->fetch()) {
						$time2 = ($time - strtotime($donnees['lost_time']));
						if (($donnees['lost']=='Y') && ($time2 <= 1800)){
							$redirect=false;
						}
						if ($time2 > 1800){
							$this->restoredpwd();	
						}
				}		
			if ($redirect==true){
				header('Location: home.php');
			}
		}
	}
	
	public function restoredpwd(){
		include ('./bdd/bdd.php');
		$updateData = $bdd->prepare('UPDATE user SET lost = :nopwd, lost_time = :time, lost_id = :code WHERE lost_id="'.$this->getCode().'"');
		$updateData->execute(array(
					'nopwd' => 'N',
					'time' => NULL,
					'code' => NULL
		));
	}
	
	public function sendemail($mailtype){ /* envoie un e-mail à l'adresse mail indiquée par l'utilisateur */
		require_once ('./functions/Swift_Mailer/swiftmailer/lib/swift_required.php');
		include_once ('./functions/functions.php');
		include_once ('./functions/options.php');
		$transport = Swift_SmtpTransport::newInstance($mailsmtp, $mailport, $mailencrypt)
  			->setUsername($mailsender)
  			->setPassword($mailuserpwd)
  			;
  		/*$transport = setupmail();*/	
  		$mailer = Swift_Mailer::newInstance($transport);
  		$message = Swift_Message::newInstance();
		switch ($mailtype){
				case "alert" :
					$message->setSubject('Task Reminder - Alerte Newsletter');
					ob_start();
					include('./functions/mail_template/newsletter.php');
					$message_html = ob_get_contents();
					ob_end_clean();
					break;
				case "lost_pwd" :
					$message->setSubject('Task Reminder - Mot de passe perdu');
					ob_start();
					include ('./functions/mail_template/lost_pwd.php');
					$message_html = ob_get_contents();
					ob_end_clean();
					break;
				case "welcome" :
					$message->setSubject('Bienvenue sur Task Reminder');
					ob_start();
					include('./functions/mail_template/welcome.php');
					$message_html = ob_get_contents();
					ob_end_clean();
					break;
		}
		$message->setFrom(array($mailsender => 'Administrateur Task Reminder'));
		$message->setTo(array($this->getEmail() => $this->getName()));
		$message->setBody($message_html, 'text/html');
		$message->setPriority(2);
		$send = $mailer->send($message);
	}
	
	public function checkerasetime($state){
		include ('../../bdd/bdd.php');
		$setTime="0";
		if ($state=="inactive") {
					$time=date("Y-m-d H:i:s", (time() + (30 * 24 * 60 * 60)));
					$updateData = $bdd->prepare('UPDATE user SET erase_time = :Etime WHERE id="'.$this->getId().'"');
					$updateData->execute(array(
							'Etime' => $time
					));
					$resultat = $bdd->query('SELECT * FROM user WHERE id="'.$this->getId().'"');
					while ($donnees = $resultat->fetch()) {
						if ($donnees['erase_time']!= "NULL") {
							$mkdate=$donnees['erase_time'];
							$setTime=date("M d, Y H:i:s", strtotime($mkdate));
						}
					}
		}
		else {
					$updateData = $bdd->prepare('UPDATE user SET erase_time = :Etime WHERE id="'.$this->getId().'"');
					$updateData->execute(array(
							'Etime' => NULL
					));
		}
		
		return $setTime;
	}
	
	public function erasecountdown(){
		include ('./bdd/bdd.php');
		$countdown="";
		$resultat = $bdd->query('SELECT * FROM user WHERE id="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			if ($donnees['erase_time']!= "NULL") {
				$mkdate=$donnees['erase_time'];
				$countdown=date("M d, Y H:i:s", strtotime($mkdate));
			}
		}
		return $countdown;
	}
	
	public function eraseLists(array $tab, $supprlist, $ajax="false"){ /* supprime une liste de l'utilisateur */
			$tmptab= array();
			foreach($tab as $list){
				if ($list->getId()!=$supprlist){
					array_push($tmptab, $list);
				}
				else {
					$list->erase($ajax);
				}
			}
			$this->setTabLists($tmptab);
	}
	
	public function saveLists(array $tab, $ajax="false"){
		foreach($tab as $list){
				$list->save($ajax);
			}
	}
	
	public function editLists(array $tab, $editlist, $title, $description, $ajax="false"){ /* supprime une liste de l'utilisateur */
			$tmptab= array();
			foreach($tab as $list){
				if ($list->getId()==$editlist){
					$list->edit($title, $description, $ajax);
				}
			}
			$this->setTabLists($tmptab);
	}
	
	public function save ($ajax="false"){ /* sauvegarde les données de l'utilisateur dans la bdd */
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$addData=$bdd->prepare('INSERT INTO user (name, pwd, email, type, state) VALUES (:name, :pwd, :email, :type, :state)');
		$addData->execute(array(
				'name' => $this->getName(),
				'pwd' => md5($this->getPwd()),
				'email' => $this->getEmail(),
				'type' => 'Inscrit',
				'state' => 'active'
				));
		/*echo '<p>User saved</p>';*/
		
		$resultat = $bdd->query('SELECT * FROM user WHERE name="'.$this->getName().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setId($donnees['id']);
			$this->setState($donnees['state']);
		}
		
		$this->saveLists($this->getTabLists());
	}
		
	public function erase ($ajax="false"){ /* efface les données de l'utilisateur de la bdd */
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$updateData = $bdd -> prepare('UPDATE user SET state = :state WHERE id="'.$this->getId().'"');
		$updateData->execute(array(
						'state' => "erase"
						));
		
		/*echo '<p>User data erased</p>';*/
		
		unset($this);
	} 
		
	public function edit ($name="", $pwd="", $cpwd="", $email="", $state="", $ajax="false"){ /* edite les données de l'utilisateur de la bdd */
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$changename=($name !=$this->getName() && $name !="");
		$changepwd=(md5($pwd) !=$this->getPwd() && $pwd !="" && $cpwd !="" && $cpwd==$pwd);
		$changeemail= ($email != $this->getEmail() && $email != "");
		
		$changetab= array ();
		$changetab[0]=0;
		$changetab[1]=0;
		$changetab[2]=0;
		$changetab[3]=0;

		if ($changename==true){
				$changetab[0]=1;
				$resultat = $bdd->query('SELECT * FROM user');
				while ($donnees = $resultat->fetch()) {
					if ($this->getId() != $donnees['id'] && $name == $donnees['name']) {
						$changetab[0]=2;
					}						
				}
				if ($changetab[0]==1) {
					$updateData = $bdd -> prepare('UPDATE user SET name = :name WHERE id="'.$this->getId().'"');
					$updateData->execute(array(
							'name' => $name
							));
				}

		}
		if ($changepwd==true){
				$changetab[1]=1;
				$updateData = $bdd -> prepare('UPDATE user SET pwd = :pwd WHERE id="'.$this->getId().'"');
				$updateData->execute(array(
						'pwd' => md5($pwd)
						));
		}
		else {
			if ($cpwd!=$pwd){
				$changetab[1]=2;
			}	
		}
		if ($changeemail==true){
				$changetab[2]=1;
				$resultat = $bdd->query('SELECT * FROM user');
				while ($donnees = $resultat->fetch()) {
					if ($this->getId() != $donnees['id'] && $email == $donnees['email']) {
						$changetab[2]=2;
					}						
				}
				if ($changetab[2]==1){
					$updateData = $bdd -> prepare('UPDATE user SET email = :email WHERE id="'.$this->getId().'"');
					$updateData->execute(array(
							'email' => $email
							));
				}
		}
		
		if ($state != "") {
			$changetab[3]=1;
			$updateData = $bdd -> prepare('UPDATE user SET state = :state WHERE id="'.$this->getId().'"');
			$updateData->execute(array(
					'state' => $state
					));
		}
		
		$resultat = $bdd->query('SELECT * FROM user WHERE id="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$this->setName($donnees['name']);
			$this->setPwd($donnees['pwd']);
			$this->setEmail($donnees['email']);
			$this->setState($donnees['state']);
		}
				
		return $changetab;
	}
	
	public function showInfo(){
		echo 'user '.$this->getId().' info<br>';
		echo 'name : '.$this->getName().'<br>';
		echo 'type : '.$this->getType().'<br>';
		echo 'pwd : '.$this->getPwd().'<br>';
		echo 'state : '.$this->getState().'<br>';
	}
	
	public function load($name, $ajax="false"){
		if ($ajax=="false"){
			include ('./bdd/bdd.php');
		}
		else {
			include ('../../bdd/bdd.php');
		}
		$resultat = $bdd->query('SELECT * FROM user WHERE name="'.$name.'" || email="'.$name.'" || lost_id="'.$name.'"');
		while ($donnees = $resultat->fetch()) {
			$this->setName($donnees['name']);
			$this->setPwd($donnees['pwd']);
			$this->setEmail($donnees['email']);
			$this->setType($donnees['type']);
			$this->setId($donnees['id']);
			$this->setCode($donnees['lost_id']);
			$this->setState($donnees['state']);
		}
		
		$tablist=array();
		$resultat = $bdd->query('SELECT * FROM list WHERE numuser="'.$this->getId().'"');
		while ($donnees = $resultat->fetch()) {
			$list= new ToDoList ($this, "", "");
			$list->load($this, $donnees['id']);
			array_push($tablist, $list);
		}
		$this->setTabLists($tablist);
	}	
}
?>