CREATE TABLE Farm(
FarmId int,
Name varchar(40),
FarmSize int,
Longitude float,
Latitude float,
PRIMARY KEY(FarmId)
);


create table SensorData(
CowId int,
FarmId int,
Lactation_Num int,
Age int,
DateOfSample datetime,
Yield float,
Conductivity float,
Fat float,
Protein float,
Lactose float,
Milking_Time float,
RestTime float,
RestBout float,
PRIMARY KEY(CowId,FarmId,DateOfSample),
FOREIGN KEY (FarmId) references Farm
);

