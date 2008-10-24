==== How to install gelato CMS =====

1) Download and unzip the gelato package, if you haven't already.
2) Place the gelato files in the desired location on your web server.
3) Run the gelato installation script by accessing install.php in your favorite web browser.
	* If you installed gelato in the root directory, you should visit: http://example.com/install.php
    * If you installed gelato in its own subdirectory called tumblelog, for example, you should visit: http://example.com/tumblelog/install.php 
4) Set CHMOD / permission ( Chmod 777 ) to the folder 'uploads' and the folder 'uploads/CACHE'
5) REMOVE the install.php and update.php files.

That's it! gelato should now be installed.

==== How to update to v1.0 from v0.95 version =====

1) Download and unzip the gelato package, if you haven't already.
2) Create a backup of your config.php file.
3) Replace all the old files with those from the new 1.0.
4) Rename the config-sample.php file to config.php (DO NOT use your old config.php file).
5) Open config.php in your favorite text editor and fill in your database details (using the information on your old config.php).
4) Execute the update.php file.
7) Set CHMOD / permission ( Chmod 777 ) to the folder 'uploads'  and the folder 'uploads/CACHE'.
8) REMOVE the install.php and update.php files.

That's it! gelato should now be updated.

==== Update files for version previous to v1.0 are available on the download section =====

==== How to install gelato CMS (Manually) =====

1) Download and unzip the gelato package, if you haven't already.
2) Create a database for gelato on your web server, as well as a MySQL user who has all privileges for accessing and modifying it.
3) Execute the script /db/gelato_db.sql into your database.
4) Rename the config-sample.php file to config.php.
5) Open config.php in your favorite text editor and fill in your database details.
	Note: The test data on the script /db/gelato_db.sql use the table prefix 'gel_'
6) Place the gelato files in the desired location on your web server.
	Note: The test data on the script /db/gelato_db.sql point the installation to http://localhost/gelato
7) Set CHMOD / permission ( Chmod 777 ) to the folder 'uploads' and the folder 'uploads/CACHE'
8) REMOVE the install.php and update.php files.

That's it! gelato should now be installed.
	Demo user: admin
	Demo pass: demo