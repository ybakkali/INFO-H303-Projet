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

CREATE TABLE IF NOT EXISTS `ALL_USERS`
( `ID` int unsigned NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

INSERT INTO ALL_USERS (`ID`) SELECT `ID` FROM REGISTRED_USERS;
INSERT INTO ALL_USERS (`ID`) SELECT `ID` FROM ANONYME_USERS;

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

CREATE TABLE IF NOT EXISTS `SCOOTERS`
( `scooterID` int unsigned NOT NULL,
  `commissioningDate` DATETIME NOT NULL,
  `modelNumber` varchar(30) NOT NULL,
  `complainState` varchar(5) NOT NULL,
  `batteryLevel` int NOT NULL,
  PRIMARY KEY(`scooterID`)
) engine = innodb;

LOAD DATA LOCAL INFILE 'data2019/scooters.csv'
INTO TABLE `scooterDB`.`SCOOTERS`
FIELDS TERMINATED BY ';' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE IF NOT EXISTS `TRIPS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `sourceX` float NOT NULL,
  `sourceY` float NOT NULL,
  `destinationX` float NOT NULL,
  `destinationY` float NOT NULL,
  `starttime` DATETIME NOT NULL,
  `endtime` DATETIME NOT NULL,

  PRIMARY KEY(`userID`,`scooterID`,`starttime`),
  CONSTRAINT fk_scooter FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID),
  CONSTRAINT fk_user FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
) engine = innodb;

LOAD DATA LOCAL INFILE 'data2019/trips.csv'
INTO TABLE `scooterDB`.`TRIPS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE IF NOT EXISTS `RELOADS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `initialLoad` int unsigned NOT NULL,
  `finalLoad` int unsigned NOT NULL,
  `sourceX` float NOT NULL,
  `sourceY` float NOT NULL,
  `destinationX` float NOT NULL,
  `destinationY` float NOT NULL,
  `starttime` DATETIME NOT NULL,
  `endtime` DATETIME NOT NULL,

  PRIMARY KEY(`userID`,`scooterID`,`starttime`),
  CONSTRAINT fk_scooter_2 FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID),
  CONSTRAINT fk_user_2 FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
) engine = innodb;

LOAD DATA LOCAL INFILE 'data2019/reloads.csv'
INTO TABLE `scooterDB`.`RELOADS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE IF NOT EXISTS `REPARATIONS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `mechanicID` varchar(30) NOT NULL,
  `complainTime` DATETIME NOT NULL,
  `repaireTime` DATETIME NOT NULL,

  PRIMARY KEY(`userID`,`scooterID`,`mechanicID`,`repaireTime`),
  CONSTRAINT fk_scooter_1 FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID),
  CONSTRAINT fk_user_1 FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID),
  CONSTRAINT fk_mechanic_1 FOREIGN KEY(mechanicID)
      REFERENCES MECANICIENS(mechanicID)
) engine = innodb;

LOAD DATA LOCAL INFILE 'data2019/reparations.csv'
INTO TABLE `scooterDB`.`REPARATIONS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
-- (scooter,user,mechanic,@complainTime,@repaireTime)
-- SET complainTime = STR_TO_DATE(@expired_date, '%m/%d/%Y');
