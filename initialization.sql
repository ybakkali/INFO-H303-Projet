CREATE TABLE `REGISTRED_USERS`
( `ID` int NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `phone` varchar(35) NOT NULL,
  `address` varchar(50) NOT NULL,
  `bankaccount` varchar(16) NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

CREATE TABLE `ANONYME_USERS`
( `ID` int NOT NULL,
  `password` varchar(64) NOT NULL,
  `bankaccount` varchar(16) NOT NULL,
  PRIMARY KEY(`ID`)
) engine = innodb;

LOAD XML LOCAL INFILE 'data2019/anonyme_users.xml'
INTO TABLE `scooterDB`.`ANONYME_USERS`
ROWS IDENTIFIED BY '<user>'
-- (@Buyer, @Seller, @Time, @Rate)
-- SET `rate`=@Rate , `date`=@Time, `buyerID`=@Buyer, `sellerID`=@Seller;

-- mysql -u yahyabakkali -p scooterDB
-- mysql> source /home/yahyabakkali/Bureau/INFO-H303/BDD/initialization.sql;
