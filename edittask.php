<?php    
if (!isset($_POST['Listid']) && !isset($_POST['Taskid'])){
	header('Location: home.php');
}

function chargerClasse($classe){
	require './class/'.$classe . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.

session_start();
if (isset($_POST['logout'])){
	session_destroy();
	$_SESSION['user']="";
	unset($_SESSION);

}

if (!isset($_SESSION['user'])){
	$user = new User();
}
else {
	$user = $_SESSION['user'];	
}
include ('./bdd/bdd.php');
?>
<html class="background">
<head>
<title>Task Reminder  - Edit Task</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="./css/website.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

<!-- Add jQuery library -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="./js/website.js" type="text/javascript" charset="utf-8"></script>
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="./js/jquery/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="./js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="./js/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="./js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="./js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="./js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

<link rel="stylesheet" href="./js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="./js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<link rel="stylesheet" href="./js/dropzone/dist/dropzone.css" />
<script type="text/javascript" src="./js/dropzone/dist/dropzone.js"></script>
<script type="text/javascript">		
Dropzone.options.taskeditimgfile = {
  maxFiles: 1,
 accept: function(file, done) { 
 	  var confirmer = confirm ("Voulez-vous donnez une description à l'image ?");
    
    	if (confirmer == true){
    		var descrip = prompt ("Description de l'image :");
    	}
    	
    	if (confirmer == false || typeof descrip !== 'undefined'){
 		      
      var action = "edit";
   		if (confirmer == false){
 	  		var descriptxt = "";
   		}
   		else {
   			var descriptxt = descrip;
   		}
 	  var img = file.name;
       $.ajax({
   		type: "POST",
   		url: "./js/ajax/imgs.php",
   		data: {imgaction : action, imageid : editimgid, imgname : img, descripimg : descriptxt, task : edittaskid},
   		success: function(data){
   			//alert(data);//
   			//var testimg= document.getElementById("imgid_"+ editimgid);
   			document.getElementById("imgid_"+ editimgid).src = "./img/taskimg/"+ img ;
   			if (descriptxt != "") {
   				document.getElementById("imgid_"+ editimgid).title = descriptxt ;
   			}
   			//if (testimg.width > 500 || testimg.height > 500) {
   			//	var newheight = testimg.height/2;
   			//	var newwidth = testimg.width/2;
   			//	while (newwidth > 500 || newheight > 500) {
   			//		newheight = newheight/2;
   			//		newwidth = newwidth/2;
   			//	}
   			//	testimg.height =  newheight;	
   			//	testimg.width =  newwidth;
   			//}
   		}
 		});		
    }
 	this.on("maxfilesexceeded", function(file){
        alert("No more files please!");
        this.on("addedfile", function() {
      		if (this.files[1]!=null){
       	 		this.removeFile(this.files[1]);
      		}
   		 });
    });
    console.log("uploaded");
    done();
  },
};

Dropzone.options.taskaddimgfile = {
	accept: function(file, done) { 
 	  var confirmer = confirm ("Voulez-vous donnez une description à l'image ?");
    
    	if (confirmer == true){
    		var descrip = prompt ("Description de l'image :");
    	}
    	
    	if (confirmer == false || typeof descrip !== 'undefined'){
 		      
      var action = "add";
   		if (confirmer == false){
 	  		var descriptxt = "";
   		}
   		else {
   			var descriptxt = descrip;
   		}
 	  var img = file.name;
       $.ajax({
   		type: "POST",
   		url: "./js/ajax/imgs.php",
   		data: {imgaction : action, imgname : img, descripimg : descriptxt, task : edittaskid},
   		success: function(data){
   			//alert(data);//
   			var typeitem = "img";
			$.ajax({
   				type: "POST",
   				url: "./js/ajax/iteminfo.php",
   				data: {type : typeitem, list : edittaskid, title : img, descrip : descriptxt},
   				success: function(returnData){
  					var nextimgnum = returnData;
   					editableupimg(nextimgnum, img, descriptxt, edittaskid);    
   					}   					
 				});
   			}	
 		});		
    	}
    console.log("uploaded");
    done();
	}
};
</script>	
</head>
<?php
$page="edittask";
?>
<body onload="GetTaskid(<?php echo $_POST['Taskid']; ?>)" >
<div class="page">
<div class="header">
<div class="title"><img src="./img/TR_Logo.png"/><br>Task Reminder</div>
<div class="subtitle">Edit task</div>
</div>
<?php
	include ('./menu.php');
	if (isset($_POST['Listid']) && isset($_POST['Taskid'])){
		$testlist=$_SESSION['user']->findList($_SESSION['user']->getTabLists(), $_POST['Listid']);
		$testtask=$testlist->findTask($testlist->getListTask(), $_POST['Taskid']);
		$testtask->showInfo2();
	}
?>
<div class="footer">
	<p>&copy; Yan 2015 - <?php echo date('Y'); ?></p>
</div>
</div>

</body>
</html>