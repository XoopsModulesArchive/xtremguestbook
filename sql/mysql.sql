# MySQL-Front Dump 2.4
#
# Host: localhost   Database: xoops
#--------------------------------------------------------
# Server version 3.23.40-nt


#
# Table structure for table 'xoops_xtremguestbook'
#

CREATE TABLE `xtremguestbook` (
    `xtremguestbook_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id`           INT(11)          DEFAULT NULL,
    `uname`             VARCHAR(150)     DEFAULT NULL,
    `title`             VARCHAR(150)     DEFAULT NULL,
    `message`           LONGTEXT,
    `note`              LONGTEXT,
    `post_time`         INT(10) NOT NULL DEFAULT '0',
    `email`             VARCHAR(60)      DEFAULT NULL,
    `url`               VARCHAR(100)     DEFAULT NULL,
    `poster_ip`         VARCHAR(15)      DEFAULT NULL,
    `moderate`          TINYINT(1)       DEFAULT NULL,
    PRIMARY KEY (`xtremguestbook_id`)
)
    ENGINE = ISAM;
