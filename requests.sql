USE scooterDB;

/*R1*/
SELECT s.`scooterID`, s.`locationX`, s.`locationY`
FROM `SCOOTERS` s
WHERE s.`availability` = 'available';

/*R2*/
CREATE VIEW  INTERSECTION_RELOADS_TRIPS (userID,scooterID) AS
SELECT DISTINCT userID, scooterID
FROM RELOADS
INNER JOIN TRIPS USING (userID,scooterID);

CREATE VIEW  UNION_RELOADS_AND_INTERSECTION_RELOADS_TRIPS (userID,scooterID) AS
SELECT userID,scooterID
FROM RELOADS
UNION ALL
SELECT userID,scooterID
FROM INTERSECTION_RELOADS_TRIPS;

CREATE VIEW  DIFFERENCE_RELOADS_AND_INTERSECTION_RELOADS_TRIPS (userID) AS
SELECT DISTINCT userID
FROM UNION_RELOADS_AND_INTERSECTION_RELOADS_TRIPS
GROUP BY userID, scooterID
HAVING COUNT(*) = 1;

SELECT DISTINCT userID
FROM RELOADS
WHERE userID NOT IN ( SELECT userID
                      FROM DIFFERENCE_RELOADS_AND_INTERSECTION_RELOADS_TRIPS);

/*R3*/
SELECT T.`scooterID`
FROM `TRIPS` T
GROUP BY T.`scooterID`
HAVING sum(ST_Distance_Sphere(point(T.`sourceX`, T.`sourceY`), point(T.`destinationX`, T.`destinationY`)))

       =( SELECT max(`Distance`)
          FROM ( SELECT S.`scooterID`, sum(ST_Distance_Sphere(point(S.`sourceX`, S.`sourceY`), point(S.`destinationX`, S.`destinationY`))) as `Distance`
                 FROM `TRIPS` S
                 GROUP BY S.`scooterID`
                ) SUM_SCOOTER_DISTANCE
        );

/*R4*/
SELECT `scooterID`
FROM `REPARATIONS`
GROUP BY `scooterID`
HAVING count(`scooterID`) >= 10;

/*R5*/
SELECT `userID`,
        count(`userID`) as `Total trips`,
        SEC_TO_TIME(AVG(TIME_TO_SEC(`duration`))) as `Average duration`,
        sum(`price`) AS `Total amount (€)`
FROM `TRIPS`
GROUP BY `userID`
HAVING count(`userID`) >= 10;
