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

// Received a valid request, better start setting globals we'll need throughout the app in entry.php
require_once('entry.php');
global $user, $tumble, $conf, $db;

$template = new plantillas($conf->template);
        // My approach to MVC


        if(isset($_SERVER['PATH_INFO'])) $param_url = explode("/",$_SERVER['PATH_INFO']);

        if (isset($_GET["post"])) {
                $id_post = $_GET["post"];
                if (!is_numeric($id_post) && $id_post < 1 ){
                	header("Location: index.php");
                }
        } else {
                if (isset($param_url[1]) && $param_url[1]=="post") {
                        $id_post = (isset($param_url[2])) ? ((is_numeric($param_url[2])) ? $param_url[2] : NULL) : NULL;
                } else {
                        $id_post = NULL;
                }
        }		
        
        if (isset($_GET["page"])) {
                $page_num = $_GET["page"];
        } else {
                if (isset($param_url[1]) && $param_url[1]=="page") {
                        $page_num = (isset($param_url[2])) ? ((is_numeric($param_url[2])) ? $param_url[2] : NULL) : NULL;
                } else {
                        $page_num = NULL;
                }
        }
        
        $gelato_includes = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n";
        $gelato_includes .= "\t<meta name=\"generator\" content=\"gelato  ".codeName()." (".version().")\" />\n";
        $gelato_includes .= "\t<link rel=\"shortcut icon\" href=\"".$conf->urlGelato."/images/favicon.ico\" />\n";
        $gelato_includes .= "\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"".$conf->urlGelato.($conf->urlFriendly?"/rss/":"/rss.php")."\"/>\n";
        $gelato_includes .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$conf->urlGelato."/themes/".$conf->template."/style.css\"/>\n";
        $gelato_includes .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$conf->urlGelato."/admin/css/lightbox.css\" />\n";    
        $gelato_includes .= "\t<script language=\"javascript\" type=\"text/javascript\" src=\"".$conf->urlGelato."/admin/scripts/jquery.js\"></script>\n";
        $gelato_includes .= "\t<script language=\"javascript\" type=\"text/javascript\" src=\"".$conf->urlGelato."/admin/scripts/lightbox.js\"></script>";
        
        $input = array("{Gelato_includes}","{Title}", "{Description}", "{URL_Tumble}", "{Template_name}");
        $output = array($gelato_includes, $conf->title, $conf->description, $conf->urlGelato, $conf->template);
        
        $template->cargarPlantilla($input, $output, "template_header");
        $template->mostrarPlantilla();
        
        if ($user->isAuthenticated()) { 
                $input = array("{User}", "{URL_Tumble}");
                $output = array($_SESSION["user_login"], $conf->urlGelato);
                
                $template->cargarPlantilla($input, $output, "template_isadmin");
                $template->mostrarPlantilla();
        }
        
        if (!$id_post) {

                $limit=$conf->postLimit;
        
                if(isset($page_num) && is_numeric($page_num) && $page_num>0) { // Is defined the page and is numeric?
                        $from = (($page_num-1) * $limit);
                } else {
                        $from = 0;
                }
        
                $rs = $tumble->getPosts($limit, $from);

                if ($tumble->contarRegistros()>0) {
                        $dateTmp = null;          
                        while($register = mysql_fetch_array($rs)) {
								$formatedDate = gmdate("M d", strtotime($register["date"])+transform_offset($conf->offsetTime));
                                if ( $dateTmp != null && $formatedDate == $dateTmp ) { $formatedDate = ""; } else { $dateTmp = $formatedDate; }
								$strEnd=($conf->urlFriendly) ? "/" : "";
								$permalink = $conf->urlGelato.($conf->urlFriendly?"/post/":"/index.php?post=").$register["id_post"].$strEnd;
                                
								$textile = new Textile();				
								$register["description"] = $textile->TextileThis($register["description"]);

                                $register["title"] = stripslashes($register["title"]);
                                $register["description"] = stripslashes($register["description"]);

                                switch ($tumble->getType($register["id_post"])) {
                                        case "1":
                                                $input = array("{Date_Added}", "{Permalink}", "{Title}", "{Body}", "{URL_Tumble}");
                                                $output = array($formatedDate, $permalink, $register["title"], $register["description"], $conf->urlGelato);
                                                                                        
                                                $template->cargarPlantilla($input, $output, "template_regular_post");
                                                $template->mostrarPlantilla();
                                                break;
                                        case "2":                                               
                                                $fileName = "uploads/".getFileName($register["url"]);
                                                
                                                $x = @getimagesize($fileName);                                          
                                                if ($x[0] > 500) {                                                      
													$photoPath = $conf->urlGelato."/classes/imgsize.php?w=500&img=".$register["url"];
                                                } else {													
													$photoPath = str_replace("../", $conf->urlGelato."/", $register["url"]);
                                                }

												$effect = " href=\"".str_replace("../", $conf->urlGelato."/", $register["url"])."\" rel=\"lightbox\"";
                                                
                                                $input = array("{Date_Added}", "{Permalink}", "{PhotoURL}", "{PhotoAlt}", "{Caption}", "{Effect}", "{URL_Tumble}");
                                                $output = array($formatedDate, $permalink, $photoPath, strip_tags($register["description"]), $register["description"], $effect, $conf->urlGelato);
                                                
                                                $template->cargarPlantilla($input, $output, "template_photo");
                                                $template->mostrarPlantilla();                                                     
                                                break;
                                        case "3":
                                                $input = array("{Date_Added}", "{Permalink}", "{Quote}", "{Source}", "{URL_Tumble}");
                                                $output = array($formatedDate, $permalink, $register["description"], $register["title"], $conf->urlGelato);
                                                
                                                $template->cargarPlantilla($input, $output, "template_quote");
                                                $template->mostrarPlantilla();
                                                break;
                                        case "4":
                                                if($conf->shorten_links){
													$register["url"] = _file_get_contents("http://api.abbrr.com/api.php?out=link&url=".$register["url"]);
												}
												$register["title"] = ($register["title"]=="")? $register["url"] : $register["title"];
												$input = array("{Date_Added}", "{Permalink}", "{URL}", "{Name}", "{Description}", "{URL_Tumble}");
                                                $output = array($formatedDate, $permalink, $register["url"], $register["title"], $register["description"], $conf->urlGelato);
                                                
                                                $template->cargarPlantilla($input, $output, "template_url");
                                                $template->mostrarPlantilla();
                                                break;
                                        case "5":
                                                $input = array("{Date_Added}", "{Permalink}", "{Title}", "{Conversation}", "{URL_Tumble}");
                                                $output = array($formatedDate, $permalink, $register["title"], $tumble->formatConversation($register["description"]), $conf->urlGelato);
                                                
                                                $template->cargarPlantilla($input, $output, "template_conversation");
                                                $template->mostrarPlantilla();
                                                break;
                                        case "6":
                                                $input = array("{Date_Added}", "{Permalink}", "{Video}", "{Caption}", "{URL_Tumble}");
                                                $output = array($formatedDate, $permalink, $tumble->getVideoPlayer($register["url"]), $register["description"], $conf->urlGelato);
                                                
                                                $template->cargarPlantilla($input, $output, "template_video");
                                                $template->mostrarPlantilla();
                                                break;
                                        case "7":
                                                $input = array("{Date_Added}", "{Permalink}", "{Mp3}", "{Caption}", "{URL_Tumble}");
                                                $output = array($formatedDate, $permalink, $tumble->getMp3Player($register["url"]), $register["description"], $conf->urlGelato);
                                                
                                                $template->cargarPlantilla($input, $output, "template_mp3");
                                                $template->mostrarPlantilla();
                                                break;
                                }
                        }

                        $p = new pagination;
                        $p->Items($tumble->getPostsNumber());
                        $p->limit($limit);
                        
						if($conf->urlFriendly){
								$p->urlFriendly('[...]');
								$p->target($conf->urlGelato."/page/[...]");
							}else
								$p->target($conf->urlGelato);
                        
                        $p->currentPage(isset($page_num) ? $page_num : 1);
                        $p->show();


                } else {
                        $template->renderizaEtiqueta("No posts in this tumblelog.", "div","error");
                }
        } else {
                $register = $tumble->getPost($id_post);
                
				$formatedDate = gmdate("M d", strtotime($register["date"])+transform_offset($conf->offsetTime));
				$strEnd=($conf->urlFriendly) ? "/" : "";
				$permalink = $conf->urlGelato.($conf->urlFriendly?"/post/":"/index.php?post=").$register["id_post"].$strEnd;
                
				$textile = new Textile();				
				$register["description"] = $textile->TextileThis($register["description"]);
				
				$register["title"] = stripslashes($register["title"]);
                $register["description"] = stripslashes($register["description"]);
                
                switch ($tumble->getType($register["id_post"])) {
                        case "1":
                                $input = array("{Date_Added}", "{Permalink}", "{Title}", "{Body}", "{URL_Tumble}");
                                $output = array($formatedDate, $permalink, $register["title"], $register["description"], $conf->urlGelato);
                                                                        
                                $template->cargarPlantilla($input, $output, "template_regular_post");
                                $template->mostrarPlantilla();
                                break;
                        case "2":
                                $fileName = "uploads/".getFileName($register["url"]);
                                                
                                $x = @getimagesize($fileName);                                          
                                if ($x[0] > 500) {                                      
                                        $photoPath = $conf->urlGelato."/classes/imgsize.php?w=500&img=".$register["url"];
                                } else {
										$photoPath = str_replace("../", $conf->urlGelato."/", $register["url"]);
                                }

								$effect = " href=\"".str_replace("../", $conf->urlGelato."/", $register["url"])."\" rel=\"lightbox\"";
                                $input = array("{Date_Added}", "{Permalink}", "{PhotoURL}", "{PhotoAlt}", "{Caption}", "{Effect}", "{URL_Tumble}");
                                $output = array($formatedDate, $permalink, $photoPath, strip_tags($register["description"]), $register["description"], $effect, $conf->urlGelato);
                                
                                $template->cargarPlantilla($input, $output, "template_photo");
                                $template->mostrarPlantilla();                                                     
                                break;
                        case "3":
                                $input = array("{Date_Added}", "{Permalink}", "{Quote}", "{Source}", "{URL_Tumble}");
                                $output = array($formatedDate, $permalink, $register["description"], $register["title"], $conf->urlGelato);
                                
                                $template->cargarPlantilla($input, $output, "template_quote");
                                $template->mostrarPlantilla();
                                break;
                        case "4":
                                if($conf->shorten_links){
									$register["url"] = _file_get_contents("http://api.abbrr.com/api.php?out=link&url=".$register["url"]);
								}
								$register["title"] = ($register["title"]=="")? $register["url"] : $register["title"];
								$input = array("{Date_Added}", "{Permalink}", "{URL}", "{Name}", "{Description}", "{URL_Tumble}");
                                $output = array($formatedDate, $permalink, $register["url"], $register["title"], $register["description"], $conf->urlGelato);
                                
                                $template->cargarPlantilla($input, $output, "template_url");
                                $template->mostrarPlantilla();
                                break;
                        case "5":
                                $input = array("{Date_Added}", "{Permalink}", "{Title}", "{Conversation}", "{URL_Tumble}");
                                $output = array($formatedDate, $permalink, $register["title"], $tumble->formatConversation($register["description"]), $conf->urlGelato);
                                
                                $template->cargarPlantilla($input, $output, "template_conversation");
                                $template->mostrarPlantilla();
                                break;
                        case "6":
                                $input = array("{Date_Added}", "{Permalink}", "{Video}", "{Caption}", "{URL_Tumble}");
                                $output = array($formatedDate, $permalink, $tumble->getVideoPlayer($register["url"]), $register["description"], $conf->urlGelato);
                                
                                $template->cargarPlantilla($input, $output, "template_video");
                                $template->mostrarPlantilla();
                                break;
                        case "7":
                                $input = array("{Date_Added}", "{Permalink}", "{Mp3}", "{Caption}", "{URL_Tumble}");
                                $output = array($formatedDate, $permalink, $tumble->getMp3Player($register["url"]), $register["description"], $conf->urlGelato);
                                
                                $template->cargarPlantilla($input, $output, "template_mp3");
                                $template->mostrarPlantilla();
                                break;
                }
				
				if ($conf->allowComments) {
					
					$comment = new comments();
					$rsComments = $comment->getComments($register["id_post"]);
					
					$input = array("{Comments_Number}", "{Post_Title}");				
					$output = array($comment->countComments($register["id_post"]), $register["title"]);
					$template->precargarPlantillaConBloque($input, $output, "template_comments", "comments");

					while($rowComment = mysql_fetch_assoc($rsComments)) {
						echo "<pre>";
						print_r($rowComment);
						echo "</pre>";
						$commentAuthor = ($rowComment["web"]=="") ? $rowComment["username"] : "<a href=\"".$rowComment["web"]."\" rel=\"external\">".$rowComment["username"]."</a>";
						$input = array("{Id_Comment}", "{Comment_Author}", "{Date}", "{Comment}");
						$output = array($rowComment["id_comment"], $commentAuthor, gmdate("d.m.y", strtotime($rowComment["comment_date"])+transform_offset($conf->offsetTime)), $rowComment["content"]);
						$template->cargarPlantillaConBloque($input, $output, "template_comments", "comments");
					}
					$template->mostrarPlantillaConBloque();
										
					$input = array("{User_Cookie}", "{Email_Cookie}", "{Web_Cookie}", "{Id_Post}", "{Form_Action}", "{Date_Added}");
					$output = array(isset($_COOKIE['cookie_gel_user'])?$_COOKIE['cookie_gel_user']:'', isset($_COOKIE['cookie_gel_email'])?$_COOKIE['cookie_gel_email']:'', isset($_COOKIE['cookie_gel_web'])?$_COOKIE['cookie_gel_web']:'', $register["id_post"], $conf->urlGelato."/admin/comments.php", gmmktime());
					
					$template->cargarPlantilla($input, $output, "template_comment_post");
					$template->mostrarPlantilla(); 
					
				}
        }
        
        $input = array("{URL_Tumble}");
        $output = array($conf->urlGelato);
        
        $template->cargarPlantilla($input, $output, "template_footer");
        $template->mostrarPlantilla();
?> 
