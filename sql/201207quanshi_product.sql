-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 13, 2012 at 02:35 PM
-- Server version: 5.5.22
-- PHP Version: 5.3.10-1ubuntu3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `201205quanshi_product`
--

CREATE TABLE IF NOT EXISTS `201207quanshi_product` (
  `id` 	    int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',

  `uid`	    bigint(64) unsigned NOT NULL DEFAULT '0' COMMENT 'user id',
  `uname`   varchar(64) NOT NULL DEFAULT '' COMMENT 'user name',
  `purl`    varchar(256) NOT NULL DEFAULT '' COMMENT 'user profile',
  `a50`     varchar(64) NOT NULL DEFAULT '' COMMENT 'user avatar',

  `title`   varchar(1024) NOT NULL DEFAULT '' COMMENT 'product title',
  `text`    varchar(5120) NOT NULL DEFAULT '' COMMENT 'product text',

  `small`   varchar(255) NOT NULL DEFAULT '' COMMENT 'thumbnail weibo image',
  `big`     varchar(255) NOT NULL DEFAULT '' COMMENT 'original weibo image',

  `ip` 	    varchar(64) NOT NULL DEFAULT '' COMMENT 'source ip',
  `page`    varchar(255) NOT NULL DEFAULT '' COMMENT 'post page',
  `source`  varchar(255) NOT NULL DEFAULT '' COMMENT 'weibo source',

  `time`    int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `fdate`   date NOT NULL DEFAULT '0000-00-00' COMMENT '添加日期',
  `ftime`   datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `status`  tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',

  PRIMARY KEY (`id`),
  KEY `idx_text` (`text`),
  KEY `idx_title` (`title`),
  KEY `idx_fdate` (`fdate`),
  KEY `idx_ftime` (`ftime`),
  KEY `idx_uid` (`uid`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='hd duanyong@201207quanshi_product' AUTO_INCREMENT=1 ;
