<?php
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?php
	// My approach to MVC
	
	$configFile = dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
	
	if (!file_exists($configFile)) {
		$mensaje = "
			<h3 class=\"important\">Error reading configuration file</h3>			
			<p>There doesn't seem to be a <code>config.php</code> file. I need this before we can get started.</p>
			<p>This either means that you did not rename the <code>config-sample.php</code> file to <code>config.php</code>.</p>";
		die($mensaje);	
	} else {
		require(dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");
	}	
	
	include("classes/configuration.class.php");
	include("classes/textile.class.php");
	include("classes/gelato.class.php");	
	include("classes/templates.class.php");
	include("classes/pagination.php");
	include("classes/user.class.php");
		
	$user = new user();
	$conf = new configuration();
	$tumble = new gelato();
	$template = new plantillas($conf->template);

	if(isset($_SERVER['PATH_INFO'])) $param_url = explode("/",$_SERVER['PATH_INFO']);

	if (isset($_GET["post"])) {
		$id_post = $_GET["post"];
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
	
	$gelato_includes = "<script language=\"javascript\" type=\"text/javascript\" src=\"".$conf->urlGelato."/admin/scripts/mootools.js\"></script>\n";
	$gelato_includes .= "\t<script language=\"javascript\" type=\"text/javascript\" src=\"".$conf->urlGelato."/admin/scripts/slimbox.js\"></script>\n";
	$gelato_includes .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$conf->urlGelato."/admin/css/slimbox.css\" />\n";
	$gelato_includes .= "\t<link rel=\"shortcut icon\" href=\"".$conf->urlGelato."/images/favicon.ico\" />\n";
	$gelato_includes .= "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$conf->urlGelato."/themes/".$conf->template."/style.css\"/>\n";
	$gelato_includes .= "\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"".$conf->urlGelato."/rss.php\"/>";
	
	$input = array("{Gelato_includes}","{Title}", "{Description}", "{URL_Tumble}", "{Template_name}");
	$output = array($gelato_includes, $conf->title, $conf->description, $conf->urlGelato, $conf->template);
	
	$template->cargarPlantilla($input, $output, "template_header");
	$template->mostrarPlantilla();
	
	if ($user->isAdmin()) {	
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
			$fecha = null;		
			while($register = mysql_fetch_array($rs)) {			
				$formatedDate = date("M d", strtotime($register["date"]));
				if ( $fecha != null && $formatedDate == $fecha ) { $formatedDate = ""; } else { $fecha = $formatedDate; }
				$permalink = $conf->urlGelato."/index.php/post/".$register["id_post"]."/";
				
				$textile = new Textile; 				
				$register["description"] = $textile->process($register["description"]);
				
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
							$photoPath = $register["url"];
						}
						
						$effect = " onclick=\"Lightbox.show('".$register["url"]."', '".strip_tags($register["description"])."');\" ";
						
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

			echo pagination($tumble->getPostsNumber(), $limit, isset($page_num) ? $page_num : 1, array($conf->urlGelato."/index.php/page/[...]/","[...]"), 2);


		} else {
			$template->renderizaEtiqueta("No posts in this tumblelog.", "div","error");
		}
	} else {
		$register = $tumble->getPost($id_post);
		
		$formatedDate = date("M d", strtotime($register["date"]));
		$permalink = $conf->urlGelato."/index.php/post/".$register["id_post"]."/";
		
		$textile = new Textile;
		$register["description"] = $textile->process($register["description"]);
		
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
					$photoPath = $register["url"];
				}
				
				$effect = " onclick=\"Lightbox.show('".$register["url"]."', '".strip_tags($register["description"])."');\" ";
						
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
	
	$input = array("{URL_Tumble}");
	$output = array($conf->urlGelato);
	
	$template->cargarPlantilla($input, $output, "template_footer");
	$template->mostrarPlantilla();
?> 