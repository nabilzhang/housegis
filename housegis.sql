-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 06 月 07 日 06:59
-- 服务器版本: 5.1.47
-- PHP 版本: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `housegis`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `last_login` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_ip` varchar(20) DEFAULT NULL COMMENT '最后登录IP',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`username`, `password`, `last_login`, `last_ip`) VALUES
('admin', 'admin888', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `house`
--

CREATE TABLE IF NOT EXISTS `house` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `number` varchar(255) DEFAULT NULL COMMENT '房间号',
  `type` varchar(10) DEFAULT NULL COMMENT '性质',
  `state` varchar(255) DEFAULT NULL COMMENT '出租状态',
  `area` decimal(10,2) DEFAULT NULL COMMENT '面积',
  `structure` varchar(10) DEFAULT NULL COMMENT '户型',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `premises_id` int(11) NOT NULL DEFAULT '0' COMMENT '对应楼盘号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='房源' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `house`
--

INSERT INTO `house` (`id`, `number`, `type`, `state`, `area`, `structure`, `remark`, `premises_id`) VALUES
(2, '2-1', 'ecorent', 'unrented', '40.00', '三方', '冬暖夏凉', 3);

-- --------------------------------------------------------

--
-- 表的结构 `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(20) DEFAULT NULL COMMENT '名字',
  `now` int(11) DEFAULT NULL COMMENT '现住址',
  `want` int(11) DEFAULT NULL COMMENT '意向地址',
  `state` varchar(10) DEFAULT NULL COMMENT '配租状态',
  `description` text COMMENT '简介',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='保障对象' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `person`
--

INSERT INTO `person` (`id`, `name`, `now`, `want`, `state`, `description`) VALUES
(1, '张弼', 59, 60, 'yes', '已经租到房屋'),
(2, '李四', 61, 62, 'not', '住房保障制度真好'),
(3, '王五', 63, 64, 'not', '符合申请条件');

-- --------------------------------------------------------

--
-- 表的结构 `point`
--

CREATE TABLE IF NOT EXISTS `point` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `longitude` decimal(11,8) DEFAULT NULL COMMENT '经度',
  `latitude` decimal(11,8) DEFAULT NULL COMMENT '纬度',
  `zoom` int(11) DEFAULT NULL COMMENT '缩放级别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='地图标注点' AUTO_INCREMENT=67 ;

--
-- 转存表中的数据 `point`
--

INSERT INTO `point` (`id`, `longitude`, `latitude`, `zoom`) VALUES
(30, '114.42345800', '30.52015700', 15),
(31, '114.41533700', '30.51810400', 15),
(32, '114.41609200', '30.51916200', 15),
(33, '114.45425200', '30.52214800', 15),
(34, '114.41828400', '30.51916200', 15),
(35, '114.42690700', '30.52700100', 15),
(38, '114.42604500', '30.51630000', 15),
(39, '114.43790300', '30.51742000', 15),
(40, '114.41325300', '30.51262800', 15),
(41, '114.42123000', '30.51244200', 15),
(42, '114.44386700', '30.51294000', 15),
(43, '114.43057300', '30.50839700', 15),
(44, '114.39040000', '30.50236100', 15),
(45, '114.40897700', '30.50192500', 15),
(46, '114.40822300', '30.50845900', 15),
(51, '114.41878700', '30.51586400', 15),
(52, '114.41285800', '30.52961500', 15),
(53, '114.40445000', '30.51661100', 15),
(54, '114.40207800', '30.50447700', 15),
(59, '114.41846300', '30.52606800', 15),
(60, '114.41170800', '30.52171300', 15),
(61, '114.45098200', '30.52893100', 15),
(62, '114.44990400', '30.52457500', 15),
(63, '114.42996200', '30.51779300', 15),
(64, '114.38964600', '30.52308200', 15),
(65, '114.40340800', '30.52656600', 15),
(66, '114.38745400', '30.51829100', 15);

-- --------------------------------------------------------

--
-- 表的结构 `premises`
--

CREATE TABLE IF NOT EXISTS `premises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `description` text COMMENT '描述',
  `point_id` int(11) DEFAULT NULL COMMENT '对应标注点',
  `project_id` int(11) DEFAULT NULL COMMENT '所属项目(内容)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='楼盘' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `premises`
--

INSERT INTO `premises` (`id`, `name`, `description`, `point_id`, `project_id`) VALUES
(2, '九栋', '好房子', 51, 9),
(3, '洪桐苑', '不错的房子啊', 52, 12),
(4, '二期五栋', '规划得挺好啊', 53, 9),
(5, '楼盘七', '依山傍水', 54, 9),
(6, '楼盘4', '楼盘4', 66, 9);

-- --------------------------------------------------------

--
-- 表的结构 `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `description` text COMMENT '描述',
  `type` varchar(10) NOT NULL,
  `point_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='项目' AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `type`, `point_id`) VALUES
(7, '江边二期', '楼房坚固,在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'built', 30),
(8, '福禄院', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'built', 31),
(9, '未知项目', '在建未知项目', 'built', 32),
(10, '江江胡同', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'built', 33),
(11, '在建项目一', '在建保障房项目', 'building', 34),
(12, '规划项目1', '以后应该就在这里建新的', 'build', 35),
(15, '项目', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'built', 38),
(16, '三期项目', '三期项目描述', 'built', 39),
(17, '测试在建项目', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'building', 40),
(18, '测试规划项目', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'build', 41),
(19, '规划的项目9', '规划的项目9 描述', 'build', 42),
(20, '再规划', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'build', 43),
(21, '风铃度', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'built', 44),
(22, '关山风情', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'built', 45),
(23, '站边情', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'built', 46),
(24, '新项目', '在市场经济条件下，为了保障每个人都有房子住，政府要实施一些特殊的政策措施，帮助单纯依靠市场解决住房有困难的群体。', 'built', 65);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
