CREATE TABLE afi_tag(
  afi_tag_id int primary key,
  production_date DATE,
  last_fix_date DATE,
  num_of_steps int,
  hours_of_chill int
);

CREATE TABLE afi_lab(
  afi_lab_id int primary key,
  production_date DATE,
  last_fix_date DATE,
  milking_station_ID int,
  FOREIGN KEY (milking_station_ID) REFERENCES milking_station(milking_station_ID)
);

CREATE TABLE afi_weight(
  afi_weight_id int primary key,
  production_date DATE,
  last_fix_date DATE,
  milking_station_ID int,
  FOREIGN KEY (milking_station_ID) REFERENCES milking_station(milking_station_ID)
);

CREATE TABLE iron_bracelet(
  afi_tag_id int primary key,
  years_running int,
  km_avg_yearly float,
  production DATE

);

CREATE TABLE dairy_farm(
  dairy_farm_ID int primary key,
  name varchar(50),
  country varchar(50)
);

CREATE TABLE milking_station(
  milking_station_ID int primary key,
  GPS_coordinates float(12),
  country varchar(50)
);


/* TODO: add an constraint that only after num_of_milkings>5 the milking is recorded*/
CREATE TABLE holshtein_cow(
  afi_tag_id int primary key,
  FOREIGN KEY (afi_tag_id) REFERENCES afi_tag(afi_tag_id),
  age int,
  parturitions int,
  gynecologic_status varchar(50),
  num_of_milkings int,
  last_milk_date DATE,
  generation int
);

CREATE TABLE angus_cow(
  afi_tag_id int primary key ,
  FOREIGN KEY (afi_tag_id) REFERENCES afi_tag(afi_tag_id),
  age int,
  parturitions int,
  gynecologic_status varchar(50),
  num_of_milkings int,
  last_milk_date DATE,
  foot_size int
);

CREATE TABLE milking(
  CONSTRAINT PK_milking PRIMARY KEY (milking_station_ID, afi_tag_id),
  cow_weight_after_milking int,
  milk_amount int,
  protein_amount int,
  fat_amount int,
  milk_conductivity float
);


CREATE TABLE in_dairy_farm(
  afi_lab_id int,
  afi_weight_id int,
  milking_station_ID int,
  dairy_farm_ID int
  CONSTRAINT PK_in_dairy_farm PRIMARY KEY (afi_lab_id, afi_weight_id, milking_station_ID)
)

DROP TABLE milking;
DROP TABLE in_dairy_farm;
DROP TABLE wearing;
DROP TABLE is_in;
DROP TABLE angus_cow;
DROP TABLE holshtein_cow;
DROP TABLE milking_station;
DROP TABLE dairy_farm;
DROP TABLE iron_bracelet;
DROP TABLE afi_weight;
DROP TABLE afi_lab;
DROP TABLE afi_tag;

/* first view of milkings per cow with date */
CREATE VIEW milking_by_id
AS
SELECT afi_tag_id, last_milk_date,
       cow_weight_after_milking, milk_amount
       protein_amount, fat_amount, milk_conductivity
FROM angus_cow, holshtein_cow, milking
GROUP BY afi_tag_id

SELECT *
FROM milking_by_id
WHERE afi_tag_id = 100
