CREATE TABLE `calenders`.`Customers` ( 
        `Name` TEXT NOT NULL ,
        `Phone` TEXT NULL ,
        `Location` TEXT NULL ,
        `Gallery` TEXT NULL  DEFAULT '0',
        `Adate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        `ID` INT NOT NULL AUTO_INCREMENT ,
        PRIMARY KEY (`ID`)
    ) ENGINE = InnoDB;

CREATE TABLE `calenders`.`Calenders` ( 
        `Name` TEXT NOT NULL ,
        `Comment` TEXT  NULL ,
        `Adate` TIMESTAMP NOT NULL ,
        `ID` INT NOT NULL AUTO_INCREMENT ,
        PRIMARY KEY (`ID`)
    ) ENGINE = InnoDB;

CREATE TABLE `calenders`.`Orders` (
        `Customer` INT NOT NULL ,
        `Calender` INT NOT NULL ,
        `Number` INT NOT NULL DEFAULT '0',
        `Delivered` INT NOT NULL DEFAULT '0',
        `Paid` INT NOT NULL DEFAULT '0',
        `Date` DATE NOT NULL ,
        `Adate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        `ID` INT NOT NULL AUTO_INCREMENT ,
        PRIMARY KEY (`ID`)
    ) ENGINE = InnoDB;

CREATE TABLE `calenders`.`Purchases` ( 
        `Calender` INT NOT NULL ,
        `Number` INT NOT NULL ,
        `Amount` INT NOT NULL ,
        `Adate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        `ID` INT NOT NULL AUTO_INCREMENT ,
        PRIMARY KEY (`ID`)
    ) ENGINE = InnoDB;


CREATE TABLE `calenders`.`Auth` 
 (
    `Token` TEXT NOT NULL , 
    `Ip` TEXT NOT NULL , 
    `Expire` TEXT NOT NULL , 
    `User` TEXT NULL , 
    `Adate` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `ID` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

CREATE TABLE `calenders`.`Users` 
  ( `User` TEXT NOT NULL , 
    `Pass` TEXT NOT NULL , 
    `Adate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `ID` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

INSERT INTO `Users` (`User`, `Pass`, `Adate`, `ID`) VALUES ('Admin', '--ADD_YOUR_PASSWORD_HASH_HERE--', CURRENT_TIMESTAMP, NULL);
