# streamable-spike

## How to set up local database for Windows

1. download and install xampp (I recommend in C:)
2. navigate to `C:\xampp\apache\conf\extra` or wherever your XAMPP files are located.
3. open `httpd-vhosts.conf` with any text editor
4. uncomment `NameVirtualHosts *:80`
5. add this code to the end of the code

```
<VirtualHost *:80>
    DocumentRoot "C:/Users/bryms/School/cs506/streamable-spike"
    ServerName spike.local
    <Directory "file path to spike repository">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

6. Open `C:\Windows\System32\drivers\etc\hosts` in any text editor
7. paste this to end of code

```
127.0.0.1       localhost
::1             localhost
127.0.0.1       spike.local
```

(you may have to save as administrator)

8. open `C:\xampp\xampp-control.exe`
9. press start on Apache and MySQL
11. open localhost/phpmyadmin
12. click import tab and import 20210226.sql
13. if unable to import, manually create databases and tables with queries in the sql file
14. edit the information in the php brackets in the files with updated database information if necessary and save the files (ex: servername, username, password, dbname)

```
<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "spike";
?>
```
15. you will now be able to access this project (start at spike.local/index.html)

## How to set up local database on Mac

1. download and install MAMP
2. download and install MySQL Workbench Community 8.0.21 (you must download an older version than the one that's currently available due to a bug that prevents the application from opening)
3. download and install MySQL Shell 8.0.23
4. open your Mac's Preferences app and go to the MySQL section
5. click `Start MySQL Server` and enter in your password if it is requested
6. open MySQL Workbench
7. open MAMP
8. in MAMP, click Preferences and change your MySQL port to the one that's listed in your Mac's settings and click OK
9. in MAMP, click Preferences and change the document root to the project's directory and click OK
10. hit Start in MAMP
11. import the SQL database in MySql workbench by clicking server in the menu bar and then selecting data import.
12. then choose to import from a self-contained file and select the sql file from the GitHub repository
13. edit the information in the php brackets in the files with updated database information if necessary and save the files (ex: servername, username, password, dbname)

```
<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "streamable1";
    $dbname = "spike";
?>
```

Note that for the submitted version of this project, the servername is set to `127.0.0.1`, the username is `root`, the password is `streamable1`, and the database name is `spike`. Depending on your setup, you may need to modify these values (ex: changing `127.0.0.1` to `localhost` or changing the database name)
