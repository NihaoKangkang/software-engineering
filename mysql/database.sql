create database lzu;

use lzu;

create table admin(
	adm_ID char(6) not null,
	adm_pass varchar(32)
);

create table student(
	stu_ID char(12) not null,
	stu_name varchar(20),
	stu_pass varchar(32)
);

create table car(
	car_ID char(7) not null,
	car_v tinyint,
	car_driver varchar(20),
	car_phone char(11)
);
create table clock(
	car_ID char(7),
	begin_time datetime,
	begin_place varchar(10)
);
create table book(
	car_ID char(7),
	stu_ID char(12),
	begin_time datetime
);
alter table admin add primary key (adm_ID);
alter table student add primary key (stu_ID);
alter table car add primary key(car_ID);
alter table clock add primary key(car_ID, begin_time);
alter table clock add constraint car_id_clock foreign key(car_ID)references car(car_ID);
alter table book add constraint car_id_book foreign key (car_ID,begin_time)references clock(car_ID,begin_time);
alter table book add constraint stu_id_book foreign key (stu_ID)references student(stu_ID);

insert into student (stu_ID,stu_name,stu_pass) values ('132112354610', 'zhangsan', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354623', 'lisi', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354636', 'wangwu', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354649', 'tom', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354662', 'jerry', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354675', 'tim', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354688', 'ammy', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354701', 'kim', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354714', 'ketty', 'e10adc3949ba59abbe56e057f20f883e');
insert into student (stu_ID,stu_name,stu_pass) values ('132112354727', 'demasiya', 'e10adc3949ba59abbe56e057f20f883e');
insert into admin (adm_ID, adm_pass) values ('100000','e10adc3949ba59abbe56e057f20f883e');
insert into admin (adm_ID, adm_pass) values ('100001','e10adc3949ba59abbe56e057f20f883e');
insert into car (car_ID, car_v, car_driver, car_phone) values ('GA10001', 24, 'AAA', '13666667777');
insert into car (car_ID, car_v, car_driver, car_phone) values ('GA10002', 30, 'BBB', '13666667778');
insert into car (car_ID, car_v, car_driver, car_phone) values ('GA10003', 26, 'CCC', '13666667779');
insert into car (car_ID, car_v, car_driver, car_phone) values ('GA10004', 5, 'DDD', '13666667776');
insert into car (car_ID, car_v, car_driver, car_phone) values ('GA10005', 60, 'EEE', '13666667770');
insert into clock (car_ID, begin_time, begin_place) values ('GA10001', '2020/5/20  19:55:32', 'Aplace');
insert into clock (car_ID, begin_time, begin_place) values ('GA10004', '2020/5/22  01:05:32', 'Bplace');
insert into book (car_ID, stu_ID) values ('GA10001', '132112354610');
insert into book (car_ID, stu_ID) values ('GA10004', '132112354727');