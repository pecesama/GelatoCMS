==== How to install gelato CMS =====

1) Download and unzip the gelato package, if you haven't already.
2) Create a database for gelato on your web server, as well as a MySQL user who has all privileges for accessing and modifying it.
3) Rename the config-sample.php file to config.php.
4) Open config.php in your favorite text editor and fill in your database details.
5) Place the gelato files in the desired location on your web server.
6) Run the gelato installation script by accessing install.php in your favorite web browser.
	* If you installed gelato in the root directory, you should visit: http://example.com/install.php
          * If you installed gelato in its own subdirectory called tumblelog, for example, you should visit: http://example.com/tumblelog/install.php 
7) Set CHMOD / permission ( Chmod 777 ) to the folder 'uploads'

That's it! gelato should now be installed.   


==== How to install gelato CMS (Manual) =====

1) Download and unzip the gelato package, if you haven't already.
2) Create a database for gelato on your web server, as well as a MySQL user who has all privileges for accessing and modifying it.
3) Execute the script /db/gelato_db.sql into your database.
4) Rename the config-sample.php file to config.php.
5) Open config.php in your favorite text editor and fill in your database details.
	Note: The test data on the script /db/gelato_db.sql use the table prefix 'gel_'
6) Place the gelato files in the desired location on your web server.
	Note: The test data on the script /db/gelato_db.sql point the installation to http://localhost/gelato
7) Set CHMOD / permission ( Chmod 777 ) to the folder 'uploads'

That's it! gelato should now be installed.
	Demo user: admin
	Demo pass: demo