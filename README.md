# Calender Todo

I built this small webapp to help me keep track of calender deliveries this year. It can track different types of calenders dirent users to deliver to and individual orders.

As well as allowing me to experiment with PHP classes.


To set up this project simply place this folder in any php server and set up your mysql and apache server as follows.

Create Main Database
```SQL
CREATE DATABASE calenders;
```
Create The Admin User
```SQL
CREATE USER 'cal_admin'@'localhost' IDENTIFIED BY 'YourPassword';
```
And Grant all Privileges
```SQL
GRANT ALL PRIVILEGES ON calenders. * TO 'cal_admin'@'localhost';
```
You will have to update your <code>/etc/apache2/apache2.conf</code>
to allow for the <code>.htaccess</code> and routing to work correctly.

```bash
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>

```
As well as enable the rewrite module.

```bash
a2enmod rewrite
```
