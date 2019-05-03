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

UPDATE `SCOOTERS`
SET `availability` = 1;


/*R1*/
SELECT s.`scooterID`, s.`locationX`, s.`locationY`
FROM `SCOOTERS` s;


/*R2 (Si c'est: -> La liste des utilisateurs ayant utilisé toutes les trottinettes qu'ils ont rechargées.)*/

SELECT DISTINCT rl.`userID`
FROM `RELOADS` rl, `TRIPS` tr
WHERE rl.`userID` = tr.`userID`
ORDER BY rl.`userID`;


/*R3*/
SELECT tr.`scooterID`
FROM `TRIPS` tr
WHERE ((power(power(tr.`destinationY`-tr.`sourceY`,2)+power(tr.`destinationX`-tr.`sourceX`,2),0.5)) =
       ( SELECT max(power(power(t.`destinationY`-t.`sourceY`,2)+power(t.`destinationX`-t.`sourceX`,2),0.5))
         FROM `TRIPS` t
       ));



/*R4*/
SELECT DISTINCT rep.`scooterID`
FROM `REPARATIONS` rep
WHERE(
      SELECT count(r.`scooterID`)
      FROM `REPARATIONS` r
      WHERE r.`scooterID` = rep.`scooterID`
      GROUP BY r.`scooterID`
    ) >= 10;
