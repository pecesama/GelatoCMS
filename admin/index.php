<?php
if(!defined('entry'))define('entry', true);
/* ===========================

  gelato CMS - A PHP based tumblelog CMS
  development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under the GPL 2.0
  Copyright (C) 2007 by Pedro Santana <pecesama at gmail dot com>

  =========================== */
?>
<?php
require('../entry.php');

global $user, $conf, $tumble;
#$template = new plantillas("admin");
$theme = new themes;

$isEdition = (isset($_GET["edit"])) ? true : false;
$postId = ($isEdition) ? $_GET["edit"] : NULL;

$theme->set('isEdition',$isEdition);
$theme->set('postId',$postId);
$theme->set('pagination','');

if (get_magic_quotes_gpc()) {
	foreach($_GET as $k=>$get){
		$_GET[$k]=stripslashes($get);
	}
}

if ($user->isAuthenticated()) {
	if (isset($_GET["delete"])) {
		$tumble->deletePost($_GET['delete']);
		header("Location: index.php?deleted=true");
		die();
	}

	if(isset($_POST["btnAdd"])){
		unset($_POST["btnAdd"]);
		$_POST['type'] = type2Number($_POST['type']);

		if ($_POST["type"]=="2") { // is Photo type
			if (isset($_POST["url"]) && $_POST["url"]!="")  {
				$photoName = getFileName($_POST["url"]);
				if (!$tumble->savePhoto($_POST["url"])) {
					header("Location: ".$conf->urlGelato."/admin/index.php?photo=false");
					die();
				}
				$_POST["url"] = "../uploads/".sanitizeName($photoName);
			}

			if ( move_uploaded_file( $_FILES['photo']['tmp_name'], "../uploads/".sanitizeName($_FILES['photo']['name']) ) ) {
				$_POST["url"] = "../uploads/".sanitizeName($_FILES['photo']['name']);
			}

			unset($_POST["photo"]);
			unset($_POST["MAX_FILE_SIZE"]);
		}

		if ($_POST["type"]=="7") { // is MP3 type
			set_time_limit(300);
			$mp3Name = getFileName($_POST["url"]);
			if (!$tumble->saveMP3($_POST["url"])) {
				header("Location: ".$conf->urlGelato."/admin/index.php?mp3=false");
				die();
			}
			if (isMP3($remoteFileName)) {
				$_POST["url"] = $conf->urlGelato."/uploads/".$mp3Name;
			}
		}

		if (!get_magic_quotes_gpc()) {
			$_POST["title"] = addslashes($_POST["title"]);
			$_POST["description"] = addslashes($_POST["description"]);
		}

		/*
		$textile = new Textile();

		$_POST["title"] = $textile->TextileThis(removeBadTags($_POST["title"],true));
		$_POST["description"] = $textile->TextileThis(removeBadTags($_POST["description"]));
		*/

		$_POST["title"] = removeBadTags($_POST["title"],true);
		$_POST["description"] = removeBadTags($_POST["description"]);

		if (isset($_POST["id_post"]) and  is_numeric($_POST["id_post"]) and $_POST["id_post"]>0) {
			$tumble->modifyPost($_POST, $_POST["id_post"]);
		} else {
			if ($tumble->addPost($_POST)) {
				header("Location: ".$conf->urlGelato."/admin/index.php?added=true");
				die();
			} else {
				header("Location: ".$conf->urlGelato."/admin/index.php?error=2&des=".$tumble->merror);
				die();
			}
		}
	} else {
		if ($isEdition) {
			$post = $tumble->getPost($postId);
		}

		$theme->set('version',version());
		$theme->set('conf', array(
			'urlGelato'=>$conf->urlGelato,
			'richText'=>$conf->richText
		));
		$theme->set('new',isset($_GET['new'])?$_GET['new']:'');
		$theme->set('information',false);
		$theme->set('error',false);

		if($conf->check_version){
			$present = version();
			$lastest = _file_get_contents("http://www.gelatocms.com/vgel.txt");
			if ($present < $lastest)
				$theme->set('information',__("A new gelato version has been released and is ready <a href=\"http://www.gelatocms.com/\">for download</a>."));
		}

		$actions = array(
			'deleted'=>false,
			'modified'=>false,
			'added'=>false
		);

		if(isset($_GET['deleted']) and $_GET['deleted']=='true'){
			$theme->set('exito',__("The post has been eliminated successfully."));
			$actions['deleted'] = true;
		}

		if(isset($_GET["modified"]) and $_GET["modified"]==true){
			$theme->set('exito',__("The post has been modified successfully."));
			$actions['modified']=true;
		}

		if(isset($_GET["added"]) and $_GET["added"]==true) {
			$theme->set('exito',__("The post has been added successfully."));
			$actions['added']=true;
		}

		$theme->set('action',$actions);

		if (isset($_GET["error"]) and $_GET["error"]==2)
			$theme->set('error',__("Error on the database server:")." </strong>".$_GET["des"]);

		if (isset($_GET["mp3"]) and $_GET["mp3"]=='false')
			$theme->set('error',__("Not an MP3 file or an upload problem."));

		if (isset($_GET["photo"]) and $_GET["photo"]=='false')
			$theme->set('error',__("Not a photo file or an upload problem."));

		if ($isEdition) {
			switch ($post["type"]) {
				case "1": $_GET["new"] = "post"; break;
				case "2": $_GET["new"] = "photo"; break;
				case "3": $_GET["new"] = "quote"; break;
				case "4": $_GET["new"] = "url"; break;
				case "5": $_GET["new"] = "conversation"; break;
				case "6": $_GET["new"] = "video"; break;
				case "7": $_GET["new"] = "mp3"; break;
			}
		}

		$date = ($isEdition) ? strtotime($post["date"]) : gmmktime();
		$title = ($isEdition) ? htmlspecialchars(stripslashes($post["title"])) : "";
		$body = ($isEdition) ? stripslashes($post["description"]) : "";
		$url = ($isEdition) ? $post["url"] : "";

		if (!isset($_GET['new'])) $_GET['new'] = 'post';

		$theme->set('date',$date);
		$theme->set('id_user',$_SESSION['user_id']);
		$theme->set('type',$_GET["new"]);
		$theme->set('editBody',$body);

		switch ($_GET["new"]) {
			case "post":
				$theme->set('editTitle',$title);
				break;
			case "photo":
				$url = str_replace("../", $conf->urlGelato."/", $url);
				$theme->set('editUrl',$url);
				break;
			case "quote":
				$theme->set('editTitle',$title);
				break;
			case "url":
				$theme->set('editTitle',$title);
				$theme->set('editUrl',$url);
				break;
			case "conversation":
				$theme->set('editTitle',$title);
				break;
			case "video":
				$theme->set('editUrl',$url);
				break;
			case "mp3":
				$theme->set('editUrl',$url);
				break;
			}

		if (!$isEdition){
			if (isset($_GET["page"]))
				$page_num = $_GET["page"];
			else
				$page_num = NULL;

			$limit=$conf->postLimit;

			if(isset($page_num) && is_numeric($page_num) && $page_num>0)// Is defined the page and is numeric?
				$from = (($page_num-1) * $limit);
			else
				$from = 0;

			$rs = $tumble->getPosts($limit, $from);
			$theme->set('Posts_Number',$tumble->contarRegistros());

			$rows = array();
			if ($tumble->contarRegistros()>0) {
				while($register = mysql_fetch_array($rs)) {
					$row['postType'] = type2Text($tumble->getType($register["id_post"]));

					$formatedDate = gmdate("M d", strtotime($register["date"])+transform_offset($conf->offsetTime));
					$permalink = $conf->urlGelato."/index.php/post/".$register["id_post"]."/";

					$register["title"] = stripslashes($register["title"]);
					$register["description"] = stripslashes($register["description"]);

					$row['Id_Post'] = $register["id_post"];
					$row['Date_Added'] = $formatedDate;
					$row['Permalink'] = $permalink;

					switch ($tumble->getType($register["id_post"])) {
						case "1":
							$row['Title'] = $register["title"];
							$row['Body'] = $register["description"];
							break;
						case "2":
							$fileName = "../uploads/".getFileName($register["url"]);

							$x = @getimagesize($fileName);
							if ($x[0] > 100)
								$photoPath = $conf->urlGelato."/classes/imgsize.php?w=100&img=".$register["url"];
							else
								$photoPath = $register["url"];

							$effect = " href=\"".str_replace("../", $conf->urlGelato."/", $register["url"])."\" rel=\"lightbox\"";

							$row['PhotoURL'] = $photoPath;
							$row['PhotoAlt'] = strip_tags($register["description"]);
							$row['Caption'] = $register["description"];
							$row['Effect'] = $effect;
							break;
						case "3":
							$row['Quote'] = $register["description"];
							$row['Source'] = $register["title"];
							break;
						case "4":
							if($conf->shorten_links)
								$register["url"] = _file_get_contents("http://api.abbrr.com/api.php?out=link&url=".$register["url"]);
							$register["title"] = ($register["title"]=="")? $register["url"] : $register["title"];

							$row['URL'] = $register["url"];
							$row['Name'] = $register["title"];
							$row['Description'] = $register["description"];
							break;
						case "5":
							$row['Title'] = $register["title"];
							$row['Conversation'] = $tumble->formatConversation($register["description"]);
						break;
							case "6":
							$row['Video'] = $tumble->getVideoPlayer($register["url"]);
							$row['Caption'] = $register["description"];
							break;
						case "7":
							$row['Mp3'] = $tumble->getMp3Player($register["url"]);
							$row['Caption'] = $register["description"];
							break;
					}
<<<<<<< .mine
					$rows[] = $row;
				}
=======
					
					$limit=$conf->postLimit;
					
					if(isset($page_num) && is_numeric($page_num) && $page_num>0) { // Is defined the page and is numeric?
						$from = (($page_num-1) * $limit);
					} else {
						$from = 0;
					}
					
					$rs = $tumble->getPosts($limit, $from);
					
					if ($tumble->contarRegistros()>0) {				
						while($register = mysql_fetch_array($rs)) {			
							$formatedDate = gmdate("M d", strtotime($register["date"])+transform_offset($conf->offsetTime));
							$strEnd=($conf->urlFriendly) ? "/" : "";
							$permalink = $conf->urlGelato.($conf->urlFriendly?"/post/":"/index.php?post=").$register["id_post"].$strEnd;
							$register["title"] = stripslashes($register["title"]);
							$register["description"] = stripslashes($register["description"]);
							
							switch ($tumble->getType($register["id_post"])) {
								case "1":
									$input = array("{Id_Post}", "{Date_Added}", "{Permalink}", "{Title}", "{Body}", "{URL_Tumble}");
									$output = array($register["id_post"], $formatedDate, $permalink, $register["title"], $register["description"], $conf->urlGelato);
														
									$template->cargarPlantilla($input, $output, "template_regular_post");
									$template->mostrarPlantilla();
									break;
								case "2":
									$fileName = "../uploads/".getFileName($register["url"]);
									
									$x = @getimagesize($fileName);						
									if ($x[0] > 100) {							
										$photoPath = $conf->urlGelato."/classes/imgsize.php?w=100&img=".$register["url"];
									} else {
										$photoPath = $register["url"];
									}
>>>>>>> .r241

				$p = new pagination;
				$p->items($tumble->getPostsNumber());
				$p->limit($limit);
				$p->currentPage(isset($page_num) ? $page_num : 1);

				$theme->set('pagination',$p->getPagination());
				$theme->set('rows',$rows);
			}else{
				$theme->set('error',__("No posts in this tumblelog."));
			}
		}
		$theme->display(Absolute_Path.'admin/themes/admin/index.htm');
	}
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>
