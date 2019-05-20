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
( `scooterID` int unsigned AUTO_INCREMENT NOT NULL,
  `commissioningDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modelNumber` varchar(30) NOT NULL,
  `complainState` boolean NOT NULL DEFAULT False,
  `batteryLevel` int unsigned NOT NULL DEFAULT 4,
  `locationX` DECIMAL(10, 5)  NULL,
  `locationY` DECIMAL(11, 5)  NULL,
  `lastLocationTime` DATETIME NULL DEFAULT '2017-01-01T09:00:00',
  `availability` ENUM('available','occupy','inRepair','inReload','defective') NOT NULL DEFAULT 'available',
  PRIMARY KEY(`scooterID`)
) engine = innodb;

SELECT '<CREATE COMPLAINTS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `COMPLAINTS`
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
      REFERENCES COMPLAINTS(`date`)
      ON UPDATE CASCADE
) engine = innodb;

SELECT '<CREATE RELOADS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `RELOADS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `initialLoad` int unsigned NOT NULL,
  `finalLoad` int unsigned NULL ,
  `sourceX` DECIMAL(10, 5) NOT NULL,
  `sourceY` DECIMAL(11, 5) NOT NULL,
  `destinationX` DECIMAL(10, 5) NULL,
  `destinationY` DECIMAL(11, 5) NULL,
  `starttime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endtime` DATETIME NULL,

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
  `price` DECIMAL(10, 2) NOT NULL,

  PRIMARY KEY(`userID`,`scooterID`,`starttime`),
  CONSTRAINT fk_scooter FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID)
      ON UPDATE CASCADE,
  CONSTRAINT fk_user FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
      ON UPDATE CASCADE
) engine = innodb;

SELECT '<CREATE EXTRA_PAYMENT TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `EXTRA_PAYMENT`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `price` float NOT NULL,
  `type` ENUM('reservation','penalty') NOT NULL,
  PRIMARY KEY(`userID`,`scooterID`,`date`),
  CONSTRAINT fk_scooter_4 FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID)
      ON UPDATE CASCADE,
  CONSTRAINT fk_user_4 FOREIGN KEY(userID)
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
      ELSEIF (NEW.availability = 'available' OR NEW.availability = 'inRepair')
               AND OLD.availability = 'defective' THEN
               signal sqlstate '45000'  SET MESSAGE_TEXT = 'This scooter is defective, you cannot repair it';
      END IF;
  END |

  SELECT '<CREATE EXTRA_PAYMENT TRIGGER>' AS '';
  CREATE TRIGGER EXTRA_PAYMENT_TRIGGER BEFORE INSERT ON `EXTRA_PAYMENT`
    FOR EACH ROW
    BEGIN
        set @var = (SELECT availability FROM SCOOTERS WHERE `scooterID` = NEW.scooterID);

        IF @var != "occupy" AND NEW.type = "reservation" THEN
            UPDATE `SCOOTERS` S
            SET availability = "occupy"
            WHERE S.`scooterID` = NEW.scooterID;
        ELSE
            signal sqlstate '45000'  SET MESSAGE_TEXT = 'Already reserved';
        END IF;
    END |

SELECT '<CREATE COMPLAINTS TRIGGER>' AS '';
CREATE TRIGGER COMPLAINS_TRIGGER BEFORE INSERT ON `COMPLAINTS`
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
        signal sqlstate '45000'  SET MESSAGE_TEXT = 'repaireTime < complainTime';
    ELSEIF (HOUR(TIME(NEW.repaireTime)) NOT BETWEEN 22 AND 23)
            AND (HOUR(TIME(NEW.repaireTime)) NOT BETWEEN 0 AND 7)
            THEN
                signal sqlstate '45000'  SET MESSAGE_TEXT = 'A reparation should be between 22:00 and 7:00';
    ELSE
        UPDATE `COMPLAINTS` C
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

  set @var = (SELECT availability FROM SCOOTERS WHERE `scooterID` = NEW.scooterID);

  IF @var = "inReload" THEN
    signal sqlstate '45000'  SET MESSAGE_TEXT = 'Already in reload';

  ELSEIF NEW.endtime IS NULL THEN
        IF (HOUR(TIME(NEW.starttime)) NOT BETWEEN 22 AND 23)
            AND (HOUR(TIME(NEW.starttime)) NOT BETWEEN 0 AND 7)
            THEN
                signal sqlstate '45000'  SET MESSAGE_TEXT = 'A reload should be between 22:00 and 7:00';
        ELSE
             UPDATE `SCOOTERS` S
             SET availability = "inReload"
             WHERE S.`scooterID` = NEW.scooterID;

              SET NEW.initialLoad = (SELECT batteryLevel FROM SCOOTERS WHERE scooterID = NEW.scooterID),
                  NEW.sourceX = (SELECT locationX FROM SCOOTERS WHERE scooterID = NEW.scooterID),
                  NEW.sourceY = (SELECT locationY FROM SCOOTERS WHERE scooterID = NEW.scooterID);

        END IF;
  ELSEIF NEW.endtime IS NOT NULL THEN
        IF NEW.initialLoad > NEW.finalLoad AND NEW.starttime > NEW.endtime
            AND ((HOUR(TIME(NEW.starttime)) NOT BETWEEN 22 AND 23) AND (HOUR(TIME(NEW.starttime)) NOT BETWEEN 0 AND 7))
            AND ((HOUR(TIME(NEW.endtime)) NOT BETWEEN 22 AND 23) AND (HOUR(TIME(NEW.endtime)) NOT BETWEEN 0 AND 7))
            THEN
              signal sqlstate '45000'  SET MESSAGE_TEXT = 'Constraint not respected';
        ELSE
            UPDATE IGNORE `SCOOTERS` S
            SET S.`locationX` = NEW.destinationX, S.`locationY` = NEW.destinationY, S.`lastLocationTime` = NEW.endtime
            WHERE S.`scooterID` = NEW.scooterID AND NEW.endtime > S.`lastLocationTime`;
        END IF;
    END IF;
  END |

  CREATE TRIGGER RELOADS_TRIGGER_2 BEFORE UPDATE ON `RELOADS`
    FOR EACH ROW
    BEGIN

    set @var = (SELECT availability FROM SCOOTERS WHERE `scooterID` = OLD.scooterID);
    IF @var = "inReload" AND OLD.endtime IS NULL THEN
        UPDATE `SCOOTERS` S
        SET availability = "available"
        WHERE S.`scooterID` = NEW.scooterID;

        UPDATE IGNORE `SCOOTERS` S
        SET S.`locationX` = NEW.destinationX, S.`locationY` = NEW.destinationY, S.`lastLocationTime` = NEW.endtime
        WHERE S.`scooterID` = NEW.scooterID AND NEW.endtime > S.`lastLocationTime`;

        IF (HOUR(TIME(NEW.endtime)) NOT BETWEEN 22 AND 23) AND (HOUR(TIME(NEW.endtime)) NOT BETWEEN 0 AND 7) THEN
            INSERT INTO `EXTRA_PAYMENT` (scooterID,userID,price,type)
            VALUES (NEW.scooterID, NEW.userID, 20, "penalty");
        END IF;

    ELSE
        signal sqlstate '45000'  SET MESSAGE_TEXT = 'Not in reload';
    END IF;
    END |

SELECT '<CREATE TRIPS TRIGGER>' AS '';
CREATE TRIGGER TRIPS_TRIGGER BEFORE INSERT ON `TRIPS`
  FOR EACH ROW
  BEGIN
    IF NEW.starttime > NEW.endtime THEN
          signal sqlstate '45000'  SET MESSAGE_TEXT = 'endtime < starttime';
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
(@SID,`commissioningDate`,`modelNumber`,@var,`batteryLevel`)
SET `scooterID` = @SID + 1,`complainState` = IF(@var = 'False',0,1);

SELECT '<LOAD COMPLAINTS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reparations.csv' IGNORE
INTO TABLE `COMPLAINTS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@SID, `userID`, @dummy, `date`, @dummy)
SET `scooterID` = @SID + 1;

SELECT '<LOAD REPARATIONS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reparations.csv' IGNORE
INTO TABLE `REPARATIONS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@SID, `userID`, `mechanicID`, `complainTime`, `repaireTime`)
SET `scooterID` = @SID + 1;

SELECT '<LOAD RELOADS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reloads.csv' IGNORE
INTO TABLE `RELOADS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@SID,`userID`,`initialLoad`,`finalLoad`,@x, @y, @i , @j,`starttime`,`endtime`)
SET `scooterID` = @SID + 1, sourceX = @y, sourceY = @x, destinationX = @j, destinationY = @i;

SELECT '<LOAD TRIPS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/trips.csv' IGNORE
INTO TABLE `TRIPS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@SID,`userID`,@x, @y, @i , @j ,`starttime`,`endtime`)
SET `scooterID` = @SID + 1, sourceX = @y, sourceY = @x, destinationX = @j, destinationY = @i;
