/* To run script do :

    mysql -u username -p scooterDB
    mysql> source ./initialization.sql;

  OR

    mysql < initialization.sql
*/

DROP DATABASE IF EXISTS `scooterDB`;
CREATE DATABASE IF NOT EXISTS `scooterDB`;
USE `scooterDB`;

SELECT '<CREATE ALL_USERS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `ALL_USERS`
( `ID` int unsigned NOT NULL,
  `lastname` varchar(30) NULL,
  `firstname` varchar(30) NULL,
  `password` varchar(64) NOT NULL,
  `phone` varchar(10) NULL,
  `bankaccount` varchar(16) NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

SELECT '<CREATE USER_ADDRESS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `USER_ADDRESS`
( `ID` int unsigned NOT NULL,
  `city` varchar(30) NOT NULL,
  `cp` int unsigned NOT NULL,
  `street` varchar(40) NOT NULL,
  `number` int unsigned NOT NULL,

  PRIMARY KEY(`ID`),
  CONSTRAINT fk_userID FOREIGN KEY(ID)
      REFERENCES ALL_USERS(ID)
      ON UPDATE CASCADE
) engine = innodb;

SELECT '<CREATE MECANICIENS TABLE>' AS '';
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

SELECT '<CREATE MECHANIC_ADDRESS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `MECHANIC_ADDRESS`
( `mechanicID` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `cp` int unsigned NOT NULL,
  `street` varchar(40) NOT NULL,
  `number` int unsigned NOT NULL,

  PRIMARY KEY(`mechanicID`),
  CONSTRAINT fk_mechanicID FOREIGN KEY(mechanicID)
      REFERENCES MECANICIENS(mechanicID)
      ON UPDATE CASCADE
) engine = innodb;

SELECT '<CREATE SCOOTERS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `SCOOTERS`
( `scooterID` int unsigned NOT NULL,
  `commissioningDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modelNumber` varchar(30) NOT NULL,
  `complainState` boolean NOT NULL DEFAULT False,
  `batteryLevel` int unsigned NOT NULL DEFAULT 4,
  `locationX` float  NULL,
  `locationY` float  NULL,
  `lastLocationTime` DATETIME NULL DEFAULT '2017-01-01T09:00:00',
  `availability` ENUM('available','occupy','inRepair','inReload','defective') NOT NULL DEFAULT 'available',
  PRIMARY KEY(`scooterID`)
) engine = innodb;

SELECT '<CREATE COMPLAINS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `COMPLAINS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(250) NOT NULL DEFAULT '',
  `state` ENUM('inProcess','notTreated','treated') NOT NULL DEFAULT 'notTreated',

  INDEX(`date`),
  PRIMARY KEY(`userID`,`scooterID`,`date`),
  CONSTRAINT fk_scooter_3 FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID)
      ON UPDATE CASCADE,
  CONSTRAINT fk_user_3 FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
      ON UPDATE CASCADE
) engine = innodb;

SELECT '<CREATE REPARATIONS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `REPARATIONS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `mechanicID` varchar(30) NOT NULL,
  `complainTime` DATETIME NOT NULL,
  `repaireTime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` varchar(250) NOT NULL DEFAULT '',

  PRIMARY KEY(`userID`,`scooterID`,`mechanicID`,`complainTime`),
  CONSTRAINT fk_scooter_1 FOREIGN KEY(`scooterID`)
      REFERENCES SCOOTERS(scooterID)
      ON UPDATE CASCADE,
  CONSTRAINT fk_user_1 FOREIGN KEY(userID)
      REFERENCES ALL_USERS(`ID`)
      ON UPDATE CASCADE,
  CONSTRAINT fk_mechanic_1 FOREIGN KEY(mechanicID)
      REFERENCES MECANICIENS(`mechanicID`)
      ON UPDATE CASCADE,
  CONSTRAINT fk_complain FOREIGN KEY(complainTime)
      REFERENCES COMPLAINS(`date`)
      ON UPDATE CASCADE
) engine = innodb;

SELECT '<CREATE RELOADS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `RELOADS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `initialLoad` int unsigned NOT NULL,
  `finalLoad` int unsigned NOT NULL,
  `sourceX` DECIMAL(10, 5) NOT NULL,
  `sourceY` DECIMAL(11, 5) NOT NULL,
  `destinationX` DECIMAL(10, 5) NOT NULL,
  `destinationY` DECIMAL(11, 5) NOT NULL,
  `starttime` DATETIME NOT NULL,
  `endtime` DATETIME NOT NULL,

  PRIMARY KEY(`userID`,`scooterID`,`starttime`),
  CONSTRAINT fk_scooter_2 FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID)
      ON UPDATE CASCADE,
  CONSTRAINT fk_user_2 FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
      ON UPDATE CASCADE
) engine = innodb;

SELECT '<CREATE TRIPS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `TRIPS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `sourceX` DECIMAL(10, 5) NOT NULL,
  `sourceY` DECIMAL(11, 5) NOT NULL,
  `destinationX` DECIMAL(10, 5) NOT NULL,
  `destinationY` DECIMAL(11, 5) NOT NULL,
  `starttime` DATETIME NOT NULL,
  `endtime` DATETIME NOT NULL,
  `duration` TIME NOT NULL,
  `price` float NOT NULL,

  PRIMARY KEY(`userID`,`scooterID`,`starttime`),
  CONSTRAINT fk_scooter FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID)
      ON UPDATE CASCADE,
  CONSTRAINT fk_user FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
      ON UPDATE CASCADE
) engine = innodb;

DELIMITER |

SELECT '<CREATE SCOOTERS TRIGGER>' AS '';
CREATE TRIGGER SCOOTERS_TRIGGER BEFORE UPDATE ON `SCOOTERS`
  FOR EACH ROW
  BEGIN
      IF NEW.availability = 'defective' THEN
        SET NEW.locationX = NULL, NEW.locationY = NULL, NEW.lastLocationTime = NULL;
      END IF;
  END |

SELECT '<CREATE COMPLAINS TRIGGER>' AS '';
CREATE TRIGGER COMPLAINS_TRIGGER BEFORE INSERT ON `COMPLAINS`
  FOR EACH ROW
  BEGIN
      UPDATE `SCOOTERS` S
      SET complainState = 1
      WHERE S.`scooterID` = NEW.scooterID;
  END |

SELECT '<CREATE REPARATIONS TRIGGER>' AS '';
CREATE TRIGGER REPARATIONS_TRIGGER BEFORE INSERT ON `REPARATIONS`
  FOR EACH ROW
  BEGIN
    IF NEW.complainTime > NEW.repaireTime THEN
      signal sqlstate '45000'  SET MESSAGE_TEXT = 'An error occurred';
    ELSE
      UPDATE `COMPLAINS` C
      SET state = 'treated'
      WHERE C.`scooterID` = NEW.scooterID AND
            C.`date`      < NEW.repaireTime;

      UPDATE `SCOOTERS` S
      SET complainState = 0
      WHERE S.`scooterID` = NEW.scooterID;
    END IF;
  END |

SELECT '<CREATE RELOADS TRIGGER>' AS '';
CREATE TRIGGER RELOADS_TRIGGER BEFORE INSERT ON `RELOADS`
  FOR EACH ROW
  BEGIN
    IF NEW.initialLoad > NEW.finalLoad
       AND NEW.starttime > NEW.endtime
       AND TIME(NEW.starttime) NOT BETWEEN '22:00:00' and '07:00:00'
       AND TIME(NEW.endtime) NOT BETWEEN '22:00:00' and '07:00:00'
       THEN
          signal sqlstate '45000'  SET MESSAGE_TEXT = 'An error occurred';
    END IF;
  END |

SELECT '<CREATE TRIPS TRIGGER>' AS '';
CREATE TRIGGER TRIPS_TRIGGER BEFORE INSERT ON `TRIPS`
  FOR EACH ROW
  BEGIN
    IF NEW.starttime > NEW.endtime THEN
          signal sqlstate '45000'  SET MESSAGE_TEXT = 'An error occurred';
    ELSE

      SET NEW.duration = TIMEDIFF(NEW.endtime, NEW.starttime),
          NEW.price = (1 + (HOUR(NEW.duration) DIV 24) * 36
                       + (HOUR(NEW.duration) % 24) * 6.5
                       + MINUTE(NEW.duration) * 0.15);

      UPDATE IGNORE `SCOOTERS` S
      SET S.`locationX` = NEW.destinationX, S.`locationY` = NEW.destinationY, S.`lastLocationTime` = NEW.endtime
      WHERE S.`scooterID` = NEW.scooterID AND NEW.endtime > S.`lastLocationTime`;
    END IF;
  END |

DELIMITER ;

SELECT '<LOAD ALL_USERS TABLE>' AS '';
LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml' IGNORE
INTO TABLE `ALL_USERS`
ROWS IDENTIFIED BY '<user>';

LOAD XML LOCAL INFILE 'data2019/anonyme_users.xml' IGNORE
INTO TABLE `ALL_USERS`
ROWS IDENTIFIED BY '<user>';

SELECT '<LOAD USER_ADDRESS TABLE>' AS '';
LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml' IGNORE
INTO TABLE `USER_ADDRESS`
ROWS IDENTIFIED BY '<address>';

SELECT '<LOAD MECANICIENS TABLE>' AS '';
LOAD XML LOCAL INFILE 'data2019/mecaniciens.xml' IGNORE
INTO TABLE `MECANICIENS`
ROWS IDENTIFIED BY '<mechanic>';

SELECT '<LOAD MECHANIC_ADDRESS TABLE>' AS '';
LOAD XML LOCAL INFILE 'data2019/mecaniciens.xml' IGNORE
INTO TABLE `MECHANIC_ADDRESS`
ROWS IDENTIFIED BY '<address>';

SELECT '<LOAD SCOOTERS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/scooters.csv' IGNORE
INTO TABLE `SCOOTERS`
FIELDS TERMINATED BY ';' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(`scooterID`,`commissioningDate`,`modelNumber`,@var,`batteryLevel`)
SET `complainState` = IF(@var = 'False',0,1);

SELECT '<LOAD COMPLAINS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reparations.csv' IGNORE
INTO TABLE `COMPLAINS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(`scooterID`, `userID`, @dummy, `date`, @dummy);

SELECT '<LOAD REPARATIONS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reparations.csv' IGNORE
INTO TABLE `REPARATIONS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

SELECT '<LOAD RELOADS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reloads.csv' IGNORE
INTO TABLE `RELOADS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(`scooterID`,`userID`,`initialLoad`,`finalLoad`,@x, @y, @i , @j,`starttime`,`endtime`)
SET sourceX = @y, sourceY = @x, destinationX = @j, destinationY = @i;

SELECT '<LOAD TRIPS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/trips.csv' IGNORE
INTO TABLE `TRIPS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(`scooterID`,`userID`,@x, @y, @i , @j ,`starttime`,`endtime`)
SET sourceX = @y, sourceY = @x, destinationX = @j, destinationY = @i;

/*
CREATE TABLE IF NOT EXISTS `REGISTRED_USERS`
( `ID` int unsigned NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `bankaccount` varchar(16) NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

CREATE TABLE IF NOT EXISTS `REGISTRED_ADDRESS`
( `ID` int unsigned NOT NULL,
  `city` varchar(30) NOT NULL,
  `cp` int NOT NULL,
  `street` varchar(40) NOT NULL,
  `number` int NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml'
INTO TABLE `REGISTRED_USERS`
ROWS IDENTIFIED BY '<user>';

LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml'
INTO TABLE `REGISTRED_ADDRESS`
ROWS IDENTIFIED BY '<address>';

CREATE TABLE IF NOT EXISTS `ANONYME_USERS`
( `ID`  int unsigned NOT NULL,
  `password` varchar(64) NOT NULL,
  `bankaccount` varchar(16) NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

LOAD XML LOCAL INFILE 'data2019/anonyme_users.xml'
INTO TABLE `ANONYME_USERS`
ROWS IDENTIFIED BY '<user>';

CREATE TABLE IF NOT EXISTS `ALL_USERS`
( `ID` int unsigned NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

INSERT INTO ALL_USERS (`ID`) SELECT `ID` FROM REGISTRED_USERS;
INSERT INTO ALL_USERS (`ID`) SELECT `ID` FROM ANONYME_USERS;
*/
