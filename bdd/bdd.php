<?php
$hostdb='localhost';
$db='tr_bdd';
$userdb='root';
$pwddb='root';
$bdd = new PDO('mysql:host='.$hostdb.';dbname='.$db.'',$userdb,$pwddb);
$bdd->exec("SET CHARACTER SET utf8");
?>