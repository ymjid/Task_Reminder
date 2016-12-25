		function showTask(Listid){
		   var tags=document.getElementsByTagName("*"),res=[];
   			for(var i=0;i<tags.length;i++)
   			{
      			if(tags[i].className === "taskitem " + Listid)
      			{
         		res.push(tags[i]);
      			}
   			}
     		for(i=0; i < res.length; i++)// parcours une boucle de la longueur de l'ensemble
    		{
                //s'il est caché l'affiche, sinon le cache
        		if(res[i].style.display == "none")
        		{
            		res[i].style.display = "block";
            		var show=false;
        		}
        		else
        		{
            		res[i].style.display = "none";
            		var show=true;
        		}
    		}
    		
    		if (show == false){
    			document.getElementById("showlist_"+ Listid).src = "./img/list.png";
    			document.getElementById("showlist_"+ Listid).title = "Masquer les tâches";
    			document.getElementById("showlist_"+ Listid).alt = "Masquer les tâches";
    		}
    		else {
    			document.getElementById("showlist_"+ Listid).src = "./img/tasks.png";
    			document.getElementById("showlist_"+ Listid).title = "Afficher les tâches";
    			document.getElementById("showlist_"+ Listid).alt = "Afficher les tâches";
    		}
		}
		
		function addListButton(){
			if (document.getElementById("addlist_").style.display == "none"){
				document.getElementById("addlist_").style.display = "";
				document.getElementById("cancellist_").style.display = "";
				document.getElementById("addicon_").style.display = "none";

			}	
		}
		
		function addTaskButton(Listid){
			if (document.getElementById("addtask_" + Listid).style.display == "none"){
				document.getElementById("addtask_" + Listid).style.display = "";
				document.getElementById("canceltask_" + Listid).style.display = "";
				document.getElementById("addicon_" + Listid).style.display = "none";

			}	
		}
		
		function addList(){
				var title= document.getElementsByName("addtitlelist")[0].value;
				var descrip= document.getElementsByName("newdescriplist")[0].value;
				var action="add";
				$.ajax({
   					type: "POST",
   					url: "./js/ajax/item.php",
   					data: {action : action, titleitem : title, descripitem : descrip},
   					success: function(data){
   						//alert(data);//
   						document.getElementById("addlist_").style.display = "none";
						document.getElementById("cancellist_").style.display = "none";
						document.getElementById("addicon_").style.display = "";
						var typeitem = "list";
						$.ajax({
   						type: "POST",
   							url: "./js/ajax/iteminfo.php",
   							data: {type : typeitem, title: title, descrip : descrip},
   							success: function(returnData){
  									var nextlistnum = returnData;
   									newlist (nextlistnum, title, descrip);
   									document.getElementsByName("addtitlelist")[0].value="";
   									document.getElementsByName("newdescriplist")[0].value="";
   									if (document.getElementById("nolist")!=null) {
   										document.getElementById("nolist").style.display = "none";
   									}
   									
    						}   					
 						});	
   					}
 				});
		}
		
		function addTask(Listid){
				var list= Listid;
				var title= document.getElementsByName("addtitletask"+ Listid)[0].value;
				var descrip=document.getElementsByName("adddescriptask"+ Listid)[0].value;
				var action="add";
				$.ajax({
   					type: "POST",
   					url: "./js/ajax/tasks.php",
   					data: {action : action, listitem : list, titleitem : title, descripitem : descrip},
   					success: function(data){
   						//alert(data);//
   						document.getElementById("addtask_" + Listid).style.display = "none";
						document.getElementById("canceltask_" + Listid).style.display = "none";
						document.getElementById("addicon_" + Listid).style.display = "";
						var typeitem = "task";
						$.ajax({
   						type: "POST",
   							url: "./js/ajax/iteminfo.php",
   							data: {type : typeitem, list : list, title: title, descrip : descrip},
   							success: function(returnData){
  									var nexttasknum = returnData;
   									newtask (Listid, nexttasknum, title, descrip);
   									document.getElementsByName("addtitletask"+ Listid)[0].value="";
									document.getElementsByName("adddescriptask"+ Listid)[0].value="";
    						}   					
 						});	
   					}
 				});
		}
		
		function eraseTask(Listid, Taskid){
				var erase=confirm ('Etes-vous sûr de vouloir supprimer cette tâche?');
				if (erase==true){
					var list= Listid;
					var task= Taskid;
					var action="erase";
					$.ajax({
   						type: "POST",
   						url: "./js/ajax/tasks.php",
   						data: {action : action, listitem : list, taskitem : task},
   						success: function(data){
   							//alert(data);//
   							document.getElementById("task_" + Taskid).style.display = "none";
   							document.getElementById("task_" + Taskid).className = "eraseitem";
   							var tags=document.getElementsByTagName("*"), tnb=0;
   							for(var i=0;i<tags.length;i++)
   							{
      							if(tags[i].className === "taskitem " + Listid && tags[i].style.display!="none")
      							{
        			 				tnb = tnb+1;
      							}
 
   							}
   							if (tnb==0){
   								document.getElementById("showlist_" + Listid).style.display = "none";
   								document.getElementById("tasklist" + Listid).setAttribute("class", "listitem notaskitem" );
   							}
   						}
 					});
				}
		}
		
		function switchStateTask(Listid, Taskid, actionid){
			if (actionid== 2){
				var newState= "active";
				document.getElementById("taskstate_" + Taskid).className="taskinfo waititem";
				document.getElementById("changestate1_" + Taskid).style.display="";
				document.getElementById("changestate2_" + Taskid).style.display="none";
				document.getElementById("changestate3_" + Taskid).style.display="";
			}
			if (actionid== 1) {
				var newState= "done";
				document.getElementById("taskstate_" + Taskid).className="taskinfo doneitem";
				document.getElementById("changestate1_" + Taskid).style.display="none";
				document.getElementById("changestate2_" + Taskid).style.display="";
				document.getElementById("changestate3_" + Taskid).style.display="";

			}

			if (actionid== 3){
				var newState= "inactive";
				document.getElementById("taskstate_" + Taskid).className="taskinfo cancelitem";
				document.getElementById("changestate1_" + Taskid).style.display="";
				document.getElementById("changestate2_" + Taskid).style.display="";
				document.getElementById("changestate3_" + Taskid).style.display="none";

			}

			var list= Listid;
			var task= Taskid;
			
			var action="changeState";
			$.ajax({
   					type: "POST",
   					url: "./js/ajax/tasks.php",
   					data: {action : action, listitem : list, taskitem : task, modifState : newState},
   					success: function(data){
   						//alert(data);//
   						var nbtasks=document.getElementsByClassName("taskitem "+ list).length;
   						var tasks=document.getElementsByClassName("taskitem "+ list);
   						var activetask=0;
   						for (var i=0; i<tasks.length; i++) {
   							if (tasks[i].firstChild.className=='taskinfo doneitem') {
   								activetask++;
   							}
   						}
   						var percent= ((activetask*100)/nbtasks);
						$('#listprogress_'+ list).css('width', percent +'%');
   					}
 				});
		}	
		
		function submiteditform (formid) {
				document.getElementById(formid).submit();
		}
		
		function editTask(Listid, Taskid){
					var list= Listid;
					var task= Taskid;
					var title= document.getElementsByName("edittitletask"+ Taskid)[0].value;
					var descrip=document.getElementsByName("editdescriptask"+ Taskid)[0].value;
					var action="edit";
					$.ajax({
   						type: "POST",
   						url: "./js/ajax/tasks.php",
   						data: {action : action, listitem : list, taskitem : task, titleitem : title, descripitem : descrip},
   						success: function(data){
   							//alert(data);//
   						   	document.getElementById("edittitletask"+ Taskid).style.display = "none";
							document.getElementById("editdescriptask"+ Taskid).style.display = "none";
							document.getElementById("taskinfo"+ Taskid).style.display = "";
							document.getElementById("edittaskbutton"+ Taskid).src = "./img/edit.png";
							newtitle = document.getElementsByName("edittitletask"+ Taskid)[0].value;
							newdescrip = document.getElementsByName("editdescriptask"+ Taskid)[0].value;
							if (newtitle==""){
								newtitle = document.getElementById("tasktitle"+ Taskid).innerHTML;
							}
							if (newdescrip==""){
								newdescrip = document.getElementById("taskdescrip"+ Taskid).innerHTML;
							}
							document.getElementById("tasktitle"+ Taskid).innerHTML = newtitle;
							document.getElementById("taskdescrip"+ Taskid).innerHTML = newdescrip;
							document.getElementsByName("edittitletask"+ Taskid)[0].value = "";
							document.getElementsByName("editdescriptask"+ Taskid)[0].value = "";
   						}
 					});
		}
		
		function cancelAddTask(Listid){
			if (document.getElementById("addtask_" + Listid).style.display == ""){
				document.getElementById("addtask_" + Listid).style.display = "none";
				document.getElementById("canceltask_" + Listid).style.display = "none";
				document.getElementById("addicon_" + Listid).style.display = "";
				document.getElementsByName("addtitletask"+ Listid)[0].value = "";
				document.getElementsByName("adddescriptask"+ Listid)[0].value = "";

			}	
		}
		
		function cancelAddList(){
			if (document.getElementById("addlist_").style.display == ""){
				document.getElementById("addlist_").style.display = "none";
				document.getElementById("cancellist_").style.display = "none";
				document.getElementById("addicon_").style.display = "";
				document.getElementsByName("addtitlelist")[0].value = "";
				document.getElementsByName("newdescriplist")[0].value = "";

			}	
		}
		
		function editListButton(Listid){
			if (document.getElementById("edittitlelist"+ Listid).style.display == "none"){
				document.getElementById("edittitlelist"+ Listid).style.display = "";
				//document.getElementById("editdescriplist"+ Listid).style.display = "";
				//document.getElementById("editconfirmlist"+ Listid).style.display = "";
				document.getElementById("editlistbutton"+ Listid).src = "./img/cancel.png";
				document.getElementById("editlistbutton"+ Listid).title = "Annuler action";	
				document.getElementById("listinfo"+ Listid).style.display = "none";
			}
			else{
				document.getElementById("edittitlelist"+ Listid).style.display = "none";
				//document.getElementById("editdescriplist"+ Listid).style.display = "none";
				//document.getElementById("editconfirmlist"+ Listid).style.display = "none";
				document.getElementById("editlistbutton"+ Listid).src = "./img/edit.png";
				document.getElementById("editlistbutton"+ Listid).title = "Editer liste";	
				document.getElementById("listinfo"+ Listid).style.display = "";
				document.getElementsByName("edittitlelist"+ Listid)[0].value = "";
				document.getElementsByName("editdescriplist"+ Listid)[0].value = "";
			}	
		}
		
		function editList(Listid){
					var list= Listid;
					var title= document.getElementsByName("edittitlelist"+ Listid)[0].value;
					var descrip=document.getElementsByName("editdescriplist"+ Listid)[0].value;
					var action="edit";
					$.ajax({
   						type: "POST",
   						url: "./js/ajax/item.php",
   						data: {action : action, listitem : list, titleitem : title, descripitem : descrip},
   						success: function(data){
   							//alert(data);//
   						   	document.getElementById("edittitlelist"+ Listid).style.display = "none";
							document.getElementById("editdescriplist"+ Listid).style.display = "none";
							document.getElementById("editconfirmlist"+ Listid).style.display = "none";
							document.getElementById("listinfo"+ Listid).style.display = "";
							document.getElementById("editlistbutton"+ Listid).src = "./img/edit.png";
							newtitle = document.getElementsByName("edittitlelist"+ Listid)[0].value;
							newdescrip = document.getElementsByName("editdescriplist"+ Listid)[0].value;
							if (newtitle==""){
								newtitle = document.getElementById("listtitle"+ Listid).innerHTML;
							}
							if (newdescrip==""){
								newdescrip = document.getElementById("listdescrip"+ Listid).innerHTML;
							}
							document.getElementById("listtitle"+ Listid).innerHTML = newtitle;
							document.getElementById("listdescrip"+ Listid).innerHTML = newdescrip;
							document.getElementsByName("edittitlelist"+ Listid)[0].value = "";
							document.getElementsByName("editdescriplist"+ Listid)[0].value = "";
   						}
 					});
		}
		
		function supprItem(Listid){
			var erase=confirm ('Etes-vous sûr de vouloir supprimer cette liste?');
			if (erase==true){
				var id = Listid;
				var action="erase";
				$.ajax({
   					type: "POST",
   					url: "./js/ajax/item.php",
   					data: {action : action, Listnum : id},
   					success: function(data){
   						//alert(data);//
   						document.getElementById('tasklist' + id).style.display = "none";
   						 var tags=document.getElementsByTagName("*"),res=[], nb=0;
   						for(var i=0;i<tags.length;i++)
   						{
      						if(tags[i].className === "taskitem " + Listid)
      						{
        			 			res.push(tags[i]);
      						}
      						if(tags[i].className === "listitem" && tags[i].style.display!="none")
      						{
        			 			nb = nb+1;
      						}
   						}
     					for(i=0; i < res.length; i++)// parcours une boucle de la longueur de l'ensemble
    					{
             			   //s'il est caché l'affiche, sinon le cache
            				res[i].style.display = "none";
            				res[i].className = "eraseitem";
        				}
        				if (nb==0) {
        					if (document.getElementById("nolist")!=null) {
   								document.getElementById("nolist").style.display = "block";
   							}
   							else {
   								// nolistdiv element //
   								nolistdiv = document.createElement("div");
								nolistdiv.setAttribute("class", "listitem");
								nolistdiv.setAttribute("id", "nolist");
								page = document.getElementById("page");
								footer = document.getElementById("footer");
								page.insertBefore(nolistdiv, footer);
								
								// nolistshift element //
								nolistshift = document.createElement("div");
								nolistshift.setAttribute("class", "listoption");
								nolistdiv.appendChild(nolistshift);
								// //
								
								// customdiv element //
								customdiv = document.createElement("div");
								nolistdiv.appendChild(customdiv);

								// //
								
								// divtitle element //
								divtitle = document.createElement("div");
								divtitle.setAttribute("class", "listtitle");
								customdiv.appendChild(divtitle);
								nolisttext = document.createTextNode('Aucune liste de tâches n\'existe.');
								divtitle.appendChild(nolisttext);
								// //
								
								// //

   							}
        				}
    				}   					
 			});
			}
		}
		
		function testimg (img){
			if (img !=""){
				return true;				
			}
			else {
				return false;
			}
			
		}
		
		function savelinkimg (task, img, descrip){
				var savetaskid=task;
				var saveimg=img;
				var savedescrip=descrip;
				var action="add";
				$.ajax({
   					type: "POST",
   					url: "./js/ajax/linkimgs.php",
   					data: {action : action, tasknum : task, link : saveimg, txt : savedescrip},
   					success: function(returnData){
   						//alert(data);//
   						//alert (returnData);//
   						var nextimgnum = returnData;
   						var parts = saveimg.split("/");
        				var newsaveimg = parts[(parts.length)-1];
        				editableupimg(nextimgnum, newsaveimg, savedescrip, savetaskid);
   						//document.getElementById("imgid_"+ nextimgnum).src = "./img/taskimg/"+ newsaveimg ;
   						//document.getElementById("imgid_"+ nextimgnum).title = descrip;
    				}   					
 				});	
		}
		
		function addlinkimg (Taskid){
			var id=Taskid;
			//for (var i=1; i<6; i++){//
				var testlink = testimg(document.getElementById("task" + id + "addimglink").value);
				if (testlink == true){
					var confirmer = confirm ("Voulez-vous donnez une description à l'image ?");
					if (confirmer == true){
						var descrip =prompt("Description de l'image :");
					}
					else {
				 		var descrip ="";	
					}
					var img =document.getElementById("task" + id + "addimglink").value;
					savelinkimg (id, img, descrip);
					document.getElementById("task" + id + "addimglink").value="";
				}
			//}//
		}
		
		function addimgbutton (Taskid){
			if (document.getElementById("addimg"+ Taskid).style.display == "none"){
				document.getElementById("addimg"+ Taskid).style.display = "";
				document.getElementById("editimgbutton"+ Taskid).style.display = "none";
				document.getElementById("addimgbutton"+ Taskid).src = "./img/cancel.png";
				document.getElementById("addimgbutton"+ Taskid).title = "annuler action";
			}
			else{
				document.getElementById("addimg"+ Taskid).style.display = "none";
				document.getElementById("editimgbutton"+ Taskid).style.display = "";
				document.getElementById("addimgbutton"+ Taskid).src = "./img/add.png";	
				document.getElementById("addimgbutton"+ Taskid).title = "ajouter images";
			}
			
		}
		
		function editimgbutton (Taskid){
			if (document.getElementById("editimg"+ Taskid).style.display == "none"){
				document.getElementById("editimg"+ Taskid).style.display = "";
				document.getElementById("addimgbutton"+ Taskid).style.display = "none";
				document.getElementById("editimgbutton"+ Taskid).src = "./img/cancel.png";
				document.getElementById("editimgbutton"+ Taskid).title = "annuler action";
			}
			else{
				document.getElementById("editimg"+ Taskid).style.display = "none";
				document.getElementById("addimgbutton"+ Taskid).style.display = "";
				document.getElementById("editimgbutton"+ Taskid).src = "./img/edit.png";
				document.getElementById("editimgbutton"+ Taskid).title = "editer images";	
			}
			
		}
		
		function editlinkimg (task, imgnum, img, descrip){
				var edittaskid=task;
				var editimg=img;
				var editdescrip=descrip;
				var editimgnum=imgnum;
				var action="edit";
				$.ajax({
   					type: "POST",
   					url: "./js/ajax/linkimgs.php",
   					data: {action: action, numimg : imgnum, tasknum : task, link : editimg, txt : editdescrip},
   					success: function(data){
   						//alert(data);//
   						var parts = editimg.split("/");
        				var newimg = parts[(parts.length)-1];
        				//alert(newimg);
   						document.getElementById("imgid_"+ editimgnum).src = "./img/taskimg/"+ newimg ;
   						document.getElementById("imgid_"+ editimgnum).title = descrip;
    				}   					
 				});	
		}


		function editimg(Taskid, Imgid){
					var id=Taskid;
					var confirmer = confirm ("Voulez-vous donnez une description à l'image ?");
					if (confirmer == true){
						var descrip =prompt("Description de l'image :");
					}
					else {
				 		var descrip ="";	
					}
					if (document.getElementById("task" + Taskid + "editimglink" + Imgid).value !=""){
						var img =document.getElementById("task" + Taskid + "editimglink" + Imgid).value;
						editlinkimg (id, Imgid, img, descrip);
					}

		}
		
		function GetImgid(Imageid){
					if (document.getElementById("img_"+ Imageid).className == "newitem"){
						document.getElementById("img_"+ Imageid).className = "";
						document.getElementById("imgbutton_"+ Imageid).className = "option";
					}
					if (document.getElementById("img_"+ Imageid).style.display == "none"){
						if (typeof in_action === 'undefined'){
							document.getElementById("img_"+ Imageid).style.display = "block";
							document.getElementById("imgbutton_"+ Imageid).src = "./img/cancel.png";
							document.getElementById("imgbutton_"+ Imageid).title = "Annuler action";
							document.getElementById("imgbutton_"+ Imageid).alt = "annuler action";
							editimgid=Imageid;
							in_action=true;
						}
						else {
							if (document.getElementById("img_next").style.display != "none"){
								alert ('Vous ne pouvez pas modifier des images pendant que vous en ajouter une.');
							}
							else {
								alert ('Vous ne pouvez pas modifier plusieurs images en même temps');
							}
						}
					}
					else{
						document.getElementById("img_"+ Imageid).style.display = "none";
						document.getElementById("imgbutton_"+ Imageid).src = "./img/imgs.png";
						document.getElementById("imgbutton_"+ Imageid).title = "Changer image";
						document.getElementById("imgbutton_"+ Imageid).alt = "changer image";
						delete editimgid;
						delete in_action;
					}
		}
		
		function GetTaskid(Taskid){
			edittaskid=Taskid;
		}
		
		function Opennewimg(){
					if (document.getElementById("img_next").style.display == "none"){
						if (typeof in_action === 'undefined'){
							document.getElementById("img_next").style.display = "table-cell";
							document.getElementById("img_next_shift").style.display = "table-cell";
							document.getElementById("img_next_div").style.display = "block";
							document.getElementById("imgnextbutton").src = "./img/cancel.png";
							document.getElementById("imgnextbutton").title = "Annuler action";
							document.getElementById("imgnextbutton").alt = "annuler action";
							in_action=true;
						}
						else {
							alert ('Vous ne pouvez pas ajouter des images pendant que vous en modifier une.');
						}
					}
					else{
						document.getElementById("img_next").style.display = "none";
						document.getElementById("img_next_shift").style.display = "none";
						document.getElementById("img_next_div").style.display = "none";
						document.getElementById("imgnextbutton").src = "./img/add.png";
						document.getElementById("imgnextbutton").title = "Ajouter image";
						document.getElementById("imgnextbutton").alt = "ajouter image";
						delete in_action;
					}
		}
		
		function supprimg(numimg){
			var erase=confirm ('Etes-vous sûr de vouloir supprimer cette image?');
			if (erase==true){
				var action="erase";
				$.ajax({
   					type: "POST",
   					url: "./js/ajax/imgs.php",
   					data: {imgaction : action, imageid : numimg, task : edittaskid},
   					success: function(data){
   						//alert(data);//
   						document.getElementById("img_"+ numimg).style.display = "none";
   						document.getElementById("imgbutton_"+ numimg).style.display = "none";
   						in_action=false;
    				}   					
 				});
			}

		}
		
		function editableupimg(numimg, imglink, imgdescrip, numtask){
			// upimgbutton element //
			editupimgbutton = document.createElement("img");
			editupimgbutton.setAttribute("id", "imgbutton_" + numimg);
			editupimgbutton.setAttribute("class", "option newitem");
			editupimgbutton.setAttribute("onclick", "GetImgid(" + numimg + ")");
			editupimgbutton.setAttribute("src", "./img/imgs.png");
			editupimgbutton.setAttribute("alt", "changer image");
			editupimgbutton.setAttribute("title", "Changer image");

			edittaskbuttons = document.getElementById("editimg" + numtask);
			imgmenudiv = document.getElementById("imgmenu");
			addimgbutton = document.getElementById("imgnextbutton");
			imgmenudiv.insertBefore(editupimgbutton, addimgbutton);
			// //
			
			// showupimg element //
			showupimg = document.createElement("div");
			showupimg.setAttribute("id", "img_" + numimg);
			showupimg.setAttribute("style", "display:none;border-bottom: 1px solid blue;");
			showupimg.setAttribute("class", "newitem");
			page = document.getElementsByClassName('page')[0];
			imgnextdiv= document.getElementById("img_next_div");
			page.insertBefore(showupimg, imgnextdiv);
			
			// imgdivshift element //
			imgdivshift= document.createElement("div");
			imgdivshift.setAttribute('id', 'img_shift_' + numimg);
			imgdivshift.setAttribute('style', 'border-right: 1px solid red; display: table-cell; width: 214px; min-width: 92px;');
			imgdivshift.setAttribute('class', ' ');
			showupimg.appendChild(imgdivshift);
			
			// imgdivpreview element //
			imgdivpreview = document.createElement("div");
			imgdivpreview.setAttribute('style', 'display: table-cell; padding: 5px;width: 85%;');
			showupimg.appendChild(imgdivpreview);
			
			// upimgsuppr element //
			upimgsuppr = document.createElement("img");
			upimgsuppr.setAttribute("id", "imgbutton_" + numimg);
			upimgsuppr.setAttribute("class", "option eraseoption");
			upimgsuppr.setAttribute("onclick", "supprimg(" + numimg + ")");
			upimgsuppr.setAttribute("src", "./img/suppr.png");
			upimgsuppr.setAttribute("alt", "supprimer image");
			upimgsuppr.setAttribute("title", "Supprimer image");
			imgdivpreview.appendChild(upimgsuppr);
			
			// imgdiv element // 
			imgdiv = document.createElement("div");
			imgdiv.setAttribute('id', 'imgpreview');
			imgdiv.setAttribute('class', 'imgpreview');
			imgdivpreview.appendChild(imgdiv);
			
			upimg = document.createElement("img");
			upimg.setAttribute("id", "imgid_" + numimg);
			upimg.setAttribute("title", imgdescrip);
			upimg.setAttribute("alt", imglink);
			upimg.setAttribute("src", "./img/taskimg/" + imglink);
			var testimg= upimg;
   			if (testimg.width > 500 || testimg.height > 500) {
   				var cent="false";
   				var newheight = 0;
   				var newwidth = 0;

   				if (testimg.width > 500) {
   					newwidth = 100;
   					cent= "true";
   				}
   				else {
   					newwidth=testimg.width;
   				}
   				if (testimg.height > 500) {
   					newheight = 100;
   					cent= "true";
   				}
   				else {
   					newheight = testimg.height;
   				}
   				testimg.height =  newheight;	
   				testimg.width =  newwidth;
   				if (cent== "true") {
   					upimg.setAttribute("style", 'width:' + newwidth + '%;height:' + newheight + '%;');
   					//upimg.setAttribute("width", newwidth + '%');
   					//upimg.setAttribute("height", newheight + '%');
   				}
   				else {
   					upimg.setAttribute("style", 'width:' + newwidth + 'px;height:' + newheight + 'px;');
   					//upimg.setAttribute("width", newwidth + 'px');
   					//upimg.setAttribute("height", newheight + 'px');
   				}  				
   			}
			imgdiv.appendChild(upimg);
			// imgdiv element fin //
			
			// editupimgdiv element //
			editupimgdiv = document.createElement("div");
			editupimgdiv.setAttribute("class", "editimg");
			imgdivpreview.appendChild(editupimgdiv);
			
			uploadtext = document.createTextNode("Uploader la nouvelle image :");
			editupimgdiv.appendChild(uploadtext);
			
			newupimg = document.createElement("form");
			newupimg.setAttribute("id", "taskeditimgfile");
			newupimg.setAttribute("class", "dropzone dz-clickable");
			newupimg.setAttribute("action", "./upload.php");
			editupimgdiv.appendChild(newupimg);
			
			dropzonediv= document.createElement("div");
			dropzonediv.setAttribute('class', 'dz-default dz-message');
			newupimg.appendChild(dropzonediv);
			dropzonemsg= document.createElement("span");
			dropzonemsg.innerHTML='Drop files here to upload';
			dropzonediv.appendChild(dropzonemsg);
			
			space1 = document.createElement("br");
			editupimgdiv.appendChild(space1);
			
			linktext = document.createTextNode("Changer image via Url :");
			editupimgdiv.appendChild(linktext);
			
			newupimglink = document.createElement("input");
			newupimglink.setAttribute("id", "task" + numtask + "editimglink" + numimg);
			newupimglink.setAttribute("style", "min-width:100%");
			newupimglink.setAttribute("type", "url");
			newupimglink.setAttribute("size", "55");
			newupimglink.setAttribute("patern", "https?://.+");
			editupimgdiv.appendChild(newupimglink);
			
			space2 = document.createElement("br");
			editupimgdiv.appendChild(space2);
			
			newuplink = document.createElement("input");
			newuplink.setAttribute("type", "button");
			newuplink.setAttribute("onclick", "editimg(" + numtask + " , " + numimg + ")");
			newuplink.setAttribute("value", "Modifier Image");
			editupimgdiv.appendChild(newuplink);
			
			space3 = document.createElement("br");
			editupimgdiv.appendChild(space3);
			// editupimgdiv element fin //
			// showupimg element fin //
			
			
		}
		
		function newtask (numlist, numtask, tasktitle, taskdescrip){
			// newlistoption element //
			if (document.getElementById("showlist_" + numlist) == null) {
				optiondiv = document.getElementById("listoption_" + numlist);
				newlistoption = document.createElement("img");
				newlistoption.setAttribute("class", "option");
				newlistoption.setAttribute("id", "showlist_" + numlist);
				newlistoption.setAttribute("onclick", "showTask(" + numlist + ")");
				newlistoption.setAttribute("title", "Afficher les tâches");
				newlistoption.setAttribute("alt", "Afficher les tâches");
				newlistoption.setAttribute("src", "./img/tasks.png");
				optiondiv.appendChild(newlistoption);
				
				// delete variable //
				delete newlistoption;
			}
			else {
				if (document.getElementById("showlist_" + numlist).style.display == "none"){
					document.getElementById("showlist_" + numlist).style.display = "";				
				}
			}
			document.getElementById("tasklist" + numlist).setAttribute("class", "listitem" );
 				
			// newtasked element //
			newtasked = document.createElement("div");
			newtasked.setAttribute("id", "task_" + numtask);
			newtasked.setAttribute("class", "taskitem " + numlist);
			if (document.getElementById("showlist_" + numlist).title == "Masquer les tâches"){
				newtasked.setAttribute("style", "display:block");
			}
			else {
				newtasked.setAttribute("style", "display:none");
			}
			page = document.getElementById("page");
			nextlist = document.getElementById("endlist" + numlist);
			//page.appendChild(newtask);
			page.insertBefore(newtasked, nextlist);
						
			//newtaskstate element //
			newtaskstate = document.createElement("div");
			newtaskstate.setAttribute("id", "taskstate_" + numtask);
			newtaskstate.setAttribute("class", "taskinfo waititem");
			newtasked.appendChild(newtaskstate);
			
			
			// newtaskoption element //
			newtaskoption = document.createElement("div");
			newtaskoption.setAttribute("class", "taskoption");
			newtaskstate.appendChild(newtaskoption);
						
			// newtaskoptionimg1 element //
			newtaskoptionimg = document.createElement("img");
			newtaskoptionimg.setAttribute("class", "option");
			newtaskoptionimg.setAttribute("onclick", "eraseTask(" + numlist + ", " + numtask +")");
			newtaskoptionimg.setAttribute("title", "supprimer tâche");
			newtaskoptionimg.setAttribute("alt", "Supprimer tâche");
			newtaskoptionimg.setAttribute("src", "./img/suppr.png");
			newtaskoption.appendChild(newtaskoptionimg);
			//  //
						
			// newtaskoptionimg2 element //
			newtaskoptionimg2 = document.createElement("form");
			newtaskoptionimg2.setAttribute("class", "editbuttonform");
			newtaskoptionimg2.setAttribute("action", "edittask.php");
			newtaskoptionimg2.setAttribute("method", "POST");
			newtaskoption.appendChild(newtaskoptionimg2);	
			
			// listidvalue element //
			listidvalue = document.createElement("input");
			listidvalue.setAttribute("type", "text");
			listidvalue.setAttribute("hidden", " ");
			listidvalue.setAttribute("value", numlist);
			listidvalue.setAttribute("name", "Listid");
			newtaskoptionimg2.appendChild(listidvalue);
			//  // 
			
			// taskidvalue element //
			taskidvalue = document.createElement("input");
			taskidvalue.setAttribute("type", "text");
			taskidvalue.setAttribute("hidden", " ");
			taskidvalue.setAttribute("value", numtask);
			taskidvalue.setAttribute("name", "Taskid");
			newtaskoptionimg2.appendChild(taskidvalue);
			//  // 
			
			// edittaskbutton element //
			edittaskbutton = document.createElement("input");
			edittaskbutton.setAttribute("class", "editbuttonform");
			edittaskbutton.setAttribute("type", "image");
			edittaskbutton.setAttribute("border", "0");
			edittaskbutton.setAttribute("align", "middle");
			edittaskbutton.setAttribute("title", "editer tâche");
			edittaskbutton.setAttribute("alt", "Editer tâche");
			edittaskbutton.setAttribute("value", "submit");
			edittaskbutton.setAttribute("src", "./img/edit.png");
			newtaskoptionimg2.appendChild(edittaskbutton);
			// // 
			//  //
			//  //
			
			// finishtask element //
			finishtask = document.createElement("img");
			finishtask.setAttribute("class", "minioption");
			finishtask.setAttribute("id", "changestate1_" + numtask);
			finishtask.setAttribute("style", "display: ");
			finishtask.setAttribute("onclick", "switchStateTask(" + numlist + ", " + numtask +", 1)");
			finishtask.setAttribute("title", "terminer tâche");
			finishtask.setAttribute("alt", "Terminer tâche");
			finishtask.setAttribute("src", "./img/activated.png");
			newtaskoption.appendChild(finishtask);
			// //
			
			// activatetask element //
			activatetask = document.createElement("img");
			activatetask.setAttribute("class", "minioption");
			activatetask.setAttribute("id", "changestate2_" + numtask);
			activatetask.setAttribute("style", "display:none");
			activatetask.setAttribute("onclick", "switchStateTask(" + numlist + ", " + numtask +", 2)");
			activatetask.setAttribute("title", "activer tâche");
			activatetask.setAttribute("alt", "Activer tâche");
			activatetask.setAttribute("src", "./img/progress.png");
			newtaskoption.appendChild(activatetask);
			// //
			
			// canceltask element //
			canceltask = document.createElement("img");
			canceltask.setAttribute("class", "minioption");
			canceltask.setAttribute("id", "changestate3_" + numtask);
			canceltask.setAttribute("style", "display: ");
			canceltask.setAttribute("onclick", "switchStateTask(" + numlist + ", " + numtask +", 3)");
			canceltask.setAttribute("title", "suspendre tâche");
			canceltask.setAttribute("alt", "Suspendre tâche");
			canceltask.setAttribute("src", "./img/desactivated.png");
			newtaskoption.appendChild(canceltask);
			// //
			
			// taskinfo element //
			taskinfo = document.createElement("div");
			taskinfo.setAttribute("id", "taskinfo" + numtask);
			newtaskstate.appendChild(taskinfo);
			
			// tasktitle element //
			tasktitl = document.createElement("div");
			tasktitl.setAttribute("id", "tasktitle" + numtask);
			tasktitl.setAttribute("class", "tasktitle");
			tasktitletext = document.createTextNode(tasktitle);
			tasktitl.appendChild(tasktitletext);
			taskinfo.appendChild(tasktitl);
			// //
			
			// taskdesc element //
			taskdesc = document.createElement("div");
			taskdesc.setAttribute("id", "taskdescrip" + numtask);
			taskdesc.setAttribute("class", "taskdescrip");
			taskdesctext = document.createTextNode(taskdescrip);
			taskdesc.appendChild(taskdesctext);
			taskinfo.appendChild(taskdesc);
			// //
			
			// //
			// //	
			
			// delete variables //
			delete newtasked;
			delete newtaskstate;
			delete newtaskoption;
			delete newtaskoptionimg;
			delete newtaskoptionimg2;
			delete listidvalue;
			delete taskidvalue;
			delete edittaskbutton;
			delete taskinfo;
			delete tasktitl;
			delete tasktitletext;
			delete taskdesc;
			delete taskdesctext;
			// //
		}
		
		function newlist (numlist, titlelist, descriplist){
			// newlisted element //
			newlisted = document.createElement("div");
			newlisted.setAttribute ("id", "tasklist" + numlist);
			newlisted.setAttribute ("class", "listitem notaskitem");
			page = document.getElementById("page");
			//page.appendChild(newlist);
			footer = document.getElementById("footer");
			page.insertBefore(newlisted, footer);
			
			// canceltask element //
			canceltask = document.createElement("div");
			canceltask.setAttribute("id", "canceltask_" + numlist);
			canceltask.setAttribute("class", "taskoption");
			canceltask.setAttribute("style", "display:none");
			//page.appendChild(canceltask);
			page.insertBefore(canceltask, footer);
			// cancelimg element //
			cancelimg = document.createElement("img");
			cancelimg.setAttribute("class", "option");
			cancelimg.setAttribute("onclick", "cancelAddTask(" + numlist + ")");
			cancelimg.setAttribute("alt", "Annuler action");
			cancelimg.setAttribute("title", "annuler action");
			cancelimg.setAttribute("src", "./img/cancel.png");
			canceltask.appendChild(cancelimg);
			// //			
			// //
			
			// addtask element //
			addtask = document.createElement("div");
			addtask.setAttribute("id", "addtask_" + numlist);
			addtask.setAttribute("class", "taskitem wait");
			addtask.setAttribute("style", "display:none");
			//page.appendChild(addtask);
			page.insertBefore(addtask, footer);
			// addtasktitle element //
			addtasktitle = document.createElement("div");
			addtasktitle.setAttribute("class", "tasktitle wait");
			addtask.appendChild(addtasktitle);
			// tasktitle element //
			tasktitle=document.createElement("input");
			tasktitle.setAttribute("type", "text");
			tasktitle.setAttribute("placeholder", "Nom de la tâche");
			tasktitle.setAttribute("name", "addtitletask" + numlist);
			addtasktitle.appendChild(tasktitle);
			// //
			// //	
			
			//addtaskdescrip element //
			addtaskdescrip = document.createElement("div");
			addtaskdescrip.setAttribute("class", "taskdescrip");
			addtask.appendChild(addtaskdescrip);
			//taskdescrip element //
			taskdescrip = document.createElement("input");
			taskdescrip.setAttribute("type", "text");
			taskdescrip.setAttribute("placeholder", "Description de la tâche");
			taskdescrip.setAttribute("name", "adddescriptask" + numlist);
			addtaskdescrip.appendChild(taskdescrip);
			// buttondiv //
			buttondiv = document.createElement("div");
			addtaskdescrip.appendChild(buttondiv);
			// addbutton element //
			addbutton = document.createElement("input");
			addbutton.setAttribute("type", "button");
			addbutton.setAttribute("onclick", "addTask(" + numlist + ")");
			addbutton.setAttribute("value", "Ajouter tâche");
			buttondiv.appendChild(addbutton);
			// //
			// //
			// //		
			// //
			
			// newlistoption element //
			newlistoption = document.createElement("div");
			newlistoption.setAttribute("class", "listoption");
			newlistoption.setAttribute("id", "listoption_" + numlist + "");
			newlisted.appendChild(newlistoption);
			
			// newlistoptionimg1 element //
			newlistoptionimg1 = document.createElement("img");
			newlistoptionimg1.setAttribute("class", "option");
			newlistoptionimg1.setAttribute("onclick", "supprItem(" + numlist + ")");
			newlistoptionimg1.setAttribute("title", "supprimer liste");
			newlistoptionimg1.setAttribute("alt", "Supprimer liste");
			newlistoptionimg1.setAttribute("src", "./img/suppr.png");
			newlistoption.appendChild(newlistoptionimg1);
			// //
			// newlistoptionimg2 element //
			newlistoptionimg2 = document.createElement("img");
			newlistoptionimg2.setAttribute("id", "editlistbutton" + numlist);
			newlistoptionimg2.setAttribute("class", "option");
			newlistoptionimg2.setAttribute("onclick", "editListButton(" + numlist + ")");
			newlistoptionimg2.setAttribute("title", "editer liste");
			newlistoptionimg2.setAttribute("alt", "Editer liste");
			newlistoptionimg2.setAttribute("src", "./img/edit.png");
			newlistoption.appendChild(newlistoptionimg2);
			// //
			// newlistoptionimg3 element //
			newlistoptionimg3 = document.createElement("img");
			newlistoptionimg3.setAttribute("id", "addicon_" + numlist);
			newlistoptionimg3.setAttribute("class", "option");
			newlistoptionimg3.setAttribute("onclick", "addTaskButton(" + numlist + ")");
			newlistoptionimg3.setAttribute("title", "ajouter liste");
			newlistoptionimg3.setAttribute("alt", "Ajouter liste");
			newlistoptionimg3.setAttribute("src", "./img/add.png");
			newlistoption.appendChild(newlistoptionimg3);
			// //
			// newlistoptionimg4 element //
			//newlistoptionimg4 = document.createElement("img");
			//newlistoptionimg4.setAttribute("id", "showlist_" + numlist);
			//newlistoptionimg4.setAttribute("class", "option");
			//newlistoptionimg4.setAttribute("onclick", "showTask(" + numlist + ")");
			//newlistoptionimg4.setAttribute("title", "afficher les tâches");
			//newlistoptionimg4.setAttribute("alt", "Afficher les tâches");
			//newlistoptionimg4.setAttribute("src", "./img/tasks.png");
			//newlistoption.appendChild(newlistoptionimg4);
			// //
			//  //

			// newlistinfo element //
			newlistinfo = document.createElement("div");
			newlistinfo.setAttribute("id", "listinfo" + numlist);
			newlisted.appendChild(newlistinfo);
			
			// listtitle element //
			listtitle = document.createElement("div");
			listtitle.setAttribute("id", "listtitle" + numlist);
			listtitle.setAttribute("class", "listtitle");
			listtitletext = document.createTextNode(titlelist);
			listtitle.appendChild(listtitletext);
			newlistinfo.appendChild(listtitle);
			// //
			
			// listdescrip element //
			listdescrip = document.createElement("div");
			listdescrip.setAttribute("id", "listdescrip" + numlist);
			listdescrip.setAttribute("class", "listdescrip");
			listdescriptext = document.createTextNode(descriplist);
			listdescrip.appendChild(listdescriptext);
			newlistinfo.appendChild(listdescrip);
			// //
			// //
			
			// editlisttitle element //
			editlisttitle = document.createElement("div");
			editlisttitle.setAttribute("id", "edittitlelist" + numlist);
			editlisttitle.setAttribute("class", "listtitle");
			editlisttitle.setAttribute("style", "display:none");
			newlisted.appendChild(editlisttitle);
			// new title element //
			newtitle = document.createElement("input");
			newtitle.setAttribute("type", "text");
			newtitle.setAttribute("placeholder", "Nouveau nom");
			newtitle.setAttribute("name", "edittitlelist" + numlist);
			editlisttitle.appendChild(newtitle);
			// //
			// //
			
			// editlistdesc element //
			editlistdesc = document.createElement("div");
			editlistdesc.setAttribute("id", "editdescriplist" + numlist);
			editlistdesc.setAttribute("class", "listdescrip");
			editlistdesc.setAttribute("style", "display:none");
			newlisted.appendChild(editlistdesc);
			// new desc element //
			newdesc = document.createElement("input");
			newdesc.setAttribute("type", "text");
			newdesc.setAttribute("placeholder", "Nouvelle description");
			newdesc.setAttribute("name", "editdescriplist" + numlist);
			editlistdesc.appendChild(newdesc);
			// //
			// //
			
			// editconfirm element //
			editconfirm = document.createElement("div");
			editconfirm.setAttribute("id", "editconfirmlist" + numlist);
			editconfirm.setAttribute("class", "confirmeditlist");
			editconfirm.setAttribute("style", "display:none");
			newlisted.appendChild(editconfirm);
			// confirmbutton element //
			confirmbutton = document.createElement("input");
			confirmbutton.setAttribute("type", "button");
			confirmbutton.setAttribute("onclick", "editList(" + numlist + ")");
			confirmbutton.setAttribute("value", "Editer liste");
			editconfirm.appendChild(confirmbutton);
			// //
			// //
			
			// endlist element //
			endlist = document.createElement("div");
			endlist.setAttribute("id", "endlist" + numlist);
			endlist.setAttribute("style", "display:none");
			end = document.createTextNode(endlist);
			//page.appendChild(endlist);
			page.insertBefore(endlist, footer);
			// //
			
			//  //
			
			// delete variables //
			delete newlisted;
			delete canceltask;
			delete cancelimg;
			delete addtask;
			delete addtasktitle;
			delete tasktitle;
			delete addtaskdescrip;
			delete taskdescrip;
			delete buttondiv;
			delete addbutton;
			delete newlistoption;
			delete newlistoptionimg1;
			delete newlistoptionimg2;
			delete newlistoptionimg3;
			delete newlistinfo;
			delete listtitle;
			delete listdescrip;
			delete editlisttitle;
			delete newtitle;
			delete editlistdesc;
			delete newdesc;
			delete editconfirm;
			delete confirmbutton;
			delete endlist;
			//  //	
		}
		
		function saveProfil(){
			if (document.getElementById("errormsg") != null){
				document.getElementById("errormsg").remove();	
			}
			if (document.getElementById("msg") != null){
				document.getElementById("msg").remove();	
			}
			var pseudo = document.getElementById("pseudo").value;
			var email = document.getElementById("e-mail").value;
			var password = document.getElementById("password").value;
			var confirmpwd = document.getElementById("confirmpwd").value;
			$.ajax({
   					type: "POST",
   					url: "./js/ajax/profil.php",
   					data: {Pseudo : pseudo , Email : email , Pwd : password, Cpwd : confirmpwd},
   					success: function(returnData){
   						var changeDone = returnData;
   						// errordiv element //
   						errordiv = document.createElement("div");
   						errordiv.setAttribute("id", "errormsg");
						errordiv.setAttribute("class", "errormsg");
						page = document.getElementById("page");
						form = document.getElementById("form");
						page.insertBefore(errordiv, form);
   						// msgdiv element //
   						msgdiv = document.createElement("div");
   						msgdiv.setAttribute("id", "msg");
						msgdiv.setAttribute("class", "succesmsg");
						page = document.getElementById("page");
						form = document.getElementById("form");
						page.insertBefore(msgdiv, form);
   						if (changeDone[0] == 1){
   							msgp1 = document.createElement("p");
							msgdiv.appendChild(msgp1);
							msgtxt1 = document.createTextNode("Le Pseudo a été modifié avec succès");
							msgp1.appendChild(msgtxt1);
							newpseudo = document.getElementById("pseudo").value;
							document.getElementById("username").innerHTML = newpseudo;
   						}
   						if (changeDone[0] == 2){
   							errorp1 = document.createElement("p");
							errordiv.appendChild(errorp1);
							errortxt1 = document.createTextNode("Le Pseudo est déjà utilisé par un autre utilisateur");
							errorp1.appendChild(errortxt1);
   						}
   						if (changeDone[1]== 1){
   							msgp2 = document.createElement("p");
							msgdiv.appendChild(msgp2);
							msgtxt2 = document.createTextNode("Le Mot de passe a été modifié avec succès");
							msgp2.appendChild(msgtxt2);
   						}
   						if (changeDone[1] == 2){
   							errorp2 = document.createElement("p");
							errordiv.appendChild(errorp2);
							errortxt2 = document.createTextNode("Le Mot de passe et la confirmation sont différent");
							errorp2.appendChild(errortxt2);
   						}
   						if (changeDone[2]== 1){
   							msgp3 = document.createElement("p");
							msgdiv.appendChild(msgp3);
							msgtxt3 = document.createTextNode("L' E-mail a été modifié avec succès");
							msgp3.appendChild(msgtxt3);
   						}
   						if (changeDone[2] == 2){
   							errorp3 = document.createElement("p");
							errordiv.appendChild(errorp3);
							errortxt3 = document.createTextNode("L'email est déjà utilisé par un autre utilisateur");
							errorp3.appendChild(errortxt3);
   						}
   						setTimeout(function() {
 							 $("#msg").remove();
						}, 5000);

   					}
 				});		
		}
		
		function showform1(){
			if (document.getElementById("form1").style.display == "none"){
				document.getElementById("form1").style.display = "block";
				document.getElementById("form2").style.display = "none";
				document.getElementById("login").src = "./img/cancel.png";
				document.getElementById("login").title = "annuler action";
				document.getElementById("register").src = "./img/register.png";
				document.getElementById("register").title = "register";
				
			}
			else {
				document.getElementById("form1").style.display = "none";
				document.getElementById("login").src = "./img/login.png";
				document.getElementById("login").title = "connexion";
				document.getElementById("lostdiv").style.display = "none";
				document.getElementById("key").src = "./img/forget.png";
				document.getElementById("key").title = "mot de passe oublié?";
			}
		}
		
		function showform2(){
			if (document.getElementById("form2").style.display == "none"){
				document.getElementById("form1").style.display = "none";
				document.getElementById("form2").style.display = "block";	
				document.getElementById("register").src = "./img/cancel.png";
				document.getElementById("register").title = "annuler action";
				document.getElementById("login").src = "./img/login.png";
				document.getElementById("login").title = "connexion";
				document.getElementById("lostdiv").style.display = "none";
				document.getElementById("key").src = "./img/forget.png";
				document.getElementById("key").title = "mot de passe oublié?";
			}
			else {
				document.getElementById("form2").style.display = "none";
				document.getElementById("register").src = "./img/register.png";
				document.getElementById("register").title = "register";
			}
		}
		
		function showkey(){
			if (document.getElementById("lostdiv").style.display == "none"){
				document.getElementById("lostdiv").style.display = "block";	
				document.getElementById("key").src = "./img/cancel.png";
				document.getElementById("key").title = "annuler action";
			}
			else {
				document.getElementById("lostdiv").style.display = "none";
				document.getElementById("key").src = "./img/forget.png";
				document.getElementById("key").title = "mot de passe oublié?";
			}
		}
		
		function userState(){
			if (document.getElementById("errormsg") != null){
				document.getElementById("errormsg").remove();	
			}
			if (document.getElementById("msg") != null){
				document.getElementById("msg").remove();	
			}
			if (document.getElementById("stateaccount").value=="Supprimer compte") {
				var newState="inactive";
			}
			else {
				var newState="active";
			}
			$.ajax({
   					type: "POST",
   					url: "./js/ajax/profil.php",
   					data: {ChangeState : newState},
   					success: function(returnData){
   						var changeDone = returnData;
   						// errordivc element //
   						errordivc = document.createElement("div");
   						errordivc.setAttribute("id", "errormsg");
						errordivc.setAttribute("class", "errormsg");
						page = document.getElementById("page");
						form = document.getElementById("form");
						page.insertBefore(errordivc, form);
   						// msgdivc element //
   						msgdivc = document.createElement("div");
   						msgdivc.setAttribute("id", "msg");
						msgdivc.setAttribute("class", "succesmsg");
						page = document.getElementById("page");
						form = document.getElementById("form");
						page.insertBefore(msgdivc, form);
						if (changeDone[3] == 1){
   							msgpc = document.createElement("p");
							if (newState=="inactive") {
								msgdivc.appendChild(msgpc);
								msgtxtc = document.createTextNode("La demande de suppression du compte a bien été pris en compte");
								document.getElementById("stateaccount").value="Réactiver compte";
								document.getElementById("stateaccountinfo").innerHTML="La réactivation du compte vous permettra de modifier vos listes/tâches.";
								if (changeDone[4] != "0"){
									countdowndiv = document.createElement("div");
   									countdowndiv.setAttribute("id", "countdown");
									countdowndiv.setAttribute("class", "errormsg");
									page = document.getElementById("page");
									form = document.getElementById("form");
									page.insertBefore(countdowndiv, form);
									var num = 4; // index de changeDone à partir de laquelle la date commence //
									var timestring= ""; // la date de supression de compte en chaine de caractère //
									while (changeDone[num]!=null){ // on boucle jusqu'à avoir toute la date dans la variable timestring //
										timestring = timestring + changeDone[num];
										num++;
										
									}
									timeRemain(timestring);
								}
							}
							else {
								errordivc.appendChild(msgpc);
								msgtxtc = document.createTextNode("La demande de suppression du compte a été annulé");
								document.getElementById("stateaccount").value="Supprimer compte";
								document.getElementById("stateaccountinfo").innerHTML="Le compte sera désactivé en attendant sa suppression, vous ne pourrez plus modifier vos listes/tâches.";
								if (interval01!=null || document.getElementById("countdown")!=null) {
									clearInterval(interval01);
									document.getElementById("countdown").remove();
								}
							}
							msgpc.appendChild(msgtxtc);
							setTimeout(function() {
 							 $("#msg").remove();
							}, 5000);
   						}

   					}

			});
		}
		
		function timeRemain(timeleft=""){
			if (document.getElementById("countdown")!=null && (timeleft!="" || timeleft!="Jan 01, 1970 01:00:00")) {
				// set the date we're counting down to
				//alert (timeleft);
				var target_date = new Date(timeleft).getTime();

				// variables for time units
				var days, hours, minutes, seconds;

				// get tag element
				var countdown = document.getElementById("countdown");

				// update the tag with id "countdown" every 1 second
				 interval01 = setInterval(function () {

    				// find the amount of "seconds" between now and target
    				var current_date = new Date().getTime();
    				var seconds_left = (target_date - current_date) / 1000;

    				// do some time calculations
    				days = parseInt(seconds_left / 86400);
    				seconds_left = seconds_left % 86400;

    				hours = parseInt(seconds_left / 3600);
    				seconds_left = seconds_left % 3600;

    				minutes = parseInt(seconds_left / 60);
    				seconds = parseInt(seconds_left % 60);

    				// format countdown string + set tag value
    				countdown.innerHTML = "Ce compte sera supprimé dans : " + days + " jours, " + hours + " heures, "
    			+ minutes + " minutes et " + seconds + " secondes.";  
    				
    				if (days<=0 && hours<=0 && minutes<=0 && seconds<=0){
    					clearInterval(interval01);
    					countdown.class="succesmsg"
    					countdown.innerHTML = "Ce compte est maintenant supprimé.";
    					document.getElementById("countdown").remove();
    				}

				}, 1000);
			}	
		}