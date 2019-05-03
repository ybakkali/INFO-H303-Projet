use scooterDB;

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
*/

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
