-- https://dev.mysql.com/doc/refman/5.5/en/load-xml.html
-- mysql -u yahyabakkali -p scooterDB
-- mysql> source /home/yahyabakkali/Bureau/INFO-H303/BDD/initialization.sql;

CREATE DATABASE IF NOT EXISTS `scooterDB`;

CREATE TABLE IF NOT EXISTS `REGISTRED_USERS`
( `ID` int unsigned NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `bankaccount` varchar(16) NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

CREATE TABLE IF NOT EXISTS `USER_ADDRESS`
( `ID` int unsigned NOT NULL,
  `city` varchar(30) NOT NULL,
  `cp` int NOT NULL,
  `street` varchar(40) NOT NULL,
  `number` int NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml'
INTO TABLE `scooterDB`.`REGISTRED_USERS`
ROWS IDENTIFIED BY '<user>';

LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml'
INTO TABLE `scooterDB`.`USER_ADDRESS`
ROWS IDENTIFIED BY '<address>';

CREATE TABLE IF NOT EXISTS `ANONYME_USERS`
( `ID`  int unsigned NOT NULL,
  `password` varchar(64) NOT NULL,
  `bankaccount` varchar(16) NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

LOAD XML LOCAL INFILE 'data2019/anonyme_users.xml'
INTO TABLE `scooterDB`.`ANONYME_USERS`
ROWS IDENTIFIED BY '<user>';

CREATE TABLE IF NOT EXISTS `MECANICIENS`
( `mechanicID` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `hireDate` DATE NOT NULL,
  `bankaccount` varchar(16) NOT NULL,
  PRIMARY KEY(`mechanicID`)
) engine = innodb;

CREATE TABLE IF NOT EXISTS `MECHANIC_ADDRESS`
( `mechanicID` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `cp` int NOT NULL,
  `street` varchar(40) NOT NULL,
  `number` int NOT NULL,
  PRIMARY KEY(`mechanicID`)
) engine = innodb;

LOAD XML LOCAL INFILE 'data2019/mecaniciens.xml'
INTO TABLE `scooterDB`.`MECANICIENS`
ROWS IDENTIFIED BY '<mechanic>';

LOAD XML LOCAL INFILE 'data2019/mecaniciens.xml'
INTO TABLE `scooterDB`.`MECHANIC_ADDRESS`
ROWS IDENTIFIED BY '<address>';
