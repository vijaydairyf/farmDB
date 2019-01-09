CREATE TABLE dairy_farm(
 dairy_farm_ID int primary key,
 name varchar(50),
 country varchar(50)
);
CREATE TABLE milking_station(
 milking_station_ID int primary key,
 GPS_coordinates DECIMAL(6,6)
);
CREATE TABLE sensor(
 sensor_ID int primary key,
 production_date DATE,
 last_fix_date DATE,
);
CREATE TABLE afi_tag(
 afi_tag_id int primary key,
 num_of_steps int,
 hours_of_chill int,
 FOREIGN KEY (afi_tag_id) REFERENCES sensor(sensor_ID)
);
CREATE TABLE afi_lab(
 afi_lab_id int primary key,
 milking_station_ID int,
 FOREIGN KEY (milking_station_ID) REFERENCES milking_station(milking_station_ID),
 FOREIGN KEY (afi_lab_id) REFERENCES sensor(sensor_ID)
);
CREATE TABLE afi_weight(
 afi_weight_id int primary key,
 milking_station_ID int,
 FOREIGN KEY (milking_station_ID) REFERENCES milking_station(milking_station_ID),
 FOREIGN KEY (afi_weight_id) REFERENCES sensor(sensor_ID)
);
CREATE TABLE iron_bracelet(
 afi_tag_id int primary key,
 activation_date DATE,
 km_avg_yearly float,
 production DATE
);
CREATE TABLE cow(
 afi_tag_id int primary key,
 FOREIGN KEY (afi_tag_id) REFERENCES afi_tag(afi_tag_id),
 date_of_birth DATE,
 num_children int,
 gynecologic_status varchar(50),
 num_of_milkings int,
 last_milk_date DATE
);
CREATE TABLE holshtein_cow(
 afi_tag_id int primary key,
 FOREIGN KEY (afi_tag_id) REFERENCES cow(afi_tag_id),
 generation int
);
CREATE TABLE angus_cow(
 afi_tag_id int primary key ,
 FOREIGN KEY (afi_tag_id) REFERENCES cow(afi_tag_id),
 foot_size int
);
CREATE TABLE milking(
 milking_station_ID int,
 afi_tag_id int,
 PRIMARY KEY (milking_station_ID, afi_tag_id),
 cow_weight_after_milking int,
 milk_amount int,
 protein_amount int,
 fat_amount int,
 milk_conductivity float,
 milking_date DATE,
 milking_hour INT CHECK (milking_hour>0 and milking_hour<=24)
);
CREATE TABLE in_dairy_farm(
 afi_lab_id int,
 afi_weight_id int,
 milking_station_ID int,
 PRIMARY KEY (afi_lab_id, afi_weight_id, milking_station_ID),
 dairy_farm_ID int
);

/* first view of milkings per cow with date */
CREATE VIEW milking_by_id
 AS
 SELECT m.afi_tag_id,m.milking_date,
 m.cow_weight_after_milking, m.milk_amount,
 m.protein_amount, m.fat_amount, m.milk_conductivity
 FROM milking m
 WHERE m.milking_date=(select max(m2.milking_date)
 from milking m2
where m.afi_tag_id=m2.afi_tag_id)

CREATE VIEW heat_map
 AS
 SELECT milking_station_ID, milking_hour,
 SUM(milk_amount) as milk_amount,
 SUM(protein_amount)as protein_amount,
 SUM(fat_amount)as fat_amount,
 COUNT(*) as num_cows
 FROM milking
 GROUP BY milking_station_ID, milking_hour