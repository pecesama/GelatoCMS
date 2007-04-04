<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?	
	// MySql configuration
	define('DB_Server', '[server]');			// Set the MySQL hostname (generally "localhost")
	define('DB_name', '[database]');			// Set the MySQL database gelato should use
	define('DB_User', '[userdb]');				// Set the MySQL username
	define('DB_Password', '[dbpass]'); 			// Set the MySQL password
	define('Table_prefix', '[tableprefix]');	// Set the MySQL tables prefixes

	/* Do not edit below this line */

	define('Absolute_Path', dirname(__FILE__).'/');	
	
	session_start();
	header("Expires: Mon, 26 Jul 1957 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s ") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	require_once(Absolute_Path."classes/mysql_connection.class.php");
?>