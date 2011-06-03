LOAD DATA LOCAL INFILE 'applications.csv'
INTO TABLE applications
FIELDS TERMINATED BY ','
     OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

UPDATE applications SET lat = NULL WHERE lat = 0;
UPDATE applications SET lng = NULL WHERE lng = 0;
UPDATE applications SET tweet_id = '1';
