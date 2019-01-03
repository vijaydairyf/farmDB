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
  Milking_Position_ID int,
  FOREIGN KEY (milking_station_ID) REFERENCES milking_station(milking_station_ID)
);

CREATE TABLE afi_weight(
  afi_weight_id int primary key,
  production_date DATE,
  last_fix_date DATE,
  Milking_Position_ID int,
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
  GPS_Coordinates float(12),
  country varchar(50)
);

CREATE TABLE holshtein_cow(
  afi_tag_id int primary key,
  FOREIGN KEY (afi_tag_id) REFERENCES afi_tag(afi_tag_id),
  age int,
  parturitions int,
  gynecologic_status varchar(50),
  num_of_milkings int,
  generation int
);

CREATE TABLE angus_cow(
  afi_tag_id int primary key ,
  FOREIGN KEY (afi_tag_id) REFERENCES afi_tag(afi_tag_id),
  age int,
  parturitions int,
  gynecologic_status varchar(50),
  num_of_milkings int,
  Foot_size int
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
  Milking_Position_ID int,
  Dairy_Farm_ID int
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