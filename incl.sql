
set names utf8;

DROP DATABASE IF EXISTS incl;

CREATE DATABASE IF NOT EXISTS incl;

use incl;

DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS group_lists;
DROP TABLE IF EXISTS group_members;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS mails;
DROP TABLE IF EXISTS send_mails;
DROP TABLE IF EXISTS send_mail_states;
DROP TABLE IF EXISTS statuses;
DROP TABLE IF EXISTS auth_levels;

set foreign_key_checks = 0;

CREATE TABLE news(
	id int AUTO_INCREMENT Primary Key,
	title varchar(500),
	created datetime,
	modified datetime,
	user_id int,
	group_list_id int,
	foreign key(user_id) references users(id),
	foreign key(group_list_id) references group_lists(id)
);

CREATE TABLE group_lists(
	id int AUTO_INCREMENT Primary Key,
	name varchar(50),
	created datetime,
	modified datetime,
	maxcapa int Default 209715200,
	precapa int Default 0,
	backimg varchar(500),
	ranid varchar(100),
	user_id int,
	foreign key(user_id) references users(id)
);

CREATE TABLE group_members(
	id int AUTO_INCREMENT Primary Key,
	created datetime,
	modified datetime,
	user_id int,
	group_list_id int,
	auth_level_id int Default 1,
	foreign key(user_id) references users(id),
	foreign key(group_list_id) references group_lists(id),
	foreign key(auth_level_id) references auth_levels(id)
);

CREATE TABLE auth_levels (
	id int AUTO_INCREMENT Primary Key,
	name varchar(50),
	created datetime,
	modified datetime
);

CREATE TABLE statuses (
	id int AUTO_INCREMENT Primary Key,
	name varchar(50),
	created datetime,
	modified datetime
);

CREATE TABLE users(
	id int AUTO_INCREMENT Primary Key,
	name varchar(50),
	created datetime,
	modified datetime,
	mail varchar(50),
	password varchar(20),
	userimg varchar(500),
	backimg varchar(500),
	maxcapa int Default 209715200,
	precapa int Default 0,
	status_id int DEFAULT 1,
	randnum varchar(100),
	randid varchar(100),
	langid int DEFAULT 1,
	foreign key(status_id) references statuses(id)
);

CREATE TABLE mails (
	id int AUTO_INCREMENT Primary Key,
	title varchar(500),
	created datetime,
	modified datetime,
	text text
);

CREATE TABLE send_mail_states (
	id int AUTO_INCREMENT Primary Key,
	created datetime,
	modified datetime,
	name text,
	snum text
);

CREATE TABLE send_mails (
	id int AUTO_INCREMENT Primary Key,
	title varchar(500),
	created datetime,
	modified datetime,
	name text,
	from_name text,
	to_name text,
	to_address text,
	subject text,
	body text,
	group_code text,
	from_address text,
	finish_dt datetime,
	result boolean,
	send_date date,
	send_time time,
	fw_to text,
	server_ip text,
	server_name text,
	send_mail_state_id bigint DEFAULT 1
);

INSERT INTO mails(id,title,text) VALUES ( 1, '【incl】会員登録のご案内', '

※まだ本登録が完了しておりません※

%uad%さま
inclへ仮登録いただきまして、誠にありがとうございます。

下記URLをクリックして本登録を完了させてください。
%mail_url%

※このメールに覚えがない場合、他の方がメールアドレスを間違えて入力された可能性があります。
　URLをクリックしなければ本登録されることはございませんのでご安心ください。

━━━━━━━━━━━━━━━━
※本メールアドレスは送信専用です。返信いただいてもお答えできません。ご了承ください。
【発行元】incl
');

INSERT INTO statuses (id, created, modified, name) VALUES (1, '2015-01-01 00:00:00', '2015-01-01 00:00:00', '仮登録');
INSERT INTO statuses (id, created, modified, name) VALUES (2, '2015-01-01 00:00:00', '2015-01-01 00:00:00', '本登録');
INSERT INTO statuses (id, created, modified, name) VALUES (3, '2015-01-01 00:00:00', '2015-01-01 00:00:00', '退会済み');

INSERT INTO auth_levels (id, created, modified, name) VALUES (1, '2015-01-01 00:00:00', '2015-01-01 00:00:00', '管理者');
INSERT INTO auth_levels (id, created, modified, name) VALUES (2, '2015-01-01 00:00:00', '2015-01-01 00:00:00', '一般');

INSERT INTO send_mail_states (id, created, modified, name, snum) VALUES (1, '2014-08-28 00:00:00', '2014-08-28 00:00:00', '送信待ち', '10');
INSERT INTO send_mail_states (id, created, modified, name, snum) VALUES (2, '2014-08-28 00:00:00', '2014-08-28 00:00:00', '送信中', '20');
INSERT INTO send_mail_states (id, created, modified, name, snum) VALUES (3, '2014-08-28 00:00:00', '2014-08-28 00:00:00', '送信済み', '30');
INSERT INTO send_mail_states (id, created, modified, name, snum) VALUES (4, '2014-08-28 00:00:00', '2014-08-28 00:00:00', 'エラー', '40');


set foreign_key_checks = 1;
