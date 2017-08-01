/*
SQLyog  v12.2.6 (64 bit)
MySQL - 10.1.21-MariaDB : Database - goods
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`goods` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `goods`;

/*Table structure for table `act` */

DROP TABLE IF EXISTS `act`;

CREATE TABLE `act` (
  `act_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act_name` varchar(50) DEFAULT NULL COMMENT '活动名',
  `start_time` int(15) DEFAULT NULL COMMENT '活动开始时间',
  `end_time` int(15) DEFAULT NULL COMMENT '活动结束时间',
  `act_type` int(1) DEFAULT '0' COMMENT '0:短期  1：长期  默认为0 当为1时，活动就不判断开始和结束时间了，即长期有效',
  `user_rank` int(20) DEFAULT NULL COMMENT '活动针对的会员级别',
  KEY `act_id` (`act_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `act` */

/*Table structure for table `attr` */

DROP TABLE IF EXISTS `attr`;

CREATE TABLE `attr` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(10) DEFAULT NULL COMMENT '属性名',
  PRIMARY KEY (`attr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `attr` */

insert  into `attr`(`attr_id`,`attr_name`) values 
(1,'颜色'),
(2,'网络类型'),
(3,'存储容量'),
(4,'CPU品牌'),
(5,'屏幕尺寸'),
(7,'分辨率');

/*Table structure for table `attr_info` */

DROP TABLE IF EXISTS `attr_info`;

CREATE TABLE `attr_info` (
  `attr_info_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性详情id',
  `attr_id` int(10) DEFAULT NULL COMMENT '属性名id',
  `attr_info_name` varchar(255) DEFAULT NULL COMMENT '属性值',
  PRIMARY KEY (`attr_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `attr_info` */

insert  into `attr_info`(`attr_info_id`,`attr_id`,`attr_info_name`) values 
(1,1,'红'),
(2,1,'橙'),
(3,1,'黄'),
(9,2,'电信'),
(10,2,'联通'),
(11,3,'16G'),
(13,3,'64G'),
(14,3,'128G'),
(15,4,'麒麟960'),
(16,4,'高通'),
(17,5,'4寸'),
(18,5,'5寸'),
(19,5,'7寸'),
(20,7,'1080'),
(21,7,'1280');

/*Table structure for table `cate_attr_info` */

DROP TABLE IF EXISTS `cate_attr_info`;

CREATE TABLE `cate_attr_info` (
  `cate_id` int(11) DEFAULT NULL COMMENT '三级分类id',
  `attr_info_id` int(11) DEFAULT NULL COMMENT 'attr_info表id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `cate_attr_info` */

/*Table structure for table `coupon` */

DROP TABLE IF EXISTS `coupon`;

CREATE TABLE `coupon` (
  `coupon_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_name` varchar(30) DEFAULT NULL COMMENT '优惠券名称',
  `start_time` varchar(20) DEFAULT NULL COMMENT '开始时间',
  `end_time` varchar(20) DEFAULT NULL COMMENT '结束时间',
  `get_limit` int(5) DEFAULT '0' COMMENT '0：一人允许领一次  1：一天领一次',
  `total` int(255) DEFAULT NULL COMMENT '优惠券总数',
  `received_num` int(255) DEFAULT NULL COMMENT '已领取的数量',
  `used_num` int(255) DEFAULT NULL COMMENT '已使用的数量',
  `add_time` int(20) DEFAULT NULL COMMENT '优惠券生成时间',
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `coupon` */

/*Table structure for table `coupon_list` */

DROP TABLE IF EXISTS `coupon_list`;

CREATE TABLE `coupon_list` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` int(255) DEFAULT NULL COMMENT '优惠券id',
  `code` varchar(20) NOT NULL COMMENT '优惠券码',
  `is_received` int(1) DEFAULT '0' COMMENT '是否被领取 0：未领取  1：已领取',
  `user_id` int(20) DEFAULT NULL COMMENT '若已被领取，存领取人id',
  `is_used` int(1) DEFAULT NULL COMMENT '是否已被使用 0：未使用  1：已使用',
  `is_freezed` int(1) DEFAULT NULL COMMENT '0：未被冻结  1：已被冻结 订单提交后，优惠券状态设为被冻结，若支付完成，解冻并设为已使用，若支付未完成，解冻，优惠券回滚为未使用',
  `receive_time` int(20) DEFAULT NULL COMMENT '领取时间',
  `use_time` int(20) DEFAULT NULL COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `coupon_list` */

/*Table structure for table `item` */

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) DEFAULT NULL COMMENT '货品名称，货品是一类商品的集合',
  `cate_id` int(100) DEFAULT NULL,
  `public_attr_ids` varchar(255) DEFAULT NULL COMMENT '公共属性id集合，对应attr_info表',
  `item_pic` varchar(255) DEFAULT NULL COMMENT '公共图片，若不选择颜色时默认选择的图片',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `item` */

insert  into `item`(`item_id`,`item_name`,`cate_id`,`public_attr_ids`,`item_pic`) values 
(1,'华为P10',20,'15,17,20',NULL),
(2,'OPPOR11',20,'16,18,21',NULL);

/*Table structure for table `promotion_rule` */

DROP TABLE IF EXISTS `promotion_rule`;

CREATE TABLE `promotion_rule` (
  `rule_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rule_name` varchar(50) DEFAULT NULL COMMENT '规则名称',
  `rule_relation` int(1) DEFAULT NULL COMMENT '规则间的关系  0：择优匹配  1：排他（只有当前规则生效） 2：并存（折上折）',
  `rule_rank` int(10) DEFAULT NULL COMMENT '规则的优先级 当择优匹配时，该值越大，优先级越高，优先使用该规则',
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `promotion_rule` */

/*Table structure for table `promotion_rule_set` */

DROP TABLE IF EXISTS `promotion_rule_set`;

CREATE TABLE `promotion_rule_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(11) DEFAULT NULL COMMENT '活动id',
  `coupon_id` int(50) DEFAULT NULL COMMENT '优惠券id',
  `rule_id` int(11) DEFAULT NULL COMMENT '规则id',
  `meet` int(11) DEFAULT NULL COMMENT '满 ：金额  或满：数量',
  `save` varchar(20) DEFAULT NULL COMMENT '减 ：金额  或打几折  例：95%',
  `gift_type` int(1) DEFAULT NULL COMMENT '0:赠品是商品 1：赠品是优惠券',
  `gift` varchar(255) DEFAULT NULL COMMENT '若是优惠券，值为coupon表的id；若是赠品，值为赠品id',
  `free_shipping` smallint(1) DEFAULT NULL COMMENT '是否包邮，0：不包邮  1：包邮',
  `discounted_price` float DEFAULT NULL COMMENT '直接设置折后价',
  `field` smallint(1) DEFAULT NULL COMMENT '规则的对象是单品还是整单，单品：0  整单：1',
  `goods_ids` int(20) DEFAULT NULL COMMENT '活动商品id，一条规则对应一个商品或一组商品（秒杀商品需要单独设定秒杀价）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='\r\n\r\n活动跟优惠券共用的表；\r\n\r\n当meet有值时，就是满减或满赠，当meet为空时，就是无条件降价或折扣。';

/*Data for the table `promotion_rule_set` */

/*Table structure for table `sku` */

DROP TABLE IF EXISTS `sku`;

CREATE TABLE `sku` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `item_id` int(10) DEFAULT NULL COMMENT '货品id',
  `sku_attr_ids` varchar(255) DEFAULT NULL COMMENT '商品sku属性值的集合,对应attr_info表',
  `price` float(10,0) DEFAULT NULL COMMENT '商品价格',
  `inventory` int(10) DEFAULT NULL COMMENT '商品库存',
  `sale_num` int(10) DEFAULT NULL COMMENT '商品销量',
  `goods_pic` varchar(255) DEFAULT NULL COMMENT '商品图片，一个颜色对应一张',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `sku` */

insert  into `sku`(`goods_id`,`item_id`,`sku_attr_ids`,`price`,`inventory`,`sale_num`,`goods_pic`) values 
(1,1,'1,9,11',3000,1200,NULL,NULL),
(2,1,'2,9,11',3000,900,NULL,NULL),
(3,1,'3,9,11',3000,600,NULL,NULL),
(8,1,'1,10,11',2900,1100,NULL,NULL),
(9,1,'2,10,11',2900,300,NULL,NULL),
(10,1,'3,10,11',2900,100,NULL,NULL),
(11,1,'1,9,13',3600,2300,NULL,NULL),
(12,1,'2,9,13',3600,1500,NULL,NULL),
(13,1,'3,9,13',3600,450,NULL,NULL),
(14,1,'1,10,13',3500,1600,NULL,NULL),
(15,1,'2,10,13',3500,980,NULL,NULL),
(16,1,'3,10,13',3500,123,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
