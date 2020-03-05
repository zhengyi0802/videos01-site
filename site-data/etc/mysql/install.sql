ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'mundi8888';
FLUSH PRIVILEGES;
CREATE USER 'dbadmin'@'localhost' IDENTIFIED BY 'mundi8888';
GRANT ALL PRIVILEGES ON *.* TO 'dbadmin'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

