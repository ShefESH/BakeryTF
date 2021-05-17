# Saucy Part 3

## Deploy This Challenge Manually

### Install MySQL

```bash
$ sudo apt install mysql-server
```

Start service:

```bash
$ sudo service mysql start
```

*Note:* if using WSL, you may get a failed to start message. This is because windows may have a similar `mysql.exe` process running that binds to same port. Close this in task manager to fix the error. You may also need to run the following:

```bash
$ sudo service mysql stop
$ sudo usermod -d /var/lib/mysql/ mysql
```

Setup MySQL:

```bash
$ sudo mysql_secure_installation
```

Install the MySQL PHP library:

```bash
$ sudo apt install php-mysql
```

### Create a conf_secret.php file

Create a `php` file with the credentials to your local MySQL database, or another database you're connecting to:

```php
<?php

$server = 'localhost';
$username = 'username';
$password = 'password';
$database = 'recipes';

?>
```

### Seed the Database

Run the following command, supplying the `username` and `password` you set:

```bash
$ mysql -u [username] -p < seed.sql
```

## Run

```bash
php -S locahost:8002
```

## Start the Challenge

Visit [http://localhost:8002](http://localhost:8002)