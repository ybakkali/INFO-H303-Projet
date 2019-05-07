-- (scooter,user,mechanic,@complainTime,@repaireTime)
-- SET complainTime = STR_TO_DATE(@expired_date, '%m/%d/%Y');

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

SELECT '<LOAD ALL_USERS TABLE>' AS '';
LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml'
INTO TABLE `scooterDB`.`ALL_USERS`
ROWS IDENTIFIED BY '<user>';

LOAD XML LOCAL INFILE 'data2019/anonyme_users.xml'
INTO TABLE `scooterDB`.`ALL_USERS`
ROWS IDENTIFIED BY '<user>';

SELECT '<CREATE USER_ADDRESS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `USER_ADDRESS`
( `ID` int unsigned NOT NULL,
  `city` varchar(30) NOT NULL,
  `cp` int NOT NULL,
  `street` varchar(40) NOT NULL,
  `number` int NOT NULL,

  PRIMARY KEY(`ID`),
  CONSTRAINT fk_userID FOREIGN KEY(ID)
      REFERENCES ALL_USERS(ID)
) engine = innodb;

SELECT '<LOAD USER_ADDRESS TABLE>' AS '';
LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml'
INTO TABLE `scooterDB`.`USER_ADDRESS`
ROWS IDENTIFIED BY '<address>';

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

SELECT '<LOAD MECANICIENS TABLE>' AS '';
LOAD XML LOCAL INFILE 'data2019/mecaniciens.xml'
INTO TABLE `scooterDB`.`MECANICIENS`
ROWS IDENTIFIED BY '<mechanic>';

SELECT '<CREATE MECHANIC_ADDRESS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `MECHANIC_ADDRESS`
( `mechanicID` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `cp` int NOT NULL,
  `street` varchar(40) NOT NULL,
  `number` int NOT NULL,

  PRIMARY KEY(`mechanicID`),
  CONSTRAINT fk_mechanicID FOREIGN KEY(mechanicID)
      REFERENCES MECANICIENS(mechanicID)
) engine = innodb;

SELECT '<LOAD MECHANIC_ADDRESS TABLE>' AS '';
LOAD XML LOCAL INFILE 'data2019/mecaniciens.xml'
INTO TABLE `scooterDB`.`MECHANIC_ADDRESS`
ROWS IDENTIFIED BY '<address>';

SELECT '<CREATE SCOOTERS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `SCOOTERS`
( `scooterID` int unsigned NOT NULL,
  `commissioningDate` DATETIME NOT NULL,
  `modelNumber` varchar(30) NOT NULL,
  `complainState` boolean NOT NULL,
  `batteryLevel` int NOT NULL,
  `locationX` float  NOT NULL DEFAULT 0,
  `locationY` float  NOT NULL DEFAULT 0,
  `availability` boolean NOT NULL DEFAULT True,
  PRIMARY KEY(`scooterID`)
) engine = innodb;

SELECT '<LOAD SCOOTERS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/scooters.csv'
INTO TABLE `scooterDB`.`SCOOTERS`
FIELDS TERMINATED BY ';' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

SELECT '<CREATE COMPLAINS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `COMPLAINS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(250) NOT NULL DEFAULT '',

  INDEX(`date`),
  PRIMARY KEY(`userID`,`scooterID`,`date`),
  CONSTRAINT fk_scooter_3 FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID),
  CONSTRAINT fk_user_3 FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
) engine = innodb;

SELECT '<LOAD COMPLAINS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reparations.csv'
INTO TABLE `scooterDB`.`COMPLAINS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(`scooterID`, `userID`, @dummy, `date`, @dummy);

SELECT '<CREATE REPARATIONS TABLE>' AS '';
CREATE TABLE IF NOT EXISTS `REPARATIONS`
( `scooterID` int unsigned NOT NULL,
  `userID` int unsigned NOT NULL,
  `mechanicID` varchar(30) NOT NULL,
  `complainTime` DATETIME NOT NULL,
  `repaireTime` DATETIME NOT NULL,

  PRIMARY KEY(`userID`,`scooterID`,`mechanicID`,`complainTime`),
  CONSTRAINT fk_scooter_1 FOREIGN KEY(`scooterID`)
      REFERENCES SCOOTERS(`scooterID`),
  CONSTRAINT fk_user_1 FOREIGN KEY(`userID`)
      REFERENCES ALL_USERS(`ID`),
  CONSTRAINT fk_mechanic_1 FOREIGN KEY(`mechanicID`)
      REFERENCES MECANICIENS(`mechanicID`),
  FOREIGN KEY(`complainTime`)
      REFERENCES COMPLAINS(`date`)
) engine = innodb;

SELECT '<LOAD REPARATIONS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reparations.csv'
INTO TABLE `scooterDB`.`REPARATIONS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

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
      REFERENCES SCOOTERS(scooterID),
  CONSTRAINT fk_user_2 FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
) engine = innodb;

SELECT '<LOAD RELOADS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/reloads.csv'
INTO TABLE `scooterDB`.`RELOADS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

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
  `duration` TIME AS (TIMEDIFF(`endtime`, `starttime`)),
  `price` float AS (1 + (HOUR(`duration`) DIV 24) * 36 +  (HOUR(`duration`) % 24) * 6.5 + MINUTE(`duration`) * 0.15),

  INDEX(scooterID, endtime,destinationX),
  INDEX(userID),
  PRIMARY KEY(`userID`,`scooterID`,`starttime`),
  CONSTRAINT fk_scooter FOREIGN KEY(scooterID)
      REFERENCES SCOOTERS(scooterID),
  CONSTRAINT fk_user FOREIGN KEY(userID)
      REFERENCES ALL_USERS(ID)
) engine = innodb;

SELECT '<LOAD TRIPS TABLE>' AS '';
LOAD DATA LOCAL INFILE 'data2019/trips.csv'
INTO TABLE `scooterDB`.`TRIPS`
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

UPDATE `SCOOTERS` S, `TRIPS` T
SET S.`locationX` = T.`destinationX`, S.`locationY` = T.`destinationY`
WHERE T.`scooterID` = S.`scooterID`
      AND (T.`scooterID`,T.`endtime`) IN
          ( SELECT `scooterID`, max(`endtime`)
            FROM `TRIPS`
            GROUP BY `scooterID`
          );
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
INTO TABLE `scooterDB`.`REGISTRED_USERS`
ROWS IDENTIFIED BY '<user>';

LOAD XML LOCAL INFILE 'data2019/registeredUsers.xml'
INTO TABLE `scooterDB`.`REGISTRED_ADDRESS`
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
*/

/*
SELECT `scooterID`, `endtime`, `destinationX`
FROM TRIPS
WHERE scooterID = 0;

SELECT `scooterID`, `endtime`, `destinationX`
FROM `TRIPS` T
WHERE `endtime` = ( SELECT max(`endtime`)
                    FROM `TRIPS` t
                    WHERE T.`scooterID` = t.`scooterID`
                  );

SELECT `scooterID`, `endtime`, `destinationX`
FROM `TRIPS`
WHERE (`scooterID`,`endtime`) IN (SELECT `scooterID`, max(`endtime`)
                               FROM `TRIPS`
                               GROUP BY `scooterID`
                               ORDER BY `scooterID`
                             );
                             UPDATE `SCOOTERS` S
                             SET `locationX` =( SELECT `destinationX`
                                                FROM `TRIPS` T
                                                WHERE T.`scooterID` = S.`scooterID`
                                                      AND (`scooterID`,`endtime`) IN
                                                          ( SELECT `scooterID`, max(`endtime`)
                                                            FROM `TRIPS`
                                                            GROUP BY `scooterID`
                                                            ORDER BY `scooterID`
                                                          )
                                               );

UPDATE `SCOOTERS` S
SET `locationY` =( SELECT `destinationY`
                  FROM `TRIPS` T
                  WHERE T.`scooterID` = S.`scooterID`
                        AND (`scooterID`,`endtime`) IN
                            ( SELECT `scooterID`, max(`endtime`)
                              FROM `TRIPS`
                              GROUP BY `scooterID`
                              ORDER BY `scooterID`
                            )
                 );

(CASE
WHEN HOUR(`duration`) = 0 THEN 1 + (MINUTE(`duration`) * 0.15) / 100
WHEN HOUR(`duration`) BETWEEN 1 AND 23 THEN 1 + HOUR(`duration`) * 6,5 + (MINUTE(`duration`) * 0.15) / 100
ELSE 1 + HOUR(`duration`) DIV 24 * 36 +  HOUR(`duration`) % 24 * 6.5 + (MINUTE(`duration`) * 0.15) / 100
END)
*/
