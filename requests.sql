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
FROM `SCOOTERS` s
WHERE s.`availability` = 1;


/*R2 (Si c'est: -> La liste des utilisateurs ayant utilisé toutes les trottinettes qu'ils ont rechargées.)*/
SELECT DISTINCT rl.`userID`
FROM `RELOADS` rl, `TRIPS` tr
WHERE rl.`userID` = tr.`userID`
ORDER BY rl.`userID`;


/*R3*/
SELECT T.`scooterID`
FROM `TRIPS` T
GROUP BY T.`scooterID`
HAVING sum(ST_Distance_Sphere(point(T.`sourceX`, T.`sourceY`),
                  point(T.`destinationX`, T.`destinationY`))) = ( SELECT max(`Distance`)
                                                                  FROM ( SELECT S.`scooterID`, sum(ST_Distance_Sphere(point(S.`sourceX`, S.`sourceY`),
                                                                                                          point(S.`destinationX`, S.`destinationY`))) as `Distance`
                                                                         FROM `TRIPS` S
                                                                         GROUP BY S.`scooterID`
                                                                        ) SUM_SCOOTER_DISTANCE
                                                                  );

/*
CREATE TABLE IF NOT EXISTS `TEST`
( `ID` int unsigned NOT NULL,
  `Distance` int unsigned NOT NULL
)

INSERT INTO TEST
VALUES (1,10),(1,15),(2,20),(3,5),(4,10),(2,10),(3,15),(1,20),(4,5),(8,10);

SELECT T.ID
FROM TEST T
GROUP BY T.ID
HAVING sum(T.distance) = (SELECT min(`D`)
                          FROM ( SELECT t.ID, sum(t.distance) as `D`
                                 FROM TEST t
                                 GROUP BY t.ID
                                ) K
                       );
*/
/*R4*/
SELECT DISTINCT rep.`scooterID`
FROM `REPARATIONS` rep
WHERE(
      SELECT count(r.`scooterID`)
      FROM `REPARATIONS` r
      WHERE r.`scooterID` = rep.`scooterID`
      GROUP BY r.`scooterID`
    ) >= 10;


/*R5
