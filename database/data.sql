/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.27 : Database - demo_chacbumbum
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`demo_chacbumbum` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `demo_chacbumbum`;

/*Table structure for table `auth_belong` */

DROP TABLE IF EXISTS `auth_belong`;

CREATE TABLE `auth_belong` (
  `user_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `fk_belong__group_id___group__user_id` (`group_id`),
  CONSTRAINT `fk_belong__group_id___group__user_id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`),
  CONSTRAINT `fk_reference_32` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `auth_belong` */

insert  into `auth_belong`(`user_id`,`group_id`) values (1,1),(3,1),(2,4),(2,8),(3,8);

/*Table structure for table `auth_group` */

DROP TABLE IF EXISTS `auth_group`;

CREATE TABLE `auth_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `group_parent_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_group__group_parent_id___group__id` (`group_parent_id`),
  CONSTRAINT `fk_group__group_parent_id___group__id` FOREIGN KEY (`group_parent_id`) REFERENCES `auth_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `auth_group` */

insert  into `auth_group`(`id`,`name`,`description`,`group_parent_id`) values (1,'Administrator','Administrator',NULL),(2,'Webmaster','Webmaster',NULL),(3,'User','User',4),(4,'Moderator','Moderator',1),(6,'Demo',NULL,8),(8,'Demo developper','Demo developper',2);

/*Table structure for table `auth_group_permission` */

DROP TABLE IF EXISTS `auth_group_permission`;

CREATE TABLE `auth_group_permission` (
  `group_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`permission_id`),
  KEY `fk_group_permission__permission_id___permission__id` (`permission_id`),
  CONSTRAINT `fk_group_permission__group_id___group__id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`),
  CONSTRAINT `fk_group_permission__permission_id___permission__id` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `auth_group_permission` */

insert  into `auth_group_permission`(`group_id`,`permission_id`) values (4,1),(4,2),(8,2),(4,4),(8,4),(4,5),(8,5),(8,6),(8,7);

/*Table structure for table `auth_navigation` */

DROP TABLE IF EXISTS `auth_navigation`;

CREATE TABLE `auth_navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ten cua menu',
  `parent_id` int(11) DEFAULT '0' COMMENT '0 - la menu goc',
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `module` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `controller` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `router` varchar(145) COLLATE utf8_unicode_ci DEFAULT 'none',
  `params` text COLLATE utf8_unicode_ci,
  `type` varchar(45) COLLATE utf8_unicode_ci DEFAULT 'module' COMMENT 'link - module',
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL COMMENT 'active - inactive',
  `rang` int(4) DEFAULT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `auth_navigation` */

insert  into `auth_navigation`(`id`,`name`,`parent_id`,`level`,`module`,`controller`,`action`,`router`,`params`,`type`,`url`,`ordering`,`status`,`rang`,`note`) values (45,'Navigation',0,0,'admin','admin-navigation','','none',NULL,'module',NULL,NULL,1,5,''),(46,'Create',45,0,'admin','admin-navigation','create','none',NULL,'module',NULL,NULL,1,6,''),(47,'System Administration',0,0,'admin','index','','none',NULL,'module',NULL,NULL,1,0,''),(48,'User Management',47,0,'admin','admin-user-management','index','none',NULL,'module',NULL,NULL,1,3,''),(49,'Groups Management',47,0,'admin','group-permission','','none',NULL,'module',NULL,NULL,1,2,''),(50,'Groups Permission',47,0,'admin','groups-permission-manager','','none',NULL,'module',NULL,NULL,1,4,''),(51,'System Administration',47,0,'admin','admin-permission-list','','none',NULL,'module',NULL,NULL,1,1,'');

/*Table structure for table `auth_permission` */

DROP TABLE IF EXISTS `auth_permission`;

CREATE TABLE `auth_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `auth_permission` */

insert  into `auth_permission`(`id`,`module`,`controller`,`action`,`description`) values (1,'admin','admin-permission-list','','Danh sách quyền quản trị'),(2,'admin','admin-user-management','','Quản lý user'),(3,'admin','admin-user-management','create','tạo user quản trị'),(4,'admin','admin-navigation','','Quản lý giao diện'),(5,'admin','admin-navigation','create','Tạo giao diện'),(6,'admin','admin-permission-list','create','tạo quyền quản trị'),(7,'admin','admin-permission-list','edit','Sửa quyền quản trị'),(8,'admin','admin-user-management','edit','Sửa thông tin quản trị viên');

/*Table structure for table `auth_user` */

DROP TABLE IF EXISTS `auth_user`;

CREATE TABLE `auth_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) DEFAULT NULL,
  `algorithm` varchar(128) NOT NULL,
  `can_be_deleted` int(1) unsigned NOT NULL DEFAULT '1',
  `is_active` int(1) unsigned NOT NULL DEFAULT '0',
  `is_super_admin` int(1) unsigned NOT NULL DEFAULT '0',
  `is_staff` int(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_parent_id` int(11) unsigned DEFAULT NULL,
  `role` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user__user_parent_id___user__id` (`user_parent_id`),
  CONSTRAINT `fk_user__user_parent_id___user__id` FOREIGN KEY (`user_parent_id`) REFERENCES `auth_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `auth_user` */

insert  into `auth_user`(`id`,`username`,`first_name`,`last_name`,`email`,`password`,`salt`,`algorithm`,`can_be_deleted`,`is_active`,`is_super_admin`,`is_staff`,`created_at`,`last_login`,`updated_at`,`user_parent_id`,`role`) values (1,'nopbongdem','Recycle','Bin','mr.theak007@gmail.com','4e989f488a107065f122d4425a13eec9',NULL,'',1,1,1,0,'2013-05-02 21:19:01','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,1),(2,'chacbumbum','Dương','Thế','nopbongdem@gmail.com','4e989f488a107065f122d4425a13eec9',NULL,'',1,1,0,0,'2013-05-02 21:19:25','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,2),(3,'thedollar','Tháº¿','DÆ°Æ¡ng','nopbongdem@hotmail.com','4e989f488a107065f122d4425a13eec9',NULL,'',1,1,0,0,'2013-05-07 00:32:07','0000-00-00 00:00:00','0000-00-00 00:00:00',NULL,NULL);

/*Table structure for table `auth_user_permission` */

DROP TABLE IF EXISTS `auth_user_permission`;

CREATE TABLE `auth_user_permission` (
  `user_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`permission_id`),
  KEY `fk_persmission__action_id___action__id` (`permission_id`),
  CONSTRAINT `fk_permission__user_id___user__id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`),
  CONSTRAINT `fk_persmission__action_id___action__id` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `auth_user_permission` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
