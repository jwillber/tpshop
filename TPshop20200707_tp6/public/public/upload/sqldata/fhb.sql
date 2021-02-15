/*
Navicat MySQL Data Transfer

Source Server         : 百币网
Source Server Version : 50173
Source Host           : 139.196.17.237:3306
Source Database       : fhb

Target Server Type    : MYSQL
Target Server Version : 50173
File Encoding         : 65001

Date: 2017-01-16 10:36:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `acer_member`
-- ----------------------------
DROP TABLE IF EXISTS `acer_member`;
CREATE TABLE `acer_member` (
  `member_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(16) NOT NULL DEFAULT '',
  `paypassword` varchar(16) NOT NULL DEFAULT '' COMMENT '交易密码',
  `uname` varchar(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `realname` varchar(16) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `certificates_type` varchar(16) NOT NULL COMMENT '证件类型',
  `certificates` varchar(32) NOT NULL COMMENT '证件号',
  `tel` int(16) NOT NULL,
  `register_time` int(10) NOT NULL COMMENT '注册时间',
  `log_time` int(10) NOT NULL COMMENT '登录时间',
  `vip` tinyint(2) NOT NULL,
  `vip_end` int(10) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `cny` decimal(16,3) NOT NULL DEFAULT '0.000' COMMENT '人民币',
  `cny_cold` decimal(16,3) NOT NULL DEFAULT '0.000' COMMENT '冻结rmb',
  `headpic` varchar(32) NOT NULL DEFAULT '' COMMENT '头像',
  `content` varchar(256) NOT NULL DEFAULT '' COMMENT '个人简介',
  `city` varchar(32) NOT NULL DEFAULT '',
  `region` varchar(128) NOT NULL DEFAULT '' COMMENT '区域',
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of acer_member
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_admin`
-- ----------------------------
DROP TABLE IF EXISTS `yang_admin`;
CREATE TABLE `yang_admin` (
  `admin_id` int(32) NOT NULL AUTO_INCREMENT COMMENT '管理员表',
  `username` varchar(32) NOT NULL COMMENT '管理员登陆账号',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `pwd_show` varchar(64) NOT NULL,
  `nav` varchar(255) NOT NULL COMMENT '权限',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_admin
-- ----------------------------
INSERT INTO `yang_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', '1,2,3,4,6,7,8,15,9,10,11,12,13,14,17,16,21,22,26,27,28,29,30,18,33,32,35,36,40,44,41,52,43,45,46,47,48,49,50,51,56,57,58,59,60,64,69,70,71,72,73', '0');
INSERT INTO `yang_admin` VALUES ('2', '123', 'e10adc3949ba59abbe56e057f20f883e', '', '4,71,49,50,51,73', '0');
INSERT INTO `yang_admin` VALUES ('3', '1', '96e79218965eb72c92a549dd5a330112', '111111', '', '0');

-- ----------------------------
-- Table structure for `yang_areas`
-- ----------------------------
DROP TABLE IF EXISTS `yang_areas`;
CREATE TABLE `yang_areas` (
  `area_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区id',
  `parent_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '地区父id',
  `area_name` varchar(120) NOT NULL DEFAULT '' COMMENT '地区名称',
  `area_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '地区类型 0:country,1:province,2:city,3:district',
  PRIMARY KEY (`area_id`),
  KEY `parent_id` (`parent_id`),
  KEY `area_type` (`area_type`)
) ENGINE=MyISAM AUTO_INCREMENT=3438 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_areas
-- ----------------------------
INSERT INTO `yang_areas` VALUES ('1', '0', '中国', '0');
INSERT INTO `yang_areas` VALUES ('2', '1', '北京', '1');
INSERT INTO `yang_areas` VALUES ('3', '1', '安徽', '1');
INSERT INTO `yang_areas` VALUES ('4', '1', '福建', '1');
INSERT INTO `yang_areas` VALUES ('5', '1', '甘肃', '1');
INSERT INTO `yang_areas` VALUES ('6', '1', '广东', '1');
INSERT INTO `yang_areas` VALUES ('7', '1', '广西', '1');
INSERT INTO `yang_areas` VALUES ('8', '1', '贵州', '1');
INSERT INTO `yang_areas` VALUES ('9', '1', '海南', '1');
INSERT INTO `yang_areas` VALUES ('10', '1', '河北', '1');
INSERT INTO `yang_areas` VALUES ('11', '1', '河南', '1');
INSERT INTO `yang_areas` VALUES ('12', '1', '黑龙江', '1');
INSERT INTO `yang_areas` VALUES ('13', '1', '湖北', '1');
INSERT INTO `yang_areas` VALUES ('14', '1', '湖南', '1');
INSERT INTO `yang_areas` VALUES ('15', '1', '吉林', '1');
INSERT INTO `yang_areas` VALUES ('16', '1', '江苏', '1');
INSERT INTO `yang_areas` VALUES ('17', '1', '江西', '1');
INSERT INTO `yang_areas` VALUES ('18', '1', '辽宁', '1');
INSERT INTO `yang_areas` VALUES ('19', '1', '内蒙古', '1');
INSERT INTO `yang_areas` VALUES ('20', '1', '宁夏', '1');
INSERT INTO `yang_areas` VALUES ('21', '1', '青海', '1');
INSERT INTO `yang_areas` VALUES ('22', '1', '山东', '1');
INSERT INTO `yang_areas` VALUES ('23', '1', '山西', '1');
INSERT INTO `yang_areas` VALUES ('24', '1', '陕西', '1');
INSERT INTO `yang_areas` VALUES ('25', '1', '上海', '1');
INSERT INTO `yang_areas` VALUES ('26', '1', '四川', '1');
INSERT INTO `yang_areas` VALUES ('27', '1', '天津', '1');
INSERT INTO `yang_areas` VALUES ('28', '1', '西藏', '1');
INSERT INTO `yang_areas` VALUES ('29', '1', '新疆', '1');
INSERT INTO `yang_areas` VALUES ('30', '1', '云南', '1');
INSERT INTO `yang_areas` VALUES ('31', '1', '浙江', '1');
INSERT INTO `yang_areas` VALUES ('32', '1', '重庆', '1');
INSERT INTO `yang_areas` VALUES ('33', '1', '香港', '1');
INSERT INTO `yang_areas` VALUES ('34', '1', '澳门', '1');
INSERT INTO `yang_areas` VALUES ('35', '1', '台湾', '1');
INSERT INTO `yang_areas` VALUES ('36', '3', '安庆', '2');
INSERT INTO `yang_areas` VALUES ('37', '3', '蚌埠', '2');
INSERT INTO `yang_areas` VALUES ('38', '3', '巢湖', '2');
INSERT INTO `yang_areas` VALUES ('39', '3', '池州', '2');
INSERT INTO `yang_areas` VALUES ('40', '3', '滁州', '2');
INSERT INTO `yang_areas` VALUES ('41', '3', '阜阳', '2');
INSERT INTO `yang_areas` VALUES ('42', '3', '淮北', '2');
INSERT INTO `yang_areas` VALUES ('43', '3', '淮南', '2');
INSERT INTO `yang_areas` VALUES ('44', '3', '黄山', '2');
INSERT INTO `yang_areas` VALUES ('45', '3', '六安', '2');
INSERT INTO `yang_areas` VALUES ('46', '3', '马鞍山', '2');
INSERT INTO `yang_areas` VALUES ('47', '3', '宿州', '2');
INSERT INTO `yang_areas` VALUES ('48', '3', '铜陵', '2');
INSERT INTO `yang_areas` VALUES ('49', '3', '芜湖', '2');
INSERT INTO `yang_areas` VALUES ('50', '3', '宣城', '2');
INSERT INTO `yang_areas` VALUES ('51', '3', '亳州', '2');
INSERT INTO `yang_areas` VALUES ('52', '2', '北京', '2');
INSERT INTO `yang_areas` VALUES ('53', '4', '福州', '2');
INSERT INTO `yang_areas` VALUES ('54', '4', '龙岩', '2');
INSERT INTO `yang_areas` VALUES ('55', '4', '南平', '2');
INSERT INTO `yang_areas` VALUES ('56', '4', '宁德', '2');
INSERT INTO `yang_areas` VALUES ('57', '4', '莆田', '2');
INSERT INTO `yang_areas` VALUES ('58', '4', '泉州', '2');
INSERT INTO `yang_areas` VALUES ('59', '4', '三明', '2');
INSERT INTO `yang_areas` VALUES ('60', '4', '厦门', '2');
INSERT INTO `yang_areas` VALUES ('61', '4', '漳州', '2');
INSERT INTO `yang_areas` VALUES ('62', '5', '兰州', '2');
INSERT INTO `yang_areas` VALUES ('63', '5', '白银', '2');
INSERT INTO `yang_areas` VALUES ('64', '5', '定西', '2');
INSERT INTO `yang_areas` VALUES ('65', '5', '甘南', '2');
INSERT INTO `yang_areas` VALUES ('66', '5', '嘉峪关', '2');
INSERT INTO `yang_areas` VALUES ('67', '5', '金昌', '2');
INSERT INTO `yang_areas` VALUES ('68', '5', '酒泉', '2');
INSERT INTO `yang_areas` VALUES ('69', '5', '临夏', '2');
INSERT INTO `yang_areas` VALUES ('70', '5', '陇南', '2');
INSERT INTO `yang_areas` VALUES ('71', '5', '平凉', '2');
INSERT INTO `yang_areas` VALUES ('72', '5', '庆阳', '2');
INSERT INTO `yang_areas` VALUES ('73', '5', '天水', '2');
INSERT INTO `yang_areas` VALUES ('74', '5', '武威', '2');
INSERT INTO `yang_areas` VALUES ('75', '5', '张掖', '2');
INSERT INTO `yang_areas` VALUES ('76', '6', '广州', '2');
INSERT INTO `yang_areas` VALUES ('77', '6', '深圳', '2');
INSERT INTO `yang_areas` VALUES ('78', '6', '潮州', '2');
INSERT INTO `yang_areas` VALUES ('79', '6', '东莞', '2');
INSERT INTO `yang_areas` VALUES ('80', '6', '佛山', '2');
INSERT INTO `yang_areas` VALUES ('81', '6', '河源', '2');
INSERT INTO `yang_areas` VALUES ('82', '6', '惠州', '2');
INSERT INTO `yang_areas` VALUES ('83', '6', '江门', '2');
INSERT INTO `yang_areas` VALUES ('84', '6', '揭阳', '2');
INSERT INTO `yang_areas` VALUES ('85', '6', '茂名', '2');
INSERT INTO `yang_areas` VALUES ('86', '6', '梅州', '2');
INSERT INTO `yang_areas` VALUES ('87', '6', '清远', '2');
INSERT INTO `yang_areas` VALUES ('88', '6', '汕头', '2');
INSERT INTO `yang_areas` VALUES ('89', '6', '汕尾', '2');
INSERT INTO `yang_areas` VALUES ('90', '6', '韶关', '2');
INSERT INTO `yang_areas` VALUES ('91', '6', '阳江', '2');
INSERT INTO `yang_areas` VALUES ('92', '6', '云浮', '2');
INSERT INTO `yang_areas` VALUES ('93', '6', '湛江', '2');
INSERT INTO `yang_areas` VALUES ('94', '6', '肇庆', '2');
INSERT INTO `yang_areas` VALUES ('95', '6', '中山', '2');
INSERT INTO `yang_areas` VALUES ('96', '6', '珠海', '2');
INSERT INTO `yang_areas` VALUES ('97', '7', '南宁', '2');
INSERT INTO `yang_areas` VALUES ('98', '7', '桂林', '2');
INSERT INTO `yang_areas` VALUES ('99', '7', '百色', '2');
INSERT INTO `yang_areas` VALUES ('100', '7', '北海', '2');
INSERT INTO `yang_areas` VALUES ('101', '7', '崇左', '2');
INSERT INTO `yang_areas` VALUES ('102', '7', '防城港', '2');
INSERT INTO `yang_areas` VALUES ('103', '7', '贵港', '2');
INSERT INTO `yang_areas` VALUES ('104', '7', '河池', '2');
INSERT INTO `yang_areas` VALUES ('105', '7', '贺州', '2');
INSERT INTO `yang_areas` VALUES ('106', '7', '来宾', '2');
INSERT INTO `yang_areas` VALUES ('107', '7', '柳州', '2');
INSERT INTO `yang_areas` VALUES ('108', '7', '钦州', '2');
INSERT INTO `yang_areas` VALUES ('109', '7', '梧州', '2');
INSERT INTO `yang_areas` VALUES ('110', '7', '玉林', '2');
INSERT INTO `yang_areas` VALUES ('111', '8', '贵阳', '2');
INSERT INTO `yang_areas` VALUES ('112', '8', '安顺', '2');
INSERT INTO `yang_areas` VALUES ('113', '8', '毕节', '2');
INSERT INTO `yang_areas` VALUES ('114', '8', '六盘水', '2');
INSERT INTO `yang_areas` VALUES ('115', '8', '黔东南', '2');
INSERT INTO `yang_areas` VALUES ('116', '8', '黔南', '2');
INSERT INTO `yang_areas` VALUES ('117', '8', '黔西南', '2');
INSERT INTO `yang_areas` VALUES ('118', '8', '铜仁', '2');
INSERT INTO `yang_areas` VALUES ('119', '8', '遵义', '2');
INSERT INTO `yang_areas` VALUES ('120', '9', '海口', '2');
INSERT INTO `yang_areas` VALUES ('121', '9', '三亚', '2');
INSERT INTO `yang_areas` VALUES ('122', '9', '白沙', '2');
INSERT INTO `yang_areas` VALUES ('123', '9', '保亭', '2');
INSERT INTO `yang_areas` VALUES ('124', '9', '昌江', '2');
INSERT INTO `yang_areas` VALUES ('125', '9', '澄迈县', '2');
INSERT INTO `yang_areas` VALUES ('126', '9', '定安县', '2');
INSERT INTO `yang_areas` VALUES ('127', '9', '东方', '2');
INSERT INTO `yang_areas` VALUES ('128', '9', '乐东', '2');
INSERT INTO `yang_areas` VALUES ('129', '9', '临高县', '2');
INSERT INTO `yang_areas` VALUES ('130', '9', '陵水', '2');
INSERT INTO `yang_areas` VALUES ('131', '9', '琼海', '2');
INSERT INTO `yang_areas` VALUES ('132', '9', '琼中', '2');
INSERT INTO `yang_areas` VALUES ('133', '9', '屯昌县', '2');
INSERT INTO `yang_areas` VALUES ('134', '9', '万宁', '2');
INSERT INTO `yang_areas` VALUES ('135', '9', '文昌', '2');
INSERT INTO `yang_areas` VALUES ('136', '9', '五指山', '2');
INSERT INTO `yang_areas` VALUES ('137', '9', '儋州', '2');
INSERT INTO `yang_areas` VALUES ('138', '10', '石家庄', '2');
INSERT INTO `yang_areas` VALUES ('139', '10', '保定', '2');
INSERT INTO `yang_areas` VALUES ('140', '10', '沧州', '2');
INSERT INTO `yang_areas` VALUES ('141', '10', '承德', '2');
INSERT INTO `yang_areas` VALUES ('142', '10', '邯郸', '2');
INSERT INTO `yang_areas` VALUES ('143', '10', '衡水', '2');
INSERT INTO `yang_areas` VALUES ('144', '10', '廊坊', '2');
INSERT INTO `yang_areas` VALUES ('145', '10', '秦皇岛', '2');
INSERT INTO `yang_areas` VALUES ('146', '10', '唐山', '2');
INSERT INTO `yang_areas` VALUES ('147', '10', '邢台', '2');
INSERT INTO `yang_areas` VALUES ('148', '10', '张家口', '2');
INSERT INTO `yang_areas` VALUES ('149', '11', '郑州', '2');
INSERT INTO `yang_areas` VALUES ('150', '11', '洛阳', '2');
INSERT INTO `yang_areas` VALUES ('151', '11', '开封', '2');
INSERT INTO `yang_areas` VALUES ('152', '11', '安阳', '2');
INSERT INTO `yang_areas` VALUES ('153', '11', '鹤壁', '2');
INSERT INTO `yang_areas` VALUES ('154', '11', '济源', '2');
INSERT INTO `yang_areas` VALUES ('155', '11', '焦作', '2');
INSERT INTO `yang_areas` VALUES ('156', '11', '南阳', '2');
INSERT INTO `yang_areas` VALUES ('157', '11', '平顶山', '2');
INSERT INTO `yang_areas` VALUES ('158', '11', '三门峡', '2');
INSERT INTO `yang_areas` VALUES ('159', '11', '商丘', '2');
INSERT INTO `yang_areas` VALUES ('160', '11', '新乡', '2');
INSERT INTO `yang_areas` VALUES ('161', '11', '信阳', '2');
INSERT INTO `yang_areas` VALUES ('162', '11', '许昌', '2');
INSERT INTO `yang_areas` VALUES ('163', '11', '周口', '2');
INSERT INTO `yang_areas` VALUES ('164', '11', '驻马店', '2');
INSERT INTO `yang_areas` VALUES ('165', '11', '漯河', '2');
INSERT INTO `yang_areas` VALUES ('166', '11', '濮阳', '2');
INSERT INTO `yang_areas` VALUES ('167', '12', '哈尔滨', '2');
INSERT INTO `yang_areas` VALUES ('168', '12', '大庆', '2');
INSERT INTO `yang_areas` VALUES ('169', '12', '大兴安岭', '2');
INSERT INTO `yang_areas` VALUES ('170', '12', '鹤岗', '2');
INSERT INTO `yang_areas` VALUES ('171', '12', '黑河', '2');
INSERT INTO `yang_areas` VALUES ('172', '12', '鸡西', '2');
INSERT INTO `yang_areas` VALUES ('173', '12', '佳木斯', '2');
INSERT INTO `yang_areas` VALUES ('174', '12', '牡丹江', '2');
INSERT INTO `yang_areas` VALUES ('175', '12', '七台河', '2');
INSERT INTO `yang_areas` VALUES ('176', '12', '齐齐哈尔', '2');
INSERT INTO `yang_areas` VALUES ('177', '12', '双鸭山', '2');
INSERT INTO `yang_areas` VALUES ('178', '12', '绥化', '2');
INSERT INTO `yang_areas` VALUES ('179', '12', '伊春', '2');
INSERT INTO `yang_areas` VALUES ('180', '13', '武汉', '2');
INSERT INTO `yang_areas` VALUES ('181', '13', '仙桃', '2');
INSERT INTO `yang_areas` VALUES ('182', '13', '鄂州', '2');
INSERT INTO `yang_areas` VALUES ('183', '13', '黄冈', '2');
INSERT INTO `yang_areas` VALUES ('184', '13', '黄石', '2');
INSERT INTO `yang_areas` VALUES ('185', '13', '荆门', '2');
INSERT INTO `yang_areas` VALUES ('186', '13', '荆州', '2');
INSERT INTO `yang_areas` VALUES ('187', '13', '潜江', '2');
INSERT INTO `yang_areas` VALUES ('188', '13', '神农架林区', '2');
INSERT INTO `yang_areas` VALUES ('189', '13', '十堰', '2');
INSERT INTO `yang_areas` VALUES ('190', '13', '随州', '2');
INSERT INTO `yang_areas` VALUES ('191', '13', '天门', '2');
INSERT INTO `yang_areas` VALUES ('192', '13', '咸宁', '2');
INSERT INTO `yang_areas` VALUES ('193', '13', '襄樊', '2');
INSERT INTO `yang_areas` VALUES ('194', '13', '孝感', '2');
INSERT INTO `yang_areas` VALUES ('195', '13', '宜昌', '2');
INSERT INTO `yang_areas` VALUES ('196', '13', '恩施', '2');
INSERT INTO `yang_areas` VALUES ('197', '14', '长沙', '2');
INSERT INTO `yang_areas` VALUES ('198', '14', '张家界', '2');
INSERT INTO `yang_areas` VALUES ('199', '14', '常德', '2');
INSERT INTO `yang_areas` VALUES ('200', '14', '郴州', '2');
INSERT INTO `yang_areas` VALUES ('201', '14', '衡阳', '2');
INSERT INTO `yang_areas` VALUES ('202', '14', '怀化', '2');
INSERT INTO `yang_areas` VALUES ('203', '14', '娄底', '2');
INSERT INTO `yang_areas` VALUES ('204', '14', '邵阳', '2');
INSERT INTO `yang_areas` VALUES ('205', '14', '湘潭', '2');
INSERT INTO `yang_areas` VALUES ('206', '14', '湘西', '2');
INSERT INTO `yang_areas` VALUES ('207', '14', '益阳', '2');
INSERT INTO `yang_areas` VALUES ('208', '14', '永州', '2');
INSERT INTO `yang_areas` VALUES ('209', '14', '岳阳', '2');
INSERT INTO `yang_areas` VALUES ('210', '14', '株洲', '2');
INSERT INTO `yang_areas` VALUES ('211', '15', '长春', '2');
INSERT INTO `yang_areas` VALUES ('212', '15', '吉林', '2');
INSERT INTO `yang_areas` VALUES ('213', '15', '白城', '2');
INSERT INTO `yang_areas` VALUES ('214', '15', '白山', '2');
INSERT INTO `yang_areas` VALUES ('215', '15', '辽源', '2');
INSERT INTO `yang_areas` VALUES ('216', '15', '四平', '2');
INSERT INTO `yang_areas` VALUES ('217', '15', '松原', '2');
INSERT INTO `yang_areas` VALUES ('218', '15', '通化', '2');
INSERT INTO `yang_areas` VALUES ('219', '15', '延边', '2');
INSERT INTO `yang_areas` VALUES ('220', '16', '南京', '2');
INSERT INTO `yang_areas` VALUES ('221', '16', '苏州', '2');
INSERT INTO `yang_areas` VALUES ('222', '16', '无锡', '2');
INSERT INTO `yang_areas` VALUES ('223', '16', '常州', '2');
INSERT INTO `yang_areas` VALUES ('224', '16', '淮安', '2');
INSERT INTO `yang_areas` VALUES ('225', '16', '连云港', '2');
INSERT INTO `yang_areas` VALUES ('226', '16', '南通', '2');
INSERT INTO `yang_areas` VALUES ('227', '16', '宿迁', '2');
INSERT INTO `yang_areas` VALUES ('228', '16', '泰州', '2');
INSERT INTO `yang_areas` VALUES ('229', '16', '徐州', '2');
INSERT INTO `yang_areas` VALUES ('230', '16', '盐城', '2');
INSERT INTO `yang_areas` VALUES ('231', '16', '扬州', '2');
INSERT INTO `yang_areas` VALUES ('232', '16', '镇江', '2');
INSERT INTO `yang_areas` VALUES ('233', '17', '南昌', '2');
INSERT INTO `yang_areas` VALUES ('234', '17', '抚州', '2');
INSERT INTO `yang_areas` VALUES ('235', '17', '赣州', '2');
INSERT INTO `yang_areas` VALUES ('236', '17', '吉安', '2');
INSERT INTO `yang_areas` VALUES ('237', '17', '景德镇', '2');
INSERT INTO `yang_areas` VALUES ('238', '17', '九江', '2');
INSERT INTO `yang_areas` VALUES ('239', '17', '萍乡', '2');
INSERT INTO `yang_areas` VALUES ('240', '17', '上饶', '2');
INSERT INTO `yang_areas` VALUES ('241', '17', '新余', '2');
INSERT INTO `yang_areas` VALUES ('242', '17', '宜春', '2');
INSERT INTO `yang_areas` VALUES ('243', '17', '鹰潭', '2');
INSERT INTO `yang_areas` VALUES ('244', '18', '沈阳', '2');
INSERT INTO `yang_areas` VALUES ('245', '18', '大连', '2');
INSERT INTO `yang_areas` VALUES ('246', '18', '鞍山', '2');
INSERT INTO `yang_areas` VALUES ('247', '18', '本溪', '2');
INSERT INTO `yang_areas` VALUES ('248', '18', '朝阳', '2');
INSERT INTO `yang_areas` VALUES ('249', '18', '丹东', '2');
INSERT INTO `yang_areas` VALUES ('250', '18', '抚顺', '2');
INSERT INTO `yang_areas` VALUES ('251', '18', '阜新', '2');
INSERT INTO `yang_areas` VALUES ('252', '18', '葫芦岛', '2');
INSERT INTO `yang_areas` VALUES ('253', '18', '锦州', '2');
INSERT INTO `yang_areas` VALUES ('254', '18', '辽阳', '2');
INSERT INTO `yang_areas` VALUES ('255', '18', '盘锦', '2');
INSERT INTO `yang_areas` VALUES ('256', '18', '铁岭', '2');
INSERT INTO `yang_areas` VALUES ('257', '18', '营口', '2');
INSERT INTO `yang_areas` VALUES ('258', '19', '呼和浩特', '2');
INSERT INTO `yang_areas` VALUES ('259', '19', '阿拉善盟', '2');
INSERT INTO `yang_areas` VALUES ('260', '19', '巴彦淖尔盟', '2');
INSERT INTO `yang_areas` VALUES ('261', '19', '包头', '2');
INSERT INTO `yang_areas` VALUES ('262', '19', '赤峰', '2');
INSERT INTO `yang_areas` VALUES ('263', '19', '鄂尔多斯', '2');
INSERT INTO `yang_areas` VALUES ('264', '19', '呼伦贝尔', '2');
INSERT INTO `yang_areas` VALUES ('265', '19', '通辽', '2');
INSERT INTO `yang_areas` VALUES ('266', '19', '乌海', '2');
INSERT INTO `yang_areas` VALUES ('267', '19', '乌兰察布市', '2');
INSERT INTO `yang_areas` VALUES ('268', '19', '锡林郭勒盟', '2');
INSERT INTO `yang_areas` VALUES ('269', '19', '兴安盟', '2');
INSERT INTO `yang_areas` VALUES ('270', '20', '银川', '2');
INSERT INTO `yang_areas` VALUES ('271', '20', '固原', '2');
INSERT INTO `yang_areas` VALUES ('272', '20', '石嘴山', '2');
INSERT INTO `yang_areas` VALUES ('273', '20', '吴忠', '2');
INSERT INTO `yang_areas` VALUES ('274', '20', '中卫', '2');
INSERT INTO `yang_areas` VALUES ('275', '21', '西宁', '2');
INSERT INTO `yang_areas` VALUES ('276', '21', '果洛', '2');
INSERT INTO `yang_areas` VALUES ('277', '21', '海北', '2');
INSERT INTO `yang_areas` VALUES ('278', '21', '海东', '2');
INSERT INTO `yang_areas` VALUES ('279', '21', '海南', '2');
INSERT INTO `yang_areas` VALUES ('280', '21', '海西', '2');
INSERT INTO `yang_areas` VALUES ('281', '21', '黄南', '2');
INSERT INTO `yang_areas` VALUES ('282', '21', '玉树', '2');
INSERT INTO `yang_areas` VALUES ('283', '22', '济南', '2');
INSERT INTO `yang_areas` VALUES ('284', '22', '青岛', '2');
INSERT INTO `yang_areas` VALUES ('285', '22', '滨州', '2');
INSERT INTO `yang_areas` VALUES ('286', '22', '德州', '2');
INSERT INTO `yang_areas` VALUES ('287', '22', '东营', '2');
INSERT INTO `yang_areas` VALUES ('288', '22', '菏泽', '2');
INSERT INTO `yang_areas` VALUES ('289', '22', '济宁', '2');
INSERT INTO `yang_areas` VALUES ('290', '22', '莱芜', '2');
INSERT INTO `yang_areas` VALUES ('291', '22', '聊城', '2');
INSERT INTO `yang_areas` VALUES ('292', '22', '临沂', '2');
INSERT INTO `yang_areas` VALUES ('293', '22', '日照', '2');
INSERT INTO `yang_areas` VALUES ('294', '22', '泰安', '2');
INSERT INTO `yang_areas` VALUES ('295', '22', '威海', '2');
INSERT INTO `yang_areas` VALUES ('296', '22', '潍坊', '2');
INSERT INTO `yang_areas` VALUES ('297', '22', '烟台', '2');
INSERT INTO `yang_areas` VALUES ('298', '22', '枣庄', '2');
INSERT INTO `yang_areas` VALUES ('299', '22', '淄博', '2');
INSERT INTO `yang_areas` VALUES ('300', '23', '太原', '2');
INSERT INTO `yang_areas` VALUES ('301', '23', '长治', '2');
INSERT INTO `yang_areas` VALUES ('302', '23', '大同', '2');
INSERT INTO `yang_areas` VALUES ('303', '23', '晋城', '2');
INSERT INTO `yang_areas` VALUES ('304', '23', '晋中', '2');
INSERT INTO `yang_areas` VALUES ('305', '23', '临汾', '2');
INSERT INTO `yang_areas` VALUES ('306', '23', '吕梁', '2');
INSERT INTO `yang_areas` VALUES ('307', '23', '朔州', '2');
INSERT INTO `yang_areas` VALUES ('308', '23', '忻州', '2');
INSERT INTO `yang_areas` VALUES ('309', '23', '阳泉', '2');
INSERT INTO `yang_areas` VALUES ('310', '23', '运城', '2');
INSERT INTO `yang_areas` VALUES ('311', '24', '西安', '2');
INSERT INTO `yang_areas` VALUES ('312', '24', '安康', '2');
INSERT INTO `yang_areas` VALUES ('313', '24', '宝鸡', '2');
INSERT INTO `yang_areas` VALUES ('314', '24', '汉中', '2');
INSERT INTO `yang_areas` VALUES ('315', '24', '商洛', '2');
INSERT INTO `yang_areas` VALUES ('316', '24', '铜川', '2');
INSERT INTO `yang_areas` VALUES ('317', '24', '渭南', '2');
INSERT INTO `yang_areas` VALUES ('318', '24', '咸阳', '2');
INSERT INTO `yang_areas` VALUES ('319', '24', '延安', '2');
INSERT INTO `yang_areas` VALUES ('320', '24', '榆林', '2');
INSERT INTO `yang_areas` VALUES ('321', '25', '上海', '2');
INSERT INTO `yang_areas` VALUES ('322', '26', '成都', '2');
INSERT INTO `yang_areas` VALUES ('323', '26', '绵阳', '2');
INSERT INTO `yang_areas` VALUES ('324', '26', '阿坝', '2');
INSERT INTO `yang_areas` VALUES ('325', '26', '巴中', '2');
INSERT INTO `yang_areas` VALUES ('326', '26', '达州', '2');
INSERT INTO `yang_areas` VALUES ('327', '26', '德阳', '2');
INSERT INTO `yang_areas` VALUES ('328', '26', '甘孜', '2');
INSERT INTO `yang_areas` VALUES ('329', '26', '广安', '2');
INSERT INTO `yang_areas` VALUES ('330', '26', '广元', '2');
INSERT INTO `yang_areas` VALUES ('331', '26', '乐山', '2');
INSERT INTO `yang_areas` VALUES ('332', '26', '凉山', '2');
INSERT INTO `yang_areas` VALUES ('333', '26', '眉山', '2');
INSERT INTO `yang_areas` VALUES ('334', '26', '南充', '2');
INSERT INTO `yang_areas` VALUES ('335', '26', '内江', '2');
INSERT INTO `yang_areas` VALUES ('336', '26', '攀枝花', '2');
INSERT INTO `yang_areas` VALUES ('337', '26', '遂宁', '2');
INSERT INTO `yang_areas` VALUES ('338', '26', '雅安', '2');
INSERT INTO `yang_areas` VALUES ('339', '26', '宜宾', '2');
INSERT INTO `yang_areas` VALUES ('340', '26', '资阳', '2');
INSERT INTO `yang_areas` VALUES ('341', '26', '自贡', '2');
INSERT INTO `yang_areas` VALUES ('342', '26', '泸州', '2');
INSERT INTO `yang_areas` VALUES ('343', '27', '天津', '2');
INSERT INTO `yang_areas` VALUES ('344', '28', '拉萨', '2');
INSERT INTO `yang_areas` VALUES ('345', '28', '阿里', '2');
INSERT INTO `yang_areas` VALUES ('346', '28', '昌都', '2');
INSERT INTO `yang_areas` VALUES ('347', '28', '林芝', '2');
INSERT INTO `yang_areas` VALUES ('348', '28', '那曲', '2');
INSERT INTO `yang_areas` VALUES ('349', '28', '日喀则', '2');
INSERT INTO `yang_areas` VALUES ('350', '28', '山南', '2');
INSERT INTO `yang_areas` VALUES ('351', '29', '乌鲁木齐', '2');
INSERT INTO `yang_areas` VALUES ('352', '29', '阿克苏', '2');
INSERT INTO `yang_areas` VALUES ('353', '29', '阿拉尔', '2');
INSERT INTO `yang_areas` VALUES ('354', '29', '巴音郭楞', '2');
INSERT INTO `yang_areas` VALUES ('355', '29', '博尔塔拉', '2');
INSERT INTO `yang_areas` VALUES ('356', '29', '昌吉', '2');
INSERT INTO `yang_areas` VALUES ('357', '29', '哈密', '2');
INSERT INTO `yang_areas` VALUES ('358', '29', '和田', '2');
INSERT INTO `yang_areas` VALUES ('359', '29', '喀什', '2');
INSERT INTO `yang_areas` VALUES ('360', '29', '克拉玛依', '2');
INSERT INTO `yang_areas` VALUES ('361', '29', '克孜勒苏', '2');
INSERT INTO `yang_areas` VALUES ('362', '29', '石河子', '2');
INSERT INTO `yang_areas` VALUES ('363', '29', '图木舒克', '2');
INSERT INTO `yang_areas` VALUES ('364', '29', '吐鲁番', '2');
INSERT INTO `yang_areas` VALUES ('365', '29', '五家渠', '2');
INSERT INTO `yang_areas` VALUES ('366', '29', '伊犁', '2');
INSERT INTO `yang_areas` VALUES ('367', '30', '昆明', '2');
INSERT INTO `yang_areas` VALUES ('368', '30', '怒江', '2');
INSERT INTO `yang_areas` VALUES ('369', '30', '普洱', '2');
INSERT INTO `yang_areas` VALUES ('370', '30', '丽江', '2');
INSERT INTO `yang_areas` VALUES ('371', '30', '保山', '2');
INSERT INTO `yang_areas` VALUES ('372', '30', '楚雄', '2');
INSERT INTO `yang_areas` VALUES ('373', '30', '大理', '2');
INSERT INTO `yang_areas` VALUES ('374', '30', '德宏', '2');
INSERT INTO `yang_areas` VALUES ('375', '30', '迪庆', '2');
INSERT INTO `yang_areas` VALUES ('376', '30', '红河', '2');
INSERT INTO `yang_areas` VALUES ('377', '30', '临沧', '2');
INSERT INTO `yang_areas` VALUES ('378', '30', '曲靖', '2');
INSERT INTO `yang_areas` VALUES ('379', '30', '文山', '2');
INSERT INTO `yang_areas` VALUES ('380', '30', '西双版纳', '2');
INSERT INTO `yang_areas` VALUES ('381', '30', '玉溪', '2');
INSERT INTO `yang_areas` VALUES ('382', '30', '昭通', '2');
INSERT INTO `yang_areas` VALUES ('383', '31', '杭州', '2');
INSERT INTO `yang_areas` VALUES ('384', '31', '湖州', '2');
INSERT INTO `yang_areas` VALUES ('385', '31', '嘉兴', '2');
INSERT INTO `yang_areas` VALUES ('386', '31', '金华', '2');
INSERT INTO `yang_areas` VALUES ('387', '31', '丽水', '2');
INSERT INTO `yang_areas` VALUES ('388', '31', '宁波', '2');
INSERT INTO `yang_areas` VALUES ('389', '31', '绍兴', '2');
INSERT INTO `yang_areas` VALUES ('390', '31', '台州', '2');
INSERT INTO `yang_areas` VALUES ('391', '31', '温州', '2');
INSERT INTO `yang_areas` VALUES ('392', '31', '舟山', '2');
INSERT INTO `yang_areas` VALUES ('393', '31', '衢州', '2');
INSERT INTO `yang_areas` VALUES ('394', '32', '重庆', '2');
INSERT INTO `yang_areas` VALUES ('395', '33', '香港', '2');
INSERT INTO `yang_areas` VALUES ('396', '34', '澳门', '2');
INSERT INTO `yang_areas` VALUES ('397', '35', '台湾', '2');
INSERT INTO `yang_areas` VALUES ('398', '36', '迎江区', '3');
INSERT INTO `yang_areas` VALUES ('399', '36', '大观区', '3');
INSERT INTO `yang_areas` VALUES ('400', '36', '宜秀区', '3');
INSERT INTO `yang_areas` VALUES ('401', '36', '桐城市', '3');
INSERT INTO `yang_areas` VALUES ('402', '36', '怀宁县', '3');
INSERT INTO `yang_areas` VALUES ('403', '36', '枞阳县', '3');
INSERT INTO `yang_areas` VALUES ('404', '36', '潜山县', '3');
INSERT INTO `yang_areas` VALUES ('405', '36', '太湖县', '3');
INSERT INTO `yang_areas` VALUES ('406', '36', '宿松县', '3');
INSERT INTO `yang_areas` VALUES ('407', '36', '望江县', '3');
INSERT INTO `yang_areas` VALUES ('408', '36', '岳西县', '3');
INSERT INTO `yang_areas` VALUES ('409', '37', '中市区', '3');
INSERT INTO `yang_areas` VALUES ('410', '37', '东市区', '3');
INSERT INTO `yang_areas` VALUES ('411', '37', '西市区', '3');
INSERT INTO `yang_areas` VALUES ('412', '37', '郊区', '3');
INSERT INTO `yang_areas` VALUES ('413', '37', '怀远县', '3');
INSERT INTO `yang_areas` VALUES ('414', '37', '五河县', '3');
INSERT INTO `yang_areas` VALUES ('415', '37', '固镇县', '3');
INSERT INTO `yang_areas` VALUES ('416', '38', '居巢区', '3');
INSERT INTO `yang_areas` VALUES ('417', '38', '庐江县', '3');
INSERT INTO `yang_areas` VALUES ('418', '38', '无为县', '3');
INSERT INTO `yang_areas` VALUES ('419', '38', '含山县', '3');
INSERT INTO `yang_areas` VALUES ('420', '38', '和县', '3');
INSERT INTO `yang_areas` VALUES ('421', '39', '贵池区', '3');
INSERT INTO `yang_areas` VALUES ('422', '39', '东至县', '3');
INSERT INTO `yang_areas` VALUES ('423', '39', '石台县', '3');
INSERT INTO `yang_areas` VALUES ('424', '39', '青阳县', '3');
INSERT INTO `yang_areas` VALUES ('425', '40', '琅琊区', '3');
INSERT INTO `yang_areas` VALUES ('426', '40', '南谯区', '3');
INSERT INTO `yang_areas` VALUES ('427', '40', '天长市', '3');
INSERT INTO `yang_areas` VALUES ('428', '40', '明光市', '3');
INSERT INTO `yang_areas` VALUES ('429', '40', '来安县', '3');
INSERT INTO `yang_areas` VALUES ('430', '40', '全椒县', '3');
INSERT INTO `yang_areas` VALUES ('431', '40', '定远县', '3');
INSERT INTO `yang_areas` VALUES ('432', '40', '凤阳县', '3');
INSERT INTO `yang_areas` VALUES ('433', '41', '蚌山区', '3');
INSERT INTO `yang_areas` VALUES ('434', '41', '龙子湖区', '3');
INSERT INTO `yang_areas` VALUES ('435', '41', '禹会区', '3');
INSERT INTO `yang_areas` VALUES ('436', '41', '淮上区', '3');
INSERT INTO `yang_areas` VALUES ('437', '41', '颍州区', '3');
INSERT INTO `yang_areas` VALUES ('438', '41', '颍东区', '3');
INSERT INTO `yang_areas` VALUES ('439', '41', '颍泉区', '3');
INSERT INTO `yang_areas` VALUES ('440', '41', '界首市', '3');
INSERT INTO `yang_areas` VALUES ('441', '41', '临泉县', '3');
INSERT INTO `yang_areas` VALUES ('442', '41', '太和县', '3');
INSERT INTO `yang_areas` VALUES ('443', '41', '阜南县', '3');
INSERT INTO `yang_areas` VALUES ('444', '41', '颖上县', '3');
INSERT INTO `yang_areas` VALUES ('445', '42', '相山区', '3');
INSERT INTO `yang_areas` VALUES ('446', '42', '杜集区', '3');
INSERT INTO `yang_areas` VALUES ('447', '42', '烈山区', '3');
INSERT INTO `yang_areas` VALUES ('448', '42', '濉溪县', '3');
INSERT INTO `yang_areas` VALUES ('449', '43', '田家庵区', '3');
INSERT INTO `yang_areas` VALUES ('450', '43', '大通区', '3');
INSERT INTO `yang_areas` VALUES ('451', '43', '谢家集区', '3');
INSERT INTO `yang_areas` VALUES ('452', '43', '八公山区', '3');
INSERT INTO `yang_areas` VALUES ('453', '43', '潘集区', '3');
INSERT INTO `yang_areas` VALUES ('454', '43', '凤台县', '3');
INSERT INTO `yang_areas` VALUES ('455', '44', '屯溪区', '3');
INSERT INTO `yang_areas` VALUES ('456', '44', '黄山区', '3');
INSERT INTO `yang_areas` VALUES ('457', '44', '徽州区', '3');
INSERT INTO `yang_areas` VALUES ('458', '44', '歙县', '3');
INSERT INTO `yang_areas` VALUES ('459', '44', '休宁县', '3');
INSERT INTO `yang_areas` VALUES ('460', '44', '黟县', '3');
INSERT INTO `yang_areas` VALUES ('461', '44', '祁门县', '3');
INSERT INTO `yang_areas` VALUES ('462', '45', '金安区', '3');
INSERT INTO `yang_areas` VALUES ('463', '45', '裕安区', '3');
INSERT INTO `yang_areas` VALUES ('464', '45', '寿县', '3');
INSERT INTO `yang_areas` VALUES ('465', '45', '霍邱县', '3');
INSERT INTO `yang_areas` VALUES ('466', '45', '舒城县', '3');
INSERT INTO `yang_areas` VALUES ('467', '45', '金寨县', '3');
INSERT INTO `yang_areas` VALUES ('468', '45', '霍山县', '3');
INSERT INTO `yang_areas` VALUES ('469', '46', '雨山区', '3');
INSERT INTO `yang_areas` VALUES ('470', '46', '花山区', '3');
INSERT INTO `yang_areas` VALUES ('471', '46', '金家庄区', '3');
INSERT INTO `yang_areas` VALUES ('472', '46', '当涂县', '3');
INSERT INTO `yang_areas` VALUES ('473', '47', '埇桥区', '3');
INSERT INTO `yang_areas` VALUES ('474', '47', '砀山县', '3');
INSERT INTO `yang_areas` VALUES ('475', '47', '萧县', '3');
INSERT INTO `yang_areas` VALUES ('476', '47', '灵璧县', '3');
INSERT INTO `yang_areas` VALUES ('477', '47', '泗县', '3');
INSERT INTO `yang_areas` VALUES ('478', '48', '铜官山区', '3');
INSERT INTO `yang_areas` VALUES ('479', '48', '狮子山区', '3');
INSERT INTO `yang_areas` VALUES ('480', '48', '郊区', '3');
INSERT INTO `yang_areas` VALUES ('481', '48', '铜陵县', '3');
INSERT INTO `yang_areas` VALUES ('482', '49', '镜湖区', '3');
INSERT INTO `yang_areas` VALUES ('483', '49', '弋江区', '3');
INSERT INTO `yang_areas` VALUES ('484', '49', '鸠江区', '3');
INSERT INTO `yang_areas` VALUES ('485', '49', '三山区', '3');
INSERT INTO `yang_areas` VALUES ('486', '49', '芜湖县', '3');
INSERT INTO `yang_areas` VALUES ('487', '49', '繁昌县', '3');
INSERT INTO `yang_areas` VALUES ('488', '49', '南陵县', '3');
INSERT INTO `yang_areas` VALUES ('489', '50', '宣州区', '3');
INSERT INTO `yang_areas` VALUES ('490', '50', '宁国市', '3');
INSERT INTO `yang_areas` VALUES ('491', '50', '郎溪县', '3');
INSERT INTO `yang_areas` VALUES ('492', '50', '广德县', '3');
INSERT INTO `yang_areas` VALUES ('493', '50', '泾县', '3');
INSERT INTO `yang_areas` VALUES ('494', '50', '绩溪县', '3');
INSERT INTO `yang_areas` VALUES ('495', '50', '旌德县', '3');
INSERT INTO `yang_areas` VALUES ('496', '51', '涡阳县', '3');
INSERT INTO `yang_areas` VALUES ('497', '51', '蒙城县', '3');
INSERT INTO `yang_areas` VALUES ('498', '51', '利辛县', '3');
INSERT INTO `yang_areas` VALUES ('499', '51', '谯城区', '3');
INSERT INTO `yang_areas` VALUES ('500', '52', '东城区', '3');
INSERT INTO `yang_areas` VALUES ('501', '52', '西城区', '3');
INSERT INTO `yang_areas` VALUES ('502', '52', '海淀区', '3');
INSERT INTO `yang_areas` VALUES ('503', '52', '朝阳区', '3');
INSERT INTO `yang_areas` VALUES ('504', '52', '崇文区', '3');
INSERT INTO `yang_areas` VALUES ('505', '52', '宣武区', '3');
INSERT INTO `yang_areas` VALUES ('506', '52', '丰台区', '3');
INSERT INTO `yang_areas` VALUES ('507', '52', '石景山区', '3');
INSERT INTO `yang_areas` VALUES ('508', '52', '房山区', '3');
INSERT INTO `yang_areas` VALUES ('509', '52', '门头沟区', '3');
INSERT INTO `yang_areas` VALUES ('510', '52', '通州区', '3');
INSERT INTO `yang_areas` VALUES ('511', '52', '顺义区', '3');
INSERT INTO `yang_areas` VALUES ('512', '52', '昌平区', '3');
INSERT INTO `yang_areas` VALUES ('513', '52', '怀柔区', '3');
INSERT INTO `yang_areas` VALUES ('514', '52', '平谷区', '3');
INSERT INTO `yang_areas` VALUES ('515', '52', '大兴区', '3');
INSERT INTO `yang_areas` VALUES ('516', '52', '密云县', '3');
INSERT INTO `yang_areas` VALUES ('517', '52', '延庆县', '3');
INSERT INTO `yang_areas` VALUES ('518', '53', '鼓楼区', '3');
INSERT INTO `yang_areas` VALUES ('519', '53', '台江区', '3');
INSERT INTO `yang_areas` VALUES ('520', '53', '仓山区', '3');
INSERT INTO `yang_areas` VALUES ('521', '53', '马尾区', '3');
INSERT INTO `yang_areas` VALUES ('522', '53', '晋安区', '3');
INSERT INTO `yang_areas` VALUES ('523', '53', '福清市', '3');
INSERT INTO `yang_areas` VALUES ('524', '53', '长乐市', '3');
INSERT INTO `yang_areas` VALUES ('525', '53', '闽侯县', '3');
INSERT INTO `yang_areas` VALUES ('526', '53', '连江县', '3');
INSERT INTO `yang_areas` VALUES ('527', '53', '罗源县', '3');
INSERT INTO `yang_areas` VALUES ('528', '53', '闽清县', '3');
INSERT INTO `yang_areas` VALUES ('529', '53', '永泰县', '3');
INSERT INTO `yang_areas` VALUES ('530', '53', '平潭县', '3');
INSERT INTO `yang_areas` VALUES ('531', '54', '新罗区', '3');
INSERT INTO `yang_areas` VALUES ('532', '54', '漳平市', '3');
INSERT INTO `yang_areas` VALUES ('533', '54', '长汀县', '3');
INSERT INTO `yang_areas` VALUES ('534', '54', '永定县', '3');
INSERT INTO `yang_areas` VALUES ('535', '54', '上杭县', '3');
INSERT INTO `yang_areas` VALUES ('536', '54', '武平县', '3');
INSERT INTO `yang_areas` VALUES ('537', '54', '连城县', '3');
INSERT INTO `yang_areas` VALUES ('538', '55', '延平区', '3');
INSERT INTO `yang_areas` VALUES ('539', '55', '邵武市', '3');
INSERT INTO `yang_areas` VALUES ('540', '55', '武夷山市', '3');
INSERT INTO `yang_areas` VALUES ('541', '55', '建瓯市', '3');
INSERT INTO `yang_areas` VALUES ('542', '55', '建阳市', '3');
INSERT INTO `yang_areas` VALUES ('543', '55', '顺昌县', '3');
INSERT INTO `yang_areas` VALUES ('544', '55', '浦城县', '3');
INSERT INTO `yang_areas` VALUES ('545', '55', '光泽县', '3');
INSERT INTO `yang_areas` VALUES ('546', '55', '松溪县', '3');
INSERT INTO `yang_areas` VALUES ('547', '55', '政和县', '3');
INSERT INTO `yang_areas` VALUES ('548', '56', '蕉城区', '3');
INSERT INTO `yang_areas` VALUES ('549', '56', '福安市', '3');
INSERT INTO `yang_areas` VALUES ('550', '56', '福鼎市', '3');
INSERT INTO `yang_areas` VALUES ('551', '56', '霞浦县', '3');
INSERT INTO `yang_areas` VALUES ('552', '56', '古田县', '3');
INSERT INTO `yang_areas` VALUES ('553', '56', '屏南县', '3');
INSERT INTO `yang_areas` VALUES ('554', '56', '寿宁县', '3');
INSERT INTO `yang_areas` VALUES ('555', '56', '周宁县', '3');
INSERT INTO `yang_areas` VALUES ('556', '56', '柘荣县', '3');
INSERT INTO `yang_areas` VALUES ('557', '57', '城厢区', '3');
INSERT INTO `yang_areas` VALUES ('558', '57', '涵江区', '3');
INSERT INTO `yang_areas` VALUES ('559', '57', '荔城区', '3');
INSERT INTO `yang_areas` VALUES ('560', '57', '秀屿区', '3');
INSERT INTO `yang_areas` VALUES ('561', '57', '仙游县', '3');
INSERT INTO `yang_areas` VALUES ('562', '58', '鲤城区', '3');
INSERT INTO `yang_areas` VALUES ('563', '58', '丰泽区', '3');
INSERT INTO `yang_areas` VALUES ('564', '58', '洛江区', '3');
INSERT INTO `yang_areas` VALUES ('565', '58', '清濛开发区', '3');
INSERT INTO `yang_areas` VALUES ('566', '58', '泉港区', '3');
INSERT INTO `yang_areas` VALUES ('567', '58', '石狮市', '3');
INSERT INTO `yang_areas` VALUES ('568', '58', '晋江市', '3');
INSERT INTO `yang_areas` VALUES ('569', '58', '南安市', '3');
INSERT INTO `yang_areas` VALUES ('570', '58', '惠安县', '3');
INSERT INTO `yang_areas` VALUES ('571', '58', '安溪县', '3');
INSERT INTO `yang_areas` VALUES ('572', '58', '永春县', '3');
INSERT INTO `yang_areas` VALUES ('573', '58', '德化县', '3');
INSERT INTO `yang_areas` VALUES ('574', '58', '金门县', '3');
INSERT INTO `yang_areas` VALUES ('575', '59', '梅列区', '3');
INSERT INTO `yang_areas` VALUES ('576', '59', '三元区', '3');
INSERT INTO `yang_areas` VALUES ('577', '59', '永安市', '3');
INSERT INTO `yang_areas` VALUES ('578', '59', '明溪县', '3');
INSERT INTO `yang_areas` VALUES ('579', '59', '清流县', '3');
INSERT INTO `yang_areas` VALUES ('580', '59', '宁化县', '3');
INSERT INTO `yang_areas` VALUES ('581', '59', '大田县', '3');
INSERT INTO `yang_areas` VALUES ('582', '59', '尤溪县', '3');
INSERT INTO `yang_areas` VALUES ('583', '59', '沙县', '3');
INSERT INTO `yang_areas` VALUES ('584', '59', '将乐县', '3');
INSERT INTO `yang_areas` VALUES ('585', '59', '泰宁县', '3');
INSERT INTO `yang_areas` VALUES ('586', '59', '建宁县', '3');
INSERT INTO `yang_areas` VALUES ('587', '60', '思明区', '3');
INSERT INTO `yang_areas` VALUES ('588', '60', '海沧区', '3');
INSERT INTO `yang_areas` VALUES ('589', '60', '湖里区', '3');
INSERT INTO `yang_areas` VALUES ('590', '60', '集美区', '3');
INSERT INTO `yang_areas` VALUES ('591', '60', '同安区', '3');
INSERT INTO `yang_areas` VALUES ('592', '60', '翔安区', '3');
INSERT INTO `yang_areas` VALUES ('593', '61', '芗城区', '3');
INSERT INTO `yang_areas` VALUES ('594', '61', '龙文区', '3');
INSERT INTO `yang_areas` VALUES ('595', '61', '龙海市', '3');
INSERT INTO `yang_areas` VALUES ('596', '61', '云霄县', '3');
INSERT INTO `yang_areas` VALUES ('597', '61', '漳浦县', '3');
INSERT INTO `yang_areas` VALUES ('598', '61', '诏安县', '3');
INSERT INTO `yang_areas` VALUES ('599', '61', '长泰县', '3');
INSERT INTO `yang_areas` VALUES ('600', '61', '东山县', '3');
INSERT INTO `yang_areas` VALUES ('1021', '117', '兴仁县', '3');
INSERT INTO `yang_areas` VALUES ('1022', '117', '普安县', '3');
INSERT INTO `yang_areas` VALUES ('1023', '117', '晴隆县', '3');
INSERT INTO `yang_areas` VALUES ('1024', '117', '贞丰县', '3');
INSERT INTO `yang_areas` VALUES ('1025', '117', '望谟县', '3');
INSERT INTO `yang_areas` VALUES ('1026', '117', '册亨县', '3');
INSERT INTO `yang_areas` VALUES ('1027', '117', '安龙县', '3');
INSERT INTO `yang_areas` VALUES ('1028', '118', '铜仁市', '3');
INSERT INTO `yang_areas` VALUES ('1029', '118', '江口县', '3');
INSERT INTO `yang_areas` VALUES ('1030', '118', '石阡县', '3');
INSERT INTO `yang_areas` VALUES ('1031', '118', '思南县', '3');
INSERT INTO `yang_areas` VALUES ('1032', '118', '德江县', '3');
INSERT INTO `yang_areas` VALUES ('1033', '118', '玉屏', '3');
INSERT INTO `yang_areas` VALUES ('1034', '118', '印江', '3');
INSERT INTO `yang_areas` VALUES ('1035', '118', '沿河', '3');
INSERT INTO `yang_areas` VALUES ('1036', '118', '松桃', '3');
INSERT INTO `yang_areas` VALUES ('1037', '118', '万山特区', '3');
INSERT INTO `yang_areas` VALUES ('1038', '119', '红花岗区', '3');
INSERT INTO `yang_areas` VALUES ('1039', '119', '务川县', '3');
INSERT INTO `yang_areas` VALUES ('1040', '119', '道真县', '3');
INSERT INTO `yang_areas` VALUES ('1041', '119', '汇川区', '3');
INSERT INTO `yang_areas` VALUES ('1042', '119', '赤水市', '3');
INSERT INTO `yang_areas` VALUES ('1043', '119', '仁怀市', '3');
INSERT INTO `yang_areas` VALUES ('1044', '119', '遵义县', '3');
INSERT INTO `yang_areas` VALUES ('1045', '119', '桐梓县', '3');
INSERT INTO `yang_areas` VALUES ('1046', '119', '绥阳县', '3');
INSERT INTO `yang_areas` VALUES ('1047', '119', '正安县', '3');
INSERT INTO `yang_areas` VALUES ('1048', '119', '凤冈县', '3');
INSERT INTO `yang_areas` VALUES ('1049', '119', '湄潭县', '3');
INSERT INTO `yang_areas` VALUES ('1050', '119', '余庆县', '3');
INSERT INTO `yang_areas` VALUES ('1051', '119', '习水县', '3');
INSERT INTO `yang_areas` VALUES ('1052', '119', '道真', '3');
INSERT INTO `yang_areas` VALUES ('1053', '119', '务川', '3');
INSERT INTO `yang_areas` VALUES ('1054', '120', '秀英区', '3');
INSERT INTO `yang_areas` VALUES ('1055', '120', '龙华区', '3');
INSERT INTO `yang_areas` VALUES ('1056', '120', '琼山区', '3');
INSERT INTO `yang_areas` VALUES ('1057', '120', '美兰区', '3');
INSERT INTO `yang_areas` VALUES ('1058', '137', '市区', '3');
INSERT INTO `yang_areas` VALUES ('1059', '137', '洋浦开发区', '3');
INSERT INTO `yang_areas` VALUES ('1060', '137', '那大镇', '3');
INSERT INTO `yang_areas` VALUES ('1061', '137', '王五镇', '3');
INSERT INTO `yang_areas` VALUES ('1062', '137', '雅星镇', '3');
INSERT INTO `yang_areas` VALUES ('1063', '137', '大成镇', '3');
INSERT INTO `yang_areas` VALUES ('1064', '137', '中和镇', '3');
INSERT INTO `yang_areas` VALUES ('1065', '137', '峨蔓镇', '3');
INSERT INTO `yang_areas` VALUES ('1066', '137', '南丰镇', '3');
INSERT INTO `yang_areas` VALUES ('1067', '137', '白马井镇', '3');
INSERT INTO `yang_areas` VALUES ('1068', '137', '兰洋镇', '3');
INSERT INTO `yang_areas` VALUES ('1069', '137', '和庆镇', '3');
INSERT INTO `yang_areas` VALUES ('1070', '137', '海头镇', '3');
INSERT INTO `yang_areas` VALUES ('1071', '137', '排浦镇', '3');
INSERT INTO `yang_areas` VALUES ('1072', '137', '东成镇', '3');
INSERT INTO `yang_areas` VALUES ('1073', '137', '光村镇', '3');
INSERT INTO `yang_areas` VALUES ('1074', '137', '木棠镇', '3');
INSERT INTO `yang_areas` VALUES ('1075', '137', '新州镇', '3');
INSERT INTO `yang_areas` VALUES ('1076', '137', '三都镇', '3');
INSERT INTO `yang_areas` VALUES ('1077', '137', '其他', '3');
INSERT INTO `yang_areas` VALUES ('1078', '138', '长安区', '3');
INSERT INTO `yang_areas` VALUES ('1079', '138', '桥东区', '3');
INSERT INTO `yang_areas` VALUES ('1080', '138', '桥西区', '3');
INSERT INTO `yang_areas` VALUES ('1081', '138', '新华区', '3');
INSERT INTO `yang_areas` VALUES ('1082', '138', '裕华区', '3');
INSERT INTO `yang_areas` VALUES ('1083', '138', '井陉矿区', '3');
INSERT INTO `yang_areas` VALUES ('1084', '138', '高新区', '3');
INSERT INTO `yang_areas` VALUES ('1085', '138', '辛集市', '3');
INSERT INTO `yang_areas` VALUES ('1086', '138', '藁城市', '3');
INSERT INTO `yang_areas` VALUES ('1087', '138', '晋州市', '3');
INSERT INTO `yang_areas` VALUES ('1088', '138', '新乐市', '3');
INSERT INTO `yang_areas` VALUES ('1089', '138', '鹿泉市', '3');
INSERT INTO `yang_areas` VALUES ('1090', '138', '井陉县', '3');
INSERT INTO `yang_areas` VALUES ('1091', '138', '正定县', '3');
INSERT INTO `yang_areas` VALUES ('1092', '138', '栾城县', '3');
INSERT INTO `yang_areas` VALUES ('1093', '138', '行唐县', '3');
INSERT INTO `yang_areas` VALUES ('1094', '138', '灵寿县', '3');
INSERT INTO `yang_areas` VALUES ('1095', '138', '高邑县', '3');
INSERT INTO `yang_areas` VALUES ('1096', '138', '深泽县', '3');
INSERT INTO `yang_areas` VALUES ('1097', '138', '赞皇县', '3');
INSERT INTO `yang_areas` VALUES ('1098', '138', '无极县', '3');
INSERT INTO `yang_areas` VALUES ('1099', '138', '平山县', '3');
INSERT INTO `yang_areas` VALUES ('1100', '138', '元氏县', '3');
INSERT INTO `yang_areas` VALUES ('1101', '138', '赵县', '3');
INSERT INTO `yang_areas` VALUES ('1102', '139', '新市区', '3');
INSERT INTO `yang_areas` VALUES ('1103', '139', '南市区', '3');
INSERT INTO `yang_areas` VALUES ('1104', '139', '北市区', '3');
INSERT INTO `yang_areas` VALUES ('1105', '139', '涿州市', '3');
INSERT INTO `yang_areas` VALUES ('1106', '139', '定州市', '3');
INSERT INTO `yang_areas` VALUES ('1107', '139', '安国市', '3');
INSERT INTO `yang_areas` VALUES ('1108', '139', '高碑店市', '3');
INSERT INTO `yang_areas` VALUES ('1109', '139', '满城县', '3');
INSERT INTO `yang_areas` VALUES ('1110', '139', '清苑县', '3');
INSERT INTO `yang_areas` VALUES ('1111', '139', '涞水县', '3');
INSERT INTO `yang_areas` VALUES ('1112', '139', '阜平县', '3');
INSERT INTO `yang_areas` VALUES ('1113', '139', '徐水县', '3');
INSERT INTO `yang_areas` VALUES ('1114', '139', '定兴县', '3');
INSERT INTO `yang_areas` VALUES ('1115', '139', '唐县', '3');
INSERT INTO `yang_areas` VALUES ('1116', '139', '高阳县', '3');
INSERT INTO `yang_areas` VALUES ('1117', '139', '容城县', '3');
INSERT INTO `yang_areas` VALUES ('1118', '139', '涞源县', '3');
INSERT INTO `yang_areas` VALUES ('1119', '139', '望都县', '3');
INSERT INTO `yang_areas` VALUES ('1120', '139', '安新县', '3');
INSERT INTO `yang_areas` VALUES ('1121', '139', '易县', '3');
INSERT INTO `yang_areas` VALUES ('1122', '139', '曲阳县', '3');
INSERT INTO `yang_areas` VALUES ('1123', '139', '蠡县', '3');
INSERT INTO `yang_areas` VALUES ('1124', '139', '顺平县', '3');
INSERT INTO `yang_areas` VALUES ('1125', '139', '博野县', '3');
INSERT INTO `yang_areas` VALUES ('1126', '139', '雄县', '3');
INSERT INTO `yang_areas` VALUES ('1127', '140', '运河区', '3');
INSERT INTO `yang_areas` VALUES ('1128', '140', '新华区', '3');
INSERT INTO `yang_areas` VALUES ('1129', '140', '泊头市', '3');
INSERT INTO `yang_areas` VALUES ('1130', '140', '任丘市', '3');
INSERT INTO `yang_areas` VALUES ('1131', '140', '黄骅市', '3');
INSERT INTO `yang_areas` VALUES ('1132', '140', '河间市', '3');
INSERT INTO `yang_areas` VALUES ('1133', '140', '沧县', '3');
INSERT INTO `yang_areas` VALUES ('1134', '140', '青县', '3');
INSERT INTO `yang_areas` VALUES ('1135', '140', '东光县', '3');
INSERT INTO `yang_areas` VALUES ('1136', '140', '海兴县', '3');
INSERT INTO `yang_areas` VALUES ('1137', '140', '盐山县', '3');
INSERT INTO `yang_areas` VALUES ('1138', '140', '肃宁县', '3');
INSERT INTO `yang_areas` VALUES ('1139', '140', '南皮县', '3');
INSERT INTO `yang_areas` VALUES ('1140', '140', '吴桥县', '3');
INSERT INTO `yang_areas` VALUES ('1141', '140', '献县', '3');
INSERT INTO `yang_areas` VALUES ('1142', '140', '孟村', '3');
INSERT INTO `yang_areas` VALUES ('1143', '141', '双桥区', '3');
INSERT INTO `yang_areas` VALUES ('1144', '141', '双滦区', '3');
INSERT INTO `yang_areas` VALUES ('1145', '141', '鹰手营子矿区', '3');
INSERT INTO `yang_areas` VALUES ('1146', '141', '承德县', '3');
INSERT INTO `yang_areas` VALUES ('1147', '141', '兴隆县', '3');
INSERT INTO `yang_areas` VALUES ('1148', '141', '平泉县', '3');
INSERT INTO `yang_areas` VALUES ('1149', '141', '滦平县', '3');
INSERT INTO `yang_areas` VALUES ('1150', '141', '隆化县', '3');
INSERT INTO `yang_areas` VALUES ('1151', '141', '丰宁', '3');
INSERT INTO `yang_areas` VALUES ('1152', '141', '宽城', '3');
INSERT INTO `yang_areas` VALUES ('1153', '141', '围场', '3');
INSERT INTO `yang_areas` VALUES ('1154', '142', '从台区', '3');
INSERT INTO `yang_areas` VALUES ('1155', '142', '复兴区', '3');
INSERT INTO `yang_areas` VALUES ('1156', '142', '邯山区', '3');
INSERT INTO `yang_areas` VALUES ('1157', '142', '峰峰矿区', '3');
INSERT INTO `yang_areas` VALUES ('1158', '142', '武安市', '3');
INSERT INTO `yang_areas` VALUES ('1159', '142', '邯郸县', '3');
INSERT INTO `yang_areas` VALUES ('1160', '142', '临漳县', '3');
INSERT INTO `yang_areas` VALUES ('1161', '142', '成安县', '3');
INSERT INTO `yang_areas` VALUES ('1162', '142', '大名县', '3');
INSERT INTO `yang_areas` VALUES ('1163', '142', '涉县', '3');
INSERT INTO `yang_areas` VALUES ('1164', '142', '磁县', '3');
INSERT INTO `yang_areas` VALUES ('1165', '142', '肥乡县', '3');
INSERT INTO `yang_areas` VALUES ('1166', '142', '永年县', '3');
INSERT INTO `yang_areas` VALUES ('1167', '142', '邱县', '3');
INSERT INTO `yang_areas` VALUES ('1168', '142', '鸡泽县', '3');
INSERT INTO `yang_areas` VALUES ('1169', '142', '广平县', '3');
INSERT INTO `yang_areas` VALUES ('1170', '142', '馆陶县', '3');
INSERT INTO `yang_areas` VALUES ('1171', '142', '魏县', '3');
INSERT INTO `yang_areas` VALUES ('1172', '142', '曲周县', '3');
INSERT INTO `yang_areas` VALUES ('1173', '143', '桃城区', '3');
INSERT INTO `yang_areas` VALUES ('1174', '143', '冀州市', '3');
INSERT INTO `yang_areas` VALUES ('1175', '143', '深州市', '3');
INSERT INTO `yang_areas` VALUES ('1176', '143', '枣强县', '3');
INSERT INTO `yang_areas` VALUES ('1177', '143', '武邑县', '3');
INSERT INTO `yang_areas` VALUES ('1178', '143', '武强县', '3');
INSERT INTO `yang_areas` VALUES ('1179', '143', '饶阳县', '3');
INSERT INTO `yang_areas` VALUES ('1180', '143', '安平县', '3');
INSERT INTO `yang_areas` VALUES ('1181', '143', '故城县', '3');
INSERT INTO `yang_areas` VALUES ('1182', '143', '景县', '3');
INSERT INTO `yang_areas` VALUES ('1183', '143', '阜城县', '3');
INSERT INTO `yang_areas` VALUES ('1184', '144', '安次区', '3');
INSERT INTO `yang_areas` VALUES ('1185', '144', '广阳区', '3');
INSERT INTO `yang_areas` VALUES ('1186', '144', '霸州市', '3');
INSERT INTO `yang_areas` VALUES ('1187', '144', '三河市', '3');
INSERT INTO `yang_areas` VALUES ('1188', '144', '固安县', '3');
INSERT INTO `yang_areas` VALUES ('1189', '144', '永清县', '3');
INSERT INTO `yang_areas` VALUES ('1190', '144', '香河县', '3');
INSERT INTO `yang_areas` VALUES ('1191', '144', '大城县', '3');
INSERT INTO `yang_areas` VALUES ('1192', '144', '文安县', '3');
INSERT INTO `yang_areas` VALUES ('1193', '144', '大厂', '3');
INSERT INTO `yang_areas` VALUES ('1194', '145', '海港区', '3');
INSERT INTO `yang_areas` VALUES ('1195', '145', '山海关区', '3');
INSERT INTO `yang_areas` VALUES ('1196', '145', '北戴河区', '3');
INSERT INTO `yang_areas` VALUES ('1197', '145', '昌黎县', '3');
INSERT INTO `yang_areas` VALUES ('1198', '145', '抚宁县', '3');
INSERT INTO `yang_areas` VALUES ('1199', '145', '卢龙县', '3');
INSERT INTO `yang_areas` VALUES ('1200', '145', '青龙', '3');
INSERT INTO `yang_areas` VALUES ('1201', '146', '路北区', '3');
INSERT INTO `yang_areas` VALUES ('1202', '146', '路南区', '3');
INSERT INTO `yang_areas` VALUES ('1203', '146', '古冶区', '3');
INSERT INTO `yang_areas` VALUES ('1204', '146', '开平区', '3');
INSERT INTO `yang_areas` VALUES ('1205', '146', '丰南区', '3');
INSERT INTO `yang_areas` VALUES ('1206', '146', '丰润区', '3');
INSERT INTO `yang_areas` VALUES ('1207', '146', '遵化市', '3');
INSERT INTO `yang_areas` VALUES ('1208', '146', '迁安市', '3');
INSERT INTO `yang_areas` VALUES ('1209', '146', '滦县', '3');
INSERT INTO `yang_areas` VALUES ('1210', '146', '滦南县', '3');
INSERT INTO `yang_areas` VALUES ('1211', '146', '乐亭县', '3');
INSERT INTO `yang_areas` VALUES ('1212', '146', '迁西县', '3');
INSERT INTO `yang_areas` VALUES ('1213', '146', '玉田县', '3');
INSERT INTO `yang_areas` VALUES ('1214', '146', '唐海县', '3');
INSERT INTO `yang_areas` VALUES ('1215', '147', '桥东区', '3');
INSERT INTO `yang_areas` VALUES ('1216', '147', '桥西区', '3');
INSERT INTO `yang_areas` VALUES ('1217', '147', '南宫市', '3');
INSERT INTO `yang_areas` VALUES ('1218', '147', '沙河市', '3');
INSERT INTO `yang_areas` VALUES ('1219', '147', '邢台县', '3');
INSERT INTO `yang_areas` VALUES ('1220', '147', '临城县', '3');
INSERT INTO `yang_areas` VALUES ('1221', '147', '内丘县', '3');
INSERT INTO `yang_areas` VALUES ('1222', '147', '柏乡县', '3');
INSERT INTO `yang_areas` VALUES ('1223', '147', '隆尧县', '3');
INSERT INTO `yang_areas` VALUES ('1224', '147', '任县', '3');
INSERT INTO `yang_areas` VALUES ('1225', '147', '南和县', '3');
INSERT INTO `yang_areas` VALUES ('1226', '147', '宁晋县', '3');
INSERT INTO `yang_areas` VALUES ('1227', '147', '巨鹿县', '3');
INSERT INTO `yang_areas` VALUES ('1228', '147', '新河县', '3');
INSERT INTO `yang_areas` VALUES ('1229', '147', '广宗县', '3');
INSERT INTO `yang_areas` VALUES ('1230', '147', '平乡县', '3');
INSERT INTO `yang_areas` VALUES ('1231', '147', '威县', '3');
INSERT INTO `yang_areas` VALUES ('1232', '147', '清河县', '3');
INSERT INTO `yang_areas` VALUES ('1233', '147', '临西县', '3');
INSERT INTO `yang_areas` VALUES ('1234', '148', '桥西区', '3');
INSERT INTO `yang_areas` VALUES ('1235', '148', '桥东区', '3');
INSERT INTO `yang_areas` VALUES ('1236', '148', '宣化区', '3');
INSERT INTO `yang_areas` VALUES ('1237', '148', '下花园区', '3');
INSERT INTO `yang_areas` VALUES ('1238', '148', '宣化县', '3');
INSERT INTO `yang_areas` VALUES ('1239', '148', '张北县', '3');
INSERT INTO `yang_areas` VALUES ('1240', '148', '康保县', '3');
INSERT INTO `yang_areas` VALUES ('1241', '148', '沽源县', '3');
INSERT INTO `yang_areas` VALUES ('1242', '148', '尚义县', '3');
INSERT INTO `yang_areas` VALUES ('1243', '148', '蔚县', '3');
INSERT INTO `yang_areas` VALUES ('1244', '148', '阳原县', '3');
INSERT INTO `yang_areas` VALUES ('1245', '148', '怀安县', '3');
INSERT INTO `yang_areas` VALUES ('1246', '148', '万全县', '3');
INSERT INTO `yang_areas` VALUES ('1247', '148', '怀来县', '3');
INSERT INTO `yang_areas` VALUES ('1248', '148', '涿鹿县', '3');
INSERT INTO `yang_areas` VALUES ('1249', '148', '赤城县', '3');
INSERT INTO `yang_areas` VALUES ('1250', '148', '崇礼县', '3');
INSERT INTO `yang_areas` VALUES ('1251', '149', '金水区', '3');
INSERT INTO `yang_areas` VALUES ('1252', '149', '邙山区', '3');
INSERT INTO `yang_areas` VALUES ('1253', '149', '二七区', '3');
INSERT INTO `yang_areas` VALUES ('1254', '149', '管城区', '3');
INSERT INTO `yang_areas` VALUES ('1255', '149', '中原区', '3');
INSERT INTO `yang_areas` VALUES ('1256', '149', '上街区', '3');
INSERT INTO `yang_areas` VALUES ('1257', '149', '惠济区', '3');
INSERT INTO `yang_areas` VALUES ('1258', '149', '郑东新区', '3');
INSERT INTO `yang_areas` VALUES ('1259', '149', '经济技术开发区', '3');
INSERT INTO `yang_areas` VALUES ('1260', '149', '高新开发区', '3');
INSERT INTO `yang_areas` VALUES ('1261', '149', '出口加工区', '3');
INSERT INTO `yang_areas` VALUES ('1262', '149', '巩义市', '3');
INSERT INTO `yang_areas` VALUES ('1263', '149', '荥阳市', '3');
INSERT INTO `yang_areas` VALUES ('1264', '149', '新密市', '3');
INSERT INTO `yang_areas` VALUES ('1265', '149', '新郑市', '3');
INSERT INTO `yang_areas` VALUES ('1266', '149', '登封市', '3');
INSERT INTO `yang_areas` VALUES ('1267', '149', '中牟县', '3');
INSERT INTO `yang_areas` VALUES ('1268', '150', '西工区', '3');
INSERT INTO `yang_areas` VALUES ('1269', '150', '老城区', '3');
INSERT INTO `yang_areas` VALUES ('1270', '150', '涧西区', '3');
INSERT INTO `yang_areas` VALUES ('1271', '150', '瀍河回族区', '3');
INSERT INTO `yang_areas` VALUES ('1272', '150', '洛龙区', '3');
INSERT INTO `yang_areas` VALUES ('1273', '150', '吉利区', '3');
INSERT INTO `yang_areas` VALUES ('1274', '150', '偃师市', '3');
INSERT INTO `yang_areas` VALUES ('1275', '150', '孟津县', '3');
INSERT INTO `yang_areas` VALUES ('1276', '150', '新安县', '3');
INSERT INTO `yang_areas` VALUES ('1277', '150', '栾川县', '3');
INSERT INTO `yang_areas` VALUES ('1278', '150', '嵩县', '3');
INSERT INTO `yang_areas` VALUES ('1279', '150', '汝阳县', '3');
INSERT INTO `yang_areas` VALUES ('1280', '150', '宜阳县', '3');
INSERT INTO `yang_areas` VALUES ('1281', '150', '洛宁县', '3');
INSERT INTO `yang_areas` VALUES ('1282', '150', '伊川县', '3');
INSERT INTO `yang_areas` VALUES ('1283', '151', '鼓楼区', '3');
INSERT INTO `yang_areas` VALUES ('1284', '151', '龙亭区', '3');
INSERT INTO `yang_areas` VALUES ('1285', '151', '顺河回族区', '3');
INSERT INTO `yang_areas` VALUES ('1286', '151', '金明区', '3');
INSERT INTO `yang_areas` VALUES ('1287', '151', '禹王台区', '3');
INSERT INTO `yang_areas` VALUES ('1288', '151', '杞县', '3');
INSERT INTO `yang_areas` VALUES ('1289', '151', '通许县', '3');
INSERT INTO `yang_areas` VALUES ('1290', '151', '尉氏县', '3');
INSERT INTO `yang_areas` VALUES ('1291', '151', '开封县', '3');
INSERT INTO `yang_areas` VALUES ('1292', '151', '兰考县', '3');
INSERT INTO `yang_areas` VALUES ('1293', '152', '北关区', '3');
INSERT INTO `yang_areas` VALUES ('1294', '152', '文峰区', '3');
INSERT INTO `yang_areas` VALUES ('1295', '152', '殷都区', '3');
INSERT INTO `yang_areas` VALUES ('1296', '152', '龙安区', '3');
INSERT INTO `yang_areas` VALUES ('1297', '152', '林州市', '3');
INSERT INTO `yang_areas` VALUES ('1298', '152', '安阳县', '3');
INSERT INTO `yang_areas` VALUES ('1299', '152', '汤阴县', '3');
INSERT INTO `yang_areas` VALUES ('1300', '152', '滑县', '3');
INSERT INTO `yang_areas` VALUES ('1301', '152', '内黄县', '3');
INSERT INTO `yang_areas` VALUES ('1302', '153', '淇滨区', '3');
INSERT INTO `yang_areas` VALUES ('1303', '153', '山城区', '3');
INSERT INTO `yang_areas` VALUES ('1304', '153', '鹤山区', '3');
INSERT INTO `yang_areas` VALUES ('1305', '153', '浚县', '3');
INSERT INTO `yang_areas` VALUES ('1306', '153', '淇县', '3');
INSERT INTO `yang_areas` VALUES ('1307', '154', '济源市', '3');
INSERT INTO `yang_areas` VALUES ('1308', '155', '解放区', '3');
INSERT INTO `yang_areas` VALUES ('1309', '155', '中站区', '3');
INSERT INTO `yang_areas` VALUES ('1310', '155', '马村区', '3');
INSERT INTO `yang_areas` VALUES ('1311', '155', '山阳区', '3');
INSERT INTO `yang_areas` VALUES ('1312', '155', '沁阳市', '3');
INSERT INTO `yang_areas` VALUES ('1313', '155', '孟州市', '3');
INSERT INTO `yang_areas` VALUES ('1314', '155', '修武县', '3');
INSERT INTO `yang_areas` VALUES ('1315', '155', '博爱县', '3');
INSERT INTO `yang_areas` VALUES ('1316', '155', '武陟县', '3');
INSERT INTO `yang_areas` VALUES ('1317', '155', '温县', '3');
INSERT INTO `yang_areas` VALUES ('1318', '156', '卧龙区', '3');
INSERT INTO `yang_areas` VALUES ('1319', '156', '宛城区', '3');
INSERT INTO `yang_areas` VALUES ('1320', '156', '邓州市', '3');
INSERT INTO `yang_areas` VALUES ('1321', '156', '南召县', '3');
INSERT INTO `yang_areas` VALUES ('1322', '156', '方城县', '3');
INSERT INTO `yang_areas` VALUES ('1323', '156', '西峡县', '3');
INSERT INTO `yang_areas` VALUES ('1324', '156', '镇平县', '3');
INSERT INTO `yang_areas` VALUES ('1325', '156', '内乡县', '3');
INSERT INTO `yang_areas` VALUES ('1326', '156', '淅川县', '3');
INSERT INTO `yang_areas` VALUES ('1327', '156', '社旗县', '3');
INSERT INTO `yang_areas` VALUES ('1328', '156', '唐河县', '3');
INSERT INTO `yang_areas` VALUES ('1329', '156', '新野县', '3');
INSERT INTO `yang_areas` VALUES ('1330', '156', '桐柏县', '3');
INSERT INTO `yang_areas` VALUES ('1331', '157', '新华区', '3');
INSERT INTO `yang_areas` VALUES ('1332', '157', '卫东区', '3');
INSERT INTO `yang_areas` VALUES ('1333', '157', '湛河区', '3');
INSERT INTO `yang_areas` VALUES ('1334', '157', '石龙区', '3');
INSERT INTO `yang_areas` VALUES ('1335', '157', '舞钢市', '3');
INSERT INTO `yang_areas` VALUES ('1336', '157', '汝州市', '3');
INSERT INTO `yang_areas` VALUES ('1337', '157', '宝丰县', '3');
INSERT INTO `yang_areas` VALUES ('1338', '157', '叶县', '3');
INSERT INTO `yang_areas` VALUES ('1339', '157', '鲁山县', '3');
INSERT INTO `yang_areas` VALUES ('1340', '157', '郏县', '3');
INSERT INTO `yang_areas` VALUES ('1341', '158', '湖滨区', '3');
INSERT INTO `yang_areas` VALUES ('1342', '158', '义马市', '3');
INSERT INTO `yang_areas` VALUES ('1343', '158', '灵宝市', '3');
INSERT INTO `yang_areas` VALUES ('1344', '158', '渑池县', '3');
INSERT INTO `yang_areas` VALUES ('1345', '158', '陕县', '3');
INSERT INTO `yang_areas` VALUES ('1346', '158', '卢氏县', '3');
INSERT INTO `yang_areas` VALUES ('1347', '159', '梁园区', '3');
INSERT INTO `yang_areas` VALUES ('1348', '159', '睢阳区', '3');
INSERT INTO `yang_areas` VALUES ('1349', '159', '永城市', '3');
INSERT INTO `yang_areas` VALUES ('1350', '159', '民权县', '3');
INSERT INTO `yang_areas` VALUES ('1351', '159', '睢县', '3');
INSERT INTO `yang_areas` VALUES ('1352', '159', '宁陵县', '3');
INSERT INTO `yang_areas` VALUES ('1353', '159', '虞城县', '3');
INSERT INTO `yang_areas` VALUES ('1354', '159', '柘城县', '3');
INSERT INTO `yang_areas` VALUES ('1355', '159', '夏邑县', '3');
INSERT INTO `yang_areas` VALUES ('1356', '160', '卫滨区', '3');
INSERT INTO `yang_areas` VALUES ('1357', '160', '红旗区', '3');
INSERT INTO `yang_areas` VALUES ('1358', '160', '凤泉区', '3');
INSERT INTO `yang_areas` VALUES ('1359', '160', '牧野区', '3');
INSERT INTO `yang_areas` VALUES ('1360', '160', '卫辉市', '3');
INSERT INTO `yang_areas` VALUES ('1361', '160', '辉县市', '3');
INSERT INTO `yang_areas` VALUES ('1362', '160', '新乡县', '3');
INSERT INTO `yang_areas` VALUES ('1363', '160', '获嘉县', '3');
INSERT INTO `yang_areas` VALUES ('1364', '160', '原阳县', '3');
INSERT INTO `yang_areas` VALUES ('1365', '160', '延津县', '3');
INSERT INTO `yang_areas` VALUES ('1366', '160', '封丘县', '3');
INSERT INTO `yang_areas` VALUES ('1367', '160', '长垣县', '3');
INSERT INTO `yang_areas` VALUES ('1368', '161', '浉河区', '3');
INSERT INTO `yang_areas` VALUES ('1369', '161', '平桥区', '3');
INSERT INTO `yang_areas` VALUES ('1370', '161', '罗山县', '3');
INSERT INTO `yang_areas` VALUES ('1371', '161', '光山县', '3');
INSERT INTO `yang_areas` VALUES ('1372', '161', '新县', '3');
INSERT INTO `yang_areas` VALUES ('1373', '161', '商城县', '3');
INSERT INTO `yang_areas` VALUES ('1374', '161', '固始县', '3');
INSERT INTO `yang_areas` VALUES ('1375', '161', '潢川县', '3');
INSERT INTO `yang_areas` VALUES ('1376', '161', '淮滨县', '3');
INSERT INTO `yang_areas` VALUES ('1377', '161', '息县', '3');
INSERT INTO `yang_areas` VALUES ('1378', '162', '魏都区', '3');
INSERT INTO `yang_areas` VALUES ('1379', '162', '禹州市', '3');
INSERT INTO `yang_areas` VALUES ('1380', '162', '长葛市', '3');
INSERT INTO `yang_areas` VALUES ('1381', '162', '许昌县', '3');
INSERT INTO `yang_areas` VALUES ('1382', '162', '鄢陵县', '3');
INSERT INTO `yang_areas` VALUES ('1383', '162', '襄城县', '3');
INSERT INTO `yang_areas` VALUES ('1384', '163', '川汇区', '3');
INSERT INTO `yang_areas` VALUES ('1385', '163', '项城市', '3');
INSERT INTO `yang_areas` VALUES ('1386', '163', '扶沟县', '3');
INSERT INTO `yang_areas` VALUES ('1387', '163', '西华县', '3');
INSERT INTO `yang_areas` VALUES ('1388', '163', '商水县', '3');
INSERT INTO `yang_areas` VALUES ('1389', '163', '沈丘县', '3');
INSERT INTO `yang_areas` VALUES ('1390', '163', '郸城县', '3');
INSERT INTO `yang_areas` VALUES ('1391', '163', '淮阳县', '3');
INSERT INTO `yang_areas` VALUES ('1392', '163', '太康县', '3');
INSERT INTO `yang_areas` VALUES ('1393', '163', '鹿邑县', '3');
INSERT INTO `yang_areas` VALUES ('1394', '164', '驿城区', '3');
INSERT INTO `yang_areas` VALUES ('1395', '164', '西平县', '3');
INSERT INTO `yang_areas` VALUES ('1396', '164', '上蔡县', '3');
INSERT INTO `yang_areas` VALUES ('1397', '164', '平舆县', '3');
INSERT INTO `yang_areas` VALUES ('1398', '164', '正阳县', '3');
INSERT INTO `yang_areas` VALUES ('1399', '164', '确山县', '3');
INSERT INTO `yang_areas` VALUES ('1400', '164', '泌阳县', '3');
INSERT INTO `yang_areas` VALUES ('1401', '164', '汝南县', '3');
INSERT INTO `yang_areas` VALUES ('1402', '164', '遂平县', '3');
INSERT INTO `yang_areas` VALUES ('1403', '164', '新蔡县', '3');
INSERT INTO `yang_areas` VALUES ('1404', '165', '郾城区', '3');
INSERT INTO `yang_areas` VALUES ('1405', '165', '源汇区', '3');
INSERT INTO `yang_areas` VALUES ('1406', '165', '召陵区', '3');
INSERT INTO `yang_areas` VALUES ('1407', '165', '舞阳县', '3');
INSERT INTO `yang_areas` VALUES ('1408', '165', '临颍县', '3');
INSERT INTO `yang_areas` VALUES ('1409', '166', '华龙区', '3');
INSERT INTO `yang_areas` VALUES ('1410', '166', '清丰县', '3');
INSERT INTO `yang_areas` VALUES ('1411', '166', '南乐县', '3');
INSERT INTO `yang_areas` VALUES ('1412', '166', '范县', '3');
INSERT INTO `yang_areas` VALUES ('1413', '166', '台前县', '3');
INSERT INTO `yang_areas` VALUES ('1414', '166', '濮阳县', '3');
INSERT INTO `yang_areas` VALUES ('1415', '167', '道里区', '3');
INSERT INTO `yang_areas` VALUES ('1416', '167', '南岗区', '3');
INSERT INTO `yang_areas` VALUES ('1417', '167', '动力区', '3');
INSERT INTO `yang_areas` VALUES ('1418', '167', '平房区', '3');
INSERT INTO `yang_areas` VALUES ('1419', '167', '香坊区', '3');
INSERT INTO `yang_areas` VALUES ('1420', '167', '太平区', '3');
INSERT INTO `yang_areas` VALUES ('1421', '167', '道外区', '3');
INSERT INTO `yang_areas` VALUES ('1422', '167', '阿城区', '3');
INSERT INTO `yang_areas` VALUES ('1423', '167', '呼兰区', '3');
INSERT INTO `yang_areas` VALUES ('1424', '167', '松北区', '3');
INSERT INTO `yang_areas` VALUES ('1425', '167', '尚志市', '3');
INSERT INTO `yang_areas` VALUES ('1426', '167', '双城市', '3');
INSERT INTO `yang_areas` VALUES ('1427', '167', '五常市', '3');
INSERT INTO `yang_areas` VALUES ('1428', '167', '方正县', '3');
INSERT INTO `yang_areas` VALUES ('1429', '167', '宾县', '3');
INSERT INTO `yang_areas` VALUES ('1430', '167', '依兰县', '3');
INSERT INTO `yang_areas` VALUES ('1431', '167', '巴彦县', '3');
INSERT INTO `yang_areas` VALUES ('1432', '167', '通河县', '3');
INSERT INTO `yang_areas` VALUES ('1433', '167', '木兰县', '3');
INSERT INTO `yang_areas` VALUES ('1434', '167', '延寿县', '3');
INSERT INTO `yang_areas` VALUES ('1435', '168', '萨尔图区', '3');
INSERT INTO `yang_areas` VALUES ('1436', '168', '红岗区', '3');
INSERT INTO `yang_areas` VALUES ('1437', '168', '龙凤区', '3');
INSERT INTO `yang_areas` VALUES ('1438', '168', '让胡路区', '3');
INSERT INTO `yang_areas` VALUES ('1439', '168', '大同区', '3');
INSERT INTO `yang_areas` VALUES ('1440', '168', '肇州县', '3');
INSERT INTO `yang_areas` VALUES ('1441', '168', '肇源县', '3');
INSERT INTO `yang_areas` VALUES ('1442', '168', '林甸县', '3');
INSERT INTO `yang_areas` VALUES ('1443', '168', '杜尔伯特', '3');
INSERT INTO `yang_areas` VALUES ('1444', '169', '呼玛县', '3');
INSERT INTO `yang_areas` VALUES ('1445', '169', '漠河县', '3');
INSERT INTO `yang_areas` VALUES ('1446', '169', '塔河县', '3');
INSERT INTO `yang_areas` VALUES ('1447', '170', '兴山区', '3');
INSERT INTO `yang_areas` VALUES ('1448', '170', '工农区', '3');
INSERT INTO `yang_areas` VALUES ('1449', '170', '南山区', '3');
INSERT INTO `yang_areas` VALUES ('1450', '170', '兴安区', '3');
INSERT INTO `yang_areas` VALUES ('1451', '170', '向阳区', '3');
INSERT INTO `yang_areas` VALUES ('1452', '170', '东山区', '3');
INSERT INTO `yang_areas` VALUES ('1453', '170', '萝北县', '3');
INSERT INTO `yang_areas` VALUES ('1454', '170', '绥滨县', '3');
INSERT INTO `yang_areas` VALUES ('1455', '171', '爱辉区', '3');
INSERT INTO `yang_areas` VALUES ('1456', '171', '五大连池市', '3');
INSERT INTO `yang_areas` VALUES ('1457', '171', '北安市', '3');
INSERT INTO `yang_areas` VALUES ('1458', '171', '嫩江县', '3');
INSERT INTO `yang_areas` VALUES ('1459', '171', '逊克县', '3');
INSERT INTO `yang_areas` VALUES ('1460', '171', '孙吴县', '3');
INSERT INTO `yang_areas` VALUES ('1461', '172', '鸡冠区', '3');
INSERT INTO `yang_areas` VALUES ('1462', '172', '恒山区', '3');
INSERT INTO `yang_areas` VALUES ('1463', '172', '城子河区', '3');
INSERT INTO `yang_areas` VALUES ('1464', '172', '滴道区', '3');
INSERT INTO `yang_areas` VALUES ('1465', '172', '梨树区', '3');
INSERT INTO `yang_areas` VALUES ('1466', '172', '虎林市', '3');
INSERT INTO `yang_areas` VALUES ('1467', '172', '密山市', '3');
INSERT INTO `yang_areas` VALUES ('1468', '172', '鸡东县', '3');
INSERT INTO `yang_areas` VALUES ('1469', '173', '前进区', '3');
INSERT INTO `yang_areas` VALUES ('1470', '173', '郊区', '3');
INSERT INTO `yang_areas` VALUES ('1471', '173', '向阳区', '3');
INSERT INTO `yang_areas` VALUES ('1472', '173', '东风区', '3');
INSERT INTO `yang_areas` VALUES ('1473', '173', '同江市', '3');
INSERT INTO `yang_areas` VALUES ('1474', '173', '富锦市', '3');
INSERT INTO `yang_areas` VALUES ('1475', '173', '桦南县', '3');
INSERT INTO `yang_areas` VALUES ('1476', '173', '桦川县', '3');
INSERT INTO `yang_areas` VALUES ('1477', '173', '汤原县', '3');
INSERT INTO `yang_areas` VALUES ('1478', '173', '抚远县', '3');
INSERT INTO `yang_areas` VALUES ('1479', '174', '爱民区', '3');
INSERT INTO `yang_areas` VALUES ('1480', '174', '东安区', '3');
INSERT INTO `yang_areas` VALUES ('1481', '174', '阳明区', '3');
INSERT INTO `yang_areas` VALUES ('1482', '174', '西安区', '3');
INSERT INTO `yang_areas` VALUES ('1483', '174', '绥芬河市', '3');
INSERT INTO `yang_areas` VALUES ('1484', '174', '海林市', '3');
INSERT INTO `yang_areas` VALUES ('1485', '174', '宁安市', '3');
INSERT INTO `yang_areas` VALUES ('1486', '174', '穆棱市', '3');
INSERT INTO `yang_areas` VALUES ('1487', '174', '东宁县', '3');
INSERT INTO `yang_areas` VALUES ('1488', '174', '林口县', '3');
INSERT INTO `yang_areas` VALUES ('1489', '175', '桃山区', '3');
INSERT INTO `yang_areas` VALUES ('1490', '175', '新兴区', '3');
INSERT INTO `yang_areas` VALUES ('1491', '175', '茄子河区', '3');
INSERT INTO `yang_areas` VALUES ('1492', '175', '勃利县', '3');
INSERT INTO `yang_areas` VALUES ('1493', '176', '龙沙区', '3');
INSERT INTO `yang_areas` VALUES ('1494', '176', '昂昂溪区', '3');
INSERT INTO `yang_areas` VALUES ('1495', '176', '铁峰区', '3');
INSERT INTO `yang_areas` VALUES ('1496', '176', '建华区', '3');
INSERT INTO `yang_areas` VALUES ('1497', '176', '富拉尔基区', '3');
INSERT INTO `yang_areas` VALUES ('1498', '176', '碾子山区', '3');
INSERT INTO `yang_areas` VALUES ('1499', '176', '梅里斯达斡尔区', '3');
INSERT INTO `yang_areas` VALUES ('1500', '176', '讷河市', '3');
INSERT INTO `yang_areas` VALUES ('1501', '176', '龙江县', '3');
INSERT INTO `yang_areas` VALUES ('1502', '176', '依安县', '3');
INSERT INTO `yang_areas` VALUES ('1503', '176', '泰来县', '3');
INSERT INTO `yang_areas` VALUES ('1504', '176', '甘南县', '3');
INSERT INTO `yang_areas` VALUES ('1505', '176', '富裕县', '3');
INSERT INTO `yang_areas` VALUES ('1506', '176', '克山县', '3');
INSERT INTO `yang_areas` VALUES ('1507', '176', '克东县', '3');
INSERT INTO `yang_areas` VALUES ('1508', '176', '拜泉县', '3');
INSERT INTO `yang_areas` VALUES ('1509', '177', '尖山区', '3');
INSERT INTO `yang_areas` VALUES ('1510', '177', '岭东区', '3');
INSERT INTO `yang_areas` VALUES ('1511', '177', '四方台区', '3');
INSERT INTO `yang_areas` VALUES ('1512', '177', '宝山区', '3');
INSERT INTO `yang_areas` VALUES ('1513', '177', '集贤县', '3');
INSERT INTO `yang_areas` VALUES ('1514', '177', '友谊县', '3');
INSERT INTO `yang_areas` VALUES ('1515', '177', '宝清县', '3');
INSERT INTO `yang_areas` VALUES ('1516', '177', '饶河县', '3');
INSERT INTO `yang_areas` VALUES ('1517', '178', '北林区', '3');
INSERT INTO `yang_areas` VALUES ('1518', '178', '安达市', '3');
INSERT INTO `yang_areas` VALUES ('1519', '178', '肇东市', '3');
INSERT INTO `yang_areas` VALUES ('1520', '178', '海伦市', '3');
INSERT INTO `yang_areas` VALUES ('1521', '178', '望奎县', '3');
INSERT INTO `yang_areas` VALUES ('1522', '178', '兰西县', '3');
INSERT INTO `yang_areas` VALUES ('1523', '178', '青冈县', '3');
INSERT INTO `yang_areas` VALUES ('1524', '178', '庆安县', '3');
INSERT INTO `yang_areas` VALUES ('1525', '178', '明水县', '3');
INSERT INTO `yang_areas` VALUES ('1526', '178', '绥棱县', '3');
INSERT INTO `yang_areas` VALUES ('1527', '179', '伊春区', '3');
INSERT INTO `yang_areas` VALUES ('1528', '179', '带岭区', '3');
INSERT INTO `yang_areas` VALUES ('1529', '179', '南岔区', '3');
INSERT INTO `yang_areas` VALUES ('1530', '179', '金山屯区', '3');
INSERT INTO `yang_areas` VALUES ('1531', '179', '西林区', '3');
INSERT INTO `yang_areas` VALUES ('1532', '179', '美溪区', '3');
INSERT INTO `yang_areas` VALUES ('1533', '179', '乌马河区', '3');
INSERT INTO `yang_areas` VALUES ('1534', '179', '翠峦区', '3');
INSERT INTO `yang_areas` VALUES ('1535', '179', '友好区', '3');
INSERT INTO `yang_areas` VALUES ('1536', '179', '上甘岭区', '3');
INSERT INTO `yang_areas` VALUES ('1537', '179', '五营区', '3');
INSERT INTO `yang_areas` VALUES ('1538', '179', '红星区', '3');
INSERT INTO `yang_areas` VALUES ('1539', '179', '新青区', '3');
INSERT INTO `yang_areas` VALUES ('1540', '179', '汤旺河区', '3');
INSERT INTO `yang_areas` VALUES ('1541', '179', '乌伊岭区', '3');
INSERT INTO `yang_areas` VALUES ('1542', '179', '铁力市', '3');
INSERT INTO `yang_areas` VALUES ('1543', '179', '嘉荫县', '3');
INSERT INTO `yang_areas` VALUES ('1544', '180', '江岸区', '3');
INSERT INTO `yang_areas` VALUES ('1545', '180', '武昌区', '3');
INSERT INTO `yang_areas` VALUES ('1546', '180', '江汉区', '3');
INSERT INTO `yang_areas` VALUES ('1547', '180', '硚口区', '3');
INSERT INTO `yang_areas` VALUES ('1548', '180', '汉阳区', '3');
INSERT INTO `yang_areas` VALUES ('1549', '180', '青山区', '3');
INSERT INTO `yang_areas` VALUES ('1550', '180', '洪山区', '3');
INSERT INTO `yang_areas` VALUES ('1551', '180', '东西湖区', '3');
INSERT INTO `yang_areas` VALUES ('1552', '180', '汉南区', '3');
INSERT INTO `yang_areas` VALUES ('1553', '180', '蔡甸区', '3');
INSERT INTO `yang_areas` VALUES ('1554', '180', '江夏区', '3');
INSERT INTO `yang_areas` VALUES ('1555', '180', '黄陂区', '3');
INSERT INTO `yang_areas` VALUES ('1556', '180', '新洲区', '3');
INSERT INTO `yang_areas` VALUES ('1557', '180', '经济开发区', '3');
INSERT INTO `yang_areas` VALUES ('1558', '181', '仙桃市', '3');
INSERT INTO `yang_areas` VALUES ('1559', '182', '鄂城区', '3');
INSERT INTO `yang_areas` VALUES ('1560', '182', '华容区', '3');
INSERT INTO `yang_areas` VALUES ('1561', '182', '梁子湖区', '3');
INSERT INTO `yang_areas` VALUES ('1562', '183', '黄州区', '3');
INSERT INTO `yang_areas` VALUES ('1563', '183', '麻城市', '3');
INSERT INTO `yang_areas` VALUES ('1564', '183', '武穴市', '3');
INSERT INTO `yang_areas` VALUES ('1565', '183', '团风县', '3');
INSERT INTO `yang_areas` VALUES ('1566', '183', '红安县', '3');
INSERT INTO `yang_areas` VALUES ('1567', '183', '罗田县', '3');
INSERT INTO `yang_areas` VALUES ('1568', '183', '英山县', '3');
INSERT INTO `yang_areas` VALUES ('1569', '183', '浠水县', '3');
INSERT INTO `yang_areas` VALUES ('1570', '183', '蕲春县', '3');
INSERT INTO `yang_areas` VALUES ('1571', '183', '黄梅县', '3');
INSERT INTO `yang_areas` VALUES ('1572', '184', '黄石港区', '3');
INSERT INTO `yang_areas` VALUES ('1573', '184', '西塞山区', '3');
INSERT INTO `yang_areas` VALUES ('1574', '184', '下陆区', '3');
INSERT INTO `yang_areas` VALUES ('1575', '184', '铁山区', '3');
INSERT INTO `yang_areas` VALUES ('1576', '184', '大冶市', '3');
INSERT INTO `yang_areas` VALUES ('1577', '184', '阳新县', '3');
INSERT INTO `yang_areas` VALUES ('1578', '185', '东宝区', '3');
INSERT INTO `yang_areas` VALUES ('1579', '185', '掇刀区', '3');
INSERT INTO `yang_areas` VALUES ('1580', '185', '钟祥市', '3');
INSERT INTO `yang_areas` VALUES ('1581', '185', '京山县', '3');
INSERT INTO `yang_areas` VALUES ('1582', '185', '沙洋县', '3');
INSERT INTO `yang_areas` VALUES ('1583', '186', '沙市区', '3');
INSERT INTO `yang_areas` VALUES ('1584', '186', '荆州区', '3');
INSERT INTO `yang_areas` VALUES ('1585', '186', '石首市', '3');
INSERT INTO `yang_areas` VALUES ('1586', '186', '洪湖市', '3');
INSERT INTO `yang_areas` VALUES ('1587', '186', '松滋市', '3');
INSERT INTO `yang_areas` VALUES ('1588', '186', '公安县', '3');
INSERT INTO `yang_areas` VALUES ('1589', '186', '监利县', '3');
INSERT INTO `yang_areas` VALUES ('1590', '186', '江陵县', '3');
INSERT INTO `yang_areas` VALUES ('1591', '187', '潜江市', '3');
INSERT INTO `yang_areas` VALUES ('1592', '188', '神农架林区', '3');
INSERT INTO `yang_areas` VALUES ('1593', '189', '张湾区', '3');
INSERT INTO `yang_areas` VALUES ('1594', '189', '茅箭区', '3');
INSERT INTO `yang_areas` VALUES ('1595', '189', '丹江口市', '3');
INSERT INTO `yang_areas` VALUES ('1596', '189', '郧县', '3');
INSERT INTO `yang_areas` VALUES ('1597', '189', '郧西县', '3');
INSERT INTO `yang_areas` VALUES ('1598', '189', '竹山县', '3');
INSERT INTO `yang_areas` VALUES ('1599', '189', '竹溪县', '3');
INSERT INTO `yang_areas` VALUES ('1600', '189', '房县', '3');
INSERT INTO `yang_areas` VALUES ('1601', '190', '曾都区', '3');
INSERT INTO `yang_areas` VALUES ('1602', '190', '广水市', '3');
INSERT INTO `yang_areas` VALUES ('1603', '191', '天门市', '3');
INSERT INTO `yang_areas` VALUES ('1604', '192', '咸安区', '3');
INSERT INTO `yang_areas` VALUES ('1605', '192', '赤壁市', '3');
INSERT INTO `yang_areas` VALUES ('1606', '192', '嘉鱼县', '3');
INSERT INTO `yang_areas` VALUES ('1607', '192', '通城县', '3');
INSERT INTO `yang_areas` VALUES ('1608', '192', '崇阳县', '3');
INSERT INTO `yang_areas` VALUES ('1609', '192', '通山县', '3');
INSERT INTO `yang_areas` VALUES ('1610', '193', '襄城区', '3');
INSERT INTO `yang_areas` VALUES ('1611', '193', '樊城区', '3');
INSERT INTO `yang_areas` VALUES ('1612', '193', '襄阳区', '3');
INSERT INTO `yang_areas` VALUES ('1613', '193', '老河口市', '3');
INSERT INTO `yang_areas` VALUES ('1614', '193', '枣阳市', '3');
INSERT INTO `yang_areas` VALUES ('1615', '193', '宜城市', '3');
INSERT INTO `yang_areas` VALUES ('1616', '193', '南漳县', '3');
INSERT INTO `yang_areas` VALUES ('1617', '193', '谷城县', '3');
INSERT INTO `yang_areas` VALUES ('1618', '193', '保康县', '3');
INSERT INTO `yang_areas` VALUES ('1619', '194', '孝南区', '3');
INSERT INTO `yang_areas` VALUES ('1620', '194', '应城市', '3');
INSERT INTO `yang_areas` VALUES ('1621', '194', '安陆市', '3');
INSERT INTO `yang_areas` VALUES ('1622', '194', '汉川市', '3');
INSERT INTO `yang_areas` VALUES ('1623', '194', '孝昌县', '3');
INSERT INTO `yang_areas` VALUES ('1624', '194', '大悟县', '3');
INSERT INTO `yang_areas` VALUES ('1625', '194', '云梦县', '3');
INSERT INTO `yang_areas` VALUES ('1626', '195', '长阳', '3');
INSERT INTO `yang_areas` VALUES ('1627', '195', '五峰', '3');
INSERT INTO `yang_areas` VALUES ('1628', '195', '西陵区', '3');
INSERT INTO `yang_areas` VALUES ('1629', '195', '伍家岗区', '3');
INSERT INTO `yang_areas` VALUES ('1630', '195', '点军区', '3');
INSERT INTO `yang_areas` VALUES ('1631', '195', '猇亭区', '3');
INSERT INTO `yang_areas` VALUES ('1632', '195', '夷陵区', '3');
INSERT INTO `yang_areas` VALUES ('1633', '195', '宜都市', '3');
INSERT INTO `yang_areas` VALUES ('1634', '195', '当阳市', '3');
INSERT INTO `yang_areas` VALUES ('1635', '195', '枝江市', '3');
INSERT INTO `yang_areas` VALUES ('1636', '195', '远安县', '3');
INSERT INTO `yang_areas` VALUES ('1637', '195', '兴山县', '3');
INSERT INTO `yang_areas` VALUES ('1638', '195', '秭归县', '3');
INSERT INTO `yang_areas` VALUES ('1639', '196', '恩施市', '3');
INSERT INTO `yang_areas` VALUES ('1640', '196', '利川市', '3');
INSERT INTO `yang_areas` VALUES ('1641', '196', '建始县', '3');
INSERT INTO `yang_areas` VALUES ('1642', '196', '巴东县', '3');
INSERT INTO `yang_areas` VALUES ('1643', '196', '宣恩县', '3');
INSERT INTO `yang_areas` VALUES ('1644', '196', '咸丰县', '3');
INSERT INTO `yang_areas` VALUES ('1645', '196', '来凤县', '3');
INSERT INTO `yang_areas` VALUES ('1646', '196', '鹤峰县', '3');
INSERT INTO `yang_areas` VALUES ('1647', '197', '岳麓区', '3');
INSERT INTO `yang_areas` VALUES ('1648', '197', '芙蓉区', '3');
INSERT INTO `yang_areas` VALUES ('1649', '197', '天心区', '3');
INSERT INTO `yang_areas` VALUES ('1650', '197', '开福区', '3');
INSERT INTO `yang_areas` VALUES ('1651', '197', '雨花区', '3');
INSERT INTO `yang_areas` VALUES ('1652', '197', '开发区', '3');
INSERT INTO `yang_areas` VALUES ('1653', '197', '浏阳市', '3');
INSERT INTO `yang_areas` VALUES ('1654', '197', '长沙县', '3');
INSERT INTO `yang_areas` VALUES ('1655', '197', '望城县', '3');
INSERT INTO `yang_areas` VALUES ('1656', '197', '宁乡县', '3');
INSERT INTO `yang_areas` VALUES ('1657', '198', '永定区', '3');
INSERT INTO `yang_areas` VALUES ('1658', '198', '武陵源区', '3');
INSERT INTO `yang_areas` VALUES ('1659', '198', '慈利县', '3');
INSERT INTO `yang_areas` VALUES ('1660', '198', '桑植县', '3');
INSERT INTO `yang_areas` VALUES ('1661', '199', '武陵区', '3');
INSERT INTO `yang_areas` VALUES ('1662', '199', '鼎城区', '3');
INSERT INTO `yang_areas` VALUES ('1663', '199', '津市市', '3');
INSERT INTO `yang_areas` VALUES ('1664', '199', '安乡县', '3');
INSERT INTO `yang_areas` VALUES ('1665', '199', '汉寿县', '3');
INSERT INTO `yang_areas` VALUES ('1666', '199', '澧县', '3');
INSERT INTO `yang_areas` VALUES ('1667', '199', '临澧县', '3');
INSERT INTO `yang_areas` VALUES ('1668', '199', '桃源县', '3');
INSERT INTO `yang_areas` VALUES ('1669', '199', '石门县', '3');
INSERT INTO `yang_areas` VALUES ('1670', '200', '北湖区', '3');
INSERT INTO `yang_areas` VALUES ('1671', '200', '苏仙区', '3');
INSERT INTO `yang_areas` VALUES ('1672', '200', '资兴市', '3');
INSERT INTO `yang_areas` VALUES ('1673', '200', '桂阳县', '3');
INSERT INTO `yang_areas` VALUES ('1674', '200', '宜章县', '3');
INSERT INTO `yang_areas` VALUES ('1675', '200', '永兴县', '3');
INSERT INTO `yang_areas` VALUES ('1676', '200', '嘉禾县', '3');
INSERT INTO `yang_areas` VALUES ('1677', '200', '临武县', '3');
INSERT INTO `yang_areas` VALUES ('1678', '200', '汝城县', '3');
INSERT INTO `yang_areas` VALUES ('1679', '200', '桂东县', '3');
INSERT INTO `yang_areas` VALUES ('1680', '200', '安仁县', '3');
INSERT INTO `yang_areas` VALUES ('1681', '201', '雁峰区', '3');
INSERT INTO `yang_areas` VALUES ('1682', '201', '珠晖区', '3');
INSERT INTO `yang_areas` VALUES ('1683', '201', '石鼓区', '3');
INSERT INTO `yang_areas` VALUES ('1684', '201', '蒸湘区', '3');
INSERT INTO `yang_areas` VALUES ('1685', '201', '南岳区', '3');
INSERT INTO `yang_areas` VALUES ('1686', '201', '耒阳市', '3');
INSERT INTO `yang_areas` VALUES ('1687', '201', '常宁市', '3');
INSERT INTO `yang_areas` VALUES ('1688', '201', '衡阳县', '3');
INSERT INTO `yang_areas` VALUES ('1689', '201', '衡南县', '3');
INSERT INTO `yang_areas` VALUES ('1690', '201', '衡山县', '3');
INSERT INTO `yang_areas` VALUES ('1691', '201', '衡东县', '3');
INSERT INTO `yang_areas` VALUES ('1692', '201', '祁东县', '3');
INSERT INTO `yang_areas` VALUES ('1693', '202', '鹤城区', '3');
INSERT INTO `yang_areas` VALUES ('1694', '202', '靖州', '3');
INSERT INTO `yang_areas` VALUES ('1695', '202', '麻阳', '3');
INSERT INTO `yang_areas` VALUES ('1696', '202', '通道', '3');
INSERT INTO `yang_areas` VALUES ('1697', '202', '新晃', '3');
INSERT INTO `yang_areas` VALUES ('1698', '202', '芷江', '3');
INSERT INTO `yang_areas` VALUES ('1699', '202', '沅陵县', '3');
INSERT INTO `yang_areas` VALUES ('1700', '202', '辰溪县', '3');
INSERT INTO `yang_areas` VALUES ('1701', '202', '溆浦县', '3');
INSERT INTO `yang_areas` VALUES ('1702', '202', '中方县', '3');
INSERT INTO `yang_areas` VALUES ('1703', '202', '会同县', '3');
INSERT INTO `yang_areas` VALUES ('1704', '202', '洪江市', '3');
INSERT INTO `yang_areas` VALUES ('1705', '203', '娄星区', '3');
INSERT INTO `yang_areas` VALUES ('1706', '203', '冷水江市', '3');
INSERT INTO `yang_areas` VALUES ('1707', '203', '涟源市', '3');
INSERT INTO `yang_areas` VALUES ('1708', '203', '双峰县', '3');
INSERT INTO `yang_areas` VALUES ('1709', '203', '新化县', '3');
INSERT INTO `yang_areas` VALUES ('1710', '204', '城步', '3');
INSERT INTO `yang_areas` VALUES ('1711', '204', '双清区', '3');
INSERT INTO `yang_areas` VALUES ('1712', '204', '大祥区', '3');
INSERT INTO `yang_areas` VALUES ('1713', '204', '北塔区', '3');
INSERT INTO `yang_areas` VALUES ('1714', '204', '武冈市', '3');
INSERT INTO `yang_areas` VALUES ('1715', '204', '邵东县', '3');
INSERT INTO `yang_areas` VALUES ('1716', '204', '新邵县', '3');
INSERT INTO `yang_areas` VALUES ('1717', '204', '邵阳县', '3');
INSERT INTO `yang_areas` VALUES ('1718', '204', '隆回县', '3');
INSERT INTO `yang_areas` VALUES ('1719', '204', '洞口县', '3');
INSERT INTO `yang_areas` VALUES ('1720', '204', '绥宁县', '3');
INSERT INTO `yang_areas` VALUES ('1721', '204', '新宁县', '3');
INSERT INTO `yang_areas` VALUES ('1722', '205', '岳塘区', '3');
INSERT INTO `yang_areas` VALUES ('1723', '205', '雨湖区', '3');
INSERT INTO `yang_areas` VALUES ('1724', '205', '湘乡市', '3');
INSERT INTO `yang_areas` VALUES ('1725', '205', '韶山市', '3');
INSERT INTO `yang_areas` VALUES ('1726', '205', '湘潭县', '3');
INSERT INTO `yang_areas` VALUES ('1727', '206', '吉首市', '3');
INSERT INTO `yang_areas` VALUES ('1728', '206', '泸溪县', '3');
INSERT INTO `yang_areas` VALUES ('1729', '206', '凤凰县', '3');
INSERT INTO `yang_areas` VALUES ('1730', '206', '花垣县', '3');
INSERT INTO `yang_areas` VALUES ('1731', '206', '保靖县', '3');
INSERT INTO `yang_areas` VALUES ('1732', '206', '古丈县', '3');
INSERT INTO `yang_areas` VALUES ('1733', '206', '永顺县', '3');
INSERT INTO `yang_areas` VALUES ('1734', '206', '龙山县', '3');
INSERT INTO `yang_areas` VALUES ('1735', '207', '赫山区', '3');
INSERT INTO `yang_areas` VALUES ('1736', '207', '资阳区', '3');
INSERT INTO `yang_areas` VALUES ('1737', '207', '沅江市', '3');
INSERT INTO `yang_areas` VALUES ('1738', '207', '南县', '3');
INSERT INTO `yang_areas` VALUES ('1739', '207', '桃江县', '3');
INSERT INTO `yang_areas` VALUES ('1740', '207', '安化县', '3');
INSERT INTO `yang_areas` VALUES ('1741', '208', '江华', '3');
INSERT INTO `yang_areas` VALUES ('1742', '208', '冷水滩区', '3');
INSERT INTO `yang_areas` VALUES ('1743', '208', '零陵区', '3');
INSERT INTO `yang_areas` VALUES ('1744', '208', '祁阳县', '3');
INSERT INTO `yang_areas` VALUES ('1745', '208', '东安县', '3');
INSERT INTO `yang_areas` VALUES ('1746', '208', '双牌县', '3');
INSERT INTO `yang_areas` VALUES ('1747', '208', '道县', '3');
INSERT INTO `yang_areas` VALUES ('1748', '208', '江永县', '3');
INSERT INTO `yang_areas` VALUES ('1749', '208', '宁远县', '3');
INSERT INTO `yang_areas` VALUES ('1750', '208', '蓝山县', '3');
INSERT INTO `yang_areas` VALUES ('1751', '208', '新田县', '3');
INSERT INTO `yang_areas` VALUES ('1752', '209', '岳阳楼区', '3');
INSERT INTO `yang_areas` VALUES ('1753', '209', '君山区', '3');
INSERT INTO `yang_areas` VALUES ('1754', '209', '云溪区', '3');
INSERT INTO `yang_areas` VALUES ('1755', '209', '汨罗市', '3');
INSERT INTO `yang_areas` VALUES ('1756', '209', '临湘市', '3');
INSERT INTO `yang_areas` VALUES ('1757', '209', '岳阳县', '3');
INSERT INTO `yang_areas` VALUES ('1758', '209', '华容县', '3');
INSERT INTO `yang_areas` VALUES ('1759', '209', '湘阴县', '3');
INSERT INTO `yang_areas` VALUES ('1760', '209', '平江县', '3');
INSERT INTO `yang_areas` VALUES ('1761', '210', '天元区', '3');
INSERT INTO `yang_areas` VALUES ('1762', '210', '荷塘区', '3');
INSERT INTO `yang_areas` VALUES ('1763', '210', '芦淞区', '3');
INSERT INTO `yang_areas` VALUES ('1764', '210', '石峰区', '3');
INSERT INTO `yang_areas` VALUES ('1765', '210', '醴陵市', '3');
INSERT INTO `yang_areas` VALUES ('1766', '210', '株洲县', '3');
INSERT INTO `yang_areas` VALUES ('1767', '210', '攸县', '3');
INSERT INTO `yang_areas` VALUES ('1768', '210', '茶陵县', '3');
INSERT INTO `yang_areas` VALUES ('1769', '210', '炎陵县', '3');
INSERT INTO `yang_areas` VALUES ('1770', '211', '朝阳区', '3');
INSERT INTO `yang_areas` VALUES ('1771', '211', '宽城区', '3');
INSERT INTO `yang_areas` VALUES ('1772', '211', '二道区', '3');
INSERT INTO `yang_areas` VALUES ('1773', '211', '南关区', '3');
INSERT INTO `yang_areas` VALUES ('1774', '211', '绿园区', '3');
INSERT INTO `yang_areas` VALUES ('1775', '211', '双阳区', '3');
INSERT INTO `yang_areas` VALUES ('1776', '211', '净月潭开发区', '3');
INSERT INTO `yang_areas` VALUES ('1777', '211', '高新技术开发区', '3');
INSERT INTO `yang_areas` VALUES ('1778', '211', '经济技术开发区', '3');
INSERT INTO `yang_areas` VALUES ('1779', '211', '汽车产业开发区', '3');
INSERT INTO `yang_areas` VALUES ('1780', '211', '德惠市', '3');
INSERT INTO `yang_areas` VALUES ('1781', '211', '九台市', '3');
INSERT INTO `yang_areas` VALUES ('1782', '211', '榆树市', '3');
INSERT INTO `yang_areas` VALUES ('1783', '211', '农安县', '3');
INSERT INTO `yang_areas` VALUES ('1784', '212', '船营区', '3');
INSERT INTO `yang_areas` VALUES ('1785', '212', '昌邑区', '3');
INSERT INTO `yang_areas` VALUES ('1786', '212', '龙潭区', '3');
INSERT INTO `yang_areas` VALUES ('1787', '212', '丰满区', '3');
INSERT INTO `yang_areas` VALUES ('1788', '212', '蛟河市', '3');
INSERT INTO `yang_areas` VALUES ('1789', '212', '桦甸市', '3');
INSERT INTO `yang_areas` VALUES ('1790', '212', '舒兰市', '3');
INSERT INTO `yang_areas` VALUES ('1791', '212', '磐石市', '3');
INSERT INTO `yang_areas` VALUES ('1792', '212', '永吉县', '3');
INSERT INTO `yang_areas` VALUES ('1793', '213', '洮北区', '3');
INSERT INTO `yang_areas` VALUES ('1794', '213', '洮南市', '3');
INSERT INTO `yang_areas` VALUES ('1795', '213', '大安市', '3');
INSERT INTO `yang_areas` VALUES ('1796', '213', '镇赉县', '3');
INSERT INTO `yang_areas` VALUES ('1797', '213', '通榆县', '3');
INSERT INTO `yang_areas` VALUES ('1798', '214', '江源区', '3');
INSERT INTO `yang_areas` VALUES ('1799', '214', '八道江区', '3');
INSERT INTO `yang_areas` VALUES ('1800', '214', '长白', '3');
INSERT INTO `yang_areas` VALUES ('1801', '214', '临江市', '3');
INSERT INTO `yang_areas` VALUES ('1802', '214', '抚松县', '3');
INSERT INTO `yang_areas` VALUES ('1803', '214', '靖宇县', '3');
INSERT INTO `yang_areas` VALUES ('1804', '215', '龙山区', '3');
INSERT INTO `yang_areas` VALUES ('1805', '215', '西安区', '3');
INSERT INTO `yang_areas` VALUES ('1806', '215', '东丰县', '3');
INSERT INTO `yang_areas` VALUES ('1807', '215', '东辽县', '3');
INSERT INTO `yang_areas` VALUES ('1808', '216', '铁西区', '3');
INSERT INTO `yang_areas` VALUES ('1809', '216', '铁东区', '3');
INSERT INTO `yang_areas` VALUES ('1810', '216', '伊通', '3');
INSERT INTO `yang_areas` VALUES ('1811', '216', '公主岭市', '3');
INSERT INTO `yang_areas` VALUES ('1812', '216', '双辽市', '3');
INSERT INTO `yang_areas` VALUES ('1813', '216', '梨树县', '3');
INSERT INTO `yang_areas` VALUES ('1814', '217', '前郭尔罗斯', '3');
INSERT INTO `yang_areas` VALUES ('1815', '217', '宁江区', '3');
INSERT INTO `yang_areas` VALUES ('1816', '217', '长岭县', '3');
INSERT INTO `yang_areas` VALUES ('1817', '217', '乾安县', '3');
INSERT INTO `yang_areas` VALUES ('1818', '217', '扶余县', '3');
INSERT INTO `yang_areas` VALUES ('1819', '218', '东昌区', '3');
INSERT INTO `yang_areas` VALUES ('1820', '218', '二道江区', '3');
INSERT INTO `yang_areas` VALUES ('1821', '218', '梅河口市', '3');
INSERT INTO `yang_areas` VALUES ('1822', '218', '集安市', '3');
INSERT INTO `yang_areas` VALUES ('1823', '218', '通化县', '3');
INSERT INTO `yang_areas` VALUES ('1824', '218', '辉南县', '3');
INSERT INTO `yang_areas` VALUES ('1825', '218', '柳河县', '3');
INSERT INTO `yang_areas` VALUES ('1826', '219', '延吉市', '3');
INSERT INTO `yang_areas` VALUES ('1827', '219', '图们市', '3');
INSERT INTO `yang_areas` VALUES ('1828', '219', '敦化市', '3');
INSERT INTO `yang_areas` VALUES ('1829', '219', '珲春市', '3');
INSERT INTO `yang_areas` VALUES ('1830', '219', '龙井市', '3');
INSERT INTO `yang_areas` VALUES ('1831', '219', '和龙市', '3');
INSERT INTO `yang_areas` VALUES ('1832', '219', '安图县', '3');
INSERT INTO `yang_areas` VALUES ('1833', '219', '汪清县', '3');
INSERT INTO `yang_areas` VALUES ('1834', '220', '玄武区', '3');
INSERT INTO `yang_areas` VALUES ('1835', '220', '鼓楼区', '3');
INSERT INTO `yang_areas` VALUES ('1836', '220', '白下区', '3');
INSERT INTO `yang_areas` VALUES ('1837', '220', '建邺区', '3');
INSERT INTO `yang_areas` VALUES ('1838', '220', '秦淮区', '3');
INSERT INTO `yang_areas` VALUES ('1839', '220', '雨花台区', '3');
INSERT INTO `yang_areas` VALUES ('1840', '220', '下关区', '3');
INSERT INTO `yang_areas` VALUES ('1841', '220', '栖霞区', '3');
INSERT INTO `yang_areas` VALUES ('1842', '220', '浦口区', '3');
INSERT INTO `yang_areas` VALUES ('1843', '220', '江宁区', '3');
INSERT INTO `yang_areas` VALUES ('1844', '220', '六合区', '3');
INSERT INTO `yang_areas` VALUES ('1845', '220', '溧水县', '3');
INSERT INTO `yang_areas` VALUES ('1846', '220', '高淳县', '3');
INSERT INTO `yang_areas` VALUES ('1847', '221', '沧浪区', '3');
INSERT INTO `yang_areas` VALUES ('1848', '221', '金阊区', '3');
INSERT INTO `yang_areas` VALUES ('1849', '221', '平江区', '3');
INSERT INTO `yang_areas` VALUES ('1850', '221', '虎丘区', '3');
INSERT INTO `yang_areas` VALUES ('1851', '221', '吴中区', '3');
INSERT INTO `yang_areas` VALUES ('1852', '221', '相城区', '3');
INSERT INTO `yang_areas` VALUES ('1853', '221', '园区', '3');
INSERT INTO `yang_areas` VALUES ('1854', '221', '新区', '3');
INSERT INTO `yang_areas` VALUES ('1855', '221', '常熟市', '3');
INSERT INTO `yang_areas` VALUES ('1856', '221', '张家港市', '3');
INSERT INTO `yang_areas` VALUES ('1857', '221', '玉山镇', '3');
INSERT INTO `yang_areas` VALUES ('1858', '221', '巴城镇', '3');
INSERT INTO `yang_areas` VALUES ('1859', '221', '周市镇', '3');
INSERT INTO `yang_areas` VALUES ('1860', '221', '陆家镇', '3');
INSERT INTO `yang_areas` VALUES ('1861', '221', '花桥镇', '3');
INSERT INTO `yang_areas` VALUES ('1862', '221', '淀山湖镇', '3');
INSERT INTO `yang_areas` VALUES ('1863', '221', '张浦镇', '3');
INSERT INTO `yang_areas` VALUES ('1864', '221', '周庄镇', '3');
INSERT INTO `yang_areas` VALUES ('1865', '221', '千灯镇', '3');
INSERT INTO `yang_areas` VALUES ('1866', '221', '锦溪镇', '3');
INSERT INTO `yang_areas` VALUES ('1867', '221', '开发区', '3');
INSERT INTO `yang_areas` VALUES ('1868', '221', '吴江市', '3');
INSERT INTO `yang_areas` VALUES ('1869', '221', '太仓市', '3');
INSERT INTO `yang_areas` VALUES ('1870', '222', '崇安区', '3');
INSERT INTO `yang_areas` VALUES ('1871', '222', '北塘区', '3');
INSERT INTO `yang_areas` VALUES ('1872', '222', '南长区', '3');
INSERT INTO `yang_areas` VALUES ('1873', '222', '锡山区', '3');
INSERT INTO `yang_areas` VALUES ('1874', '222', '惠山区', '3');
INSERT INTO `yang_areas` VALUES ('1875', '222', '滨湖区', '3');
INSERT INTO `yang_areas` VALUES ('1876', '222', '新区', '3');
INSERT INTO `yang_areas` VALUES ('1877', '222', '江阴市', '3');
INSERT INTO `yang_areas` VALUES ('1878', '222', '宜兴市', '3');
INSERT INTO `yang_areas` VALUES ('1879', '223', '天宁区', '3');
INSERT INTO `yang_areas` VALUES ('1880', '223', '钟楼区', '3');
INSERT INTO `yang_areas` VALUES ('1881', '223', '戚墅堰区', '3');
INSERT INTO `yang_areas` VALUES ('1882', '223', '郊区', '3');
INSERT INTO `yang_areas` VALUES ('1883', '223', '新北区', '3');
INSERT INTO `yang_areas` VALUES ('1884', '223', '武进区', '3');
INSERT INTO `yang_areas` VALUES ('1885', '223', '溧阳市', '3');
INSERT INTO `yang_areas` VALUES ('1886', '223', '金坛市', '3');
INSERT INTO `yang_areas` VALUES ('1887', '224', '清河区', '3');
INSERT INTO `yang_areas` VALUES ('1888', '224', '清浦区', '3');
INSERT INTO `yang_areas` VALUES ('1889', '224', '楚州区', '3');
INSERT INTO `yang_areas` VALUES ('1890', '224', '淮阴区', '3');
INSERT INTO `yang_areas` VALUES ('1891', '224', '涟水县', '3');
INSERT INTO `yang_areas` VALUES ('1892', '224', '洪泽县', '3');
INSERT INTO `yang_areas` VALUES ('1893', '224', '盱眙县', '3');
INSERT INTO `yang_areas` VALUES ('1894', '224', '金湖县', '3');
INSERT INTO `yang_areas` VALUES ('1895', '225', '新浦区', '3');
INSERT INTO `yang_areas` VALUES ('1896', '225', '连云区', '3');
INSERT INTO `yang_areas` VALUES ('1897', '225', '海州区', '3');
INSERT INTO `yang_areas` VALUES ('1898', '225', '赣榆县', '3');
INSERT INTO `yang_areas` VALUES ('1899', '225', '东海县', '3');
INSERT INTO `yang_areas` VALUES ('1900', '225', '灌云县', '3');
INSERT INTO `yang_areas` VALUES ('1901', '225', '灌南县', '3');
INSERT INTO `yang_areas` VALUES ('1902', '226', '崇川区', '3');
INSERT INTO `yang_areas` VALUES ('1903', '226', '港闸区', '3');
INSERT INTO `yang_areas` VALUES ('1904', '226', '经济开发区', '3');
INSERT INTO `yang_areas` VALUES ('1905', '226', '启东市', '3');
INSERT INTO `yang_areas` VALUES ('1906', '226', '如皋市', '3');
INSERT INTO `yang_areas` VALUES ('1907', '226', '通州市', '3');
INSERT INTO `yang_areas` VALUES ('1908', '226', '海门市', '3');
INSERT INTO `yang_areas` VALUES ('1909', '226', '海安县', '3');
INSERT INTO `yang_areas` VALUES ('1910', '226', '如东县', '3');
INSERT INTO `yang_areas` VALUES ('1911', '227', '宿城区', '3');
INSERT INTO `yang_areas` VALUES ('1912', '227', '宿豫区', '3');
INSERT INTO `yang_areas` VALUES ('1913', '227', '宿豫县', '3');
INSERT INTO `yang_areas` VALUES ('1914', '227', '沭阳县', '3');
INSERT INTO `yang_areas` VALUES ('1915', '227', '泗阳县', '3');
INSERT INTO `yang_areas` VALUES ('1916', '227', '泗洪县', '3');
INSERT INTO `yang_areas` VALUES ('1917', '228', '海陵区', '3');
INSERT INTO `yang_areas` VALUES ('1918', '228', '高港区', '3');
INSERT INTO `yang_areas` VALUES ('1919', '228', '兴化市', '3');
INSERT INTO `yang_areas` VALUES ('1920', '228', '靖江市', '3');
INSERT INTO `yang_areas` VALUES ('1921', '228', '泰兴市', '3');
INSERT INTO `yang_areas` VALUES ('1922', '228', '姜堰市', '3');
INSERT INTO `yang_areas` VALUES ('1923', '229', '云龙区', '3');
INSERT INTO `yang_areas` VALUES ('1924', '229', '鼓楼区', '3');
INSERT INTO `yang_areas` VALUES ('1925', '229', '九里区', '3');
INSERT INTO `yang_areas` VALUES ('1926', '229', '贾汪区', '3');
INSERT INTO `yang_areas` VALUES ('1927', '229', '泉山区', '3');
INSERT INTO `yang_areas` VALUES ('1928', '229', '新沂市', '3');
INSERT INTO `yang_areas` VALUES ('1929', '229', '邳州市', '3');
INSERT INTO `yang_areas` VALUES ('1930', '229', '丰县', '3');
INSERT INTO `yang_areas` VALUES ('1931', '229', '沛县', '3');
INSERT INTO `yang_areas` VALUES ('1932', '229', '铜山县', '3');
INSERT INTO `yang_areas` VALUES ('1933', '229', '睢宁县', '3');
INSERT INTO `yang_areas` VALUES ('1934', '230', '城区', '3');
INSERT INTO `yang_areas` VALUES ('1935', '230', '亭湖区', '3');
INSERT INTO `yang_areas` VALUES ('1936', '230', '盐都区', '3');
INSERT INTO `yang_areas` VALUES ('1937', '230', '盐都县', '3');
INSERT INTO `yang_areas` VALUES ('1938', '230', '东台市', '3');
INSERT INTO `yang_areas` VALUES ('1939', '230', '大丰市', '3');
INSERT INTO `yang_areas` VALUES ('1940', '230', '响水县', '3');
INSERT INTO `yang_areas` VALUES ('1941', '230', '滨海县', '3');
INSERT INTO `yang_areas` VALUES ('1942', '230', '阜宁县', '3');
INSERT INTO `yang_areas` VALUES ('1943', '230', '射阳县', '3');
INSERT INTO `yang_areas` VALUES ('1944', '230', '建湖县', '3');
INSERT INTO `yang_areas` VALUES ('1945', '231', '广陵区', '3');
INSERT INTO `yang_areas` VALUES ('1946', '231', '维扬区', '3');
INSERT INTO `yang_areas` VALUES ('1947', '231', '邗江区', '3');
INSERT INTO `yang_areas` VALUES ('1948', '231', '仪征市', '3');
INSERT INTO `yang_areas` VALUES ('1949', '231', '高邮市', '3');
INSERT INTO `yang_areas` VALUES ('1950', '231', '江都市', '3');
INSERT INTO `yang_areas` VALUES ('1951', '231', '宝应县', '3');
INSERT INTO `yang_areas` VALUES ('1952', '232', '京口区', '3');
INSERT INTO `yang_areas` VALUES ('1953', '232', '润州区', '3');
INSERT INTO `yang_areas` VALUES ('1954', '232', '丹徒区', '3');
INSERT INTO `yang_areas` VALUES ('1955', '232', '丹阳市', '3');
INSERT INTO `yang_areas` VALUES ('1956', '232', '扬中市', '3');
INSERT INTO `yang_areas` VALUES ('1957', '232', '句容市', '3');
INSERT INTO `yang_areas` VALUES ('1958', '233', '东湖区', '3');
INSERT INTO `yang_areas` VALUES ('1959', '233', '西湖区', '3');
INSERT INTO `yang_areas` VALUES ('1960', '233', '青云谱区', '3');
INSERT INTO `yang_areas` VALUES ('1961', '233', '湾里区', '3');
INSERT INTO `yang_areas` VALUES ('1962', '233', '青山湖区', '3');
INSERT INTO `yang_areas` VALUES ('1963', '233', '红谷滩新区', '3');
INSERT INTO `yang_areas` VALUES ('1964', '233', '昌北区', '3');
INSERT INTO `yang_areas` VALUES ('1965', '233', '高新区', '3');
INSERT INTO `yang_areas` VALUES ('1966', '233', '南昌县', '3');
INSERT INTO `yang_areas` VALUES ('1967', '233', '新建县', '3');
INSERT INTO `yang_areas` VALUES ('1968', '233', '安义县', '3');
INSERT INTO `yang_areas` VALUES ('1969', '233', '进贤县', '3');
INSERT INTO `yang_areas` VALUES ('1970', '234', '临川区', '3');
INSERT INTO `yang_areas` VALUES ('1971', '234', '南城县', '3');
INSERT INTO `yang_areas` VALUES ('1972', '234', '黎川县', '3');
INSERT INTO `yang_areas` VALUES ('1973', '234', '南丰县', '3');
INSERT INTO `yang_areas` VALUES ('1974', '234', '崇仁县', '3');
INSERT INTO `yang_areas` VALUES ('1975', '234', '乐安县', '3');
INSERT INTO `yang_areas` VALUES ('1976', '234', '宜黄县', '3');
INSERT INTO `yang_areas` VALUES ('1977', '234', '金溪县', '3');
INSERT INTO `yang_areas` VALUES ('1978', '234', '资溪县', '3');
INSERT INTO `yang_areas` VALUES ('1979', '234', '东乡县', '3');
INSERT INTO `yang_areas` VALUES ('1980', '234', '广昌县', '3');
INSERT INTO `yang_areas` VALUES ('1981', '235', '章贡区', '3');
INSERT INTO `yang_areas` VALUES ('1982', '235', '于都县', '3');
INSERT INTO `yang_areas` VALUES ('1983', '235', '瑞金市', '3');
INSERT INTO `yang_areas` VALUES ('1984', '235', '南康市', '3');
INSERT INTO `yang_areas` VALUES ('1985', '235', '赣县', '3');
INSERT INTO `yang_areas` VALUES ('1986', '235', '信丰县', '3');
INSERT INTO `yang_areas` VALUES ('1987', '235', '大余县', '3');
INSERT INTO `yang_areas` VALUES ('1988', '235', '上犹县', '3');
INSERT INTO `yang_areas` VALUES ('1989', '235', '崇义县', '3');
INSERT INTO `yang_areas` VALUES ('1990', '235', '安远县', '3');
INSERT INTO `yang_areas` VALUES ('1991', '235', '龙南县', '3');
INSERT INTO `yang_areas` VALUES ('1992', '235', '定南县', '3');
INSERT INTO `yang_areas` VALUES ('1993', '235', '全南县', '3');
INSERT INTO `yang_areas` VALUES ('1994', '235', '宁都县', '3');
INSERT INTO `yang_areas` VALUES ('1995', '235', '兴国县', '3');
INSERT INTO `yang_areas` VALUES ('1996', '235', '会昌县', '3');
INSERT INTO `yang_areas` VALUES ('1997', '235', '寻乌县', '3');
INSERT INTO `yang_areas` VALUES ('1998', '235', '石城县', '3');
INSERT INTO `yang_areas` VALUES ('1999', '236', '安福县', '3');
INSERT INTO `yang_areas` VALUES ('2000', '236', '吉州区', '3');
INSERT INTO `yang_areas` VALUES ('2001', '236', '青原区', '3');
INSERT INTO `yang_areas` VALUES ('2002', '236', '井冈山市', '3');
INSERT INTO `yang_areas` VALUES ('2003', '236', '吉安县', '3');
INSERT INTO `yang_areas` VALUES ('2004', '236', '吉水县', '3');
INSERT INTO `yang_areas` VALUES ('2005', '236', '峡江县', '3');
INSERT INTO `yang_areas` VALUES ('2006', '236', '新干县', '3');
INSERT INTO `yang_areas` VALUES ('2007', '236', '永丰县', '3');
INSERT INTO `yang_areas` VALUES ('2008', '236', '泰和县', '3');
INSERT INTO `yang_areas` VALUES ('2009', '236', '遂川县', '3');
INSERT INTO `yang_areas` VALUES ('2010', '236', '万安县', '3');
INSERT INTO `yang_areas` VALUES ('2011', '236', '永新县', '3');
INSERT INTO `yang_areas` VALUES ('2012', '237', '珠山区', '3');
INSERT INTO `yang_areas` VALUES ('2013', '237', '昌江区', '3');
INSERT INTO `yang_areas` VALUES ('2014', '237', '乐平市', '3');
INSERT INTO `yang_areas` VALUES ('2015', '237', '浮梁县', '3');
INSERT INTO `yang_areas` VALUES ('2016', '238', '浔阳区', '3');
INSERT INTO `yang_areas` VALUES ('2017', '238', '庐山区', '3');
INSERT INTO `yang_areas` VALUES ('2018', '238', '瑞昌市', '3');
INSERT INTO `yang_areas` VALUES ('2019', '238', '九江县', '3');
INSERT INTO `yang_areas` VALUES ('2020', '238', '武宁县', '3');
INSERT INTO `yang_areas` VALUES ('2021', '238', '修水县', '3');
INSERT INTO `yang_areas` VALUES ('2022', '238', '永修县', '3');
INSERT INTO `yang_areas` VALUES ('2023', '238', '德安县', '3');
INSERT INTO `yang_areas` VALUES ('2024', '238', '星子县', '3');
INSERT INTO `yang_areas` VALUES ('2025', '238', '都昌县', '3');
INSERT INTO `yang_areas` VALUES ('2026', '238', '湖口县', '3');
INSERT INTO `yang_areas` VALUES ('2027', '238', '彭泽县', '3');
INSERT INTO `yang_areas` VALUES ('2028', '239', '安源区', '3');
INSERT INTO `yang_areas` VALUES ('2029', '239', '湘东区', '3');
INSERT INTO `yang_areas` VALUES ('2030', '239', '莲花县', '3');
INSERT INTO `yang_areas` VALUES ('2031', '239', '芦溪县', '3');
INSERT INTO `yang_areas` VALUES ('2032', '239', '上栗县', '3');
INSERT INTO `yang_areas` VALUES ('2033', '240', '信州区', '3');
INSERT INTO `yang_areas` VALUES ('2034', '240', '德兴市', '3');
INSERT INTO `yang_areas` VALUES ('2035', '240', '上饶县', '3');
INSERT INTO `yang_areas` VALUES ('2036', '240', '广丰县', '3');
INSERT INTO `yang_areas` VALUES ('2037', '240', '玉山县', '3');
INSERT INTO `yang_areas` VALUES ('2038', '240', '铅山县', '3');
INSERT INTO `yang_areas` VALUES ('2039', '240', '横峰县', '3');
INSERT INTO `yang_areas` VALUES ('2040', '240', '弋阳县', '3');
INSERT INTO `yang_areas` VALUES ('2041', '240', '余干县', '3');
INSERT INTO `yang_areas` VALUES ('2042', '240', '波阳县', '3');
INSERT INTO `yang_areas` VALUES ('2043', '240', '万年县', '3');
INSERT INTO `yang_areas` VALUES ('2044', '240', '婺源县', '3');
INSERT INTO `yang_areas` VALUES ('2045', '241', '渝水区', '3');
INSERT INTO `yang_areas` VALUES ('2046', '241', '分宜县', '3');
INSERT INTO `yang_areas` VALUES ('2047', '242', '袁州区', '3');
INSERT INTO `yang_areas` VALUES ('2048', '242', '丰城市', '3');
INSERT INTO `yang_areas` VALUES ('2049', '242', '樟树市', '3');
INSERT INTO `yang_areas` VALUES ('2050', '242', '高安市', '3');
INSERT INTO `yang_areas` VALUES ('2051', '242', '奉新县', '3');
INSERT INTO `yang_areas` VALUES ('2052', '242', '万载县', '3');
INSERT INTO `yang_areas` VALUES ('2053', '242', '上高县', '3');
INSERT INTO `yang_areas` VALUES ('2054', '242', '宜丰县', '3');
INSERT INTO `yang_areas` VALUES ('2055', '242', '靖安县', '3');
INSERT INTO `yang_areas` VALUES ('2056', '242', '铜鼓县', '3');
INSERT INTO `yang_areas` VALUES ('2057', '243', '月湖区', '3');
INSERT INTO `yang_areas` VALUES ('2058', '243', '贵溪市', '3');
INSERT INTO `yang_areas` VALUES ('2059', '243', '余江县', '3');
INSERT INTO `yang_areas` VALUES ('2060', '244', '沈河区', '3');
INSERT INTO `yang_areas` VALUES ('2061', '244', '皇姑区', '3');
INSERT INTO `yang_areas` VALUES ('2062', '244', '和平区', '3');
INSERT INTO `yang_areas` VALUES ('2063', '244', '大东区', '3');
INSERT INTO `yang_areas` VALUES ('2064', '244', '铁西区', '3');
INSERT INTO `yang_areas` VALUES ('2065', '244', '苏家屯区', '3');
INSERT INTO `yang_areas` VALUES ('2066', '244', '东陵区', '3');
INSERT INTO `yang_areas` VALUES ('2067', '244', '沈北新区', '3');
INSERT INTO `yang_areas` VALUES ('2068', '244', '于洪区', '3');
INSERT INTO `yang_areas` VALUES ('2069', '244', '浑南新区', '3');
INSERT INTO `yang_areas` VALUES ('2070', '244', '新民市', '3');
INSERT INTO `yang_areas` VALUES ('2071', '244', '辽中县', '3');
INSERT INTO `yang_areas` VALUES ('2072', '244', '康平县', '3');
INSERT INTO `yang_areas` VALUES ('2073', '244', '法库县', '3');
INSERT INTO `yang_areas` VALUES ('2074', '245', '西岗区', '3');
INSERT INTO `yang_areas` VALUES ('2075', '245', '中山区', '3');
INSERT INTO `yang_areas` VALUES ('2076', '245', '沙河口区', '3');
INSERT INTO `yang_areas` VALUES ('2077', '245', '甘井子区', '3');
INSERT INTO `yang_areas` VALUES ('2078', '245', '旅顺口区', '3');
INSERT INTO `yang_areas` VALUES ('2079', '245', '金州区', '3');
INSERT INTO `yang_areas` VALUES ('2080', '245', '开发区', '3');
INSERT INTO `yang_areas` VALUES ('2081', '245', '瓦房店市', '3');
INSERT INTO `yang_areas` VALUES ('2082', '245', '普兰店市', '3');
INSERT INTO `yang_areas` VALUES ('2083', '245', '庄河市', '3');
INSERT INTO `yang_areas` VALUES ('2084', '245', '长海县', '3');
INSERT INTO `yang_areas` VALUES ('2085', '246', '铁东区', '3');
INSERT INTO `yang_areas` VALUES ('2086', '246', '铁西区', '3');
INSERT INTO `yang_areas` VALUES ('2087', '246', '立山区', '3');
INSERT INTO `yang_areas` VALUES ('2088', '246', '千山区', '3');
INSERT INTO `yang_areas` VALUES ('2089', '246', '岫岩', '3');
INSERT INTO `yang_areas` VALUES ('2090', '246', '海城市', '3');
INSERT INTO `yang_areas` VALUES ('2091', '246', '台安县', '3');
INSERT INTO `yang_areas` VALUES ('2092', '247', '本溪', '3');
INSERT INTO `yang_areas` VALUES ('2093', '247', '平山区', '3');
INSERT INTO `yang_areas` VALUES ('2094', '247', '明山区', '3');
INSERT INTO `yang_areas` VALUES ('2095', '247', '溪湖区', '3');
INSERT INTO `yang_areas` VALUES ('2096', '247', '南芬区', '3');
INSERT INTO `yang_areas` VALUES ('2097', '247', '桓仁', '3');
INSERT INTO `yang_areas` VALUES ('2098', '248', '双塔区', '3');
INSERT INTO `yang_areas` VALUES ('2099', '248', '龙城区', '3');
INSERT INTO `yang_areas` VALUES ('2100', '248', '喀喇沁左翼蒙古族自治县', '3');
INSERT INTO `yang_areas` VALUES ('2101', '248', '北票市', '3');
INSERT INTO `yang_areas` VALUES ('2102', '248', '凌源市', '3');
INSERT INTO `yang_areas` VALUES ('2103', '248', '朝阳县', '3');
INSERT INTO `yang_areas` VALUES ('2104', '248', '建平县', '3');
INSERT INTO `yang_areas` VALUES ('2105', '249', '振兴区', '3');
INSERT INTO `yang_areas` VALUES ('2106', '249', '元宝区', '3');
INSERT INTO `yang_areas` VALUES ('2107', '249', '振安区', '3');
INSERT INTO `yang_areas` VALUES ('2108', '249', '宽甸', '3');
INSERT INTO `yang_areas` VALUES ('2109', '249', '东港市', '3');
INSERT INTO `yang_areas` VALUES ('2110', '249', '凤城市', '3');
INSERT INTO `yang_areas` VALUES ('2111', '250', '顺城区', '3');
INSERT INTO `yang_areas` VALUES ('2112', '250', '新抚区', '3');
INSERT INTO `yang_areas` VALUES ('2113', '250', '东洲区', '3');
INSERT INTO `yang_areas` VALUES ('2114', '250', '望花区', '3');
INSERT INTO `yang_areas` VALUES ('2115', '250', '清原', '3');
INSERT INTO `yang_areas` VALUES ('2116', '250', '新宾', '3');
INSERT INTO `yang_areas` VALUES ('2117', '250', '抚顺县', '3');
INSERT INTO `yang_areas` VALUES ('2118', '251', '阜新', '3');
INSERT INTO `yang_areas` VALUES ('2119', '251', '海州区', '3');
INSERT INTO `yang_areas` VALUES ('2120', '251', '新邱区', '3');
INSERT INTO `yang_areas` VALUES ('2121', '251', '太平区', '3');
INSERT INTO `yang_areas` VALUES ('2122', '251', '清河门区', '3');
INSERT INTO `yang_areas` VALUES ('2123', '251', '细河区', '3');
INSERT INTO `yang_areas` VALUES ('2124', '251', '彰武县', '3');
INSERT INTO `yang_areas` VALUES ('2125', '252', '龙港区', '3');
INSERT INTO `yang_areas` VALUES ('2126', '252', '南票区', '3');
INSERT INTO `yang_areas` VALUES ('2127', '252', '连山区', '3');
INSERT INTO `yang_areas` VALUES ('2128', '252', '兴城市', '3');
INSERT INTO `yang_areas` VALUES ('2129', '252', '绥中县', '3');
INSERT INTO `yang_areas` VALUES ('2130', '252', '建昌县', '3');
INSERT INTO `yang_areas` VALUES ('2131', '253', '太和区', '3');
INSERT INTO `yang_areas` VALUES ('2132', '253', '古塔区', '3');
INSERT INTO `yang_areas` VALUES ('2133', '253', '凌河区', '3');
INSERT INTO `yang_areas` VALUES ('2134', '253', '凌海市', '3');
INSERT INTO `yang_areas` VALUES ('2135', '253', '北镇市', '3');
INSERT INTO `yang_areas` VALUES ('2136', '253', '黑山县', '3');
INSERT INTO `yang_areas` VALUES ('2137', '253', '义县', '3');
INSERT INTO `yang_areas` VALUES ('2138', '254', '白塔区', '3');
INSERT INTO `yang_areas` VALUES ('2139', '254', '文圣区', '3');
INSERT INTO `yang_areas` VALUES ('2140', '254', '宏伟区', '3');
INSERT INTO `yang_areas` VALUES ('2141', '254', '太子河区', '3');
INSERT INTO `yang_areas` VALUES ('2142', '254', '弓长岭区', '3');
INSERT INTO `yang_areas` VALUES ('2143', '254', '灯塔市', '3');
INSERT INTO `yang_areas` VALUES ('2144', '254', '辽阳县', '3');
INSERT INTO `yang_areas` VALUES ('2145', '255', '双台子区', '3');
INSERT INTO `yang_areas` VALUES ('2146', '255', '兴隆台区', '3');
INSERT INTO `yang_areas` VALUES ('2147', '255', '大洼县', '3');
INSERT INTO `yang_areas` VALUES ('2148', '255', '盘山县', '3');
INSERT INTO `yang_areas` VALUES ('2149', '256', '银州区', '3');
INSERT INTO `yang_areas` VALUES ('2150', '256', '清河区', '3');
INSERT INTO `yang_areas` VALUES ('2151', '256', '调兵山市', '3');
INSERT INTO `yang_areas` VALUES ('2152', '256', '开原市', '3');
INSERT INTO `yang_areas` VALUES ('2153', '256', '铁岭县', '3');
INSERT INTO `yang_areas` VALUES ('2154', '256', '西丰县', '3');
INSERT INTO `yang_areas` VALUES ('2155', '256', '昌图县', '3');
INSERT INTO `yang_areas` VALUES ('2156', '257', '站前区', '3');
INSERT INTO `yang_areas` VALUES ('2157', '257', '西市区', '3');
INSERT INTO `yang_areas` VALUES ('2158', '257', '鲅鱼圈区', '3');
INSERT INTO `yang_areas` VALUES ('2159', '257', '老边区', '3');
INSERT INTO `yang_areas` VALUES ('2160', '257', '盖州市', '3');
INSERT INTO `yang_areas` VALUES ('2161', '257', '大石桥市', '3');
INSERT INTO `yang_areas` VALUES ('2162', '258', '回民区', '3');
INSERT INTO `yang_areas` VALUES ('2163', '258', '玉泉区', '3');
INSERT INTO `yang_areas` VALUES ('2164', '258', '新城区', '3');
INSERT INTO `yang_areas` VALUES ('2165', '258', '赛罕区', '3');
INSERT INTO `yang_areas` VALUES ('2166', '258', '清水河县', '3');
INSERT INTO `yang_areas` VALUES ('2167', '258', '土默特左旗', '3');
INSERT INTO `yang_areas` VALUES ('2168', '258', '托克托县', '3');
INSERT INTO `yang_areas` VALUES ('2169', '258', '和林格尔县', '3');
INSERT INTO `yang_areas` VALUES ('2170', '258', '武川县', '3');
INSERT INTO `yang_areas` VALUES ('2171', '259', '阿拉善左旗', '3');
INSERT INTO `yang_areas` VALUES ('2172', '259', '阿拉善右旗', '3');
INSERT INTO `yang_areas` VALUES ('2173', '259', '额济纳旗', '3');
INSERT INTO `yang_areas` VALUES ('2174', '260', '临河区', '3');
INSERT INTO `yang_areas` VALUES ('2175', '260', '五原县', '3');
INSERT INTO `yang_areas` VALUES ('2176', '260', '磴口县', '3');
INSERT INTO `yang_areas` VALUES ('2177', '260', '乌拉特前旗', '3');
INSERT INTO `yang_areas` VALUES ('2178', '260', '乌拉特中旗', '3');
INSERT INTO `yang_areas` VALUES ('2179', '260', '乌拉特后旗', '3');
INSERT INTO `yang_areas` VALUES ('2180', '260', '杭锦后旗', '3');
INSERT INTO `yang_areas` VALUES ('2181', '261', '昆都仑区', '3');
INSERT INTO `yang_areas` VALUES ('2182', '261', '青山区', '3');
INSERT INTO `yang_areas` VALUES ('2183', '261', '东河区', '3');
INSERT INTO `yang_areas` VALUES ('2184', '261', '九原区', '3');
INSERT INTO `yang_areas` VALUES ('2185', '261', '石拐区', '3');
INSERT INTO `yang_areas` VALUES ('2186', '261', '白云矿区', '3');
INSERT INTO `yang_areas` VALUES ('2187', '261', '土默特右旗', '3');
INSERT INTO `yang_areas` VALUES ('2188', '261', '固阳县', '3');
INSERT INTO `yang_areas` VALUES ('2189', '261', '达尔罕茂明安联合旗', '3');
INSERT INTO `yang_areas` VALUES ('2190', '262', '红山区', '3');
INSERT INTO `yang_areas` VALUES ('2191', '262', '元宝山区', '3');
INSERT INTO `yang_areas` VALUES ('2192', '262', '松山区', '3');
INSERT INTO `yang_areas` VALUES ('2193', '262', '阿鲁科尔沁旗', '3');
INSERT INTO `yang_areas` VALUES ('2194', '262', '巴林左旗', '3');
INSERT INTO `yang_areas` VALUES ('2195', '262', '巴林右旗', '3');
INSERT INTO `yang_areas` VALUES ('2196', '262', '林西县', '3');
INSERT INTO `yang_areas` VALUES ('2197', '262', '克什克腾旗', '3');
INSERT INTO `yang_areas` VALUES ('2198', '262', '翁牛特旗', '3');
INSERT INTO `yang_areas` VALUES ('2199', '262', '喀喇沁旗', '3');
INSERT INTO `yang_areas` VALUES ('2200', '262', '宁城县', '3');
INSERT INTO `yang_areas` VALUES ('2201', '262', '敖汉旗', '3');
INSERT INTO `yang_areas` VALUES ('2202', '263', '东胜区', '3');
INSERT INTO `yang_areas` VALUES ('2203', '263', '达拉特旗', '3');
INSERT INTO `yang_areas` VALUES ('2204', '263', '准格尔旗', '3');
INSERT INTO `yang_areas` VALUES ('2205', '263', '鄂托克前旗', '3');
INSERT INTO `yang_areas` VALUES ('2206', '263', '鄂托克旗', '3');
INSERT INTO `yang_areas` VALUES ('2207', '263', '杭锦旗', '3');
INSERT INTO `yang_areas` VALUES ('2208', '263', '乌审旗', '3');
INSERT INTO `yang_areas` VALUES ('2209', '263', '伊金霍洛旗', '3');
INSERT INTO `yang_areas` VALUES ('2210', '264', '海拉尔区', '3');
INSERT INTO `yang_areas` VALUES ('2211', '264', '莫力达瓦', '3');
INSERT INTO `yang_areas` VALUES ('2212', '264', '满洲里市', '3');
INSERT INTO `yang_areas` VALUES ('2213', '264', '牙克石市', '3');
INSERT INTO `yang_areas` VALUES ('2214', '264', '扎兰屯市', '3');
INSERT INTO `yang_areas` VALUES ('2215', '264', '额尔古纳市', '3');
INSERT INTO `yang_areas` VALUES ('2216', '264', '根河市', '3');
INSERT INTO `yang_areas` VALUES ('2217', '264', '阿荣旗', '3');
INSERT INTO `yang_areas` VALUES ('2218', '264', '鄂伦春自治旗', '3');
INSERT INTO `yang_areas` VALUES ('2219', '264', '鄂温克族自治旗', '3');
INSERT INTO `yang_areas` VALUES ('2220', '264', '陈巴尔虎旗', '3');
INSERT INTO `yang_areas` VALUES ('2221', '264', '新巴尔虎左旗', '3');
INSERT INTO `yang_areas` VALUES ('2222', '264', '新巴尔虎右旗', '3');
INSERT INTO `yang_areas` VALUES ('2223', '265', '科尔沁区', '3');
INSERT INTO `yang_areas` VALUES ('2224', '265', '霍林郭勒市', '3');
INSERT INTO `yang_areas` VALUES ('2225', '265', '科尔沁左翼中旗', '3');
INSERT INTO `yang_areas` VALUES ('2226', '265', '科尔沁左翼后旗', '3');
INSERT INTO `yang_areas` VALUES ('2227', '265', '开鲁县', '3');
INSERT INTO `yang_areas` VALUES ('2228', '265', '库伦旗', '3');
INSERT INTO `yang_areas` VALUES ('2229', '265', '奈曼旗', '3');
INSERT INTO `yang_areas` VALUES ('2230', '265', '扎鲁特旗', '3');
INSERT INTO `yang_areas` VALUES ('2231', '266', '海勃湾区', '3');
INSERT INTO `yang_areas` VALUES ('2232', '266', '乌达区', '3');
INSERT INTO `yang_areas` VALUES ('2233', '266', '海南区', '3');
INSERT INTO `yang_areas` VALUES ('2234', '267', '化德县', '3');
INSERT INTO `yang_areas` VALUES ('2235', '267', '集宁区', '3');
INSERT INTO `yang_areas` VALUES ('2236', '267', '丰镇市', '3');
INSERT INTO `yang_areas` VALUES ('2237', '267', '卓资县', '3');
INSERT INTO `yang_areas` VALUES ('2238', '267', '商都县', '3');
INSERT INTO `yang_areas` VALUES ('2239', '267', '兴和县', '3');
INSERT INTO `yang_areas` VALUES ('2240', '267', '凉城县', '3');
INSERT INTO `yang_areas` VALUES ('2241', '267', '察哈尔右翼前旗', '3');
INSERT INTO `yang_areas` VALUES ('2242', '267', '察哈尔右翼中旗', '3');
INSERT INTO `yang_areas` VALUES ('2243', '267', '察哈尔右翼后旗', '3');
INSERT INTO `yang_areas` VALUES ('2244', '267', '四子王旗', '3');
INSERT INTO `yang_areas` VALUES ('2245', '268', '二连浩特市', '3');
INSERT INTO `yang_areas` VALUES ('2246', '268', '锡林浩特市', '3');
INSERT INTO `yang_areas` VALUES ('2247', '268', '阿巴嘎旗', '3');
INSERT INTO `yang_areas` VALUES ('2248', '268', '苏尼特左旗', '3');
INSERT INTO `yang_areas` VALUES ('2249', '268', '苏尼特右旗', '3');
INSERT INTO `yang_areas` VALUES ('2250', '268', '东乌珠穆沁旗', '3');
INSERT INTO `yang_areas` VALUES ('2251', '268', '西乌珠穆沁旗', '3');
INSERT INTO `yang_areas` VALUES ('2252', '268', '太仆寺旗', '3');
INSERT INTO `yang_areas` VALUES ('2253', '268', '镶黄旗', '3');
INSERT INTO `yang_areas` VALUES ('2254', '268', '正镶白旗', '3');
INSERT INTO `yang_areas` VALUES ('2255', '268', '正蓝旗', '3');
INSERT INTO `yang_areas` VALUES ('2256', '268', '多伦县', '3');
INSERT INTO `yang_areas` VALUES ('2257', '269', '乌兰浩特市', '3');
INSERT INTO `yang_areas` VALUES ('2258', '269', '阿尔山市', '3');
INSERT INTO `yang_areas` VALUES ('2259', '269', '科尔沁右翼前旗', '3');
INSERT INTO `yang_areas` VALUES ('2260', '269', '科尔沁右翼中旗', '3');
INSERT INTO `yang_areas` VALUES ('2261', '269', '扎赉特旗', '3');
INSERT INTO `yang_areas` VALUES ('2262', '269', '突泉县', '3');
INSERT INTO `yang_areas` VALUES ('2263', '270', '西夏区', '3');
INSERT INTO `yang_areas` VALUES ('2264', '270', '金凤区', '3');
INSERT INTO `yang_areas` VALUES ('2265', '270', '兴庆区', '3');
INSERT INTO `yang_areas` VALUES ('2266', '270', '灵武市', '3');
INSERT INTO `yang_areas` VALUES ('2267', '270', '永宁县', '3');
INSERT INTO `yang_areas` VALUES ('2268', '270', '贺兰县', '3');
INSERT INTO `yang_areas` VALUES ('2269', '271', '原州区', '3');
INSERT INTO `yang_areas` VALUES ('2270', '271', '海原县', '3');
INSERT INTO `yang_areas` VALUES ('2271', '271', '西吉县', '3');
INSERT INTO `yang_areas` VALUES ('2272', '271', '隆德县', '3');
INSERT INTO `yang_areas` VALUES ('2273', '271', '泾源县', '3');
INSERT INTO `yang_areas` VALUES ('2274', '271', '彭阳县', '3');
INSERT INTO `yang_areas` VALUES ('2275', '272', '惠农县', '3');
INSERT INTO `yang_areas` VALUES ('2276', '272', '大武口区', '3');
INSERT INTO `yang_areas` VALUES ('2277', '272', '惠农区', '3');
INSERT INTO `yang_areas` VALUES ('2278', '272', '陶乐县', '3');
INSERT INTO `yang_areas` VALUES ('2279', '272', '平罗县', '3');
INSERT INTO `yang_areas` VALUES ('2280', '273', '利通区', '3');
INSERT INTO `yang_areas` VALUES ('2281', '273', '中卫县', '3');
INSERT INTO `yang_areas` VALUES ('2282', '273', '青铜峡市', '3');
INSERT INTO `yang_areas` VALUES ('2283', '273', '中宁县', '3');
INSERT INTO `yang_areas` VALUES ('2284', '273', '盐池县', '3');
INSERT INTO `yang_areas` VALUES ('2285', '273', '同心县', '3');
INSERT INTO `yang_areas` VALUES ('2286', '274', '沙坡头区', '3');
INSERT INTO `yang_areas` VALUES ('2287', '274', '海原县', '3');
INSERT INTO `yang_areas` VALUES ('2288', '274', '中宁县', '3');
INSERT INTO `yang_areas` VALUES ('2289', '275', '城中区', '3');
INSERT INTO `yang_areas` VALUES ('2290', '275', '城东区', '3');
INSERT INTO `yang_areas` VALUES ('2291', '275', '城西区', '3');
INSERT INTO `yang_areas` VALUES ('2292', '275', '城北区', '3');
INSERT INTO `yang_areas` VALUES ('2293', '275', '湟中县', '3');
INSERT INTO `yang_areas` VALUES ('2294', '275', '湟源县', '3');
INSERT INTO `yang_areas` VALUES ('2295', '275', '大通', '3');
INSERT INTO `yang_areas` VALUES ('2296', '276', '玛沁县', '3');
INSERT INTO `yang_areas` VALUES ('2297', '276', '班玛县', '3');
INSERT INTO `yang_areas` VALUES ('2298', '276', '甘德县', '3');
INSERT INTO `yang_areas` VALUES ('2299', '276', '达日县', '3');
INSERT INTO `yang_areas` VALUES ('2300', '276', '久治县', '3');
INSERT INTO `yang_areas` VALUES ('2301', '276', '玛多县', '3');
INSERT INTO `yang_areas` VALUES ('2302', '277', '海晏县', '3');
INSERT INTO `yang_areas` VALUES ('2303', '277', '祁连县', '3');
INSERT INTO `yang_areas` VALUES ('2304', '277', '刚察县', '3');
INSERT INTO `yang_areas` VALUES ('2305', '277', '门源', '3');
INSERT INTO `yang_areas` VALUES ('2306', '278', '平安县', '3');
INSERT INTO `yang_areas` VALUES ('2307', '278', '乐都县', '3');
INSERT INTO `yang_areas` VALUES ('2308', '278', '民和', '3');
INSERT INTO `yang_areas` VALUES ('2309', '278', '互助', '3');
INSERT INTO `yang_areas` VALUES ('2310', '278', '化隆', '3');
INSERT INTO `yang_areas` VALUES ('2311', '278', '循化', '3');
INSERT INTO `yang_areas` VALUES ('2312', '279', '共和县', '3');
INSERT INTO `yang_areas` VALUES ('2313', '279', '同德县', '3');
INSERT INTO `yang_areas` VALUES ('2314', '279', '贵德县', '3');
INSERT INTO `yang_areas` VALUES ('2315', '279', '兴海县', '3');
INSERT INTO `yang_areas` VALUES ('2316', '279', '贵南县', '3');
INSERT INTO `yang_areas` VALUES ('2317', '280', '德令哈市', '3');
INSERT INTO `yang_areas` VALUES ('2318', '280', '格尔木市', '3');
INSERT INTO `yang_areas` VALUES ('2319', '280', '乌兰县', '3');
INSERT INTO `yang_areas` VALUES ('2320', '280', '都兰县', '3');
INSERT INTO `yang_areas` VALUES ('2321', '280', '天峻县', '3');
INSERT INTO `yang_areas` VALUES ('2322', '281', '同仁县', '3');
INSERT INTO `yang_areas` VALUES ('2323', '281', '尖扎县', '3');
INSERT INTO `yang_areas` VALUES ('2324', '281', '泽库县', '3');
INSERT INTO `yang_areas` VALUES ('2325', '281', '河南蒙古族自治县', '3');
INSERT INTO `yang_areas` VALUES ('2326', '282', '玉树县', '3');
INSERT INTO `yang_areas` VALUES ('2327', '282', '杂多县', '3');
INSERT INTO `yang_areas` VALUES ('2328', '282', '称多县', '3');
INSERT INTO `yang_areas` VALUES ('2329', '282', '治多县', '3');
INSERT INTO `yang_areas` VALUES ('2330', '282', '囊谦县', '3');
INSERT INTO `yang_areas` VALUES ('2331', '282', '曲麻莱县', '3');
INSERT INTO `yang_areas` VALUES ('2332', '283', '市中区', '3');
INSERT INTO `yang_areas` VALUES ('2333', '283', '历下区', '3');
INSERT INTO `yang_areas` VALUES ('2334', '283', '天桥区', '3');
INSERT INTO `yang_areas` VALUES ('2335', '283', '槐荫区', '3');
INSERT INTO `yang_areas` VALUES ('2336', '283', '历城区', '3');
INSERT INTO `yang_areas` VALUES ('2337', '283', '长清区', '3');
INSERT INTO `yang_areas` VALUES ('2338', '283', '章丘市', '3');
INSERT INTO `yang_areas` VALUES ('2339', '283', '平阴县', '3');
INSERT INTO `yang_areas` VALUES ('2340', '283', '济阳县', '3');
INSERT INTO `yang_areas` VALUES ('2341', '283', '商河县', '3');
INSERT INTO `yang_areas` VALUES ('2342', '284', '市南区', '3');
INSERT INTO `yang_areas` VALUES ('2343', '284', '市北区', '3');
INSERT INTO `yang_areas` VALUES ('2344', '284', '城阳区', '3');
INSERT INTO `yang_areas` VALUES ('2345', '284', '四方区', '3');
INSERT INTO `yang_areas` VALUES ('2346', '284', '李沧区', '3');
INSERT INTO `yang_areas` VALUES ('2347', '284', '黄岛区', '3');
INSERT INTO `yang_areas` VALUES ('2348', '284', '崂山区', '3');
INSERT INTO `yang_areas` VALUES ('2349', '284', '胶州市', '3');
INSERT INTO `yang_areas` VALUES ('2350', '284', '即墨市', '3');
INSERT INTO `yang_areas` VALUES ('2351', '284', '平度市', '3');
INSERT INTO `yang_areas` VALUES ('2352', '284', '胶南市', '3');
INSERT INTO `yang_areas` VALUES ('2353', '284', '莱西市', '3');
INSERT INTO `yang_areas` VALUES ('2354', '285', '滨城区', '3');
INSERT INTO `yang_areas` VALUES ('2355', '285', '惠民县', '3');
INSERT INTO `yang_areas` VALUES ('2356', '285', '阳信县', '3');
INSERT INTO `yang_areas` VALUES ('2357', '285', '无棣县', '3');
INSERT INTO `yang_areas` VALUES ('2358', '285', '沾化县', '3');
INSERT INTO `yang_areas` VALUES ('2359', '285', '博兴县', '3');
INSERT INTO `yang_areas` VALUES ('2360', '285', '邹平县', '3');
INSERT INTO `yang_areas` VALUES ('2361', '286', '德城区', '3');
INSERT INTO `yang_areas` VALUES ('2362', '286', '陵县', '3');
INSERT INTO `yang_areas` VALUES ('2363', '286', '乐陵市', '3');
INSERT INTO `yang_areas` VALUES ('2364', '286', '禹城市', '3');
INSERT INTO `yang_areas` VALUES ('2365', '286', '宁津县', '3');
INSERT INTO `yang_areas` VALUES ('2366', '286', '庆云县', '3');
INSERT INTO `yang_areas` VALUES ('2367', '286', '临邑县', '3');
INSERT INTO `yang_areas` VALUES ('2368', '286', '齐河县', '3');
INSERT INTO `yang_areas` VALUES ('2369', '286', '平原县', '3');
INSERT INTO `yang_areas` VALUES ('2370', '286', '夏津县', '3');
INSERT INTO `yang_areas` VALUES ('2371', '286', '武城县', '3');
INSERT INTO `yang_areas` VALUES ('2372', '287', '东营区', '3');
INSERT INTO `yang_areas` VALUES ('2373', '287', '河口区', '3');
INSERT INTO `yang_areas` VALUES ('2374', '287', '垦利县', '3');
INSERT INTO `yang_areas` VALUES ('2375', '287', '利津县', '3');
INSERT INTO `yang_areas` VALUES ('2376', '287', '广饶县', '3');
INSERT INTO `yang_areas` VALUES ('2377', '288', '牡丹区', '3');
INSERT INTO `yang_areas` VALUES ('2378', '288', '曹县', '3');
INSERT INTO `yang_areas` VALUES ('2379', '288', '单县', '3');
INSERT INTO `yang_areas` VALUES ('2380', '288', '成武县', '3');
INSERT INTO `yang_areas` VALUES ('2381', '288', '巨野县', '3');
INSERT INTO `yang_areas` VALUES ('2382', '288', '郓城县', '3');
INSERT INTO `yang_areas` VALUES ('2383', '288', '鄄城县', '3');
INSERT INTO `yang_areas` VALUES ('2384', '288', '定陶县', '3');
INSERT INTO `yang_areas` VALUES ('2385', '288', '东明县', '3');
INSERT INTO `yang_areas` VALUES ('2386', '289', '市中区', '3');
INSERT INTO `yang_areas` VALUES ('2387', '289', '任城区', '3');
INSERT INTO `yang_areas` VALUES ('2388', '289', '曲阜市', '3');
INSERT INTO `yang_areas` VALUES ('2389', '289', '兖州市', '3');
INSERT INTO `yang_areas` VALUES ('2390', '289', '邹城市', '3');
INSERT INTO `yang_areas` VALUES ('2391', '289', '微山县', '3');
INSERT INTO `yang_areas` VALUES ('2392', '289', '鱼台县', '3');
INSERT INTO `yang_areas` VALUES ('2393', '289', '金乡县', '3');
INSERT INTO `yang_areas` VALUES ('2394', '289', '嘉祥县', '3');
INSERT INTO `yang_areas` VALUES ('2395', '289', '汶上县', '3');
INSERT INTO `yang_areas` VALUES ('2396', '289', '泗水县', '3');
INSERT INTO `yang_areas` VALUES ('2397', '289', '梁山县', '3');
INSERT INTO `yang_areas` VALUES ('2398', '290', '莱城区', '3');
INSERT INTO `yang_areas` VALUES ('2399', '290', '钢城区', '3');
INSERT INTO `yang_areas` VALUES ('2400', '291', '东昌府区', '3');
INSERT INTO `yang_areas` VALUES ('2401', '291', '临清市', '3');
INSERT INTO `yang_areas` VALUES ('2402', '291', '阳谷县', '3');
INSERT INTO `yang_areas` VALUES ('2403', '291', '莘县', '3');
INSERT INTO `yang_areas` VALUES ('2404', '291', '茌平县', '3');
INSERT INTO `yang_areas` VALUES ('2405', '291', '东阿县', '3');
INSERT INTO `yang_areas` VALUES ('2406', '291', '冠县', '3');
INSERT INTO `yang_areas` VALUES ('2407', '291', '高唐县', '3');
INSERT INTO `yang_areas` VALUES ('2408', '292', '兰山区', '3');
INSERT INTO `yang_areas` VALUES ('2409', '292', '罗庄区', '3');
INSERT INTO `yang_areas` VALUES ('2410', '292', '河东区', '3');
INSERT INTO `yang_areas` VALUES ('2411', '292', '沂南县', '3');
INSERT INTO `yang_areas` VALUES ('2412', '292', '郯城县', '3');
INSERT INTO `yang_areas` VALUES ('2413', '292', '沂水县', '3');
INSERT INTO `yang_areas` VALUES ('2414', '292', '苍山县', '3');
INSERT INTO `yang_areas` VALUES ('2415', '292', '费县', '3');
INSERT INTO `yang_areas` VALUES ('2416', '292', '平邑县', '3');
INSERT INTO `yang_areas` VALUES ('2417', '292', '莒南县', '3');
INSERT INTO `yang_areas` VALUES ('2418', '292', '蒙阴县', '3');
INSERT INTO `yang_areas` VALUES ('2419', '292', '临沭县', '3');
INSERT INTO `yang_areas` VALUES ('2420', '293', '东港区', '3');
INSERT INTO `yang_areas` VALUES ('2421', '293', '岚山区', '3');
INSERT INTO `yang_areas` VALUES ('2422', '293', '五莲县', '3');
INSERT INTO `yang_areas` VALUES ('2423', '293', '莒县', '3');
INSERT INTO `yang_areas` VALUES ('2424', '294', '泰山区', '3');
INSERT INTO `yang_areas` VALUES ('2425', '294', '岱岳区', '3');
INSERT INTO `yang_areas` VALUES ('2426', '294', '新泰市', '3');
INSERT INTO `yang_areas` VALUES ('2427', '294', '肥城市', '3');
INSERT INTO `yang_areas` VALUES ('2428', '294', '宁阳县', '3');
INSERT INTO `yang_areas` VALUES ('2429', '294', '东平县', '3');
INSERT INTO `yang_areas` VALUES ('2430', '295', '荣成市', '3');
INSERT INTO `yang_areas` VALUES ('2431', '295', '乳山市', '3');
INSERT INTO `yang_areas` VALUES ('2432', '295', '环翠区', '3');
INSERT INTO `yang_areas` VALUES ('2433', '295', '文登市', '3');
INSERT INTO `yang_areas` VALUES ('2434', '296', '潍城区', '3');
INSERT INTO `yang_areas` VALUES ('2435', '296', '寒亭区', '3');
INSERT INTO `yang_areas` VALUES ('2436', '296', '坊子区', '3');
INSERT INTO `yang_areas` VALUES ('2437', '296', '奎文区', '3');
INSERT INTO `yang_areas` VALUES ('2438', '296', '青州市', '3');
INSERT INTO `yang_areas` VALUES ('2439', '296', '诸城市', '3');
INSERT INTO `yang_areas` VALUES ('2440', '296', '寿光市', '3');
INSERT INTO `yang_areas` VALUES ('2441', '296', '安丘市', '3');
INSERT INTO `yang_areas` VALUES ('2442', '296', '高密市', '3');
INSERT INTO `yang_areas` VALUES ('2443', '296', '昌邑市', '3');
INSERT INTO `yang_areas` VALUES ('2444', '296', '临朐县', '3');
INSERT INTO `yang_areas` VALUES ('2445', '296', '昌乐县', '3');
INSERT INTO `yang_areas` VALUES ('2446', '297', '芝罘区', '3');
INSERT INTO `yang_areas` VALUES ('2447', '297', '福山区', '3');
INSERT INTO `yang_areas` VALUES ('2448', '297', '牟平区', '3');
INSERT INTO `yang_areas` VALUES ('2449', '297', '莱山区', '3');
INSERT INTO `yang_areas` VALUES ('2450', '297', '开发区', '3');
INSERT INTO `yang_areas` VALUES ('2451', '297', '龙口市', '3');
INSERT INTO `yang_areas` VALUES ('2452', '297', '莱阳市', '3');
INSERT INTO `yang_areas` VALUES ('2453', '297', '莱州市', '3');
INSERT INTO `yang_areas` VALUES ('2454', '297', '蓬莱市', '3');
INSERT INTO `yang_areas` VALUES ('2455', '297', '招远市', '3');
INSERT INTO `yang_areas` VALUES ('2456', '297', '栖霞市', '3');
INSERT INTO `yang_areas` VALUES ('2457', '297', '海阳市', '3');
INSERT INTO `yang_areas` VALUES ('2458', '297', '长岛县', '3');
INSERT INTO `yang_areas` VALUES ('2459', '298', '市中区', '3');
INSERT INTO `yang_areas` VALUES ('2460', '298', '山亭区', '3');
INSERT INTO `yang_areas` VALUES ('2461', '298', '峄城区', '3');
INSERT INTO `yang_areas` VALUES ('2462', '298', '台儿庄区', '3');
INSERT INTO `yang_areas` VALUES ('2463', '298', '薛城区', '3');
INSERT INTO `yang_areas` VALUES ('2464', '298', '滕州市', '3');
INSERT INTO `yang_areas` VALUES ('2465', '299', '张店区', '3');
INSERT INTO `yang_areas` VALUES ('2466', '299', '临淄区', '3');
INSERT INTO `yang_areas` VALUES ('2467', '299', '淄川区', '3');
INSERT INTO `yang_areas` VALUES ('2468', '299', '博山区', '3');
INSERT INTO `yang_areas` VALUES ('2469', '299', '周村区', '3');
INSERT INTO `yang_areas` VALUES ('2470', '299', '桓台县', '3');
INSERT INTO `yang_areas` VALUES ('2471', '299', '高青县', '3');
INSERT INTO `yang_areas` VALUES ('2472', '299', '沂源县', '3');
INSERT INTO `yang_areas` VALUES ('2473', '300', '杏花岭区', '3');
INSERT INTO `yang_areas` VALUES ('2474', '300', '小店区', '3');
INSERT INTO `yang_areas` VALUES ('2475', '300', '迎泽区', '3');
INSERT INTO `yang_areas` VALUES ('2476', '300', '尖草坪区', '3');
INSERT INTO `yang_areas` VALUES ('2477', '300', '万柏林区', '3');
INSERT INTO `yang_areas` VALUES ('2478', '300', '晋源区', '3');
INSERT INTO `yang_areas` VALUES ('2479', '300', '高新开发区', '3');
INSERT INTO `yang_areas` VALUES ('2480', '300', '民营经济开发区', '3');
INSERT INTO `yang_areas` VALUES ('2481', '300', '经济技术开发区', '3');
INSERT INTO `yang_areas` VALUES ('2482', '300', '清徐县', '3');
INSERT INTO `yang_areas` VALUES ('2483', '300', '阳曲县', '3');
INSERT INTO `yang_areas` VALUES ('2484', '300', '娄烦县', '3');
INSERT INTO `yang_areas` VALUES ('2485', '300', '古交市', '3');
INSERT INTO `yang_areas` VALUES ('2486', '301', '城区', '3');
INSERT INTO `yang_areas` VALUES ('2487', '301', '郊区', '3');
INSERT INTO `yang_areas` VALUES ('2488', '301', '沁县', '3');
INSERT INTO `yang_areas` VALUES ('2489', '301', '潞城市', '3');
INSERT INTO `yang_areas` VALUES ('2490', '301', '长治县', '3');
INSERT INTO `yang_areas` VALUES ('2491', '301', '襄垣县', '3');
INSERT INTO `yang_areas` VALUES ('2492', '301', '屯留县', '3');
INSERT INTO `yang_areas` VALUES ('2493', '301', '平顺县', '3');
INSERT INTO `yang_areas` VALUES ('2494', '301', '黎城县', '3');
INSERT INTO `yang_areas` VALUES ('2495', '301', '壶关县', '3');
INSERT INTO `yang_areas` VALUES ('2496', '301', '长子县', '3');
INSERT INTO `yang_areas` VALUES ('2497', '301', '武乡县', '3');
INSERT INTO `yang_areas` VALUES ('2498', '301', '沁源县', '3');
INSERT INTO `yang_areas` VALUES ('2499', '302', '城区', '3');
INSERT INTO `yang_areas` VALUES ('2500', '302', '矿区', '3');
INSERT INTO `yang_areas` VALUES ('2501', '302', '南郊区', '3');
INSERT INTO `yang_areas` VALUES ('2502', '302', '新荣区', '3');
INSERT INTO `yang_areas` VALUES ('2503', '302', '阳高县', '3');
INSERT INTO `yang_areas` VALUES ('2504', '302', '天镇县', '3');
INSERT INTO `yang_areas` VALUES ('2505', '302', '广灵县', '3');
INSERT INTO `yang_areas` VALUES ('2506', '302', '灵丘县', '3');
INSERT INTO `yang_areas` VALUES ('2507', '302', '浑源县', '3');
INSERT INTO `yang_areas` VALUES ('2508', '302', '左云县', '3');
INSERT INTO `yang_areas` VALUES ('2509', '302', '大同县', '3');
INSERT INTO `yang_areas` VALUES ('2510', '303', '城区', '3');
INSERT INTO `yang_areas` VALUES ('2511', '303', '高平市', '3');
INSERT INTO `yang_areas` VALUES ('2512', '303', '沁水县', '3');
INSERT INTO `yang_areas` VALUES ('2513', '303', '阳城县', '3');
INSERT INTO `yang_areas` VALUES ('2514', '303', '陵川县', '3');
INSERT INTO `yang_areas` VALUES ('2515', '303', '泽州县', '3');
INSERT INTO `yang_areas` VALUES ('2516', '304', '榆次区', '3');
INSERT INTO `yang_areas` VALUES ('2517', '304', '介休市', '3');
INSERT INTO `yang_areas` VALUES ('2518', '304', '榆社县', '3');
INSERT INTO `yang_areas` VALUES ('2519', '304', '左权县', '3');
INSERT INTO `yang_areas` VALUES ('2520', '304', '和顺县', '3');
INSERT INTO `yang_areas` VALUES ('2521', '304', '昔阳县', '3');
INSERT INTO `yang_areas` VALUES ('2522', '304', '寿阳县', '3');
INSERT INTO `yang_areas` VALUES ('2523', '304', '太谷县', '3');
INSERT INTO `yang_areas` VALUES ('2524', '304', '祁县', '3');
INSERT INTO `yang_areas` VALUES ('2525', '304', '平遥县', '3');
INSERT INTO `yang_areas` VALUES ('2526', '304', '灵石县', '3');
INSERT INTO `yang_areas` VALUES ('2527', '305', '尧都区', '3');
INSERT INTO `yang_areas` VALUES ('2528', '305', '侯马市', '3');
INSERT INTO `yang_areas` VALUES ('2529', '305', '霍州市', '3');
INSERT INTO `yang_areas` VALUES ('2530', '305', '曲沃县', '3');
INSERT INTO `yang_areas` VALUES ('2531', '305', '翼城县', '3');
INSERT INTO `yang_areas` VALUES ('2532', '305', '襄汾县', '3');
INSERT INTO `yang_areas` VALUES ('2533', '305', '洪洞县', '3');
INSERT INTO `yang_areas` VALUES ('2534', '305', '吉县', '3');
INSERT INTO `yang_areas` VALUES ('2535', '305', '安泽县', '3');
INSERT INTO `yang_areas` VALUES ('2536', '305', '浮山县', '3');
INSERT INTO `yang_areas` VALUES ('2537', '305', '古县', '3');
INSERT INTO `yang_areas` VALUES ('2538', '305', '乡宁县', '3');
INSERT INTO `yang_areas` VALUES ('2539', '305', '大宁县', '3');
INSERT INTO `yang_areas` VALUES ('2540', '305', '隰县', '3');
INSERT INTO `yang_areas` VALUES ('2541', '305', '永和县', '3');
INSERT INTO `yang_areas` VALUES ('2542', '305', '蒲县', '3');
INSERT INTO `yang_areas` VALUES ('2543', '305', '汾西县', '3');
INSERT INTO `yang_areas` VALUES ('2544', '306', '离石市', '3');
INSERT INTO `yang_areas` VALUES ('2545', '306', '离石区', '3');
INSERT INTO `yang_areas` VALUES ('2546', '306', '孝义市', '3');
INSERT INTO `yang_areas` VALUES ('2547', '306', '汾阳市', '3');
INSERT INTO `yang_areas` VALUES ('2548', '306', '文水县', '3');
INSERT INTO `yang_areas` VALUES ('2549', '306', '交城县', '3');
INSERT INTO `yang_areas` VALUES ('2550', '306', '兴县', '3');
INSERT INTO `yang_areas` VALUES ('2551', '306', '临县', '3');
INSERT INTO `yang_areas` VALUES ('2552', '306', '柳林县', '3');
INSERT INTO `yang_areas` VALUES ('2553', '306', '石楼县', '3');
INSERT INTO `yang_areas` VALUES ('2554', '306', '岚县', '3');
INSERT INTO `yang_areas` VALUES ('2555', '306', '方山县', '3');
INSERT INTO `yang_areas` VALUES ('2556', '306', '中阳县', '3');
INSERT INTO `yang_areas` VALUES ('2557', '306', '交口县', '3');
INSERT INTO `yang_areas` VALUES ('2558', '307', '朔城区', '3');
INSERT INTO `yang_areas` VALUES ('2559', '307', '平鲁区', '3');
INSERT INTO `yang_areas` VALUES ('2560', '307', '山阴县', '3');
INSERT INTO `yang_areas` VALUES ('2561', '307', '应县', '3');
INSERT INTO `yang_areas` VALUES ('2562', '307', '右玉县', '3');
INSERT INTO `yang_areas` VALUES ('2563', '307', '怀仁县', '3');
INSERT INTO `yang_areas` VALUES ('2564', '308', '忻府区', '3');
INSERT INTO `yang_areas` VALUES ('2565', '308', '原平市', '3');
INSERT INTO `yang_areas` VALUES ('2566', '308', '定襄县', '3');
INSERT INTO `yang_areas` VALUES ('2567', '308', '五台县', '3');
INSERT INTO `yang_areas` VALUES ('2568', '308', '代县', '3');
INSERT INTO `yang_areas` VALUES ('2569', '308', '繁峙县', '3');
INSERT INTO `yang_areas` VALUES ('2570', '308', '宁武县', '3');
INSERT INTO `yang_areas` VALUES ('2571', '308', '静乐县', '3');
INSERT INTO `yang_areas` VALUES ('2572', '308', '神池县', '3');
INSERT INTO `yang_areas` VALUES ('2573', '308', '五寨县', '3');
INSERT INTO `yang_areas` VALUES ('2574', '308', '岢岚县', '3');
INSERT INTO `yang_areas` VALUES ('2575', '308', '河曲县', '3');
INSERT INTO `yang_areas` VALUES ('2576', '308', '保德县', '3');
INSERT INTO `yang_areas` VALUES ('2577', '308', '偏关县', '3');
INSERT INTO `yang_areas` VALUES ('2578', '309', '城区', '3');
INSERT INTO `yang_areas` VALUES ('2579', '309', '矿区', '3');
INSERT INTO `yang_areas` VALUES ('2580', '309', '郊区', '3');
INSERT INTO `yang_areas` VALUES ('2581', '309', '平定县', '3');
INSERT INTO `yang_areas` VALUES ('2582', '309', '盂县', '3');
INSERT INTO `yang_areas` VALUES ('2583', '310', '盐湖区', '3');
INSERT INTO `yang_areas` VALUES ('2584', '310', '永济市', '3');
INSERT INTO `yang_areas` VALUES ('2585', '310', '河津市', '3');
INSERT INTO `yang_areas` VALUES ('2586', '310', '临猗县', '3');
INSERT INTO `yang_areas` VALUES ('2587', '310', '万荣县', '3');
INSERT INTO `yang_areas` VALUES ('2588', '310', '闻喜县', '3');
INSERT INTO `yang_areas` VALUES ('2589', '310', '稷山县', '3');
INSERT INTO `yang_areas` VALUES ('2590', '310', '新绛县', '3');
INSERT INTO `yang_areas` VALUES ('2591', '310', '绛县', '3');
INSERT INTO `yang_areas` VALUES ('2592', '310', '垣曲县', '3');
INSERT INTO `yang_areas` VALUES ('2593', '310', '夏县', '3');
INSERT INTO `yang_areas` VALUES ('2594', '310', '平陆县', '3');
INSERT INTO `yang_areas` VALUES ('2595', '310', '芮城县', '3');
INSERT INTO `yang_areas` VALUES ('2596', '311', '莲湖区', '3');
INSERT INTO `yang_areas` VALUES ('2597', '311', '新城区', '3');
INSERT INTO `yang_areas` VALUES ('2598', '311', '碑林区', '3');
INSERT INTO `yang_areas` VALUES ('2599', '311', '雁塔区', '3');
INSERT INTO `yang_areas` VALUES ('2600', '311', '灞桥区', '3');
INSERT INTO `yang_areas` VALUES ('2601', '311', '未央区', '3');
INSERT INTO `yang_areas` VALUES ('2602', '311', '阎良区', '3');
INSERT INTO `yang_areas` VALUES ('2603', '311', '临潼区', '3');
INSERT INTO `yang_areas` VALUES ('2604', '311', '长安区', '3');
INSERT INTO `yang_areas` VALUES ('2605', '311', '蓝田县', '3');
INSERT INTO `yang_areas` VALUES ('2606', '311', '周至县', '3');
INSERT INTO `yang_areas` VALUES ('2607', '311', '户县', '3');
INSERT INTO `yang_areas` VALUES ('2608', '311', '高陵县', '3');
INSERT INTO `yang_areas` VALUES ('2609', '312', '汉滨区', '3');
INSERT INTO `yang_areas` VALUES ('2610', '312', '汉阴县', '3');
INSERT INTO `yang_areas` VALUES ('2611', '312', '石泉县', '3');
INSERT INTO `yang_areas` VALUES ('2612', '312', '宁陕县', '3');
INSERT INTO `yang_areas` VALUES ('2613', '312', '紫阳县', '3');
INSERT INTO `yang_areas` VALUES ('2614', '312', '岚皋县', '3');
INSERT INTO `yang_areas` VALUES ('2615', '312', '平利县', '3');
INSERT INTO `yang_areas` VALUES ('2616', '312', '镇坪县', '3');
INSERT INTO `yang_areas` VALUES ('2617', '312', '旬阳县', '3');
INSERT INTO `yang_areas` VALUES ('2618', '312', '白河县', '3');
INSERT INTO `yang_areas` VALUES ('2619', '313', '陈仓区', '3');
INSERT INTO `yang_areas` VALUES ('2620', '313', '渭滨区', '3');
INSERT INTO `yang_areas` VALUES ('2621', '313', '金台区', '3');
INSERT INTO `yang_areas` VALUES ('2622', '313', '凤翔县', '3');
INSERT INTO `yang_areas` VALUES ('2623', '313', '岐山县', '3');
INSERT INTO `yang_areas` VALUES ('2624', '313', '扶风县', '3');
INSERT INTO `yang_areas` VALUES ('2625', '313', '眉县', '3');
INSERT INTO `yang_areas` VALUES ('2626', '313', '陇县', '3');
INSERT INTO `yang_areas` VALUES ('2627', '313', '千阳县', '3');
INSERT INTO `yang_areas` VALUES ('2628', '313', '麟游县', '3');
INSERT INTO `yang_areas` VALUES ('2629', '313', '凤县', '3');
INSERT INTO `yang_areas` VALUES ('2630', '313', '太白县', '3');
INSERT INTO `yang_areas` VALUES ('2631', '314', '汉台区', '3');
INSERT INTO `yang_areas` VALUES ('2632', '314', '南郑县', '3');
INSERT INTO `yang_areas` VALUES ('2633', '314', '城固县', '3');
INSERT INTO `yang_areas` VALUES ('2634', '314', '洋县', '3');
INSERT INTO `yang_areas` VALUES ('2635', '314', '西乡县', '3');
INSERT INTO `yang_areas` VALUES ('2636', '314', '勉县', '3');
INSERT INTO `yang_areas` VALUES ('2637', '314', '宁强县', '3');
INSERT INTO `yang_areas` VALUES ('2638', '314', '略阳县', '3');
INSERT INTO `yang_areas` VALUES ('2639', '314', '镇巴县', '3');
INSERT INTO `yang_areas` VALUES ('2640', '314', '留坝县', '3');
INSERT INTO `yang_areas` VALUES ('2641', '314', '佛坪县', '3');
INSERT INTO `yang_areas` VALUES ('2642', '315', '商州区', '3');
INSERT INTO `yang_areas` VALUES ('2643', '315', '洛南县', '3');
INSERT INTO `yang_areas` VALUES ('2644', '315', '丹凤县', '3');
INSERT INTO `yang_areas` VALUES ('2645', '315', '商南县', '3');
INSERT INTO `yang_areas` VALUES ('2646', '315', '山阳县', '3');
INSERT INTO `yang_areas` VALUES ('2647', '315', '镇安县', '3');
INSERT INTO `yang_areas` VALUES ('2648', '315', '柞水县', '3');
INSERT INTO `yang_areas` VALUES ('2649', '316', '耀州区', '3');
INSERT INTO `yang_areas` VALUES ('2650', '316', '王益区', '3');
INSERT INTO `yang_areas` VALUES ('2651', '316', '印台区', '3');
INSERT INTO `yang_areas` VALUES ('2652', '316', '宜君县', '3');
INSERT INTO `yang_areas` VALUES ('2653', '317', '临渭区', '3');
INSERT INTO `yang_areas` VALUES ('2654', '317', '韩城市', '3');
INSERT INTO `yang_areas` VALUES ('2655', '317', '华阴市', '3');
INSERT INTO `yang_areas` VALUES ('2656', '317', '华县', '3');
INSERT INTO `yang_areas` VALUES ('2657', '317', '潼关县', '3');
INSERT INTO `yang_areas` VALUES ('2658', '317', '大荔县', '3');
INSERT INTO `yang_areas` VALUES ('2659', '317', '合阳县', '3');
INSERT INTO `yang_areas` VALUES ('2660', '317', '澄城县', '3');
INSERT INTO `yang_areas` VALUES ('2661', '317', '蒲城县', '3');
INSERT INTO `yang_areas` VALUES ('2662', '317', '白水县', '3');
INSERT INTO `yang_areas` VALUES ('2663', '317', '富平县', '3');
INSERT INTO `yang_areas` VALUES ('2664', '318', '秦都区', '3');
INSERT INTO `yang_areas` VALUES ('2665', '318', '渭城区', '3');
INSERT INTO `yang_areas` VALUES ('2666', '318', '杨陵区', '3');
INSERT INTO `yang_areas` VALUES ('2667', '318', '兴平市', '3');
INSERT INTO `yang_areas` VALUES ('2668', '318', '三原县', '3');
INSERT INTO `yang_areas` VALUES ('2669', '318', '泾阳县', '3');
INSERT INTO `yang_areas` VALUES ('2670', '318', '乾县', '3');
INSERT INTO `yang_areas` VALUES ('2671', '318', '礼泉县', '3');
INSERT INTO `yang_areas` VALUES ('2672', '318', '永寿县', '3');
INSERT INTO `yang_areas` VALUES ('2673', '318', '彬县', '3');
INSERT INTO `yang_areas` VALUES ('2674', '318', '长武县', '3');
INSERT INTO `yang_areas` VALUES ('2675', '318', '旬邑县', '3');
INSERT INTO `yang_areas` VALUES ('2676', '318', '淳化县', '3');
INSERT INTO `yang_areas` VALUES ('2677', '318', '武功县', '3');
INSERT INTO `yang_areas` VALUES ('2678', '319', '吴起县', '3');
INSERT INTO `yang_areas` VALUES ('2679', '319', '宝塔区', '3');
INSERT INTO `yang_areas` VALUES ('2680', '319', '延长县', '3');
INSERT INTO `yang_areas` VALUES ('2681', '319', '延川县', '3');
INSERT INTO `yang_areas` VALUES ('2682', '319', '子长县', '3');
INSERT INTO `yang_areas` VALUES ('2683', '319', '安塞县', '3');
INSERT INTO `yang_areas` VALUES ('2684', '319', '志丹县', '3');
INSERT INTO `yang_areas` VALUES ('2685', '319', '甘泉县', '3');
INSERT INTO `yang_areas` VALUES ('2686', '319', '富县', '3');
INSERT INTO `yang_areas` VALUES ('2687', '319', '洛川县', '3');
INSERT INTO `yang_areas` VALUES ('2688', '319', '宜川县', '3');
INSERT INTO `yang_areas` VALUES ('2689', '319', '黄龙县', '3');
INSERT INTO `yang_areas` VALUES ('2690', '319', '黄陵县', '3');
INSERT INTO `yang_areas` VALUES ('2691', '320', '榆阳区', '3');
INSERT INTO `yang_areas` VALUES ('2692', '320', '神木县', '3');
INSERT INTO `yang_areas` VALUES ('2693', '320', '府谷县', '3');
INSERT INTO `yang_areas` VALUES ('2694', '320', '横山县', '3');
INSERT INTO `yang_areas` VALUES ('2695', '320', '靖边县', '3');
INSERT INTO `yang_areas` VALUES ('2696', '320', '定边县', '3');
INSERT INTO `yang_areas` VALUES ('2697', '320', '绥德县', '3');
INSERT INTO `yang_areas` VALUES ('2698', '320', '米脂县', '3');
INSERT INTO `yang_areas` VALUES ('2699', '320', '佳县', '3');
INSERT INTO `yang_areas` VALUES ('2700', '320', '吴堡县', '3');
INSERT INTO `yang_areas` VALUES ('2701', '320', '清涧县', '3');
INSERT INTO `yang_areas` VALUES ('2702', '320', '子洲县', '3');
INSERT INTO `yang_areas` VALUES ('2703', '321', '长宁区', '3');
INSERT INTO `yang_areas` VALUES ('2704', '321', '闸北区', '3');
INSERT INTO `yang_areas` VALUES ('2705', '321', '闵行区', '3');
INSERT INTO `yang_areas` VALUES ('2706', '321', '徐汇区', '3');
INSERT INTO `yang_areas` VALUES ('2707', '321', '浦东新区', '3');
INSERT INTO `yang_areas` VALUES ('2708', '321', '杨浦区', '3');
INSERT INTO `yang_areas` VALUES ('2709', '321', '普陀区', '3');
INSERT INTO `yang_areas` VALUES ('2710', '321', '静安区', '3');
INSERT INTO `yang_areas` VALUES ('2711', '321', '卢湾区', '3');
INSERT INTO `yang_areas` VALUES ('2712', '321', '虹口区', '3');
INSERT INTO `yang_areas` VALUES ('2713', '321', '黄浦区', '3');
INSERT INTO `yang_areas` VALUES ('2714', '321', '南汇区', '3');
INSERT INTO `yang_areas` VALUES ('2715', '321', '松江区', '3');
INSERT INTO `yang_areas` VALUES ('2716', '321', '嘉定区', '3');
INSERT INTO `yang_areas` VALUES ('2717', '321', '宝山区', '3');
INSERT INTO `yang_areas` VALUES ('2718', '321', '青浦区', '3');
INSERT INTO `yang_areas` VALUES ('2719', '321', '金山区', '3');
INSERT INTO `yang_areas` VALUES ('2720', '321', '奉贤区', '3');
INSERT INTO `yang_areas` VALUES ('2721', '321', '崇明县', '3');
INSERT INTO `yang_areas` VALUES ('2722', '322', '青羊区', '3');
INSERT INTO `yang_areas` VALUES ('2723', '322', '锦江区', '3');
INSERT INTO `yang_areas` VALUES ('2724', '322', '金牛区', '3');
INSERT INTO `yang_areas` VALUES ('2725', '322', '武侯区', '3');
INSERT INTO `yang_areas` VALUES ('2726', '322', '成华区', '3');
INSERT INTO `yang_areas` VALUES ('2727', '322', '龙泉驿区', '3');
INSERT INTO `yang_areas` VALUES ('2728', '322', '青白江区', '3');
INSERT INTO `yang_areas` VALUES ('2729', '322', '新都区', '3');
INSERT INTO `yang_areas` VALUES ('2730', '322', '温江区', '3');
INSERT INTO `yang_areas` VALUES ('2731', '322', '高新区', '3');
INSERT INTO `yang_areas` VALUES ('2732', '322', '高新西区', '3');
INSERT INTO `yang_areas` VALUES ('2733', '322', '都江堰市', '3');
INSERT INTO `yang_areas` VALUES ('2734', '322', '彭州市', '3');
INSERT INTO `yang_areas` VALUES ('2735', '322', '邛崃市', '3');
INSERT INTO `yang_areas` VALUES ('2736', '322', '崇州市', '3');
INSERT INTO `yang_areas` VALUES ('2737', '322', '金堂县', '3');
INSERT INTO `yang_areas` VALUES ('2738', '322', '双流县', '3');
INSERT INTO `yang_areas` VALUES ('2739', '322', '郫县', '3');
INSERT INTO `yang_areas` VALUES ('2740', '322', '大邑县', '3');
INSERT INTO `yang_areas` VALUES ('2741', '322', '蒲江县', '3');
INSERT INTO `yang_areas` VALUES ('2742', '322', '新津县', '3');
INSERT INTO `yang_areas` VALUES ('2743', '322', '都江堰市', '3');
INSERT INTO `yang_areas` VALUES ('2744', '322', '彭州市', '3');
INSERT INTO `yang_areas` VALUES ('2745', '322', '邛崃市', '3');
INSERT INTO `yang_areas` VALUES ('2746', '322', '崇州市', '3');
INSERT INTO `yang_areas` VALUES ('2747', '322', '金堂县', '3');
INSERT INTO `yang_areas` VALUES ('2748', '322', '双流县', '3');
INSERT INTO `yang_areas` VALUES ('2749', '322', '郫县', '3');
INSERT INTO `yang_areas` VALUES ('2750', '322', '大邑县', '3');
INSERT INTO `yang_areas` VALUES ('2751', '322', '蒲江县', '3');
INSERT INTO `yang_areas` VALUES ('2752', '322', '新津县', '3');
INSERT INTO `yang_areas` VALUES ('2753', '323', '涪城区', '3');
INSERT INTO `yang_areas` VALUES ('2754', '323', '游仙区', '3');
INSERT INTO `yang_areas` VALUES ('2755', '323', '江油市', '3');
INSERT INTO `yang_areas` VALUES ('2756', '323', '盐亭县', '3');
INSERT INTO `yang_areas` VALUES ('2757', '323', '三台县', '3');
INSERT INTO `yang_areas` VALUES ('2758', '323', '平武县', '3');
INSERT INTO `yang_areas` VALUES ('2759', '323', '安县', '3');
INSERT INTO `yang_areas` VALUES ('2760', '323', '梓潼县', '3');
INSERT INTO `yang_areas` VALUES ('2761', '323', '北川县', '3');
INSERT INTO `yang_areas` VALUES ('2762', '324', '马尔康县', '3');
INSERT INTO `yang_areas` VALUES ('2763', '324', '汶川县', '3');
INSERT INTO `yang_areas` VALUES ('2764', '324', '理县', '3');
INSERT INTO `yang_areas` VALUES ('2765', '324', '茂县', '3');
INSERT INTO `yang_areas` VALUES ('2766', '324', '松潘县', '3');
INSERT INTO `yang_areas` VALUES ('2767', '324', '九寨沟县', '3');
INSERT INTO `yang_areas` VALUES ('2768', '324', '金川县', '3');
INSERT INTO `yang_areas` VALUES ('2769', '324', '小金县', '3');
INSERT INTO `yang_areas` VALUES ('2770', '324', '黑水县', '3');
INSERT INTO `yang_areas` VALUES ('2771', '324', '壤塘县', '3');
INSERT INTO `yang_areas` VALUES ('2772', '324', '阿坝县', '3');
INSERT INTO `yang_areas` VALUES ('2773', '324', '若尔盖县', '3');
INSERT INTO `yang_areas` VALUES ('2774', '324', '红原县', '3');
INSERT INTO `yang_areas` VALUES ('2775', '325', '巴州区', '3');
INSERT INTO `yang_areas` VALUES ('2776', '325', '通江县', '3');
INSERT INTO `yang_areas` VALUES ('2777', '325', '南江县', '3');
INSERT INTO `yang_areas` VALUES ('2778', '325', '平昌县', '3');
INSERT INTO `yang_areas` VALUES ('2779', '326', '通川区', '3');
INSERT INTO `yang_areas` VALUES ('2780', '326', '万源市', '3');
INSERT INTO `yang_areas` VALUES ('2781', '326', '达县', '3');
INSERT INTO `yang_areas` VALUES ('2782', '326', '宣汉县', '3');
INSERT INTO `yang_areas` VALUES ('2783', '326', '开江县', '3');
INSERT INTO `yang_areas` VALUES ('2784', '326', '大竹县', '3');
INSERT INTO `yang_areas` VALUES ('2785', '326', '渠县', '3');
INSERT INTO `yang_areas` VALUES ('2786', '327', '旌阳区', '3');
INSERT INTO `yang_areas` VALUES ('2787', '327', '广汉市', '3');
INSERT INTO `yang_areas` VALUES ('2788', '327', '什邡市', '3');
INSERT INTO `yang_areas` VALUES ('2789', '327', '绵竹市', '3');
INSERT INTO `yang_areas` VALUES ('2790', '327', '罗江县', '3');
INSERT INTO `yang_areas` VALUES ('2791', '327', '中江县', '3');
INSERT INTO `yang_areas` VALUES ('2792', '328', '康定县', '3');
INSERT INTO `yang_areas` VALUES ('2793', '328', '丹巴县', '3');
INSERT INTO `yang_areas` VALUES ('2794', '328', '泸定县', '3');
INSERT INTO `yang_areas` VALUES ('2795', '328', '炉霍县', '3');
INSERT INTO `yang_areas` VALUES ('2796', '328', '九龙县', '3');
INSERT INTO `yang_areas` VALUES ('2797', '328', '甘孜县', '3');
INSERT INTO `yang_areas` VALUES ('2798', '328', '雅江县', '3');
INSERT INTO `yang_areas` VALUES ('2799', '328', '新龙县', '3');
INSERT INTO `yang_areas` VALUES ('2800', '328', '道孚县', '3');
INSERT INTO `yang_areas` VALUES ('2801', '328', '白玉县', '3');
INSERT INTO `yang_areas` VALUES ('2802', '328', '理塘县', '3');
INSERT INTO `yang_areas` VALUES ('2803', '328', '德格县', '3');
INSERT INTO `yang_areas` VALUES ('2804', '328', '乡城县', '3');
INSERT INTO `yang_areas` VALUES ('2805', '328', '石渠县', '3');
INSERT INTO `yang_areas` VALUES ('2806', '328', '稻城县', '3');
INSERT INTO `yang_areas` VALUES ('2807', '328', '色达县', '3');
INSERT INTO `yang_areas` VALUES ('2808', '328', '巴塘县', '3');
INSERT INTO `yang_areas` VALUES ('2809', '328', '得荣县', '3');
INSERT INTO `yang_areas` VALUES ('2810', '329', '广安区', '3');
INSERT INTO `yang_areas` VALUES ('2811', '329', '华蓥市', '3');
INSERT INTO `yang_areas` VALUES ('2812', '329', '岳池县', '3');
INSERT INTO `yang_areas` VALUES ('2813', '329', '武胜县', '3');
INSERT INTO `yang_areas` VALUES ('2814', '329', '邻水县', '3');
INSERT INTO `yang_areas` VALUES ('2815', '330', '利州区', '3');
INSERT INTO `yang_areas` VALUES ('2816', '330', '元坝区', '3');
INSERT INTO `yang_areas` VALUES ('2817', '330', '朝天区', '3');
INSERT INTO `yang_areas` VALUES ('2818', '330', '旺苍县', '3');
INSERT INTO `yang_areas` VALUES ('2819', '330', '青川县', '3');
INSERT INTO `yang_areas` VALUES ('2820', '330', '剑阁县', '3');
INSERT INTO `yang_areas` VALUES ('2821', '330', '苍溪县', '3');
INSERT INTO `yang_areas` VALUES ('2822', '331', '峨眉山市', '3');
INSERT INTO `yang_areas` VALUES ('2823', '331', '乐山市', '3');
INSERT INTO `yang_areas` VALUES ('2824', '331', '犍为县', '3');
INSERT INTO `yang_areas` VALUES ('2825', '331', '井研县', '3');
INSERT INTO `yang_areas` VALUES ('2826', '331', '夹江县', '3');
INSERT INTO `yang_areas` VALUES ('2827', '331', '沐川县', '3');
INSERT INTO `yang_areas` VALUES ('2828', '331', '峨边', '3');
INSERT INTO `yang_areas` VALUES ('2829', '331', '马边', '3');
INSERT INTO `yang_areas` VALUES ('2830', '332', '西昌市', '3');
INSERT INTO `yang_areas` VALUES ('2831', '332', '盐源县', '3');
INSERT INTO `yang_areas` VALUES ('2832', '332', '德昌县', '3');
INSERT INTO `yang_areas` VALUES ('2833', '332', '会理县', '3');
INSERT INTO `yang_areas` VALUES ('2834', '332', '会东县', '3');
INSERT INTO `yang_areas` VALUES ('2835', '332', '宁南县', '3');
INSERT INTO `yang_areas` VALUES ('2836', '332', '普格县', '3');
INSERT INTO `yang_areas` VALUES ('2837', '332', '布拖县', '3');
INSERT INTO `yang_areas` VALUES ('2838', '332', '金阳县', '3');
INSERT INTO `yang_areas` VALUES ('2839', '332', '昭觉县', '3');
INSERT INTO `yang_areas` VALUES ('2840', '332', '喜德县', '3');
INSERT INTO `yang_areas` VALUES ('2841', '332', '冕宁县', '3');
INSERT INTO `yang_areas` VALUES ('2842', '332', '越西县', '3');
INSERT INTO `yang_areas` VALUES ('2843', '332', '甘洛县', '3');
INSERT INTO `yang_areas` VALUES ('2844', '332', '美姑县', '3');
INSERT INTO `yang_areas` VALUES ('2845', '332', '雷波县', '3');
INSERT INTO `yang_areas` VALUES ('2846', '332', '木里', '3');
INSERT INTO `yang_areas` VALUES ('2847', '333', '东坡区', '3');
INSERT INTO `yang_areas` VALUES ('2848', '333', '仁寿县', '3');
INSERT INTO `yang_areas` VALUES ('2849', '333', '彭山县', '3');
INSERT INTO `yang_areas` VALUES ('2850', '333', '洪雅县', '3');
INSERT INTO `yang_areas` VALUES ('2851', '333', '丹棱县', '3');
INSERT INTO `yang_areas` VALUES ('2852', '333', '青神县', '3');
INSERT INTO `yang_areas` VALUES ('2853', '334', '阆中市', '3');
INSERT INTO `yang_areas` VALUES ('2854', '334', '南部县', '3');
INSERT INTO `yang_areas` VALUES ('2855', '334', '营山县', '3');
INSERT INTO `yang_areas` VALUES ('2856', '334', '蓬安县', '3');
INSERT INTO `yang_areas` VALUES ('2857', '334', '仪陇县', '3');
INSERT INTO `yang_areas` VALUES ('2858', '334', '顺庆区', '3');
INSERT INTO `yang_areas` VALUES ('2859', '334', '高坪区', '3');
INSERT INTO `yang_areas` VALUES ('2860', '334', '嘉陵区', '3');
INSERT INTO `yang_areas` VALUES ('2861', '334', '西充县', '3');
INSERT INTO `yang_areas` VALUES ('2862', '335', '市中区', '3');
INSERT INTO `yang_areas` VALUES ('2863', '335', '东兴区', '3');
INSERT INTO `yang_areas` VALUES ('2864', '335', '威远县', '3');
INSERT INTO `yang_areas` VALUES ('2865', '335', '资中县', '3');
INSERT INTO `yang_areas` VALUES ('2866', '335', '隆昌县', '3');
INSERT INTO `yang_areas` VALUES ('2867', '336', '东  区', '3');
INSERT INTO `yang_areas` VALUES ('2868', '336', '西  区', '3');
INSERT INTO `yang_areas` VALUES ('2869', '336', '仁和区', '3');
INSERT INTO `yang_areas` VALUES ('2870', '336', '米易县', '3');
INSERT INTO `yang_areas` VALUES ('2871', '336', '盐边县', '3');
INSERT INTO `yang_areas` VALUES ('2872', '337', '船山区', '3');
INSERT INTO `yang_areas` VALUES ('2873', '337', '安居区', '3');
INSERT INTO `yang_areas` VALUES ('2874', '337', '蓬溪县', '3');
INSERT INTO `yang_areas` VALUES ('2875', '337', '射洪县', '3');
INSERT INTO `yang_areas` VALUES ('2876', '337', '大英县', '3');
INSERT INTO `yang_areas` VALUES ('2877', '338', '雨城区', '3');
INSERT INTO `yang_areas` VALUES ('2878', '338', '名山县', '3');
INSERT INTO `yang_areas` VALUES ('2879', '338', '荥经县', '3');
INSERT INTO `yang_areas` VALUES ('2880', '338', '汉源县', '3');
INSERT INTO `yang_areas` VALUES ('2881', '338', '石棉县', '3');
INSERT INTO `yang_areas` VALUES ('2882', '338', '天全县', '3');
INSERT INTO `yang_areas` VALUES ('2883', '338', '芦山县', '3');
INSERT INTO `yang_areas` VALUES ('2884', '338', '宝兴县', '3');
INSERT INTO `yang_areas` VALUES ('2885', '339', '翠屏区', '3');
INSERT INTO `yang_areas` VALUES ('2886', '339', '宜宾县', '3');
INSERT INTO `yang_areas` VALUES ('2887', '339', '南溪县', '3');
INSERT INTO `yang_areas` VALUES ('2888', '339', '江安县', '3');
INSERT INTO `yang_areas` VALUES ('2889', '339', '长宁县', '3');
INSERT INTO `yang_areas` VALUES ('2890', '339', '高县', '3');
INSERT INTO `yang_areas` VALUES ('2891', '339', '珙县', '3');
INSERT INTO `yang_areas` VALUES ('2892', '339', '筠连县', '3');
INSERT INTO `yang_areas` VALUES ('2893', '339', '兴文县', '3');
INSERT INTO `yang_areas` VALUES ('2894', '339', '屏山县', '3');
INSERT INTO `yang_areas` VALUES ('2895', '340', '雁江区', '3');
INSERT INTO `yang_areas` VALUES ('2896', '340', '简阳市', '3');
INSERT INTO `yang_areas` VALUES ('2897', '340', '安岳县', '3');
INSERT INTO `yang_areas` VALUES ('2898', '340', '乐至县', '3');
INSERT INTO `yang_areas` VALUES ('2899', '341', '大安区', '3');
INSERT INTO `yang_areas` VALUES ('2900', '341', '自流井区', '3');
INSERT INTO `yang_areas` VALUES ('2901', '341', '贡井区', '3');
INSERT INTO `yang_areas` VALUES ('2902', '341', '沿滩区', '3');
INSERT INTO `yang_areas` VALUES ('2903', '341', '荣县', '3');
INSERT INTO `yang_areas` VALUES ('2904', '341', '富顺县', '3');
INSERT INTO `yang_areas` VALUES ('2905', '342', '江阳区', '3');
INSERT INTO `yang_areas` VALUES ('2906', '342', '纳溪区', '3');
INSERT INTO `yang_areas` VALUES ('2907', '342', '龙马潭区', '3');
INSERT INTO `yang_areas` VALUES ('2908', '342', '泸县', '3');
INSERT INTO `yang_areas` VALUES ('2909', '342', '合江县', '3');
INSERT INTO `yang_areas` VALUES ('2910', '342', '叙永县', '3');
INSERT INTO `yang_areas` VALUES ('2911', '342', '古蔺县', '3');
INSERT INTO `yang_areas` VALUES ('2912', '343', '和平区', '3');
INSERT INTO `yang_areas` VALUES ('2913', '343', '河西区', '3');
INSERT INTO `yang_areas` VALUES ('2914', '343', '南开区', '3');
INSERT INTO `yang_areas` VALUES ('2915', '343', '河北区', '3');
INSERT INTO `yang_areas` VALUES ('2916', '343', '河东区', '3');
INSERT INTO `yang_areas` VALUES ('2917', '343', '红桥区', '3');
INSERT INTO `yang_areas` VALUES ('2918', '343', '东丽区', '3');
INSERT INTO `yang_areas` VALUES ('2919', '343', '津南区', '3');
INSERT INTO `yang_areas` VALUES ('2920', '343', '西青区', '3');
INSERT INTO `yang_areas` VALUES ('2921', '343', '北辰区', '3');
INSERT INTO `yang_areas` VALUES ('2922', '343', '塘沽区', '3');
INSERT INTO `yang_areas` VALUES ('2923', '343', '汉沽区', '3');
INSERT INTO `yang_areas` VALUES ('2924', '343', '大港区', '3');
INSERT INTO `yang_areas` VALUES ('2925', '343', '武清区', '3');
INSERT INTO `yang_areas` VALUES ('2926', '343', '宝坻区', '3');
INSERT INTO `yang_areas` VALUES ('2927', '343', '经济开发区', '3');
INSERT INTO `yang_areas` VALUES ('2928', '343', '宁河县', '3');
INSERT INTO `yang_areas` VALUES ('2929', '343', '静海县', '3');
INSERT INTO `yang_areas` VALUES ('2930', '343', '蓟县', '3');
INSERT INTO `yang_areas` VALUES ('2931', '344', '城关区', '3');
INSERT INTO `yang_areas` VALUES ('2932', '344', '林周县', '3');
INSERT INTO `yang_areas` VALUES ('2933', '344', '当雄县', '3');
INSERT INTO `yang_areas` VALUES ('2934', '344', '尼木县', '3');
INSERT INTO `yang_areas` VALUES ('2935', '344', '曲水县', '3');
INSERT INTO `yang_areas` VALUES ('2936', '344', '堆龙德庆县', '3');
INSERT INTO `yang_areas` VALUES ('2937', '344', '达孜县', '3');
INSERT INTO `yang_areas` VALUES ('2938', '344', '墨竹工卡县', '3');
INSERT INTO `yang_areas` VALUES ('2939', '345', '噶尔县', '3');
INSERT INTO `yang_areas` VALUES ('2940', '345', '普兰县', '3');
INSERT INTO `yang_areas` VALUES ('2941', '345', '札达县', '3');
INSERT INTO `yang_areas` VALUES ('2942', '345', '日土县', '3');
INSERT INTO `yang_areas` VALUES ('2943', '345', '革吉县', '3');
INSERT INTO `yang_areas` VALUES ('2944', '345', '改则县', '3');
INSERT INTO `yang_areas` VALUES ('2945', '345', '措勤县', '3');
INSERT INTO `yang_areas` VALUES ('2946', '346', '昌都县', '3');
INSERT INTO `yang_areas` VALUES ('2947', '346', '江达县', '3');
INSERT INTO `yang_areas` VALUES ('2948', '346', '贡觉县', '3');
INSERT INTO `yang_areas` VALUES ('2949', '346', '类乌齐县', '3');
INSERT INTO `yang_areas` VALUES ('2950', '346', '丁青县', '3');
INSERT INTO `yang_areas` VALUES ('2951', '346', '察雅县', '3');
INSERT INTO `yang_areas` VALUES ('2952', '346', '八宿县', '3');
INSERT INTO `yang_areas` VALUES ('2953', '346', '左贡县', '3');
INSERT INTO `yang_areas` VALUES ('2954', '346', '芒康县', '3');
INSERT INTO `yang_areas` VALUES ('2955', '346', '洛隆县', '3');
INSERT INTO `yang_areas` VALUES ('2956', '346', '边坝县', '3');
INSERT INTO `yang_areas` VALUES ('2957', '347', '林芝县', '3');
INSERT INTO `yang_areas` VALUES ('2958', '347', '工布江达县', '3');
INSERT INTO `yang_areas` VALUES ('2959', '347', '米林县', '3');
INSERT INTO `yang_areas` VALUES ('2960', '347', '墨脱县', '3');
INSERT INTO `yang_areas` VALUES ('2961', '347', '波密县', '3');
INSERT INTO `yang_areas` VALUES ('2962', '347', '察隅县', '3');
INSERT INTO `yang_areas` VALUES ('2963', '347', '朗县', '3');
INSERT INTO `yang_areas` VALUES ('2964', '348', '那曲县', '3');
INSERT INTO `yang_areas` VALUES ('2965', '348', '嘉黎县', '3');
INSERT INTO `yang_areas` VALUES ('2966', '348', '比如县', '3');
INSERT INTO `yang_areas` VALUES ('2967', '348', '聂荣县', '3');
INSERT INTO `yang_areas` VALUES ('2968', '348', '安多县', '3');
INSERT INTO `yang_areas` VALUES ('2969', '348', '申扎县', '3');
INSERT INTO `yang_areas` VALUES ('2970', '348', '索县', '3');
INSERT INTO `yang_areas` VALUES ('2971', '348', '班戈县', '3');
INSERT INTO `yang_areas` VALUES ('2972', '348', '巴青县', '3');
INSERT INTO `yang_areas` VALUES ('2973', '348', '尼玛县', '3');
INSERT INTO `yang_areas` VALUES ('2974', '349', '日喀则市', '3');
INSERT INTO `yang_areas` VALUES ('2975', '349', '南木林县', '3');
INSERT INTO `yang_areas` VALUES ('2976', '349', '江孜县', '3');
INSERT INTO `yang_areas` VALUES ('2977', '349', '定日县', '3');
INSERT INTO `yang_areas` VALUES ('2978', '349', '萨迦县', '3');
INSERT INTO `yang_areas` VALUES ('2979', '349', '拉孜县', '3');
INSERT INTO `yang_areas` VALUES ('2980', '349', '昂仁县', '3');
INSERT INTO `yang_areas` VALUES ('2981', '349', '谢通门县', '3');
INSERT INTO `yang_areas` VALUES ('2982', '349', '白朗县', '3');
INSERT INTO `yang_areas` VALUES ('2983', '349', '仁布县', '3');
INSERT INTO `yang_areas` VALUES ('2984', '349', '康马县', '3');
INSERT INTO `yang_areas` VALUES ('2985', '349', '定结县', '3');
INSERT INTO `yang_areas` VALUES ('2986', '349', '仲巴县', '3');
INSERT INTO `yang_areas` VALUES ('2987', '349', '亚东县', '3');
INSERT INTO `yang_areas` VALUES ('2988', '349', '吉隆县', '3');
INSERT INTO `yang_areas` VALUES ('2989', '349', '聂拉木县', '3');
INSERT INTO `yang_areas` VALUES ('2990', '349', '萨嘎县', '3');
INSERT INTO `yang_areas` VALUES ('2991', '349', '岗巴县', '3');
INSERT INTO `yang_areas` VALUES ('2992', '350', '乃东县', '3');
INSERT INTO `yang_areas` VALUES ('2993', '350', '扎囊县', '3');
INSERT INTO `yang_areas` VALUES ('2994', '350', '贡嘎县', '3');
INSERT INTO `yang_areas` VALUES ('2995', '350', '桑日县', '3');
INSERT INTO `yang_areas` VALUES ('2996', '350', '琼结县', '3');
INSERT INTO `yang_areas` VALUES ('2997', '350', '曲松县', '3');
INSERT INTO `yang_areas` VALUES ('2998', '350', '措美县', '3');
INSERT INTO `yang_areas` VALUES ('2999', '350', '洛扎县', '3');
INSERT INTO `yang_areas` VALUES ('3000', '350', '加查县', '3');
INSERT INTO `yang_areas` VALUES ('3001', '350', '隆子县', '3');
INSERT INTO `yang_areas` VALUES ('3002', '350', '错那县', '3');
INSERT INTO `yang_areas` VALUES ('3003', '350', '浪卡子县', '3');
INSERT INTO `yang_areas` VALUES ('3004', '351', '天山区', '3');
INSERT INTO `yang_areas` VALUES ('3005', '351', '沙依巴克区', '3');
INSERT INTO `yang_areas` VALUES ('3006', '351', '新市区', '3');
INSERT INTO `yang_areas` VALUES ('3007', '351', '水磨沟区', '3');
INSERT INTO `yang_areas` VALUES ('3008', '351', '头屯河区', '3');
INSERT INTO `yang_areas` VALUES ('3009', '351', '达坂城区', '3');
INSERT INTO `yang_areas` VALUES ('3010', '351', '米东区', '3');
INSERT INTO `yang_areas` VALUES ('3011', '351', '乌鲁木齐县', '3');
INSERT INTO `yang_areas` VALUES ('3012', '352', '阿克苏市', '3');
INSERT INTO `yang_areas` VALUES ('3013', '352', '温宿县', '3');
INSERT INTO `yang_areas` VALUES ('3014', '352', '库车县', '3');
INSERT INTO `yang_areas` VALUES ('3015', '352', '沙雅县', '3');
INSERT INTO `yang_areas` VALUES ('3016', '352', '新和县', '3');
INSERT INTO `yang_areas` VALUES ('3017', '352', '拜城县', '3');
INSERT INTO `yang_areas` VALUES ('3018', '352', '乌什县', '3');
INSERT INTO `yang_areas` VALUES ('3019', '352', '阿瓦提县', '3');
INSERT INTO `yang_areas` VALUES ('3020', '352', '柯坪县', '3');
INSERT INTO `yang_areas` VALUES ('3021', '353', '阿拉尔市', '3');
INSERT INTO `yang_areas` VALUES ('3022', '354', '库尔勒市', '3');
INSERT INTO `yang_areas` VALUES ('3023', '354', '轮台县', '3');
INSERT INTO `yang_areas` VALUES ('3024', '354', '尉犁县', '3');
INSERT INTO `yang_areas` VALUES ('3025', '354', '若羌县', '3');
INSERT INTO `yang_areas` VALUES ('3026', '354', '且末县', '3');
INSERT INTO `yang_areas` VALUES ('3027', '354', '焉耆', '3');
INSERT INTO `yang_areas` VALUES ('3028', '354', '和静县', '3');
INSERT INTO `yang_areas` VALUES ('3029', '354', '和硕县', '3');
INSERT INTO `yang_areas` VALUES ('3030', '354', '博湖县', '3');
INSERT INTO `yang_areas` VALUES ('3031', '355', '博乐市', '3');
INSERT INTO `yang_areas` VALUES ('3032', '355', '精河县', '3');
INSERT INTO `yang_areas` VALUES ('3033', '355', '温泉县', '3');
INSERT INTO `yang_areas` VALUES ('3034', '356', '呼图壁县', '3');
INSERT INTO `yang_areas` VALUES ('3035', '356', '米泉市', '3');
INSERT INTO `yang_areas` VALUES ('3036', '356', '昌吉市', '3');
INSERT INTO `yang_areas` VALUES ('3037', '356', '阜康市', '3');
INSERT INTO `yang_areas` VALUES ('3038', '356', '玛纳斯县', '3');
INSERT INTO `yang_areas` VALUES ('3039', '356', '奇台县', '3');
INSERT INTO `yang_areas` VALUES ('3040', '356', '吉木萨尔县', '3');
INSERT INTO `yang_areas` VALUES ('3041', '356', '木垒', '3');
INSERT INTO `yang_areas` VALUES ('3042', '357', '哈密市', '3');
INSERT INTO `yang_areas` VALUES ('3043', '357', '伊吾县', '3');
INSERT INTO `yang_areas` VALUES ('3044', '357', '巴里坤', '3');
INSERT INTO `yang_areas` VALUES ('3045', '358', '和田市', '3');
INSERT INTO `yang_areas` VALUES ('3046', '358', '和田县', '3');
INSERT INTO `yang_areas` VALUES ('3047', '358', '墨玉县', '3');
INSERT INTO `yang_areas` VALUES ('3048', '358', '皮山县', '3');
INSERT INTO `yang_areas` VALUES ('3049', '358', '洛浦县', '3');
INSERT INTO `yang_areas` VALUES ('3050', '358', '策勒县', '3');
INSERT INTO `yang_areas` VALUES ('3051', '358', '于田县', '3');
INSERT INTO `yang_areas` VALUES ('3052', '358', '民丰县', '3');
INSERT INTO `yang_areas` VALUES ('3053', '359', '喀什市', '3');
INSERT INTO `yang_areas` VALUES ('3054', '359', '疏附县', '3');
INSERT INTO `yang_areas` VALUES ('3055', '359', '疏勒县', '3');
INSERT INTO `yang_areas` VALUES ('3056', '359', '英吉沙县', '3');
INSERT INTO `yang_areas` VALUES ('3057', '359', '泽普县', '3');
INSERT INTO `yang_areas` VALUES ('3058', '359', '莎车县', '3');
INSERT INTO `yang_areas` VALUES ('3059', '359', '叶城县', '3');
INSERT INTO `yang_areas` VALUES ('3060', '359', '麦盖提县', '3');
INSERT INTO `yang_areas` VALUES ('3061', '359', '岳普湖县', '3');
INSERT INTO `yang_areas` VALUES ('3062', '359', '伽师县', '3');
INSERT INTO `yang_areas` VALUES ('3063', '359', '巴楚县', '3');
INSERT INTO `yang_areas` VALUES ('3064', '359', '塔什库尔干', '3');
INSERT INTO `yang_areas` VALUES ('3065', '360', '克拉玛依市', '3');
INSERT INTO `yang_areas` VALUES ('3066', '361', '阿图什市', '3');
INSERT INTO `yang_areas` VALUES ('3067', '361', '阿克陶县', '3');
INSERT INTO `yang_areas` VALUES ('3068', '361', '阿合奇县', '3');
INSERT INTO `yang_areas` VALUES ('3069', '361', '乌恰县', '3');
INSERT INTO `yang_areas` VALUES ('3070', '362', '石河子市', '3');
INSERT INTO `yang_areas` VALUES ('3071', '363', '图木舒克市', '3');
INSERT INTO `yang_areas` VALUES ('3072', '364', '吐鲁番市', '3');
INSERT INTO `yang_areas` VALUES ('3073', '364', '鄯善县', '3');
INSERT INTO `yang_areas` VALUES ('3074', '364', '托克逊县', '3');
INSERT INTO `yang_areas` VALUES ('3075', '365', '五家渠市', '3');
INSERT INTO `yang_areas` VALUES ('3076', '366', '阿勒泰市', '3');
INSERT INTO `yang_areas` VALUES ('3077', '366', '布克赛尔', '3');
INSERT INTO `yang_areas` VALUES ('3078', '366', '伊宁市', '3');
INSERT INTO `yang_areas` VALUES ('3079', '366', '布尔津县', '3');
INSERT INTO `yang_areas` VALUES ('3080', '366', '奎屯市', '3');
INSERT INTO `yang_areas` VALUES ('3081', '366', '乌苏市', '3');
INSERT INTO `yang_areas` VALUES ('3082', '366', '额敏县', '3');
INSERT INTO `yang_areas` VALUES ('3083', '366', '富蕴县', '3');
INSERT INTO `yang_areas` VALUES ('3084', '366', '伊宁县', '3');
INSERT INTO `yang_areas` VALUES ('3085', '366', '福海县', '3');
INSERT INTO `yang_areas` VALUES ('3086', '366', '霍城县', '3');
INSERT INTO `yang_areas` VALUES ('3087', '366', '沙湾县', '3');
INSERT INTO `yang_areas` VALUES ('3088', '366', '巩留县', '3');
INSERT INTO `yang_areas` VALUES ('3089', '366', '哈巴河县', '3');
INSERT INTO `yang_areas` VALUES ('3090', '366', '托里县', '3');
INSERT INTO `yang_areas` VALUES ('3091', '366', '青河县', '3');
INSERT INTO `yang_areas` VALUES ('3092', '366', '新源县', '3');
INSERT INTO `yang_areas` VALUES ('3093', '366', '裕民县', '3');
INSERT INTO `yang_areas` VALUES ('3094', '366', '和布克赛尔', '3');
INSERT INTO `yang_areas` VALUES ('3095', '366', '吉木乃县', '3');
INSERT INTO `yang_areas` VALUES ('3096', '366', '昭苏县', '3');
INSERT INTO `yang_areas` VALUES ('3097', '366', '特克斯县', '3');
INSERT INTO `yang_areas` VALUES ('3098', '366', '尼勒克县', '3');
INSERT INTO `yang_areas` VALUES ('3099', '366', '察布查尔', '3');
INSERT INTO `yang_areas` VALUES ('3100', '367', '盘龙区', '3');
INSERT INTO `yang_areas` VALUES ('3101', '367', '五华区', '3');
INSERT INTO `yang_areas` VALUES ('3102', '367', '官渡区', '3');
INSERT INTO `yang_areas` VALUES ('3103', '367', '西山区', '3');
INSERT INTO `yang_areas` VALUES ('3104', '367', '东川区', '3');
INSERT INTO `yang_areas` VALUES ('3105', '367', '安宁市', '3');
INSERT INTO `yang_areas` VALUES ('3106', '367', '呈贡县', '3');
INSERT INTO `yang_areas` VALUES ('3107', '367', '晋宁县', '3');
INSERT INTO `yang_areas` VALUES ('3108', '367', '富民县', '3');
INSERT INTO `yang_areas` VALUES ('3109', '367', '宜良县', '3');
INSERT INTO `yang_areas` VALUES ('3110', '367', '嵩明县', '3');
INSERT INTO `yang_areas` VALUES ('3111', '367', '石林县', '3');
INSERT INTO `yang_areas` VALUES ('3112', '367', '禄劝', '3');
INSERT INTO `yang_areas` VALUES ('3113', '367', '寻甸', '3');
INSERT INTO `yang_areas` VALUES ('3114', '368', '兰坪', '3');
INSERT INTO `yang_areas` VALUES ('3115', '368', '泸水县', '3');
INSERT INTO `yang_areas` VALUES ('3116', '368', '福贡县', '3');
INSERT INTO `yang_areas` VALUES ('3117', '368', '贡山', '3');
INSERT INTO `yang_areas` VALUES ('3118', '369', '宁洱', '3');
INSERT INTO `yang_areas` VALUES ('3119', '369', '思茅区', '3');
INSERT INTO `yang_areas` VALUES ('3120', '369', '墨江', '3');
INSERT INTO `yang_areas` VALUES ('3121', '369', '景东', '3');
INSERT INTO `yang_areas` VALUES ('3122', '369', '景谷', '3');
INSERT INTO `yang_areas` VALUES ('3123', '369', '镇沅', '3');
INSERT INTO `yang_areas` VALUES ('3124', '369', '江城', '3');
INSERT INTO `yang_areas` VALUES ('3125', '369', '孟连', '3');
INSERT INTO `yang_areas` VALUES ('3126', '369', '澜沧', '3');
INSERT INTO `yang_areas` VALUES ('3127', '369', '西盟', '3');
INSERT INTO `yang_areas` VALUES ('3128', '370', '古城区', '3');
INSERT INTO `yang_areas` VALUES ('3129', '370', '宁蒗', '3');
INSERT INTO `yang_areas` VALUES ('3130', '370', '玉龙', '3');
INSERT INTO `yang_areas` VALUES ('3131', '370', '永胜县', '3');
INSERT INTO `yang_areas` VALUES ('3132', '370', '华坪县', '3');
INSERT INTO `yang_areas` VALUES ('3133', '371', '隆阳区', '3');
INSERT INTO `yang_areas` VALUES ('3134', '371', '施甸县', '3');
INSERT INTO `yang_areas` VALUES ('3135', '371', '腾冲县', '3');
INSERT INTO `yang_areas` VALUES ('3136', '371', '龙陵县', '3');
INSERT INTO `yang_areas` VALUES ('3137', '371', '昌宁县', '3');
INSERT INTO `yang_areas` VALUES ('3138', '372', '楚雄市', '3');
INSERT INTO `yang_areas` VALUES ('3139', '372', '双柏县', '3');
INSERT INTO `yang_areas` VALUES ('3140', '372', '牟定县', '3');
INSERT INTO `yang_areas` VALUES ('3141', '372', '南华县', '3');
INSERT INTO `yang_areas` VALUES ('3142', '372', '姚安县', '3');
INSERT INTO `yang_areas` VALUES ('3143', '372', '大姚县', '3');
INSERT INTO `yang_areas` VALUES ('3144', '372', '永仁县', '3');
INSERT INTO `yang_areas` VALUES ('3145', '372', '元谋县', '3');
INSERT INTO `yang_areas` VALUES ('3146', '372', '武定县', '3');
INSERT INTO `yang_areas` VALUES ('3147', '372', '禄丰县', '3');
INSERT INTO `yang_areas` VALUES ('3148', '373', '大理市', '3');
INSERT INTO `yang_areas` VALUES ('3149', '373', '祥云县', '3');
INSERT INTO `yang_areas` VALUES ('3150', '373', '宾川县', '3');
INSERT INTO `yang_areas` VALUES ('3151', '373', '弥渡县', '3');
INSERT INTO `yang_areas` VALUES ('3152', '373', '永平县', '3');
INSERT INTO `yang_areas` VALUES ('3153', '373', '云龙县', '3');
INSERT INTO `yang_areas` VALUES ('3154', '373', '洱源县', '3');
INSERT INTO `yang_areas` VALUES ('3155', '373', '剑川县', '3');
INSERT INTO `yang_areas` VALUES ('3156', '373', '鹤庆县', '3');
INSERT INTO `yang_areas` VALUES ('3157', '373', '漾濞', '3');
INSERT INTO `yang_areas` VALUES ('3158', '373', '南涧', '3');
INSERT INTO `yang_areas` VALUES ('3159', '373', '巍山', '3');
INSERT INTO `yang_areas` VALUES ('3160', '374', '潞西市', '3');
INSERT INTO `yang_areas` VALUES ('3161', '374', '瑞丽市', '3');
INSERT INTO `yang_areas` VALUES ('3162', '374', '梁河县', '3');
INSERT INTO `yang_areas` VALUES ('3163', '374', '盈江县', '3');
INSERT INTO `yang_areas` VALUES ('3164', '374', '陇川县', '3');
INSERT INTO `yang_areas` VALUES ('3165', '375', '香格里拉县', '3');
INSERT INTO `yang_areas` VALUES ('3166', '375', '德钦县', '3');
INSERT INTO `yang_areas` VALUES ('3167', '375', '维西', '3');
INSERT INTO `yang_areas` VALUES ('3168', '376', '泸西县', '3');
INSERT INTO `yang_areas` VALUES ('3169', '376', '蒙自县', '3');
INSERT INTO `yang_areas` VALUES ('3170', '376', '个旧市', '3');
INSERT INTO `yang_areas` VALUES ('3171', '376', '开远市', '3');
INSERT INTO `yang_areas` VALUES ('3172', '376', '绿春县', '3');
INSERT INTO `yang_areas` VALUES ('3173', '376', '建水县', '3');
INSERT INTO `yang_areas` VALUES ('3174', '376', '石屏县', '3');
INSERT INTO `yang_areas` VALUES ('3175', '376', '弥勒县', '3');
INSERT INTO `yang_areas` VALUES ('3176', '376', '元阳县', '3');
INSERT INTO `yang_areas` VALUES ('3177', '376', '红河县', '3');
INSERT INTO `yang_areas` VALUES ('3178', '376', '金平', '3');
INSERT INTO `yang_areas` VALUES ('3179', '376', '河口', '3');
INSERT INTO `yang_areas` VALUES ('3180', '376', '屏边', '3');
INSERT INTO `yang_areas` VALUES ('3181', '377', '临翔区', '3');
INSERT INTO `yang_areas` VALUES ('3182', '377', '凤庆县', '3');
INSERT INTO `yang_areas` VALUES ('3183', '377', '云县', '3');
INSERT INTO `yang_areas` VALUES ('3184', '377', '永德县', '3');
INSERT INTO `yang_areas` VALUES ('3185', '377', '镇康县', '3');
INSERT INTO `yang_areas` VALUES ('3186', '377', '双江', '3');
INSERT INTO `yang_areas` VALUES ('3187', '377', '耿马', '3');
INSERT INTO `yang_areas` VALUES ('3188', '377', '沧源', '3');
INSERT INTO `yang_areas` VALUES ('3189', '378', '麒麟区', '3');
INSERT INTO `yang_areas` VALUES ('3190', '378', '宣威市', '3');
INSERT INTO `yang_areas` VALUES ('3191', '378', '马龙县', '3');
INSERT INTO `yang_areas` VALUES ('3192', '378', '陆良县', '3');
INSERT INTO `yang_areas` VALUES ('3193', '378', '师宗县', '3');
INSERT INTO `yang_areas` VALUES ('3194', '378', '罗平县', '3');
INSERT INTO `yang_areas` VALUES ('3195', '378', '富源县', '3');
INSERT INTO `yang_areas` VALUES ('3196', '378', '会泽县', '3');
INSERT INTO `yang_areas` VALUES ('3197', '378', '沾益县', '3');
INSERT INTO `yang_areas` VALUES ('3198', '379', '文山县', '3');
INSERT INTO `yang_areas` VALUES ('3199', '379', '砚山县', '3');
INSERT INTO `yang_areas` VALUES ('3200', '379', '西畴县', '3');
INSERT INTO `yang_areas` VALUES ('3201', '379', '麻栗坡县', '3');
INSERT INTO `yang_areas` VALUES ('3202', '379', '马关县', '3');
INSERT INTO `yang_areas` VALUES ('3203', '379', '丘北县', '3');
INSERT INTO `yang_areas` VALUES ('3204', '379', '广南县', '3');
INSERT INTO `yang_areas` VALUES ('3205', '379', '富宁县', '3');
INSERT INTO `yang_areas` VALUES ('3206', '380', '景洪市', '3');
INSERT INTO `yang_areas` VALUES ('3207', '380', '勐海县', '3');
INSERT INTO `yang_areas` VALUES ('3208', '380', '勐腊县', '3');
INSERT INTO `yang_areas` VALUES ('3209', '381', '红塔区', '3');
INSERT INTO `yang_areas` VALUES ('3210', '381', '江川县', '3');
INSERT INTO `yang_areas` VALUES ('3211', '381', '澄江县', '3');
INSERT INTO `yang_areas` VALUES ('3212', '381', '通海县', '3');
INSERT INTO `yang_areas` VALUES ('3213', '381', '华宁县', '3');
INSERT INTO `yang_areas` VALUES ('3214', '381', '易门县', '3');
INSERT INTO `yang_areas` VALUES ('3215', '381', '峨山', '3');
INSERT INTO `yang_areas` VALUES ('3216', '381', '新平', '3');
INSERT INTO `yang_areas` VALUES ('3217', '381', '元江', '3');
INSERT INTO `yang_areas` VALUES ('3218', '382', '昭阳区', '3');
INSERT INTO `yang_areas` VALUES ('3219', '382', '鲁甸县', '3');
INSERT INTO `yang_areas` VALUES ('3220', '382', '巧家县', '3');
INSERT INTO `yang_areas` VALUES ('3221', '382', '盐津县', '3');
INSERT INTO `yang_areas` VALUES ('3222', '382', '大关县', '3');
INSERT INTO `yang_areas` VALUES ('3223', '382', '永善县', '3');
INSERT INTO `yang_areas` VALUES ('3224', '382', '绥江县', '3');
INSERT INTO `yang_areas` VALUES ('3225', '382', '镇雄县', '3');
INSERT INTO `yang_areas` VALUES ('3226', '382', '彝良县', '3');
INSERT INTO `yang_areas` VALUES ('3227', '382', '威信县', '3');
INSERT INTO `yang_areas` VALUES ('3228', '382', '水富县', '3');
INSERT INTO `yang_areas` VALUES ('3229', '383', '西湖区', '3');
INSERT INTO `yang_areas` VALUES ('3230', '383', '上城区', '3');
INSERT INTO `yang_areas` VALUES ('3231', '383', '下城区', '3');
INSERT INTO `yang_areas` VALUES ('3232', '383', '拱墅区', '3');
INSERT INTO `yang_areas` VALUES ('3233', '383', '滨江区', '3');
INSERT INTO `yang_areas` VALUES ('3234', '383', '江干区', '3');
INSERT INTO `yang_areas` VALUES ('3235', '383', '萧山区', '3');
INSERT INTO `yang_areas` VALUES ('3236', '383', '余杭区', '3');
INSERT INTO `yang_areas` VALUES ('3237', '383', '市郊', '3');
INSERT INTO `yang_areas` VALUES ('3238', '383', '建德市', '3');
INSERT INTO `yang_areas` VALUES ('3239', '383', '富阳市', '3');
INSERT INTO `yang_areas` VALUES ('3240', '383', '临安市', '3');
INSERT INTO `yang_areas` VALUES ('3241', '383', '桐庐县', '3');
INSERT INTO `yang_areas` VALUES ('3242', '383', '淳安县', '3');
INSERT INTO `yang_areas` VALUES ('3243', '384', '吴兴区', '3');
INSERT INTO `yang_areas` VALUES ('3244', '384', '南浔区', '3');
INSERT INTO `yang_areas` VALUES ('3245', '384', '德清县', '3');
INSERT INTO `yang_areas` VALUES ('3246', '384', '长兴县', '3');
INSERT INTO `yang_areas` VALUES ('3247', '384', '安吉县', '3');
INSERT INTO `yang_areas` VALUES ('3248', '385', '南湖区', '3');
INSERT INTO `yang_areas` VALUES ('3249', '385', '秀洲区', '3');
INSERT INTO `yang_areas` VALUES ('3250', '385', '海宁市', '3');
INSERT INTO `yang_areas` VALUES ('3251', '385', '嘉善县', '3');
INSERT INTO `yang_areas` VALUES ('3252', '385', '平湖市', '3');
INSERT INTO `yang_areas` VALUES ('3253', '385', '桐乡市', '3');
INSERT INTO `yang_areas` VALUES ('3254', '385', '海盐县', '3');
INSERT INTO `yang_areas` VALUES ('3255', '386', '婺城区', '3');
INSERT INTO `yang_areas` VALUES ('3256', '386', '金东区', '3');
INSERT INTO `yang_areas` VALUES ('3257', '386', '兰溪市', '3');
INSERT INTO `yang_areas` VALUES ('3258', '386', '市区', '3');
INSERT INTO `yang_areas` VALUES ('3259', '386', '佛堂镇', '3');
INSERT INTO `yang_areas` VALUES ('3260', '386', '上溪镇', '3');
INSERT INTO `yang_areas` VALUES ('3261', '386', '义亭镇', '3');
INSERT INTO `yang_areas` VALUES ('3262', '386', '大陈镇', '3');
INSERT INTO `yang_areas` VALUES ('3263', '386', '苏溪镇', '3');
INSERT INTO `yang_areas` VALUES ('3264', '386', '赤岸镇', '3');
INSERT INTO `yang_areas` VALUES ('3265', '386', '东阳市', '3');
INSERT INTO `yang_areas` VALUES ('3266', '386', '永康市', '3');
INSERT INTO `yang_areas` VALUES ('3267', '386', '武义县', '3');
INSERT INTO `yang_areas` VALUES ('3268', '386', '浦江县', '3');
INSERT INTO `yang_areas` VALUES ('3269', '386', '磐安县', '3');
INSERT INTO `yang_areas` VALUES ('3270', '387', '莲都区', '3');
INSERT INTO `yang_areas` VALUES ('3271', '387', '龙泉市', '3');
INSERT INTO `yang_areas` VALUES ('3272', '387', '青田县', '3');
INSERT INTO `yang_areas` VALUES ('3273', '387', '缙云县', '3');
INSERT INTO `yang_areas` VALUES ('3274', '387', '遂昌县', '3');
INSERT INTO `yang_areas` VALUES ('3275', '387', '松阳县', '3');
INSERT INTO `yang_areas` VALUES ('3276', '387', '云和县', '3');
INSERT INTO `yang_areas` VALUES ('3277', '387', '庆元县', '3');
INSERT INTO `yang_areas` VALUES ('3278', '387', '景宁', '3');
INSERT INTO `yang_areas` VALUES ('3279', '388', '海曙区', '3');
INSERT INTO `yang_areas` VALUES ('3280', '388', '江东区', '3');
INSERT INTO `yang_areas` VALUES ('3281', '388', '江北区', '3');
INSERT INTO `yang_areas` VALUES ('3282', '388', '镇海区', '3');
INSERT INTO `yang_areas` VALUES ('3283', '388', '北仑区', '3');
INSERT INTO `yang_areas` VALUES ('3284', '388', '鄞州区', '3');
INSERT INTO `yang_areas` VALUES ('3285', '388', '余姚市', '3');
INSERT INTO `yang_areas` VALUES ('3286', '388', '慈溪市', '3');
INSERT INTO `yang_areas` VALUES ('3287', '388', '奉化市', '3');
INSERT INTO `yang_areas` VALUES ('3288', '388', '象山县', '3');
INSERT INTO `yang_areas` VALUES ('3289', '388', '宁海县', '3');
INSERT INTO `yang_areas` VALUES ('3290', '389', '越城区', '3');
INSERT INTO `yang_areas` VALUES ('3291', '389', '上虞市', '3');
INSERT INTO `yang_areas` VALUES ('3292', '389', '嵊州市', '3');
INSERT INTO `yang_areas` VALUES ('3293', '389', '绍兴县', '3');
INSERT INTO `yang_areas` VALUES ('3294', '389', '新昌县', '3');
INSERT INTO `yang_areas` VALUES ('3295', '389', '诸暨市', '3');
INSERT INTO `yang_areas` VALUES ('3296', '390', '椒江区', '3');
INSERT INTO `yang_areas` VALUES ('3297', '390', '黄岩区', '3');
INSERT INTO `yang_areas` VALUES ('3298', '390', '路桥区', '3');
INSERT INTO `yang_areas` VALUES ('3299', '390', '温岭市', '3');
INSERT INTO `yang_areas` VALUES ('3300', '390', '临海市', '3');
INSERT INTO `yang_areas` VALUES ('3301', '390', '玉环县', '3');
INSERT INTO `yang_areas` VALUES ('3302', '390', '三门县', '3');
INSERT INTO `yang_areas` VALUES ('3303', '390', '天台县', '3');
INSERT INTO `yang_areas` VALUES ('3304', '390', '仙居县', '3');
INSERT INTO `yang_areas` VALUES ('3305', '391', '鹿城区', '3');
INSERT INTO `yang_areas` VALUES ('3306', '391', '龙湾区', '3');
INSERT INTO `yang_areas` VALUES ('3307', '391', '瓯海区', '3');
INSERT INTO `yang_areas` VALUES ('3308', '391', '瑞安市', '3');
INSERT INTO `yang_areas` VALUES ('3309', '391', '乐清市', '3');
INSERT INTO `yang_areas` VALUES ('3310', '391', '洞头县', '3');
INSERT INTO `yang_areas` VALUES ('3311', '391', '永嘉县', '3');
INSERT INTO `yang_areas` VALUES ('3312', '391', '平阳县', '3');
INSERT INTO `yang_areas` VALUES ('3313', '391', '苍南县', '3');
INSERT INTO `yang_areas` VALUES ('3314', '391', '文成县', '3');
INSERT INTO `yang_areas` VALUES ('3315', '391', '泰顺县', '3');
INSERT INTO `yang_areas` VALUES ('3316', '392', '定海区', '3');
INSERT INTO `yang_areas` VALUES ('3317', '392', '普陀区', '3');
INSERT INTO `yang_areas` VALUES ('3318', '392', '岱山县', '3');
INSERT INTO `yang_areas` VALUES ('3319', '392', '嵊泗县', '3');
INSERT INTO `yang_areas` VALUES ('3320', '393', '衢州市', '3');
INSERT INTO `yang_areas` VALUES ('3321', '393', '江山市', '3');
INSERT INTO `yang_areas` VALUES ('3322', '393', '常山县', '3');
INSERT INTO `yang_areas` VALUES ('3323', '393', '开化县', '3');
INSERT INTO `yang_areas` VALUES ('3324', '393', '龙游县', '3');
INSERT INTO `yang_areas` VALUES ('3325', '394', '合川区', '3');
INSERT INTO `yang_areas` VALUES ('3326', '394', '江津区', '3');
INSERT INTO `yang_areas` VALUES ('3327', '394', '南川区', '3');
INSERT INTO `yang_areas` VALUES ('3328', '394', '永川区', '3');
INSERT INTO `yang_areas` VALUES ('3329', '394', '南岸区', '3');
INSERT INTO `yang_areas` VALUES ('3330', '394', '渝北区', '3');
INSERT INTO `yang_areas` VALUES ('3331', '394', '万盛区', '3');
INSERT INTO `yang_areas` VALUES ('3332', '394', '大渡口区', '3');
INSERT INTO `yang_areas` VALUES ('3333', '394', '万州区', '3');
INSERT INTO `yang_areas` VALUES ('3334', '394', '北碚区', '3');
INSERT INTO `yang_areas` VALUES ('3335', '394', '沙坪坝区', '3');
INSERT INTO `yang_areas` VALUES ('3336', '394', '巴南区', '3');
INSERT INTO `yang_areas` VALUES ('3337', '394', '涪陵区', '3');
INSERT INTO `yang_areas` VALUES ('3338', '394', '江北区', '3');
INSERT INTO `yang_areas` VALUES ('3339', '394', '九龙坡区', '3');
INSERT INTO `yang_areas` VALUES ('3340', '394', '渝中区', '3');
INSERT INTO `yang_areas` VALUES ('3341', '394', '黔江开发区', '3');
INSERT INTO `yang_areas` VALUES ('3342', '394', '长寿区', '3');
INSERT INTO `yang_areas` VALUES ('3343', '394', '双桥区', '3');
INSERT INTO `yang_areas` VALUES ('3344', '394', '綦江县', '3');
INSERT INTO `yang_areas` VALUES ('3345', '394', '潼南县', '3');
INSERT INTO `yang_areas` VALUES ('3346', '394', '铜梁县', '3');
INSERT INTO `yang_areas` VALUES ('3347', '394', '大足县', '3');
INSERT INTO `yang_areas` VALUES ('3348', '394', '荣昌县', '3');
INSERT INTO `yang_areas` VALUES ('3349', '394', '璧山县', '3');
INSERT INTO `yang_areas` VALUES ('3350', '394', '垫江县', '3');
INSERT INTO `yang_areas` VALUES ('3351', '394', '武隆县', '3');
INSERT INTO `yang_areas` VALUES ('3352', '394', '丰都县', '3');
INSERT INTO `yang_areas` VALUES ('3353', '394', '城口县', '3');
INSERT INTO `yang_areas` VALUES ('3354', '394', '梁平县', '3');
INSERT INTO `yang_areas` VALUES ('3355', '394', '开县', '3');
INSERT INTO `yang_areas` VALUES ('3356', '394', '巫溪县', '3');
INSERT INTO `yang_areas` VALUES ('3357', '394', '巫山县', '3');
INSERT INTO `yang_areas` VALUES ('3358', '394', '奉节县', '3');
INSERT INTO `yang_areas` VALUES ('3359', '394', '云阳县', '3');
INSERT INTO `yang_areas` VALUES ('3360', '394', '忠县', '3');
INSERT INTO `yang_areas` VALUES ('3361', '394', '石柱', '3');
INSERT INTO `yang_areas` VALUES ('3362', '394', '彭水', '3');
INSERT INTO `yang_areas` VALUES ('3363', '394', '酉阳', '3');
INSERT INTO `yang_areas` VALUES ('3364', '394', '秀山', '3');
INSERT INTO `yang_areas` VALUES ('3365', '395', '沙田区', '3');
INSERT INTO `yang_areas` VALUES ('3366', '395', '东区', '3');
INSERT INTO `yang_areas` VALUES ('3367', '395', '观塘区', '3');
INSERT INTO `yang_areas` VALUES ('3368', '395', '黄大仙区', '3');
INSERT INTO `yang_areas` VALUES ('3369', '395', '九龙城区', '3');
INSERT INTO `yang_areas` VALUES ('3370', '395', '屯门区', '3');
INSERT INTO `yang_areas` VALUES ('3371', '395', '葵青区', '3');
INSERT INTO `yang_areas` VALUES ('3372', '395', '元朗区', '3');
INSERT INTO `yang_areas` VALUES ('3373', '395', '深水埗区', '3');
INSERT INTO `yang_areas` VALUES ('3374', '395', '西贡区', '3');
INSERT INTO `yang_areas` VALUES ('3375', '395', '大埔区', '3');
INSERT INTO `yang_areas` VALUES ('3376', '395', '湾仔区', '3');
INSERT INTO `yang_areas` VALUES ('3377', '395', '油尖旺区', '3');
INSERT INTO `yang_areas` VALUES ('3378', '395', '北区', '3');
INSERT INTO `yang_areas` VALUES ('3379', '395', '南区', '3');
INSERT INTO `yang_areas` VALUES ('3380', '395', '荃湾区', '3');
INSERT INTO `yang_areas` VALUES ('3381', '395', '中西区', '3');
INSERT INTO `yang_areas` VALUES ('3382', '395', '离岛区', '3');
INSERT INTO `yang_areas` VALUES ('3383', '396', '澳门', '3');
INSERT INTO `yang_areas` VALUES ('3384', '397', '台北', '3');
INSERT INTO `yang_areas` VALUES ('3385', '397', '高雄', '3');
INSERT INTO `yang_areas` VALUES ('3386', '397', '基隆', '3');
INSERT INTO `yang_areas` VALUES ('3387', '397', '台中', '3');
INSERT INTO `yang_areas` VALUES ('3388', '397', '台南', '3');
INSERT INTO `yang_areas` VALUES ('3389', '397', '新竹', '3');
INSERT INTO `yang_areas` VALUES ('3390', '397', '嘉义', '3');
INSERT INTO `yang_areas` VALUES ('3391', '397', '宜兰县', '3');
INSERT INTO `yang_areas` VALUES ('3392', '397', '桃园县', '3');
INSERT INTO `yang_areas` VALUES ('3393', '397', '苗栗县', '3');
INSERT INTO `yang_areas` VALUES ('3394', '397', '彰化县', '3');
INSERT INTO `yang_areas` VALUES ('3395', '397', '南投县', '3');
INSERT INTO `yang_areas` VALUES ('3396', '397', '云林县', '3');
INSERT INTO `yang_areas` VALUES ('3397', '397', '屏东县', '3');
INSERT INTO `yang_areas` VALUES ('3398', '397', '台东县', '3');
INSERT INTO `yang_areas` VALUES ('3399', '397', '花莲县', '3');
INSERT INTO `yang_areas` VALUES ('3400', '397', '澎湖县', '3');
INSERT INTO `yang_areas` VALUES ('3401', '3', '合肥', '2');
INSERT INTO `yang_areas` VALUES ('3402', '3401', '庐阳区', '3');
INSERT INTO `yang_areas` VALUES ('3403', '3401', '瑶海区', '3');
INSERT INTO `yang_areas` VALUES ('3404', '3401', '蜀山区', '3');
INSERT INTO `yang_areas` VALUES ('3405', '3401', '包河区', '3');
INSERT INTO `yang_areas` VALUES ('3406', '3401', '长丰县', '3');
INSERT INTO `yang_areas` VALUES ('3407', '3401', '肥东县', '3');
INSERT INTO `yang_areas` VALUES ('3408', '3401', '肥西县', '3');
INSERT INTO `yang_areas` VALUES ('601', '61', '南靖县', '3');
INSERT INTO `yang_areas` VALUES ('602', '61', '平和县', '3');
INSERT INTO `yang_areas` VALUES ('603', '61', '华安县', '3');
INSERT INTO `yang_areas` VALUES ('604', '62', '皋兰县', '3');
INSERT INTO `yang_areas` VALUES ('605', '62', '城关区', '3');
INSERT INTO `yang_areas` VALUES ('606', '62', '七里河区', '3');
INSERT INTO `yang_areas` VALUES ('607', '62', '西固区', '3');
INSERT INTO `yang_areas` VALUES ('608', '62', '安宁区', '3');
INSERT INTO `yang_areas` VALUES ('609', '62', '红古区', '3');
INSERT INTO `yang_areas` VALUES ('610', '62', '永登县', '3');
INSERT INTO `yang_areas` VALUES ('611', '62', '榆中县', '3');
INSERT INTO `yang_areas` VALUES ('612', '63', '白银区', '3');
INSERT INTO `yang_areas` VALUES ('613', '63', '平川区', '3');
INSERT INTO `yang_areas` VALUES ('614', '63', '会宁县', '3');
INSERT INTO `yang_areas` VALUES ('615', '63', '景泰县', '3');
INSERT INTO `yang_areas` VALUES ('616', '63', '靖远县', '3');
INSERT INTO `yang_areas` VALUES ('617', '64', '临洮县', '3');
INSERT INTO `yang_areas` VALUES ('618', '64', '陇西县', '3');
INSERT INTO `yang_areas` VALUES ('619', '64', '通渭县', '3');
INSERT INTO `yang_areas` VALUES ('620', '64', '渭源县', '3');
INSERT INTO `yang_areas` VALUES ('621', '64', '漳县', '3');
INSERT INTO `yang_areas` VALUES ('622', '64', '岷县', '3');
INSERT INTO `yang_areas` VALUES ('623', '64', '安定区', '3');
INSERT INTO `yang_areas` VALUES ('624', '64', '安定区', '3');
INSERT INTO `yang_areas` VALUES ('625', '65', '合作市', '3');
INSERT INTO `yang_areas` VALUES ('626', '65', '临潭县', '3');
INSERT INTO `yang_areas` VALUES ('627', '65', '卓尼县', '3');
INSERT INTO `yang_areas` VALUES ('628', '65', '舟曲县', '3');
INSERT INTO `yang_areas` VALUES ('629', '65', '迭部县', '3');
INSERT INTO `yang_areas` VALUES ('630', '65', '玛曲县', '3');
INSERT INTO `yang_areas` VALUES ('631', '65', '碌曲县', '3');
INSERT INTO `yang_areas` VALUES ('632', '65', '夏河县', '3');
INSERT INTO `yang_areas` VALUES ('633', '66', '嘉峪关市', '3');
INSERT INTO `yang_areas` VALUES ('634', '67', '金川区', '3');
INSERT INTO `yang_areas` VALUES ('635', '67', '永昌县', '3');
INSERT INTO `yang_areas` VALUES ('636', '68', '肃州区', '3');
INSERT INTO `yang_areas` VALUES ('637', '68', '玉门市', '3');
INSERT INTO `yang_areas` VALUES ('638', '68', '敦煌市', '3');
INSERT INTO `yang_areas` VALUES ('639', '68', '金塔县', '3');
INSERT INTO `yang_areas` VALUES ('640', '68', '瓜州县', '3');
INSERT INTO `yang_areas` VALUES ('641', '68', '肃北', '3');
INSERT INTO `yang_areas` VALUES ('642', '68', '阿克塞', '3');
INSERT INTO `yang_areas` VALUES ('643', '69', '临夏市', '3');
INSERT INTO `yang_areas` VALUES ('644', '69', '临夏县', '3');
INSERT INTO `yang_areas` VALUES ('645', '69', '康乐县', '3');
INSERT INTO `yang_areas` VALUES ('646', '69', '永靖县', '3');
INSERT INTO `yang_areas` VALUES ('647', '69', '广河县', '3');
INSERT INTO `yang_areas` VALUES ('648', '69', '和政县', '3');
INSERT INTO `yang_areas` VALUES ('649', '69', '东乡族自治县', '3');
INSERT INTO `yang_areas` VALUES ('650', '69', '积石山', '3');
INSERT INTO `yang_areas` VALUES ('651', '70', '成县', '3');
INSERT INTO `yang_areas` VALUES ('652', '70', '徽县', '3');
INSERT INTO `yang_areas` VALUES ('653', '70', '康县', '3');
INSERT INTO `yang_areas` VALUES ('654', '70', '礼县', '3');
INSERT INTO `yang_areas` VALUES ('655', '70', '两当县', '3');
INSERT INTO `yang_areas` VALUES ('656', '70', '文县', '3');
INSERT INTO `yang_areas` VALUES ('657', '70', '西和县', '3');
INSERT INTO `yang_areas` VALUES ('658', '70', '宕昌县', '3');
INSERT INTO `yang_areas` VALUES ('659', '70', '武都区', '3');
INSERT INTO `yang_areas` VALUES ('660', '71', '崇信县', '3');
INSERT INTO `yang_areas` VALUES ('661', '71', '华亭县', '3');
INSERT INTO `yang_areas` VALUES ('662', '71', '静宁县', '3');
INSERT INTO `yang_areas` VALUES ('663', '71', '灵台县', '3');
INSERT INTO `yang_areas` VALUES ('664', '71', '崆峒区', '3');
INSERT INTO `yang_areas` VALUES ('665', '71', '庄浪县', '3');
INSERT INTO `yang_areas` VALUES ('666', '71', '泾川县', '3');
INSERT INTO `yang_areas` VALUES ('667', '72', '合水县', '3');
INSERT INTO `yang_areas` VALUES ('668', '72', '华池县', '3');
INSERT INTO `yang_areas` VALUES ('669', '72', '环县', '3');
INSERT INTO `yang_areas` VALUES ('670', '72', '宁县', '3');
INSERT INTO `yang_areas` VALUES ('671', '72', '庆城县', '3');
INSERT INTO `yang_areas` VALUES ('672', '72', '西峰区', '3');
INSERT INTO `yang_areas` VALUES ('673', '72', '镇原县', '3');
INSERT INTO `yang_areas` VALUES ('674', '72', '正宁县', '3');
INSERT INTO `yang_areas` VALUES ('675', '73', '甘谷县', '3');
INSERT INTO `yang_areas` VALUES ('676', '73', '秦安县', '3');
INSERT INTO `yang_areas` VALUES ('677', '73', '清水县', '3');
INSERT INTO `yang_areas` VALUES ('678', '73', '秦州区', '3');
INSERT INTO `yang_areas` VALUES ('679', '73', '麦积区', '3');
INSERT INTO `yang_areas` VALUES ('680', '73', '武山县', '3');
INSERT INTO `yang_areas` VALUES ('681', '73', '张家川', '3');
INSERT INTO `yang_areas` VALUES ('682', '74', '古浪县', '3');
INSERT INTO `yang_areas` VALUES ('683', '74', '民勤县', '3');
INSERT INTO `yang_areas` VALUES ('684', '74', '天祝', '3');
INSERT INTO `yang_areas` VALUES ('685', '74', '凉州区', '3');
INSERT INTO `yang_areas` VALUES ('686', '75', '高台县', '3');
INSERT INTO `yang_areas` VALUES ('687', '75', '临泽县', '3');
INSERT INTO `yang_areas` VALUES ('688', '75', '民乐县', '3');
INSERT INTO `yang_areas` VALUES ('689', '75', '山丹县', '3');
INSERT INTO `yang_areas` VALUES ('690', '75', '肃南', '3');
INSERT INTO `yang_areas` VALUES ('691', '75', '甘州区', '3');
INSERT INTO `yang_areas` VALUES ('692', '76', '从化市', '3');
INSERT INTO `yang_areas` VALUES ('693', '76', '天河区', '3');
INSERT INTO `yang_areas` VALUES ('694', '76', '东山区', '3');
INSERT INTO `yang_areas` VALUES ('695', '76', '白云区', '3');
INSERT INTO `yang_areas` VALUES ('696', '76', '海珠区', '3');
INSERT INTO `yang_areas` VALUES ('697', '76', '荔湾区', '3');
INSERT INTO `yang_areas` VALUES ('698', '76', '越秀区', '3');
INSERT INTO `yang_areas` VALUES ('699', '76', '黄埔区', '3');
INSERT INTO `yang_areas` VALUES ('700', '76', '番禺区', '3');
INSERT INTO `yang_areas` VALUES ('701', '76', '花都区', '3');
INSERT INTO `yang_areas` VALUES ('702', '76', '增城区', '3');
INSERT INTO `yang_areas` VALUES ('703', '76', '从化区', '3');
INSERT INTO `yang_areas` VALUES ('704', '76', '市郊', '3');
INSERT INTO `yang_areas` VALUES ('705', '77', '福田区', '3');
INSERT INTO `yang_areas` VALUES ('706', '77', '罗湖区', '3');
INSERT INTO `yang_areas` VALUES ('707', '77', '南山区', '3');
INSERT INTO `yang_areas` VALUES ('708', '77', '宝安区', '3');
INSERT INTO `yang_areas` VALUES ('709', '77', '龙岗区', '3');
INSERT INTO `yang_areas` VALUES ('710', '77', '盐田区', '3');
INSERT INTO `yang_areas` VALUES ('711', '78', '湘桥区', '3');
INSERT INTO `yang_areas` VALUES ('712', '78', '潮安县', '3');
INSERT INTO `yang_areas` VALUES ('713', '78', '饶平县', '3');
INSERT INTO `yang_areas` VALUES ('714', '79', '南城区', '3');
INSERT INTO `yang_areas` VALUES ('715', '79', '东城区', '3');
INSERT INTO `yang_areas` VALUES ('716', '79', '万江区', '3');
INSERT INTO `yang_areas` VALUES ('717', '79', '莞城区', '3');
INSERT INTO `yang_areas` VALUES ('718', '79', '石龙镇', '3');
INSERT INTO `yang_areas` VALUES ('719', '79', '虎门镇', '3');
INSERT INTO `yang_areas` VALUES ('720', '79', '麻涌镇', '3');
INSERT INTO `yang_areas` VALUES ('721', '79', '道滘镇', '3');
INSERT INTO `yang_areas` VALUES ('722', '79', '石碣镇', '3');
INSERT INTO `yang_areas` VALUES ('723', '79', '沙田镇', '3');
INSERT INTO `yang_areas` VALUES ('724', '79', '望牛墩镇', '3');
INSERT INTO `yang_areas` VALUES ('725', '79', '洪梅镇', '3');
INSERT INTO `yang_areas` VALUES ('726', '79', '茶山镇', '3');
INSERT INTO `yang_areas` VALUES ('727', '79', '寮步镇', '3');
INSERT INTO `yang_areas` VALUES ('728', '79', '大岭山镇', '3');
INSERT INTO `yang_areas` VALUES ('729', '79', '大朗镇', '3');
INSERT INTO `yang_areas` VALUES ('730', '79', '黄江镇', '3');
INSERT INTO `yang_areas` VALUES ('731', '79', '樟木头', '3');
INSERT INTO `yang_areas` VALUES ('732', '79', '凤岗镇', '3');
INSERT INTO `yang_areas` VALUES ('733', '79', '塘厦镇', '3');
INSERT INTO `yang_areas` VALUES ('734', '79', '谢岗镇', '3');
INSERT INTO `yang_areas` VALUES ('735', '79', '厚街镇', '3');
INSERT INTO `yang_areas` VALUES ('736', '79', '清溪镇', '3');
INSERT INTO `yang_areas` VALUES ('737', '79', '常平镇', '3');
INSERT INTO `yang_areas` VALUES ('738', '79', '桥头镇', '3');
INSERT INTO `yang_areas` VALUES ('739', '79', '横沥镇', '3');
INSERT INTO `yang_areas` VALUES ('740', '79', '东坑镇', '3');
INSERT INTO `yang_areas` VALUES ('741', '79', '企石镇', '3');
INSERT INTO `yang_areas` VALUES ('742', '79', '石排镇', '3');
INSERT INTO `yang_areas` VALUES ('743', '79', '长安镇', '3');
INSERT INTO `yang_areas` VALUES ('744', '79', '中堂镇', '3');
INSERT INTO `yang_areas` VALUES ('745', '79', '高埗镇', '3');
INSERT INTO `yang_areas` VALUES ('746', '80', '禅城区', '3');
INSERT INTO `yang_areas` VALUES ('747', '80', '南海区', '3');
INSERT INTO `yang_areas` VALUES ('748', '80', '顺德区', '3');
INSERT INTO `yang_areas` VALUES ('749', '80', '三水区', '3');
INSERT INTO `yang_areas` VALUES ('750', '80', '高明区', '3');
INSERT INTO `yang_areas` VALUES ('751', '81', '东源县', '3');
INSERT INTO `yang_areas` VALUES ('752', '81', '和平县', '3');
INSERT INTO `yang_areas` VALUES ('753', '81', '源城区', '3');
INSERT INTO `yang_areas` VALUES ('754', '81', '连平县', '3');
INSERT INTO `yang_areas` VALUES ('755', '81', '龙川县', '3');
INSERT INTO `yang_areas` VALUES ('756', '81', '紫金县', '3');
INSERT INTO `yang_areas` VALUES ('757', '82', '惠阳区', '3');
INSERT INTO `yang_areas` VALUES ('758', '82', '惠城区', '3');
INSERT INTO `yang_areas` VALUES ('759', '82', '大亚湾', '3');
INSERT INTO `yang_areas` VALUES ('760', '82', '博罗县', '3');
INSERT INTO `yang_areas` VALUES ('761', '82', '惠东县', '3');
INSERT INTO `yang_areas` VALUES ('762', '82', '龙门县', '3');
INSERT INTO `yang_areas` VALUES ('763', '83', '江海区', '3');
INSERT INTO `yang_areas` VALUES ('764', '83', '蓬江区', '3');
INSERT INTO `yang_areas` VALUES ('765', '83', '新会区', '3');
INSERT INTO `yang_areas` VALUES ('766', '83', '台山市', '3');
INSERT INTO `yang_areas` VALUES ('767', '83', '开平市', '3');
INSERT INTO `yang_areas` VALUES ('768', '83', '鹤山市', '3');
INSERT INTO `yang_areas` VALUES ('769', '83', '恩平市', '3');
INSERT INTO `yang_areas` VALUES ('770', '84', '榕城区', '3');
INSERT INTO `yang_areas` VALUES ('771', '84', '普宁市', '3');
INSERT INTO `yang_areas` VALUES ('772', '84', '揭东县', '3');
INSERT INTO `yang_areas` VALUES ('773', '84', '揭西县', '3');
INSERT INTO `yang_areas` VALUES ('774', '84', '惠来县', '3');
INSERT INTO `yang_areas` VALUES ('775', '85', '茂南区', '3');
INSERT INTO `yang_areas` VALUES ('776', '85', '茂港区', '3');
INSERT INTO `yang_areas` VALUES ('777', '85', '高州市', '3');
INSERT INTO `yang_areas` VALUES ('778', '85', '化州市', '3');
INSERT INTO `yang_areas` VALUES ('779', '85', '信宜市', '3');
INSERT INTO `yang_areas` VALUES ('780', '85', '电白县', '3');
INSERT INTO `yang_areas` VALUES ('781', '86', '梅县', '3');
INSERT INTO `yang_areas` VALUES ('782', '86', '梅江区', '3');
INSERT INTO `yang_areas` VALUES ('783', '86', '兴宁市', '3');
INSERT INTO `yang_areas` VALUES ('784', '86', '大埔县', '3');
INSERT INTO `yang_areas` VALUES ('785', '86', '丰顺县', '3');
INSERT INTO `yang_areas` VALUES ('786', '86', '五华县', '3');
INSERT INTO `yang_areas` VALUES ('787', '86', '平远县', '3');
INSERT INTO `yang_areas` VALUES ('788', '86', '蕉岭县', '3');
INSERT INTO `yang_areas` VALUES ('789', '87', '清城区', '3');
INSERT INTO `yang_areas` VALUES ('790', '87', '英德市', '3');
INSERT INTO `yang_areas` VALUES ('791', '87', '连州市', '3');
INSERT INTO `yang_areas` VALUES ('792', '87', '佛冈县', '3');
INSERT INTO `yang_areas` VALUES ('793', '87', '阳山县', '3');
INSERT INTO `yang_areas` VALUES ('794', '87', '清新县', '3');
INSERT INTO `yang_areas` VALUES ('795', '87', '连山', '3');
INSERT INTO `yang_areas` VALUES ('796', '87', '连南', '3');
INSERT INTO `yang_areas` VALUES ('797', '88', '南澳县', '3');
INSERT INTO `yang_areas` VALUES ('798', '88', '潮阳区', '3');
INSERT INTO `yang_areas` VALUES ('799', '88', '澄海区', '3');
INSERT INTO `yang_areas` VALUES ('800', '88', '龙湖区', '3');
INSERT INTO `yang_areas` VALUES ('801', '88', '金平区', '3');
INSERT INTO `yang_areas` VALUES ('802', '88', '濠江区', '3');
INSERT INTO `yang_areas` VALUES ('803', '88', '潮南区', '3');
INSERT INTO `yang_areas` VALUES ('804', '89', '城区', '3');
INSERT INTO `yang_areas` VALUES ('805', '89', '陆丰市', '3');
INSERT INTO `yang_areas` VALUES ('806', '89', '海丰县', '3');
INSERT INTO `yang_areas` VALUES ('807', '89', '陆河县', '3');
INSERT INTO `yang_areas` VALUES ('808', '90', '曲江县', '3');
INSERT INTO `yang_areas` VALUES ('809', '90', '浈江区', '3');
INSERT INTO `yang_areas` VALUES ('810', '90', '武江区', '3');
INSERT INTO `yang_areas` VALUES ('811', '90', '曲江区', '3');
INSERT INTO `yang_areas` VALUES ('812', '90', '乐昌市', '3');
INSERT INTO `yang_areas` VALUES ('813', '90', '南雄市', '3');
INSERT INTO `yang_areas` VALUES ('814', '90', '始兴县', '3');
INSERT INTO `yang_areas` VALUES ('815', '90', '仁化县', '3');
INSERT INTO `yang_areas` VALUES ('816', '90', '翁源县', '3');
INSERT INTO `yang_areas` VALUES ('817', '90', '新丰县', '3');
INSERT INTO `yang_areas` VALUES ('818', '90', '乳源', '3');
INSERT INTO `yang_areas` VALUES ('819', '91', '江城区', '3');
INSERT INTO `yang_areas` VALUES ('820', '91', '阳春市', '3');
INSERT INTO `yang_areas` VALUES ('821', '91', '阳西县', '3');
INSERT INTO `yang_areas` VALUES ('822', '91', '阳东县', '3');
INSERT INTO `yang_areas` VALUES ('823', '92', '云城区', '3');
INSERT INTO `yang_areas` VALUES ('824', '92', '罗定市', '3');
INSERT INTO `yang_areas` VALUES ('825', '92', '新兴县', '3');
INSERT INTO `yang_areas` VALUES ('826', '92', '郁南县', '3');
INSERT INTO `yang_areas` VALUES ('827', '92', '云安县', '3');
INSERT INTO `yang_areas` VALUES ('828', '93', '赤坎区', '3');
INSERT INTO `yang_areas` VALUES ('829', '93', '霞山区', '3');
INSERT INTO `yang_areas` VALUES ('830', '93', '坡头区', '3');
INSERT INTO `yang_areas` VALUES ('831', '93', '麻章区', '3');
INSERT INTO `yang_areas` VALUES ('832', '93', '廉江市', '3');
INSERT INTO `yang_areas` VALUES ('833', '93', '雷州市', '3');
INSERT INTO `yang_areas` VALUES ('834', '93', '吴川市', '3');
INSERT INTO `yang_areas` VALUES ('835', '93', '遂溪县', '3');
INSERT INTO `yang_areas` VALUES ('836', '93', '徐闻县', '3');
INSERT INTO `yang_areas` VALUES ('837', '94', '肇庆市', '3');
INSERT INTO `yang_areas` VALUES ('838', '94', '高要市', '3');
INSERT INTO `yang_areas` VALUES ('839', '94', '四会市', '3');
INSERT INTO `yang_areas` VALUES ('840', '94', '广宁县', '3');
INSERT INTO `yang_areas` VALUES ('841', '94', '怀集县', '3');
INSERT INTO `yang_areas` VALUES ('842', '94', '封开县', '3');
INSERT INTO `yang_areas` VALUES ('843', '94', '德庆县', '3');
INSERT INTO `yang_areas` VALUES ('844', '95', '石岐街道', '3');
INSERT INTO `yang_areas` VALUES ('845', '95', '东区街道', '3');
INSERT INTO `yang_areas` VALUES ('846', '95', '西区街道', '3');
INSERT INTO `yang_areas` VALUES ('847', '95', '环城街道', '3');
INSERT INTO `yang_areas` VALUES ('848', '95', '中山港街道', '3');
INSERT INTO `yang_areas` VALUES ('849', '95', '五桂山街道', '3');
INSERT INTO `yang_areas` VALUES ('850', '96', '香洲区', '3');
INSERT INTO `yang_areas` VALUES ('851', '96', '斗门区', '3');
INSERT INTO `yang_areas` VALUES ('852', '96', '金湾区', '3');
INSERT INTO `yang_areas` VALUES ('853', '97', '邕宁区', '3');
INSERT INTO `yang_areas` VALUES ('854', '97', '青秀区', '3');
INSERT INTO `yang_areas` VALUES ('855', '97', '兴宁区', '3');
INSERT INTO `yang_areas` VALUES ('856', '97', '良庆区', '3');
INSERT INTO `yang_areas` VALUES ('857', '97', '西乡塘区', '3');
INSERT INTO `yang_areas` VALUES ('858', '97', '江南区', '3');
INSERT INTO `yang_areas` VALUES ('859', '97', '武鸣县', '3');
INSERT INTO `yang_areas` VALUES ('860', '97', '隆安县', '3');
INSERT INTO `yang_areas` VALUES ('861', '97', '马山县', '3');
INSERT INTO `yang_areas` VALUES ('862', '97', '上林县', '3');
INSERT INTO `yang_areas` VALUES ('863', '97', '宾阳县', '3');
INSERT INTO `yang_areas` VALUES ('864', '97', '横县', '3');
INSERT INTO `yang_areas` VALUES ('865', '98', '秀峰区', '3');
INSERT INTO `yang_areas` VALUES ('866', '98', '叠彩区', '3');
INSERT INTO `yang_areas` VALUES ('867', '98', '象山区', '3');
INSERT INTO `yang_areas` VALUES ('868', '98', '七星区', '3');
INSERT INTO `yang_areas` VALUES ('869', '98', '雁山区', '3');
INSERT INTO `yang_areas` VALUES ('870', '98', '阳朔县', '3');
INSERT INTO `yang_areas` VALUES ('871', '98', '临桂县', '3');
INSERT INTO `yang_areas` VALUES ('872', '98', '灵川县', '3');
INSERT INTO `yang_areas` VALUES ('873', '98', '全州县', '3');
INSERT INTO `yang_areas` VALUES ('874', '98', '平乐县', '3');
INSERT INTO `yang_areas` VALUES ('875', '98', '兴安县', '3');
INSERT INTO `yang_areas` VALUES ('876', '98', '灌阳县', '3');
INSERT INTO `yang_areas` VALUES ('877', '98', '荔浦县', '3');
INSERT INTO `yang_areas` VALUES ('878', '98', '资源县', '3');
INSERT INTO `yang_areas` VALUES ('879', '98', '永福县', '3');
INSERT INTO `yang_areas` VALUES ('880', '98', '龙胜', '3');
INSERT INTO `yang_areas` VALUES ('881', '98', '恭城', '3');
INSERT INTO `yang_areas` VALUES ('882', '99', '右江区', '3');
INSERT INTO `yang_areas` VALUES ('883', '99', '凌云县', '3');
INSERT INTO `yang_areas` VALUES ('884', '99', '平果县', '3');
INSERT INTO `yang_areas` VALUES ('885', '99', '西林县', '3');
INSERT INTO `yang_areas` VALUES ('886', '99', '乐业县', '3');
INSERT INTO `yang_areas` VALUES ('887', '99', '德保县', '3');
INSERT INTO `yang_areas` VALUES ('888', '99', '田林县', '3');
INSERT INTO `yang_areas` VALUES ('889', '99', '田阳县', '3');
INSERT INTO `yang_areas` VALUES ('890', '99', '靖西县', '3');
INSERT INTO `yang_areas` VALUES ('891', '99', '田东县', '3');
INSERT INTO `yang_areas` VALUES ('892', '99', '那坡县', '3');
INSERT INTO `yang_areas` VALUES ('893', '99', '隆林', '3');
INSERT INTO `yang_areas` VALUES ('894', '100', '海城区', '3');
INSERT INTO `yang_areas` VALUES ('895', '100', '银海区', '3');
INSERT INTO `yang_areas` VALUES ('896', '100', '铁山港区', '3');
INSERT INTO `yang_areas` VALUES ('897', '100', '合浦县', '3');
INSERT INTO `yang_areas` VALUES ('898', '101', '江州区', '3');
INSERT INTO `yang_areas` VALUES ('899', '101', '凭祥市', '3');
INSERT INTO `yang_areas` VALUES ('900', '101', '宁明县', '3');
INSERT INTO `yang_areas` VALUES ('901', '101', '扶绥县', '3');
INSERT INTO `yang_areas` VALUES ('902', '101', '龙州县', '3');
INSERT INTO `yang_areas` VALUES ('903', '101', '大新县', '3');
INSERT INTO `yang_areas` VALUES ('904', '101', '天等县', '3');
INSERT INTO `yang_areas` VALUES ('905', '102', '港口区', '3');
INSERT INTO `yang_areas` VALUES ('906', '102', '防城区', '3');
INSERT INTO `yang_areas` VALUES ('907', '102', '东兴市', '3');
INSERT INTO `yang_areas` VALUES ('908', '102', '上思县', '3');
INSERT INTO `yang_areas` VALUES ('909', '103', '港北区', '3');
INSERT INTO `yang_areas` VALUES ('910', '103', '港南区', '3');
INSERT INTO `yang_areas` VALUES ('911', '103', '覃塘区', '3');
INSERT INTO `yang_areas` VALUES ('912', '103', '桂平市', '3');
INSERT INTO `yang_areas` VALUES ('913', '103', '平南县', '3');
INSERT INTO `yang_areas` VALUES ('914', '104', '金城江区', '3');
INSERT INTO `yang_areas` VALUES ('915', '104', '宜州市', '3');
INSERT INTO `yang_areas` VALUES ('916', '104', '天峨县', '3');
INSERT INTO `yang_areas` VALUES ('917', '104', '凤山县', '3');
INSERT INTO `yang_areas` VALUES ('918', '104', '南丹县', '3');
INSERT INTO `yang_areas` VALUES ('919', '104', '东兰县', '3');
INSERT INTO `yang_areas` VALUES ('920', '104', '都安', '3');
INSERT INTO `yang_areas` VALUES ('921', '104', '罗城', '3');
INSERT INTO `yang_areas` VALUES ('922', '104', '巴马', '3');
INSERT INTO `yang_areas` VALUES ('923', '104', '环江', '3');
INSERT INTO `yang_areas` VALUES ('924', '104', '大化', '3');
INSERT INTO `yang_areas` VALUES ('925', '105', '八步区', '3');
INSERT INTO `yang_areas` VALUES ('926', '105', '钟山县', '3');
INSERT INTO `yang_areas` VALUES ('927', '105', '昭平县', '3');
INSERT INTO `yang_areas` VALUES ('928', '105', '富川', '3');
INSERT INTO `yang_areas` VALUES ('929', '106', '兴宾区', '3');
INSERT INTO `yang_areas` VALUES ('930', '106', '合山市', '3');
INSERT INTO `yang_areas` VALUES ('931', '106', '象州县', '3');
INSERT INTO `yang_areas` VALUES ('932', '106', '武宣县', '3');
INSERT INTO `yang_areas` VALUES ('933', '106', '忻城县', '3');
INSERT INTO `yang_areas` VALUES ('934', '106', '金秀', '3');
INSERT INTO `yang_areas` VALUES ('935', '107', '城中区', '3');
INSERT INTO `yang_areas` VALUES ('936', '107', '鱼峰区', '3');
INSERT INTO `yang_areas` VALUES ('937', '107', '柳北区', '3');
INSERT INTO `yang_areas` VALUES ('938', '107', '柳南区', '3');
INSERT INTO `yang_areas` VALUES ('939', '107', '柳江县', '3');
INSERT INTO `yang_areas` VALUES ('940', '107', '柳城县', '3');
INSERT INTO `yang_areas` VALUES ('941', '107', '鹿寨县', '3');
INSERT INTO `yang_areas` VALUES ('942', '107', '融安县', '3');
INSERT INTO `yang_areas` VALUES ('943', '107', '融水', '3');
INSERT INTO `yang_areas` VALUES ('944', '107', '三江', '3');
INSERT INTO `yang_areas` VALUES ('945', '108', '钦南区', '3');
INSERT INTO `yang_areas` VALUES ('946', '108', '钦北区', '3');
INSERT INTO `yang_areas` VALUES ('947', '108', '灵山县', '3');
INSERT INTO `yang_areas` VALUES ('948', '108', '浦北县', '3');
INSERT INTO `yang_areas` VALUES ('949', '109', '万秀区', '3');
INSERT INTO `yang_areas` VALUES ('950', '109', '蝶山区', '3');
INSERT INTO `yang_areas` VALUES ('951', '109', '长洲区', '3');
INSERT INTO `yang_areas` VALUES ('952', '109', '岑溪市', '3');
INSERT INTO `yang_areas` VALUES ('953', '109', '苍梧县', '3');
INSERT INTO `yang_areas` VALUES ('954', '109', '藤县', '3');
INSERT INTO `yang_areas` VALUES ('955', '109', '蒙山县', '3');
INSERT INTO `yang_areas` VALUES ('956', '110', '玉州区', '3');
INSERT INTO `yang_areas` VALUES ('957', '110', '北流市', '3');
INSERT INTO `yang_areas` VALUES ('958', '110', '容县', '3');
INSERT INTO `yang_areas` VALUES ('959', '110', '陆川县', '3');
INSERT INTO `yang_areas` VALUES ('960', '110', '博白县', '3');
INSERT INTO `yang_areas` VALUES ('961', '110', '兴业县', '3');
INSERT INTO `yang_areas` VALUES ('962', '111', '南明区', '3');
INSERT INTO `yang_areas` VALUES ('963', '111', '云岩区', '3');
INSERT INTO `yang_areas` VALUES ('964', '111', '花溪区', '3');
INSERT INTO `yang_areas` VALUES ('965', '111', '乌当区', '3');
INSERT INTO `yang_areas` VALUES ('966', '111', '白云区', '3');
INSERT INTO `yang_areas` VALUES ('967', '111', '小河区', '3');
INSERT INTO `yang_areas` VALUES ('968', '111', '金阳新区', '3');
INSERT INTO `yang_areas` VALUES ('969', '111', '新天园区', '3');
INSERT INTO `yang_areas` VALUES ('970', '111', '清镇市', '3');
INSERT INTO `yang_areas` VALUES ('971', '111', '开阳县', '3');
INSERT INTO `yang_areas` VALUES ('972', '111', '修文县', '3');
INSERT INTO `yang_areas` VALUES ('973', '111', '息烽县', '3');
INSERT INTO `yang_areas` VALUES ('974', '112', '西秀区', '3');
INSERT INTO `yang_areas` VALUES ('975', '112', '关岭', '3');
INSERT INTO `yang_areas` VALUES ('976', '112', '镇宁', '3');
INSERT INTO `yang_areas` VALUES ('977', '112', '紫云', '3');
INSERT INTO `yang_areas` VALUES ('978', '112', '平坝县', '3');
INSERT INTO `yang_areas` VALUES ('979', '112', '普定县', '3');
INSERT INTO `yang_areas` VALUES ('980', '113', '毕节市', '3');
INSERT INTO `yang_areas` VALUES ('981', '113', '大方县', '3');
INSERT INTO `yang_areas` VALUES ('982', '113', '黔西县', '3');
INSERT INTO `yang_areas` VALUES ('983', '113', '金沙县', '3');
INSERT INTO `yang_areas` VALUES ('984', '113', '织金县', '3');
INSERT INTO `yang_areas` VALUES ('985', '113', '纳雍县', '3');
INSERT INTO `yang_areas` VALUES ('986', '113', '赫章县', '3');
INSERT INTO `yang_areas` VALUES ('987', '113', '威宁', '3');
INSERT INTO `yang_areas` VALUES ('988', '114', '钟山区', '3');
INSERT INTO `yang_areas` VALUES ('989', '114', '六枝特区', '3');
INSERT INTO `yang_areas` VALUES ('990', '114', '水城县', '3');
INSERT INTO `yang_areas` VALUES ('991', '114', '盘县', '3');
INSERT INTO `yang_areas` VALUES ('992', '115', '凯里市', '3');
INSERT INTO `yang_areas` VALUES ('993', '115', '黄平县', '3');
INSERT INTO `yang_areas` VALUES ('994', '115', '施秉县', '3');
INSERT INTO `yang_areas` VALUES ('995', '115', '三穗县', '3');
INSERT INTO `yang_areas` VALUES ('996', '115', '镇远县', '3');
INSERT INTO `yang_areas` VALUES ('997', '115', '岑巩县', '3');
INSERT INTO `yang_areas` VALUES ('998', '115', '天柱县', '3');
INSERT INTO `yang_areas` VALUES ('999', '115', '锦屏县', '3');
INSERT INTO `yang_areas` VALUES ('1000', '115', '剑河县', '3');
INSERT INTO `yang_areas` VALUES ('1001', '115', '台江县', '3');
INSERT INTO `yang_areas` VALUES ('1002', '115', '黎平县', '3');
INSERT INTO `yang_areas` VALUES ('1003', '115', '榕江县', '3');
INSERT INTO `yang_areas` VALUES ('1004', '115', '从江县', '3');
INSERT INTO `yang_areas` VALUES ('1005', '115', '雷山县', '3');
INSERT INTO `yang_areas` VALUES ('1006', '115', '麻江县', '3');
INSERT INTO `yang_areas` VALUES ('1007', '115', '丹寨县', '3');
INSERT INTO `yang_areas` VALUES ('1008', '116', '都匀市', '3');
INSERT INTO `yang_areas` VALUES ('1009', '116', '福泉市', '3');
INSERT INTO `yang_areas` VALUES ('1010', '116', '荔波县', '3');
INSERT INTO `yang_areas` VALUES ('1011', '116', '贵定县', '3');
INSERT INTO `yang_areas` VALUES ('1012', '116', '瓮安县', '3');
INSERT INTO `yang_areas` VALUES ('1013', '116', '独山县', '3');
INSERT INTO `yang_areas` VALUES ('1014', '116', '平塘县', '3');
INSERT INTO `yang_areas` VALUES ('1015', '116', '罗甸县', '3');
INSERT INTO `yang_areas` VALUES ('1016', '116', '长顺县', '3');
INSERT INTO `yang_areas` VALUES ('1017', '116', '龙里县', '3');
INSERT INTO `yang_areas` VALUES ('1018', '116', '惠水县', '3');
INSERT INTO `yang_areas` VALUES ('1019', '116', '三都', '3');
INSERT INTO `yang_areas` VALUES ('1020', '117', '兴义市', '3');
INSERT INTO `yang_areas` VALUES ('3437', '1', '其他', '1');

-- ----------------------------
-- Table structure for `yang_article`
-- ----------------------------
DROP TABLE IF EXISTS `yang_article`;
CREATE TABLE `yang_article` (
  `article_id` int(32) NOT NULL AUTO_INCREMENT,
  `position_id` int(32) NOT NULL,
  `title` varchar(64) NOT NULL,
  `content` mediumtext NOT NULL,
  `add_time` varchar(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `type` varchar(32) NOT NULL,
  `sign` int(4) NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=344 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_article
-- ----------------------------
INSERT INTO `yang_article` VALUES ('27', '1', '百币网交易平台获得合法手续', '<p>\r\n	<span style=\"font-size:14px;\"> </span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<span style=\"font-size:18px;\">百币网是【成都百年春网络科技股份有限公司】旗下数字资产交易平台。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<span style=\"font-size:18px;\">获得了文化厅颁发的【网络文化经营许可证】；</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<span style=\"font-size:18px;\">获得国家工信部【</span><span style=\"font-size:18px;\">ICP</span><span style=\"font-size:18px;\">备案证】；</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<span style=\"font-size:18px;\">百币网双域名</span><span style=\"font-size:18px;\"><a href=\"http://www.100bi.com\" target=\"_blank\">www.100bi.com</a></span><span style=\"font-size:18px;\">和</span><span style=\"font-size:18px;\"><a href=\"http://www.100bi.cn\" target=\"_blank\">www.100bi.cn</a></span><span style=\"font-size:18px;\">。</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:14px;\"> </span><span style=\"font-size:14px;\"> </span> \r\n</p>', '1484532465', '0', '', '0');
INSERT INTO `yang_article` VALUES ('340', '3', '比特币的末日？如果谣言可信，那么比特币已死过N次', '货币。根据作者所言，针对攻击比特币网络的一系列的“全球自动化测试”已经启动，它们将会给世界证明，数字资产“不能被存储财富。”<br />\r\n<br />\r\n“是时候采取方式设计一个更好的，更易控制的，更安全的系统来作为全球数字货币了，”所谓的作者Forcecoin解释。<br />\r\n<br />\r\n然而12号对比特币网络的攻击并没有出现，根据 Godaddy提供数据，该团队的网站URL也是一个不知所属的域名。此外，虽然这个问题文章已被删除，但是通过存档还是保存了下来。认真看这个帖子就会发现，作者要么是故意在戏耍比特币社区，要么就是根本不懂比特币协议。<br />\r\n<br />\r\n例如，该文章邀请全世界的黑客去偷盗Forcecoin代码，就像Mt Gox代码被泄漏一样。然而这个以前的交易所代码泄漏和比特币网络本身没有任何关系，这无可争议。该文章陈述，如果黑客成功侵入了Salesforce，并偷盗了ForceCoin 代码，他们作为Salesforce麻省理工学院的ForceCoin非营利组织团队，他们将承认失败，承认比特币获胜。<br />\r\n<br />\r\n根据主流媒体和怀疑论者，比特币已经死过N次<br />\r\n<br />\r\n根据比特币比特币讣告，以及主流媒体的报道，数字资产已经死117次了。Linkedin文章的题目为“比特币是泡沫。它会在2016年12月12日周一崩盘。”它是最近有记录的宣称比特币末日的事件。其他过于夸张宣称比特币死亡的报道也在彭博社，新闻周刊，华尔街日报等媒体上出现过。自从Mt Gox倒闭，Mike Hearn离开，Bitfinex被黑客攻击后，比特币末日的负面消息就接二连三的出现。<br />\r\n<br />\r\n大部分的加密爱好者很容易忽略这种比特币失败的标题新闻。Linkedin的文章/骗局和以往的这些新闻没有什么不同，观点十分不合逻辑。然而主流媒体和那些不懂比特币的人很容易被这种标题的新闻左右。想想你有多少朋友在说，“哦，我认为比特币失败了。”<br />\r\n<br />\r\nForcecoin背后的所谓团队看起来根本就不存在，12月12日比特币网络没有崩溃，也没有“压力测试”出现。事实上，比特币的价格在12日涨到了779美元，达到了今年的最高价格。<br />', '1484016883', '0', '', '0');
INSERT INTO `yang_article` VALUES ('333', '3', '中国人民银行数字货币研讨会在京召开', '<p>\r\n	2016年1月20日，中国人民银行数字货币研讨会在北京召开。来自人民银行、花旗银行和德勤公司的数字货币研究专家分别就数字货币发行的总体框架、货币演进中的国家数字货币、国家发行的加密货币等专题进行了研讨和交流。人民银行行长周小川出席会议，人民银行副行长范一飞主持会议。有关国内外科研机构、重要金融机构和咨询机构的专家参加了会议。\r\n</p>\r\n<p>\r\n	会议指出，随着信息科技的发展以及移动互联网、可信可控云计算、终端安全存储、区块链等技术的演进，全球范围内支付方式发生了巨大的变化，数字货币的发展正在对中央银行的货币发行和货币政策带来新的机遇和挑战。人民银行对此高度重视，从2014年起就成立了专门的研究团队，并于2015年初进一步充实力量，对数字货币发行和业务运行框架、数字货币的关键技术、数字货币发行流通环境、数字货币面临的法律问题、数字货币对经济金融体系的影响、法定数字货币与私人发行数字货币的关系、国际上数字货币的发行经验等进行了深入研究，已取得阶段性成果。\r\n</p>\r\n<p>\r\n	会议认为，在我国当前经济新常态下，探索央行发行数字货币具有积极的现实意义和深远的历史意义。发行数字货币可以降低传统纸币发行、流通的高昂成本，提升经济交易活动的便利性和透明度，减少洗钱、逃漏税等违法犯罪行为，提升央行对货币供给和货币流通的控制力，更好地支持经济和社会发展，助力普惠金融的全面实现。未来，数字货币发行、流通体系的建立还有助于我国建设全新的金融基础设施，进一步完善我国支付体系，提升支付清算效率，推动经济提质增效升级。\r\n</p>\r\n<p>\r\n	会议要求，人民银行数字货币研究团队要积极吸收国内外数字货币研究的重要成果和实践经验，在前期工作基础上继续推进，建立更为有效的组织保障机制，进一步明确央行发行数字货币的战略目标，做好关键技术攻关，研究数字货币的多场景应用，争取早日推出央行发行的数字货币。数字货币的设计应立足经济、便民和安全原则，切实保证数字货币应用的低成本、广覆盖，实现数字货币与其他支付工具的无缝衔接，提升数字货币的适用性和生命力。\r\n</p>\r\n<p>\r\n	人民银行在推进数字货币研究工作中，与有关国际机构、互联网企业建立了沟通联系，与国内外有关金融机构、传统卡基支付机构进行了广泛探讨。参与研讨的国内外人士高度重视此项工作，并就相关的理论研究、实践探索及发展路径与人民银行系统的专家进行了深入交流。\r\n</p>', '1483955290', '0', '', '0');
INSERT INTO `yang_article` VALUES ('330', '1', '百币网交易平台上线送百年通宝', '<p>\r\n	<span style=\"font-size:18px;\"> 热烈庆祝【 百币网数字资产交易平台】于2017年1月8日正式上线运行！</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">为了答谢持有明星币和百年通宝的新老客户：</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"> 请将您获取的“邀请码”在百币网注册成功，就能获得：500枚百年通宝币的赠送！ </span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"> 您把自己的“邀请码”发给新人注册开户，您和您邀请的人均可得到：500枚百年通宝的赠送！</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"> 您推荐注册的新客户越多，获赠送的就越多！1亿枚百年通宝等你拿！赠完为止。</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">     </span>\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">注：①赠送方式：5枚/每天 共10天；</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"></span><span style=\"font-size:18px;\">      ②没有获取邀请码自己注册开户的不赠送百年通宝币；</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"></span><span style=\"font-size:18px;\">      ③所有客户免费注册开户；</span> \r\n</p>', '1484532637', '0', '', '0');
INSERT INTO `yang_article` VALUES ('341', '3', '2017年比特币和区块链的发展预测，币价会很强势？', '2016年马上就要结束了，新的一年对加密货币和区块链技术来说是一个令人兴奋的时期。在这篇文章中，我们将会看到一些值得注意的对比特币、加密货币、区块链在2017年的预测。<br />\r\n<br />\r\n<br />\r\n<br />\r\n盛宝银行对比特币的大胆预测<br />\r\n<br />\r\n这并不能算盛宝官方的市场展望，这个大胆预测说比特币的价格在2017年将会达到2100美元，是现在价格的3倍。<br />\r\n<br />\r\n这个预测的背后因素是，美国总统特朗普当政后在财政上积极支出，同时美国的预算赤字也超过了10亿美元。美国的通货膨胀将会继续增加，美联储也将会被迫加息，推动美元达到一个新的高度。<br />\r\n<br />\r\n这就使得新兴市场中产生多米诺骨牌效应，特别是中国，这些国家将会寻找替代方案来逃避美国全球市场的霸权主义。加密货币作为一个整体也会收益，但是比特币具有先发优势，它受益将会最大。<br />\r\n<br />\r\n随着中国和俄罗斯银行对数字货币接受程度的增加，使用比特币和加密货币取代美元的可能性使得盛宝银行预测比特币的价格会从770美元飙升3倍达到2100美元左右。<br />\r\n<br />\r\n著名比特币专家也这么认为<br />\r\n<br />\r\n在最近的Coinscrum会议中，比特币专家Andreas Antonopolous发表了一个关于货币战争和比特币中立性的演讲。自从全球经济危机以来，世界各国都被卷入到了货币战争当中，他们通过贬值本国货币来增加出口的竞争力。<br />\r\n<br />\r\n在这种情况下，Antonopolous强调了比特币的中立性，随着地缘政治和经济战争的不断升级，这些都会促进比特币接受程度和价格的增加。<br />\r\n<br />\r\n这场货币战争已经初露端倪，委内瑞拉和津巴布韦等国内极度通货膨胀就是例子。在2017年，毫无疑问这种情况将会加剧，因为比特币没有参与到这场经济战争中，因此它不会成为国家使用他们货币进行攻击的工具。<br />\r\n<br />\r\n另外，加密货币投资者和 Digital Currency Group创始人Barry Silbert也提出了一些2017年比特币发展的预测，具体内容如下：<br />\r\n<br />\r\n1.比特币对贸易投资者来说将会更易于接受。<br />\r\n2.比特币的交易指数在印度，日本和中东地区将会增长。<br />\r\n3.随声附和类的预测，Silbert也在考虑2017年将会是供应链领域区块链技术概念证明的爆炸发展时期。<br />\r\n4.对于监管，Silbert认为证券交易委员会（SEC）可能会严格对待ICO，把它当成证券一样视为资产，因此它将会受到更加严格的审查和监管。<br />\r\n5.同时Silbert也预测了比特币价格在2017年将会将会很强势，但是他没有给出具体数值或者范围。<br />\r\n<br />\r\n区块链初创公司预测强调了区块链的发展趋势<br />\r\n<br />\r\n12月1日，Oliver Bussman和Nick Williamson在金丝雀码头（Canary Wharf）的Level39为他们的客人预测了他们对于2017年区块链发展的一些观点：<br />\r\n<br />\r\n2017年对于金融服务领域的区块链技术来说是“试验的一年”，特别是特别是跨境支付和贸易融资领域。<br />\r\n<br />\r\n区块链的广泛接受将会促进除金融领域外的诸如电子政务，医疗保健和供应链管理等方面的发展。<br />\r\n<br />\r\n修订的欧盟支付方针PSD2加强了金融科技初创公司和银行的合作。<br />', '1484016959', '0', '', '0');
INSERT INTO `yang_article` VALUES ('2', '1', '百币网加强反洗钱措施的公告', '<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">为了响应央行等国家五部委《关于防范比特币风险的通知》，以及落实《中华人民共和国反洗钱法》的规定，维护正常的交易秩序，从即日起，百币网将执行以下反洗钱措施：</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<br />\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">实名认证</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">1. 用户注册时，必须提供真实姓名，真实姓名经实名认证后将不能够更改，请务必如实填写；</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">2.百币网只接受实名充值、提现，充值及提现银行卡信息必须与您的实名认证信息一致；</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">3.	注册百币网账户需同意：我承认提交的信息属于本人所有，不存在冒用、盗用他人证件的行为。因冒用、盗用他人证件造成的一切后果，由本人承担；</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">4. 用户更改交易密码后，应当提供清晰的手持身份证明照片，百币网将对用户提供的身份信息进行识别和比对；</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">5.百币网有合理的理由怀疑用户提供虚假身份信息时，有权拒绝注册或者冻结、注销已经注册的账户及资产。</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<br />\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">资金进出审核</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">1. 根据国家反洗钱政策及保障客户资产安全，汇款人姓名必须和百币网实名认证姓名一致，提现账户的姓名也必须与百币网实名认证姓名一致；</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">2 .对于超过1万以上的人民币提现及大额的提币，我们将对用户身份通过客服电话（</span><a href=\"mailto:bncwlkj@163.com\"><span style=\"font-size:18px;\">400-9665-100</span></a><span style=\"line-height:1.5;font-size:18px;\">）进行人工核实；</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">3.百币网将参照《金融机构大额交易和可疑交易报告管理办法》的规定，保存大额交易和有洗钱嫌疑的交易记录，在监管机构要求提供大额交易和可疑交易的记录时，向监管机构提供；</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<span style=\"line-height:1.5;font-size:18px;\">4. 百币网将对用户身份信息以及大额交易、可疑交易记录进行保存，依法协助、配合司法机关和行政执法机关打击洗钱活动，依照法律法规的规定协助司法机关、海关、税务等部门查询、冻结和扣划客户存款。</span>\r\n</div>\r\n<div style=\"text-align:left;\">\r\n	<br />\r\n</div>', '1484532807', '0', '', '0');
INSERT INTO `yang_article` VALUES ('4', '1', '虚拟数字资产转换积分商城购物', '<span style=\"line-height:1.5;font-size:18px;\">百年春商城欢迎广大顾客用比特币、百年通宝币、明星币兑换积分购物。在百年春商城<a href=\"http://www.bncgw.com\" target=\"_blank\">www.bncgw.com</a>注册成功后，客户可以把任何数字资产交易平台上的比特币和明星币及百年通宝币提币到百年春商城兑换积分购物。</span>', '1484533163', '0', '', '0');
INSERT INTO `yang_article` VALUES ('334', '3', '2016区块链国际峰会聚焦区块链落地应用', '<p style=\"font-size:16px;color:#393939;font-family:宋体;background-color:#FFFFFF;\">\r\n	6月22日至24日，2016区块链国际峰会在北京召开。峰会聚焦区块链与金融科技新趋势。与会业内嘉宾围绕区块链原理与应用场景探析，区块链技术在金融领域的创新应用，区块链加密数字资产，区块链技术标准、合规及安全性等议题进行了主题分享。圆桌讨论环节深入探讨了央行数字货币，区块链在金融行业的应用前景，区块链与人工智能、物联网等创新应用的结合等话题。峰会由中关村区块链产业联盟和亚洲区块链DACA（Digital Assets Coalition Asia）协会主办。\r\n</p>\r\n<p style=\"font-size:16px;color:#393939;font-family:宋体;background-color:#FFFFFF;\">\r\n	    中钞北京智能卡技术研究院副院长张一峰、工行总行金融创新处长周永林博士、中国社会科学院金融研究所产业金融研究基地主任杨涛、IBM区块链专家董宁、普华永道风险及控制服务合伙人季瑞华、联邦银行（澳洲）区块链咨询专家张韡武等与会并发言。\r\n</p>\r\n<p style=\"font-size:16px;color:#393939;font-family:宋体;background-color:#FFFFFF;\">\r\n	    目前区块链投资主要集中在西方发达国家，美国占据着区块链投资的主导地位。在国际上，银行、投资机构、保险公司等传统金融机构是探索区块链应用可能性的先驱，它们希望在未来的金融变革中能够最先掌握这个战略性的优势武器。2015年9月R3CEV联盟成立，引导各成员银行在涉及区块链和分布式账本技术的工程、实验以及研究项目的协作。目前在国外已经有40多家金融机构加入R3区块链项目，其中包括摩根大通、高盛、美国银行、花旗银行、汇丰银行等。另外，由网络商业巨头亚马逊提供一种云计算商业平台，目前正与投资公司数字资产集团（DAH）合作，为企业提供区块链实验环境。IBM也表示正在尝试用Watson人工智能来驱动区块链技术。\r\n</p>\r\n<p style=\"font-size:16px;color:#393939;font-family:宋体;background-color:#FFFFFF;\">\r\n	    区块链在中国关注度迅速提高，但仍处于早期接触和探索阶段，同时也逐渐出现一些实际应用案例。国内纷纷建立了自己的区块链联盟，如中关村区块链产业联盟、中国区块链应用研究中心、China Ledger联盟、金融区块链合作联盟等。随着国内区块链创新企业的成长，区块链应用层方面也开始出现很多实际应用案例，太一云科技（简称太一）作为IBM中国实验室在区块链领域全行业战略合作伙伴，已在诸多领域实现落地应用。\r\n</p>\r\n<p style=\"font-size:16px;color:#393939;font-family:宋体;background-color:#FFFFFF;\">\r\n	    太一云科技董事长邓迪介绍，该公司的第一个区块产生于2014年11月，是中国自主研发的多资产区块链系统。太一云与IBM中国实验室在中国区块链领域建立全行业合作伙伴关系，现已在供应链金融领域成立区块链联合开发小组，太一在区块链方面的自主核心技术也将被引入IBM的全球项目。目前太一在资产数字化领域已推出具体的落地应用，其中金信商品交易中心和北方工业股权交易中心就是两个最典型的区块链现实应用，利用区块链公开、透明和防伪溯源的特性实现可信商品、可信企业认证和可信交易。\r\n</p>\r\n<p style=\"font-size:16px;color:#393939;font-family:宋体;background-color:#FFFFFF;\">\r\n	    在区块链商品登记的应用方面，太一云科技联合沈阳金信商品交易中心，建立了全球第一个区块链商品交易中心，通过区块链进行商品登记，提高周转效率，实现防伪溯源。太一区块链系统在商品交易领域提供区块链征信、区块链资产登记、数字资产无损交易服务、区块链安全、智能合约等技术基础设施，可以为各类商品交易所、电商、物流等大型平台提供多种交易解决方案，同时已经构建的数字资产登记平台和数字资产交易平台, 将为数字资产的流通提供有力的流动性保障。\r\n</p>\r\n<p style=\"font-size:16px;color:#393939;font-family:宋体;background-color:#FFFFFF;\">\r\n	    在区块链股权登记的应用方面，太一为北方工业股权交易中心搭建了基于太一金融云开发的区块链股权登记系统TERS系统。北方工业股权交易中心在国家工商部门注册，是中国证监会备案的混合所有制股权交易所，为企业和一级市场投资者提供丰富多样的金融产品和综合服务。目前北股交TERS系统已经开始内测，10家创业企业已经在区块链上完成了股权登记和持股凭证发放，登记企业总注册资本达到3亿元。作为国内首家运用区块链技术的股权交易所，北股交将通过TERS系统提升企业股权登记管理的效率，增加企业信息披露的准确性和透明性，为中小企业提供更为便利和高效的金融服务。\r\n</p>', '1483955330', '0', '', '0');
INSERT INTO `yang_article` VALUES ('5', '118', '关于我们', '&lt;div class=&quot;main_content&quot;&gt;\r\n	&lt;div class=&quot;page&quot;&gt;\r\n		&lt;div class=&quot;main-content&quot;&gt;\r\n			&lt;div&gt;\r\n				本站是一个统计收录虚拟币山寨币的专业网站，对新手玩币族提供一个选币的导航平台。本站坚持以最公正的态度对待每一个币种。同时提供大家评论讨论的机会，让我们一起去发现新币，热币。一起去摒弃黑币平台。\r\n			&lt;/div&gt;\r\n			&lt;div&gt;\r\n				&amp;nbsp;\r\n			&lt;/div&gt;\r\n			&lt;div&gt;\r\n				1、所有收录币种最新虚拟币、山寨币网均没有经过评测，请网友自行分析投资与挖矿风险。\r\n			&lt;/div&gt;\r\n			&lt;div&gt;\r\n				&amp;nbsp;\r\n			&lt;/div&gt;\r\n			&lt;div&gt;\r\n				2、对于已收录虚拟币、山寨币但缺乏操守的币种，请网友告知，本站将予以下架删除等妥当处理。\r\n			&lt;/div&gt;\r\n			&lt;div&gt;\r\n				&amp;nbsp;\r\n			&lt;/div&gt;\r\n			&lt;div&gt;\r\n				3、炒币有风险，投资需谨慎。选好平台是关键。山寨币但缺乏操守的币种，请网友告知，本站将予以下架删除等妥当处理。\r\n			&lt;/div&gt;\r\n		&lt;/div&gt;\r\n	&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;br /&gt;', '1458797434', '0', '', '0');
INSERT INTO `yang_article` VALUES ('6', '120', '提现人民币说明', '1. 提现手续费0.3%，请仔细确认后再操作。&lt;br /&gt;\r\n&lt;p&gt;\r\n	2. 单笔提现限额100元——5万元；单日累计最大提现限额50万元。&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;\r\n&lt;/p&gt;\r\n&lt;span style=&quot;color:#E53333;&quot;&gt;3. 由于近期充值提现业务量暴增，提现到账时间临时延长到24小时，敬请谅解!&lt;/span&gt;&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;br /&gt;', '1458808275', '0', '', '0');
INSERT INTO `yang_article` VALUES ('51', '61', '交易指南', '<h1>\r\n	<br />\r\n</h1>\r\n<p>\r\n	<span style=\"font-size:18px;\">1.首先，进入百币网首页（www.100bi.com或</span><span style=\"font-size:18px;\">www.100bi.cn</span><span style=\"font-size:18px;\">），登录已有账号，将鼠标放在【币币交易】按钮上，页面将显示百币网上所有类别的币种，选择您想交易的币种，如图：</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115072046_81853.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<b><span style=\"font-size:18px;\">买入指南 </span><span style=\"font-weight:normal;font-size:18px;\">（以明星币买入为例）</span></b> \r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">1.页面跳转到明星币交易页面，在买入栏中填入【买入价格】【买入数量】【交易密码】，点击【买入】。系统将自动成交挂单。</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115073707_43291.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span class=\"impo\"><span style=\"font-size:18px;\">注：买入数量应小于等于【最大可买】。</span><br />\r\n<span style=\"font-size:18px;\"> 不同虚拟币的买入手续费不同，请买入前仔细确认金额。 </span></span> \r\n</p>\r\n<p>\r\n	<span class=\"impo\"><span style=\"font-size:18px;\"><br />\r\n</span></span>\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">2.挂单成功后，可在【我的委托】中查看挂单委托情况。</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115074530_43439.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span class=\"impo\" style=\"font-size:18px;\">注：若挂单价格与当前市价不吻合，可能导致挂单不能及时成交。可稍作等待，观察行情。若挂单仍未成交，可撤销（交易中心-我的委托-撤销）挂单调整价格重新挂单。撤销后，未成交部分资产（如买币时冻结的资金、卖币时冻结的币）将原数返还到您的平台账户中。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<b><span style=\"font-size:18px;\">卖出指南 </span><span style=\"font-weight:normal;font-size:18px;\">（以明星币买入为例）</span></b>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">1、</span><span style=\"font-size:18px;line-height:27px;\">在卖出栏中填入【卖出价格】【卖出数量】【交易密码】，点击【卖出】。系统将自动成交挂单。</span>\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:27px;\"><br />\r\n</span>\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:27px;\"><img src=\"/Public/kindeditor/attached/image/20170115/20170115074955_19124.png\" alt=\"\" /><br />\r\n</span>\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:27px;\"><span style=\"font-size:18px;line-height:27px;\">2.挂单成功后，可在【我的委托】中查看挂单委托情况。</span></span>\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:27px;\"><span style=\"font-size:18px;line-height:27px;\">注：若挂单价格与当前市价不吻合，可能导致挂单不能及时成交。可稍作等待，观察行情。若挂单仍未成交，可撤销（交易中心-我的委托-撤销）挂单调整价格重新挂单。撤销后，未成交部分资产（如买币时冻结的资金、卖币时冻结的币）将原数返还到您的平台账户中。</span><br />\r\n</span>\r\n</p>', '1484466791', '0', '', '0');
INSERT INTO `yang_article` VALUES ('50', '63', '转出指南', '<p>\r\n	<span style=\"font-size:18px;\"></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:1.5;\">首先，进入百币网首页（www.100bi.com或www.100bi.cn），登录已有账号，进入【用户中心】，点击【账户资产】，如图：</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115085054_95999.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<span style=\"font-size:24px;\"><strong>充币（以明星币为例）</strong></span> \r\n<p>\r\n	<span style=\"font-size:18px;\">1.进入账户资产，页面跳转到币种页面，选择明星币，点击【充币】。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170116/20170116012501_71135.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">2.页面显示明星币在百币网上的钱包地址，将虚拟币转入该钱包即可。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115092221_59637.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:1.5;\">注：转入虚拟币是自动的，各个币种需要达到不同的确认数后将自动充值到百币网账户中。请了解转入币种的网络确认数。</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">3.请确认已达到确认数后，刷新页面，点击【账户资产】查看资产变化。</span><br />\r\n<span style=\"font-size:18px;\"> 可在转入记录中查询转入具体情况，如图：</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">如遇转入未到账等问题，可咨询在线客服400-9665-100。</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:1.5;\"><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:24px;line-height:1.5;\"><strong>提币 (以明星币为例)</strong></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:1.5;\">1. 进入账户资产，选择明星币，点击【提币】。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170116/20170116012750_34823.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">2.页面跳转到明星币提币页面，如果还没有钱包地址，可点击添加一个新的钱包地址。</span> \r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170116/20170116013011_27055.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">3、输入【新地址标签】，【提币地址】，点击【确认】。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170116/20170116013616_54722.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">4、添加新地址后即可选择新地址，填写【提币数量】，【交易密码】，【手机验证码】，确认填写信息无误后点击【确认提币】。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170116/20170116014801_83883.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><br />\r\n</span>\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">5.请等待客服人员确认转出。转出后，可通过明星币的区块链接查询当前的网络确认数。达到网络确认数后将自动转入到接收方的账户中 。</span><br />\r\n<span style=\"font-size:18px;\"> 如有其他问题，请咨询在线客服400-9665-100。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>', '1484531867', '0', '', '0');
INSERT INTO `yang_article` VALUES ('58', '60', '注册指南', '<h3>\r\n</h3>\r\n<h3>\r\n	<br />\r\n</h3>\r\n<h3>\r\n	<span style=\"font-size:18px;\">1.打开百币网首页（</span><a href=\"http://www.100bi.com\"><span style=\"font-size:18px;\">www.100bi.com</span></a><span style=\"font-size:18px;\">或</span><a href=\"http://www.100bi,cn\"><span style=\"font-size:18px;\">www.100bi,cn</span></a><span style=\"font-size:18px;\">)，点击右上角【注册】,进入注册页面。</span> \r\n</h3>\r\n<h3>\r\n	<br />\r\n</h3>\r\n<h3>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115081906_88274.png\" alt=\"\" /><br />\r\n</h3>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:1.5;\">2.填写用户名（用于登录）、登录密码（需设置6位以上）、邀请码（非必填），并仔细阅读《服务条款》，点击【下一步】。</span> \r\n</p>\r\n<h3>\r\n	<br />\r\n</h3>\r\n<h3>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115081536_72981.png\" alt=\"\" /><br />\r\n</h3>\r\n<h3>\r\n</h3>\r\n<p>\r\n	<br />\r\n</p>\r\n<h3>\r\n	<span style=\"font-size:18px;\">3.填写交易密码（填写6位以上，且与登录密码不同），确认后点击【下一步】。</span> \r\n</h3>\r\n<h3>\r\n	<br />\r\n</h3>\r\n<h3>\r\n	<img src=\"/Public/kindeditor/attached/image/20170112/20170112065359_20885.png\" alt=\"\" /> \r\n</h3>\r\n<p>\r\n	<br />\r\n</p>\r\n<h3>\r\n	<span style=\"font-size:18px;\">4.根据国家相关规定，百币网需对注册用户进行实名登记。</span> \r\n</h3>\r\n<h3>\r\n	<span style=\"font-size:18px;\">请选择证件类型（系统默认身份证，可选择护照/军官证等），输入用户本人真实的证件信息后（证件信息注册后不可修改），</span> \r\n</h3>\r\n<h3>\r\n	<span style=\"font-size:18px;\">填写您自己使用的手机号码（</span><span style=\"font-weight:normal;font-size:18px;\">用于您以后登录和找回密码</span><span style=\"font-size:18px;font-weight:normal;line-height:1.5;\"></span><span style=\"font-size:18px;font-weight:normal;line-height:1.5;\">），点击【点击发送】，填写手机收到的短信验证码然后点击【提交】。</span>\r\n</h3>\r\n<h3>\r\n	<br />\r\n</h3>\r\n<h3>\r\n	<span style=\"font-size:18px;\">注：提现时银行卡信息须与填写的身份信息一致，请填写真实身份信息，以免带来不便。</span> \r\n</h3>\r\n<h3>\r\n	<br />\r\n</h3>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170112/20170112065515_41898.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<h3>\r\n	<span style=\"font-size:18px;\">5. 注册成功。注册成功后系统自动跳转至【我的资产】→【账户资产】。登录账户后即可直接进行充值并交易。</span> \r\n</h3>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>', '1484532007', '0', '', '0');
INSERT INTO `yang_article` VALUES ('59', '62', '充值指南', '<p>\r\n	<span style=\"font-size:18px;\"> </span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">首先，进入百币网首页（www.100bi.com或www.100bi.cn），登录已有账号，点击【用户中心】，如图：</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"http://139.196.17.237/Public/kindeditor/attached/image/20170113/20170113061312_70642.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">然后点击左侧人民币充值，如图：</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"http://139.196.17.237/Public/kindeditor/attached/image/20170113/20170113061602_15033.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">人民币充值有两种： 在线充值和人工充值。以下分别演示两种充值方式流程。</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><strong><br />\r\n</strong></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><strong><br />\r\n</strong></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><strong>在线充值 ：</strong></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><strong><br />\r\n</strong></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">1.在页面左侧菜单选择【人民币充值（在线）】，填写充值金额，选择充值的银行，点击【马上充值】这里以建设银行为例。</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">注：每次充值金额不能低于100元且包含手续费0.5%。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115070517_25586.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">2、根据银行提示完成网上支付。完成充值。</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">注：Chrome浏览器可能不支持部分银行的网银控件，建议使用IE内核浏览器进行充值。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:27px;\">⑴、</span><span style=\"font-size:18px;\">选择网上银行支付，登录网银账号，根据提示完成支付。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115070801_24564.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;line-height:27px;\">⑵、</span><span style=\"font-size:18px;\">选择账号支付，输入支付账号，根据提示完成支付。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115070933_91431.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"> 提示充值成功后，请刷新百币网或重新登录百币账户查看资产。 </span><br />\r\n<span style=\"font-size:18px;\"> 具体到账时间根据不同银行的受理时间而定，请耐心等待。若转账后超过2小时仍未入账，请联系在线客服400-9665-100。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><strong>人工充值：支付宝</strong></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">1.在页面左侧菜单选择【人民币充值（人工）】，填写充值金额，汇款银行选择【支付宝】，填写支付宝账号，点击【立即充值】</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"http://139.196.17.237/Public/kindeditor/attached/image/20170113/20170113063449_46794.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>', '1484468825', '0', '', '0');
INSERT INTO `yang_article` VALUES ('60', '64', '提现指南', '<p>\r\n	<br />\r\n</p>\r\n<h3>\r\n	<span style=\"font-size:18px;\">1.进入百币网首页（</span><a href=\"http://www.100bi.com\"><span style=\"font-size:18px;\">www.100bi.com</span></a><span style=\"font-size:18px;\">或</span><a href=\"http://www.100bi.cn\"><span style=\"font-size:18px;\">www.100bi.cn</span></a><span style=\"font-size:18px;\">），登录已有账号，在导航栏点击【用户中心】如图：</span> \r\n</h3>\r\n<p>\r\n	<span style=\"font-size:18px;\"><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"></span> \r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170112/20170112070242_16082.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">然后点击左侧【人民币提现】，如图：</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170112/20170112070255_62834.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">2.人民币提现操作需绑定用户本人的银行卡，在页面左侧菜单选择【人民币提现】，点击【绑定银行卡】。若已绑定可直接申请提现。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170112/20170112070315_30459.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">3.进入银行卡绑定页面，填写用户本人的银行卡信息（储蓄卡/借记卡）后，点击【确认添加】。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170112/20170112070333_63889.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">注：开户姓名栏，系统默认为实名认证姓名。一定要点击【确定】</span><br />\r\n<span style=\"font-size:18px;\"> 若此处的姓名与本人真实姓名不符，请联系在线客服400-9665-100。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">4.银行卡添加成功后，返回【人民币提现】页面，填写提现申请。</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">选择提现银行卡，输入提款金额，交易密码，点击发送。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115075719_66155.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">5、输入手机收到的验证码，点击【确认提交】。</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/Public/kindeditor/attached/image/20170115/20170115080322_36865.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">注：提现金额</span><span style=\"font-size:18px;line-height:1.5;\">单笔提现限额1</span><span style=\"font-size:18px;line-height:1.5;\">00元——5万元；单日累计最大提现限额50万元。</span><span style=\"font-size:18px;line-height:1.5;\">提现手续费率为提现金额的0.5％  。</span>\r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\"><br />\r\n</span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:18px;\">6、申请提现后，财务会在24小时之内处理提现。请耐心等待并随时关注银行账户资金变动。若超过24小时仍未到账，请联系在线客服400-9665-100。</span> \r\n</p>', '1484469180', '0', '', '0');
INSERT INTO `yang_article` VALUES ('61', '110', '转入币', '<b>转入虚拟币<span style=\"font-weight:normal;\">（以狗狗币为例）</span></b> \r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/zhuanbig2.png\" />\r\n</p>\r\n<p>\r\n	1.进入财务中心，点击【转入虚拟币】，页面跳转到转入币种页面，选择狗狗币。\r\n</p>\r\n<p>\r\n	2.页面显示狗狗币在聚币网上的钱包地址，将虚拟币转入该钱包即可。\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/zhuanbig3.png\" />\r\n</p>\r\n<p>\r\n	也可通过手机扫描右侧的【狗币钱包地址】二维码，获取钱包地址。 <br />\r\n<span class=\"impo\">注：转入虚拟币是自动的，各个币种需要达到不同的确认数后将自动充值到聚币网账户中。请了解转入币种的网络确认数。</span>\r\n</p>\r\n<p>\r\n	3.请确认已达到确认数后，刷新页面，点击【财务中心】查看资产变化。 <br />\r\n可在转入记录中查询转入具体情况，如图：\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/zhuanbig4.png\" />\r\n</p>\r\n<p>\r\n	如遇转入未到账等问题，可咨询在线客服400-657-8880。\r\n</p>', '1458718009', '0', '', '1');
INSERT INTO `yang_article` VALUES ('124', '124', '卖出指南', '<img src=\"http://www.jubi.com/images/jubi/help/trade6.png\" />\r\n<p>\r\n	1.页面跳转到狗狗币交易页面，在卖出栏中填入【卖出价格】【卖出数量】【交易密码】，点击【卖出】。系统将自动成交挂单。\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/trade7.png\" />\r\n</p>\r\n<p>\r\n	<span class=\"impo\">注：卖出数量应小于等于【最大可买】。\r\n虚拟币买入数量不能小于0.1；成交金额不能少于1元。\r\n不同虚拟币的买入手续费不同，请买入前仔细确认金额。 </span>\r\n</p>\r\n<p>\r\n	2. 挂单成功后，可在【财务中心】-【委托管理】中查询挂单委托情况。\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/trade8.png\" />\r\n</p>\r\n<p>\r\n	<span class=\"impo\">注：若挂单价格与当前市价不吻合，可能导致挂单不能及时成交。可稍作等待，观察行情。若挂单仍未成交，可撤销（交易中心-我的委托挂单-撤销）挂单调整价格重新挂单。撤销后，未成交部分资产（如买币时冻结的资金、卖币时冻结的币）将原数返还到您的平台账户中。</span>\r\n</p>\r\n<p>\r\n	3. 也可在【财务中心】-【成交查询】中查询挂单成交情况。\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/trade9.png\" />\r\n</p>\r\n<p>\r\n	4. 挂单成交后，请刷新页面，点击【财务中心】查看资产变化。 <br />\r\n如有挂单其他问题，可咨询在线客服400-657-8880。\r\n</p>', '1458960091', '0', '', '0');
INSERT INTO `yang_article` VALUES ('62', '111', '转出币', '<b>转出虚拟币 <span>(以转出狗狗币为例)</span></b> \r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/zhuanbig5.png\" />\r\n</p>\r\n<p>\r\n	1. 进入财务中心，点击【转出虚拟币】，页面跳转到转出币种页面，选择狗狗币 <br />\r\n2. 在狗狗币转出页面，输入转出信息，包括钱包地址（将狗狗币转入的钱包地址）、转出数量（应少于等于可用狗狗币数量）、交易密码、GA码及手机验证码。确认无误后，点击【确认转出】。\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/zhuanbig6.png\" />\r\n</p>\r\n<p>\r\n	<span class=\"impo\">注：若短信验证码无法正常接收，请换用语音验证码。</span><br />\r\n3.请等待客服人员确认转出。转出后，可通过狗狗币的区块链接查询当前的网络确认数。达到网络确认数后将自动转入到接收方的账户中 。 <br />\r\n如有其他问题，请咨询在线客服400-657-8880。\r\n</p>', '1458718034', '0', '', '1');
INSERT INTO `yang_article` VALUES ('119', '119', '加入我们', '我要开始招聘啦，你来不来！！！！！', '1458800420', '0', '', '0');
INSERT INTO `yang_article` VALUES ('120', '121', '邀请规则', '1.被邀请的用户在元宝网成功注册账户、完善资料并充值CNY，邀请人可在活动期内每天最高获得被推荐人45%太一股配送收益。<br />\r\n2.如果同一用户被多名元宝网用户邀请，元宝网将认定第一位确定的邀请人为该用户邀请人。<br />\r\n3.禁止通过作弊手段进行虚假邀请注册骗取返利，一经发现将取消奖励并酌情处理。<br />\r\n<p>\r\n	<br />\r\n</p>', '1458973500', '0', '', '0');
INSERT INTO `yang_article` VALUES ('126', '125', '银行转账充值', '<img src=\"http://www.jubi.com/images/jubi/help/chongzhi8.png\" alt=\"充值流程8\" /> \r\n<p>\r\n	<span class=\"impo\">注：汇款人姓名默认为实名认证的姓名，仅允许使用本人的银行卡或支付宝进行转账充值。</span>\r\n</p>\r\n<img src=\"http://www.jubi.com/images/jubi/help/chongzhi9.png\" alt=\"充值流程9\" /> \r\n<p>\r\n	2. 页面弹出平台的收款账号及汇款信息，请根据“银行转账汇款”信息通过网银、去银行柜台或使用支付宝进行转账汇款。 <br />\r\n1）使用招商银行网银转账示例：\r\n</p>\r\n<img src=\"http://www.jubi.com/images/jubi/help/chongzhi10.png\" alt=\"充值流程10\" /> \r\n<p>\r\n	2）使用招商银行手机网银转账示例：\r\n</p>\r\n<img src=\"http://www.jubi.com/images/jubi/help/chongzhi11.png\" alt=\"充值流程11\" /> \r\n<p>\r\n	3）使用支付宝转账示例：\r\n</p>\r\n<img src=\"http://www.jubi.com/images/jubi/help/chongzhi12.png\" alt=\"充值流程12\" /> \r\n<p>\r\n	3. 银行转账成功后，聚币会在收到汇款后30分钟内入账。请及时刷新页面，并查看资产。\r\n                            若转账后超过30分钟仍未入账，请联系在线客服400-657-8880。\r\n</p>', '1458976342', '0', '', '0');
INSERT INTO `yang_article` VALUES ('121', '122', '邀请好友', '&lt;span&gt;邀请好友注册，可在活动期内每天获得被推荐人10%太一股配送收益。&lt;/span&gt;', '1458824052', '0', '', '0');
INSERT INTO `yang_article` VALUES ('122', '1', '春节百币网充值送大礼', '<span style=\"font-size:14px;\"> \r\n<p>\r\n	<span style=\"font-size:14px;\"> </span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<span style=\"font-size:18px;\">新会员在百币网注册，只要首次</span><span style=\"font-size:18px;\">充值</span><span style=\"font-size:18px;\">2000</span><span style=\"font-size:18px;\">元购买平台任何币种，都会获得新年礼物，（价值</span><span style=\"font-size:18px;\">498</span><span style=\"font-size:18px;\">元化妆品</span><span style=\"font-size:18px;\">5</span><span style=\"font-size:18px;\">件套礼盒）。 本活动</span><span style=\"font-size:18px;\">2017</span><span style=\"font-size:18px;\">年</span><span style=\"font-size:18px;\">1</span><span style=\"font-size:18px;\">月</span><span style=\"font-size:18px;\">12日至2月12日截止。</span>\r\n</p>\r\n</span> \r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<span style=\"font-size:14px;\"><span style=\"font-size:14px;\"><a href=\"https://yuanbaohui.com/news/detail/?id=852\" target=\"_blank\"><u></u><br />\r\n</a></span></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:14px;\"><span style=\"font-size:14px;\"><a href=\"https://yuanbaohui.com/news/detail/?id=852\" target=\"_blank\"><u></u><br />\r\n</a></span></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:14px;\"><span style=\"font-size:14px;\"><a href=\"https://yuanbaohui.com/news/detail/?id=852\" target=\"_blank\"><strong><br />\r\n</strong></a></span></span> \r\n</p>\r\n<p>\r\n	<span style=\"font-size:14px;\"></span><span style=\"font-size:14px;\"></span><span style=\"font-size:14px;\"><span style=\"font-size:14px;\"><strong></strong></span><strong></strong></span> \r\n</p>', '1484532873', '0', '', '0');
INSERT INTO `yang_article` VALUES ('123', '123', '买入指南', '<b><span style=\"font-weight:normal;\"></span></b><img src=\"http://www.jubi.com/images/jubi/help/trade2.png\" height=\"158\" width=\"1158\" />\r\n<p>\r\n	1.页面跳转到狗狗币交易页面，在买入栏中填入【买入价格】【买入数量】【交易密码】，点击【买入】。系统将自动成交挂单。\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/trade3.png\" />\r\n</p>\r\n<p>\r\n	<span class=\"impo\">注：买入数量应小于等于【最大可买】。<br />\r\n虚拟币买入数量不能小于0.1；成交金额不能少于1元。<br />\r\n不同虚拟币的买入手续费不同，请买入前仔细确认金额。 </span>\r\n</p>\r\n<p>\r\n	2.挂单成功后，可在【财务中心】-【委托管理】中查询挂单委托情况。\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/trade4.png\" />\r\n</p>\r\n<p>\r\n	<span class=\"impo\">注：若挂单价格与当前市价不吻合，可能导致挂单不能及时成交。可稍作等待，观察行情。若挂单仍未成交，可撤销（交易中心-我的委托挂单-撤销）挂单调整价格重新挂单。撤销后，未成交部分资产（如买币时冻结的资金、卖币时冻结的币）将原数返还到您的平台账户中。</span>\r\n</p>\r\n<p>\r\n	3. 也可在【财务中心】-【成交查询】中查询挂单成交情况。\r\n</p>\r\n<p>\r\n	<img src=\"http://www.jubi.com/images/jubi/help/trade5.png\" />\r\n</p>\r\n<p>\r\n	4.挂单成交后，请刷新页面，点击【财务中心】查看资产变化。 <br />\r\n如有挂单其他问题，可咨询在线客服400-657-8880。\r\n</p>', '1458960048', '0', '', '0');
INSERT INTO `yang_article` VALUES ('125', '2', '虚拟货币技术将彻底颠覆这三大行业', '<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<span>金融服务巨头瑞士信贷银行（Credit Suisse）发布了一篇全新的报告报告中分析了虚拟货币区块链对14个市场参与者的影响以及他们公司的股价表现。</span><span>报告中涉及的公司类型有大型交易所（澳大利亚证券交易所和纳斯达克）、辅助型企业（Computershare和Equiniti）以及金融服务商（Experian和摩根大通）。</span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<img width=\"auto\" src=\"http://img.mp.itc.cn/upload/20160907/e02847a302cf44c2ba55a2f46c3e5e90_th.jpeg\" />\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<span>报告共135页，详细描述了虚拟货币能颠覆的三个主要领域，分别是支付行业、资本市场、金融服务业。</span><span>虚拟货币的区块链技术的可塑性很高，可以适应不同的发展目标。虚拟货币区块链技术有3个主要特点——去中介、记录不可更改以及智能合约。这3大特点使虚拟货币区块链技术在传统系统面前更有优势。报告指出，受区虚拟货币块链技术影响最大的领域是金融服务业、交易所以及交易后处理。大部分受访企业并未因虚拟货币区块链的出现而产生危机感，反之，他们认为这项技术能带来长期的发展机遇。</span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<strong><span style=\"font-size:16px;\">支付领域</span></strong>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<span><span>瑞士信贷银行承认自己并不清楚虚拟货币区块链最终会用于哪一方面，但他们很确定虚拟货币区块链带来的改革是不可避免的。</span><span>支付行业参与者包括商业收单机构、发卡机构、金融支付处理器。虽然发展比较成熟，但虚拟货币和区块链必定能改革这一行业。</span></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<img width=\"525px\" src=\"http://img.mp.itc.cn/upload/20160907/b955882b40264e88a217a8a41eb6816a_th.jpeg\" />\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<strong><span style=\"font-size:16px;\">资本市场</span></strong>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<span><span>虚拟货币区块链对于资本市场参与者来说也是机遇大于风险。</span><span>报告详述了虚拟货币区块链对托管人、交易所、信托公司的影响，并指出其能创造全新的数据管理模式。最终，虚拟货币区块链能重塑资本市场架构，使其更加灵活、成本也更低。</span></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<img width=\"670px\" src=\"http://img.mp.itc.cn/upload/20160907/e98627169074413eaa752b5d92a4daa6_th.jpeg\" />\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<strong><span style=\"font-size:16px;\">金融服务业</span></strong>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<span><span>对于银行和金融服务业，分布式账簿或者多重的分布式账簿的影响力比较大。</span><span>报告介绍了金融服务业面临的两种市场机遇。第一，分布式账簿有利于降级证券交易和跨境支付的成本；第二，分布式账簿能提供更全面的客户信息，有利于提高客户服务。报告说，至少部分虚拟货币区块链用例在金融服务业是可行的。包括高盛集团、摩根大通、西班牙桑坦德银行等在内的多家企业都从不同角度分析了对虚拟货币区块链的兴趣。但其实际用例可能要到3年到5年之后才能成熟。</span></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<img width=\"auto\" src=\"http://img.mp.itc.cn/upload/20160907/01f16c5861d34340970e22d3922b086e_th.jpeg\" />\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<img width=\"auto\" src=\"http://img.mp.itc.cn/upload/20160907/66aaba670c364135abff522380620694_th.jpeg\" />\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:微软雅黑, 宋体;background-color:#FFFFFF;\">\r\n	<span>至少虚拟货币区块链技术的发展或多或少激发了银行对其潜在的IT架构的兴趣。这就意味着银行业的架构和利润分配将彻底改变。报告认为，大型银行或者金融机构更容易从虚拟货币区块链技术中获利。</span>\r\n</p>', '1483954832', '0', '', '0');
INSERT INTO `yang_article` VALUES ('332', '2', '数字货币行业不容忽视的热点事件', '<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	英国脱欧引起比特币暴涨\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	6月25日，英国成功脱欧。受此消息影响，火币网比特币价格从3480元人民币飙升至4748元人民币，暴涨36%。民众对欧洲经济的担忧可谓是最有影响力的因素。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<img src=\"http://img.mp.itc.cn/upload/20161013/b12084bf904c4ec998d82d9eb668ee43_th.png\" />\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<span><strong>自称中本聪的赖特申请数百种比特币专利</strong></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	克雷格·赖特（Craig Wright）虽然未能提供最终的证据来证实自己就是中本聪，但是他目前正在努力建立一个大型的比特币和区块链专利组合，正在申请一些价值数十亿美元的区块链专利。比特币社区则表示强烈的反对。随着区块链技术越来越受到重视，这种技术能够带来的潜在价值快速上升，自然就有人盯上了这个数十亿美元的专利产业。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<strong>蚂蚁金服：区块链技术将应用于支付宝</strong>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	7月9日下午，在杭州举办的首届全球XIN公益大会上，蚂蚁金服在“互联网公益”分论坛上表示区块链技术即将上线，并会首先应用于支付宝的爱心捐赠平台，目的是让每一笔款项的生命周期都记录在区块链上。由于区块链技术自身的优势特点，因此区块链技术被认为有助于解决整个中国社会的公益透明度和信任度问题。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<img src=\"http://img.mp.itc.cn/upload/20161013/81becedee12c4b5a9144f4cf69d402b0_th.jpeg\" />\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<span><strong>Bitfinex被盗12万比特币引发币价下跌25%</strong></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	8月3日凌晨，最大的美元比特币交易平台Bitfinex发出公告，由于网站出现安全漏洞，导致用户多达119,756BTC被盗，总价值约为7500万美元，随即暂时关闭比特币交易以及提现业务，从而引发全球市场的恐慌抛售。据国内最大的比特币交易平台火币网的数据显示，受此事件影响，当日比特币价格下跌25%，比特币价格最低点3005元人民币。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<img src=\"http://img.mp.itc.cn/upload/20161013/e0d2f0a1f0224ab28517c9e67b4e51b7_th.jpeg\" />\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<span><strong>四大聚首！会计业区块链标准来了？</strong></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	德勤、安永、毕马威、普华永道“四大”会计事务所的区块链代表于8月11日上午会师美国注册会计师协会，以讨论建立一个分布式账本联盟。本次会议，是由以太坊创业公司Consensys负责主持，但参与者可考虑各种可能的区块链解决方案。据Consensys的负责人格里芬安德森（Griffin Anderson）表示，本次会议的圆桌讨论将重点讨论会计行业问题，如何共同开发出一个新的区块链标准。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<span><strong>工信部曝光中国区块链技术产业发展两年规划时间表</strong></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	今年7月，工信部信软司印发了《关于组织开展区块链技术和应用发展趋势研究的函》（工信软函[2016]840号），并委托工信部电子标准院联合蚂蚁金服、万向控股、微众银行和平安保险等国内重点企业开展区块链技术和应用发展趋势研究工作。8月5号，为有效落实工信软函[2016]840号文的要求，工信部电子标准院在北京组织召开了区块链技术和产业发展论坛筹备会暨白皮书编写启动会，讨论并制定了中国区块链技术与产业发展论坛的未来两年规划。该规划指出，将以制定区块链技术的标准、编写相关区块链技术发展的白皮书为现阶段目标。同时，也提供了具体实施方案，如区块链技术的试点示范及相关市场培育、区块链技术专业人才的培养与认证、建设相关实验室、与区块链技术的相关的解决方案推广和开展区块链产业的投融资等具体实施的几大方面。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	俄罗斯迎来首家比特币交易所\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	自从俄罗斯官方爆出有意取消对比特币用户实施刑事处罚的消息之后，莫斯科迎来了本国首家数字货币交易所。目前，该交易所仅提供“单向”交易服务，即用户可以出售比特币，由交易所私下操作。而购买比特币的服务仍不可用。8月初，LocalBitcoins.com上的比特币购买量达到了历史新高。俄罗斯政府也表示将来会使用比特币区块链技术进行清算。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<span><strong>全球四大银行共同推出结算币，将成区块链与数字货币里程碑</strong></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	瑞银、德银、桑坦德和纽约梅隆四大银行已经联手开发新的电子货币，希望未来能够通过区块链技术来清算交易，并成为全球银行业通用的标准。四家银行还将与英国券商ICAP携手共同向各国央行推销该方案，并计划在2018年初进行首次商业应用。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	<span><strong>中国法定数字货币：数字货币必须由央行发行 其本身就是货币</strong></span>\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	央行也正在筹备中国人民银行数字货币研究所，姚前担任筹备组组长。筹备组将对中国法定数字货币有了一些研究成果，在《中国金融》上刊发。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	姚前对现行的数字货币理念进行梳理，提出了我国法定数字货币的7个系统设计理念。其中表达了对区块链技术的期待。区块链技术是下一代云计算的雏形，备受各方瞩目，但作为成熟的企业级应用案例尚不多见。“私有云+高性能数据库+移动终端”与“私有云+区块链+移动终端”，有可能是两个既关联又有区别的思路。让中央更强大，让数据更安全，使终端更智能，让个人的支付行为更能动，一定是未来央行数字货币追求的目标。如果将区块链技术应用于央行数字货币的研发，是否可以对其进行必要的改造?面对大规模交易的速度和效率问题，区块链技术自身如何实现实质性突破?\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	万达金融布局区块链核心技术，加快实施飞凡科技“实体＋互联网”战略\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	9月8日，万达金融集团正式宣布加入Linux基金会的超级账本（Hyperledger）项目，成为该项目第一个来自中国的核心董事会成员，这也标志着中国的金融科技企业已经全面加快区块链领域的布局。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	IOS10可在iMessage中转账比特币\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	iOS 10更新中，包括了一个全新设计的iMessage服务，开发者可以在这一平台上植入第三方app。目前已经添加了不少app，还附带音乐和GIF图片分享功能。某些第三方平台已经确认入驻，包括Square Cash、Lyft和Circle Pay，这就意味着iPhone用户可以直接在iMessage中转账比特币。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	对于如何使用iMessage转账比特币，火币网微信订阅号（huobicom）发表了一篇简单详细的教程。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	外媒：中国社保基金投资管理将采用区块链技术\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	据《中国日报欧洲版》报道，中国政府正考虑将区块链技术应用于社会保障金，如失业救济以及养老金。截至2015年年底，在全国社会保障基金理事会旗下管理的资金达到了2845亿美元，这与去年同期相比增长了24.6％。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	《中国日报欧洲版》的作者提到：“据全国社会保障基金理事会副理事长王忠民表示，毫无疑问区块链技术将应用于社会保障金系统，因其在社会保证金投资和管理当中具有价值应用。”\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	区块链创业公司R3CEV估值达2亿美元\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	纽约区块链创业公司R3CEV目前正磋商A轮融资。根据安永会计师事务所发布的一份独立报告显示，目前R3这家公司的预估值为2亿美元。截至目前，除了在纽约的总部，R3还在旧金山、伦敦、日本、苏黎世、新加坡、首尔和悉尼都设立了办事处。R3的联盟成员已扩至全球各地，该公司需要雇佣更多的人在特定的地方进行办公。而这种分布式的工作方式，意味着该集团无需在服务器、建筑或设施上进行大量的投资，也就是说该公司并不需要花费巨额的资金。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	区块链将驱动万向的2000亿元智慧城市计划\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	汽车零部件巨头万向集团9月27日宣布，该集团将使用区块链技术作为其新公布的智慧城市计划的一部分。在万向主办的全球区块链峰会上，该集团透露拟在未来7年内在这一项目中投入2000亿元（300亿美元），在杭州萧山毗邻钱塘江南岸购置83000000平方英尺的土地，用于建立一个新的创新基地。为了更好地支持这项工作，万向还寻求资助可能与其目标保持一致的区块链企业家。\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	美国司法机关再发力，纽约联邦法官裁定比特币是货币\r\n</p>\r\n<p style=\"font-size:14px;color:#333333;font-family:宋体;background-color:#FFFFFF;\">\r\n	佛罗里达州一家比特币交易所Coin.mx（现已关闭）和其前任运营者安东尼?穆尔吉（Anthony Murgio）于去年7月被指控多项洗钱罪名。近期他提出上诉，申请撤销其中两项指控，因为美国法律规定，比特币不属于“资金”。据路透社报道，纽约南部法院的艾莉森?南森（Alison Nathan）法官驳回了他的上诉请求，并写道，根据其用途，比特币的确属于货币范畴。\r\n</p>', '1483954901', '0', '', '0');
INSERT INTO `yang_article` VALUES ('32', '126', '新币上线申请', '<span>   若您是加密数字货币开发者或者官方团队成员，有意向将您开发的币种上线聚币网，欢迎您通过以下方式和流程递交申请。</span> \r\n<p>\r\n	申请流程：\r\n</p>\r\n<ol>\r\n	<li>\r\n		下载 <a href=\"http://www.jubi.com/newcoin-sheet.docx\" target=\"_blank\">“新币上线申请表”</a>，按要求填写并发送至邮箱market@jubi.com。\r\n	</li>\r\n	<li>\r\n		若申请资料填写齐全并符合聚币上线条件，我们将和申请人或官方团队联系并确定币种上线可行性。\r\n	</li>\r\n	<li>\r\n		确定上线币种后，聚币会安排技术进行钱包、交易相关开发，我们将至少提前1天通知上线新币 。\r\n	</li>\r\n</ol>\r\n<p>\r\n	另：若在聚币已上线的币种出现下列情况之一，聚币将考虑下线该币种。\r\n</p>\r\n<ol>\r\n	<li>\r\n		官方团队解散或者不再有正常维护更新，导致该币种不能进行正常挖矿、转币、区块查询等；\r\n	</li>\r\n	<li>\r\n		该币种已经没有用户进行交易或者使用、持有；\r\n	</li>\r\n	<li>\r\n		该币种出现重大技术故障，影响到区块查询、挖矿转币等正常功能；\r\n	</li>\r\n	<li>\r\n		官方团队涉嫌恶意预挖、传销诈骗、坐庄圈钱等欺诈行为。\r\n	</li>\r\n</ol>\r\n<p>\r\n	<a>为了维护广大用户的财产安全我们将尽量避免币种下线。</a>\r\n</p>', '1459152106', '0', '', '0');
INSERT INTO `yang_article` VALUES ('128', '63', '客服电话', '<a href=\"mailto:bncwlkj@163.com\"><span style=\"font-size:24px;color:#333333;\">400-9665-100</span></a>', '1484036226', '0', '', '0');
INSERT INTO `yang_article` VALUES ('129', '62', '客服电话', '<a href=\"mailto:bncwlkj@163.com\"><span style=\"color:#333333;font-size:24px;\">400-9665-100</span></a>', '1484036235', '0', '', '0');
INSERT INTO `yang_article` VALUES ('339', '3', '韩国将发行首个基于区块链技术的数字货币“BOScoin”', '韩国区块链初创公司BlockchainOS已经宣布，即将正式发布数字货币BOScoin，据描述，它将成为现有如比特币和以太币等加密货币的升级版本。<br />\r\n<br />\r\nBOScoin将在2017年2月发布，它将成为韩国的第一个基于区块链系统的数字货币。<br />\r\n<br />\r\nBlockchainOS表示他已经修复了现有数字货币的局限之处。它宣称可以加速交易速度，达到跟信用卡业务流程一样的水平，让BOScoin可以每秒处理1000的交易量。<br />\r\n<br />\r\n据报道，BOScoin如今即将面对国内投资者进行预售，而且将会于2017年在全球市场进行测试。另外，该初创公司将会发布应用，以促进BOScoin作为实际货币的使用。<br />\r\n<br />\r\n<br />\r\n<br />\r\nBlockchain OS的CEO Park Chang-ki讲到：“随着BOScoin的发布，韩国将可以挑战世界的加密货币市场，并得到认可。韩国国内的区块链技术也已经达到了相当的水平。”<br />\r\n<div>\r\n	<br />\r\n</div>', '1484016803', '0', '', '0');
INSERT INTO `yang_article` VALUES ('342', '5', '明星币资料', '<br />\r\n一、明星币参数<br />\r\n    中文名：明星币<br />\r\n    英文名：MXCOIN<br />\r\n    英文简称：MXI<br />\r\n    开发团队：明星币开发团队<br />\r\n    钱包发布日期：2016年5月8日<br />\r\n    货币总量：10亿<br />\r\n    P2P端口：51938 RPC端口：51937<br />\r\n    核心算法：scrypt<br />\r\n    成熟：100 交易确认：3<br />\r\n    超级节点：120.26.76.127<br />\r\n    区块查询：http://121.42.33.6 <br />\r\n<br />\r\n二、明星币特点介绍<br />\r\n    1、有管理的货币“去中心化、去信任化”为标志<br />\r\n    2、交易速度快,确认时间平均为1-3分钟<br />\r\n    3、超快链接节点，支付转账速度更快，更新区块更加时及时<br />\r\n    4、采用6位哈希算法，使用生日攻击寻找groestl哈希碰撞   <br />\r\n    5、源于区块链技术发展的去中心化数字货币<br />\r\n    6、区块链技术是“自由、开放、合作、共享”<br />\r\n    7、采用“分布式记账”对交易结果进行数字签名<br />\r\n <br />\r\n三、明星币（MXI）总量分配<br />\r\n    1、总量10亿<br />\r\n    2、按定价0.01元/个(认购30%)<br />\r\n    3、10%用于钱包维护<br />\r\n    4、10%用于数字货币群推广活动等各大活动赞助项目基金<br />\r\n    5、10%用于市场与公关<br />\r\n    6、10%计划用于元宝网用户众筹<br />\r\n    7、剩余30%的币，承诺冻结1年 冻结地址： <br />\r\n           mZXvcN7bW2GmBoxNJWbsU7jR4joQKVFxAX（22.5%）<br />\r\n           mP6SzWBHT9h3LmX3LTR62VthzRk52ciweT （7.5%）<br />\r\n<br />\r\n四、明星币详细参数<br />\r\n    官网：www.mingxingcoin.com<br />\r\n<br />\r\n<br />\r\n明星币QQ群：<br />\r\n    明星币中国总群： 540394822（已满群）<br />\r\n    明星币中国1群 ：540570706（已满群）<br />\r\n    明星币中国2群： 518363583<br />', '1484202074', '0', '', '0');
INSERT INTO `yang_article` VALUES ('336', '2', '国务院：区块链列入国务院“十三五”国家信息化规划', '经李克强总理签批，国务院日前印发了《“十三五”国家信息化规划》，《规划》中提到，到2020年，“数字中国”建设取得显著成效，信息化能力跻身国际前列，其中区块链技术首次被列入了《国家信息化规划》。<br />\r\n<br />\r\n<br />\r\n<br />\r\n《规划》中写道：<br />\r\n<br />\r\n“十三五”时期，全球信息化发展面临的环境、条件和内涵正发生深刻变化。从国际看，世界经济在深度调整中曲折复苏、增长乏力，全球贸易持续低迷，劳动人口数量增长放缓，资源环境约束日益趋紧，局部地区地缘博弈更加激烈，全球性问题和挑战不断增加，人类社会对信息化发展的迫切需求达到前所未有的程度。同时，全球信息化进入全面渗透、跨界融合、加速创新、引领发展的新阶段。信息技术创新代际周期大幅缩短，创新活力、集聚效应和应用潜能裂变式释放，更快速度、更广范围、更深程度地引发新一轮科技革命和产业变革。物联网、云计算、大数据、人工智能、机器深度学习、区块链、生物基因工程等新技术驱动网络空间从人人互联向万物互联演进，数字化、网络化、智能化服务将无处不在。现实世界和数字世界日益交汇融合，全球治理体系面临深刻变革。全球经济体普遍把加快信息技术创新、最大程度释放数字红利，作为应对“后金融危机”时代增长不稳定性和不确定性、深化结构性改革和推动可持续发展的关键引擎。<br />\r\n<br />\r\n从国内看，我国经济发展进入新常态，正处于速度换挡、结构优化、动力转换的关键节点，面临传统要素优势减弱和国际竞争加剧双重压力，面临稳增长、促改革、调结构、惠民生、防风险等多重挑战，面临全球新一轮科技产业革命与我国经济转型、产业升级的历史交汇，亟需发挥信息化覆盖面广、渗透性强、带动作用明显的优势，推进供给侧结构性改革，培育发展新动能，构筑国际竞争新优势。从供给侧看，推动信息化与实体经济深度融合，有利于提高全要素生产率，提高供给质量和效率，更好地满足人民群众日益增长、不断升级和个性化的需求；从需求侧看，推动互联网与经济社会深度融合，创新数据驱动型的生产和消费模式，有利于促进消费者深度参与，不断激发新的需求。<br />\r\n<br />\r\n同时，我国信息化发展还存在一些突出短板，主要是：技术产业生态系统不完善，自主创新能力不强，核心技术受制于人成为最大软肋和隐患；互联网普及速度放缓，贫困地区和农村地区信息基础设施建设滞后，针对留守儿童、残障人士等特殊人群的信息服务供给薄弱，数字鸿沟有扩大风险；信息资源开发利用和公共数据开放共享水平不高，政务服务创新不能满足国家治理体系和治理能力现代化的需求；制约数字红利释放的体制机制障碍仍然存在，与先进信息生产力相适应的法律法规和监管制度还不健全；网络安全技术、产业发展滞后，网络安全制度有待进一步完善，一些地方和部门网络安全风险意识淡薄，网络空间安全面临严峻挑战。<br />\r\n<br />\r\n综合研判，“十三五”时期是信息化引领全面创新、构筑国家竞争新优势的重要战略机遇期，是我国从网络大国迈向网络强国、成长为全球互联网引领者的关键窗口期，是信息技术从跟跑并跑到并跑领跑、抢占战略制高点的激烈竞逐期，也是信息化与经济社会深度融合、新旧动能充分释放的协同迸发期。必须认清形势，树立全球视野，保持战略定力，增强忧患意识，加强统筹谋划，着力补齐短板，主动顺应和引领新一轮信息革命浪潮，务求在未来五到十年取得重大突破、重大进展和重大成果，在新的历史起点上开创信息化发展新局面。<br />\r\n<br />\r\n……<br />\r\n<br />\r\n（一）构建现代信息技术和产业生态体系。<br />\r\n<br />\r\n打造自主先进的技术体系。制定网络强国战略工程实施纲要，以系统思维构建新一代网络技术体系、云计算体系、安全技术体系以及高端制造装备技术体系，协同攻关高端芯片、核心器件、光通信器件、操作系统、数据库系统、关键网络设备、高端服务器、安全防护产品等关键软硬件设备，建设战略清晰、技术先进、产业领先、安全可靠的网络强国。统筹经济、政治、文化、社会、生态文明等领域网络安全和信息化发展，增强自主创新能力。<br />\r\n<br />\r\n强化战略性前沿技术超前布局。立足国情，面向世界科技前沿、国家重大需求和国民经济主要领域，坚持战略导向、前沿导向和安全导向，重点突破信息化领域基础技术、通用技术以及非对称技术，超前布局前沿技术、颠覆性技术。加强量子通信、未来网络、类脑计算、人工智能、全息显示、虚拟现实、大数据认知分析、新型非易失性存储、无人驾驶交通工具、区块链、基因编辑等新技术基础研发和前沿布局，构筑新赛场先发主导优势。加快构建智能穿戴设备、高级机器人、智能汽车等新兴智能终端产业体系和政策环境。鼓励企业开展基础性前沿性创新研究。<br />\r\n<div>\r\n	<br />\r\n</div>', '1484016403', '0', '', '0');
INSERT INTO `yang_article` VALUES ('337', '2', '外媒：印度斥资5000万美元鼓励民众使用数字货币', '北京时间12月16日晚间消息，据CNNMoney网站报道，印度正陷入自己制造的现金危机。在此背景下印度政府计划拿出5000万美元鼓励人们使用数字货币的表态再次引发关注。<br />\r\n200万名印度人将从这一新的临时彩票活动中获益，而参与的条件是将自己的ID与政府的电子支付系统联接起来。<br />\r\n<br />\r\n使用该系统的人将获得一个数字，而该数字将会被输入抽奖系统，每天会抽取1.5万名幸运者，他们每人将获得1000卢比（约合15美元）。每周还会抽取7000多名幸运者，他们将获得5000至10万卢比。<br />\r\n<br />\r\n使用该电子支付系统的零售商也也有机会获得奖励，每周将抽取7000名零售商，他们将获得2500卢比到5万卢比的奖励。<br />\r\n<br />\r\n该彩票活动是印度政府给予民众的一份圣诞节礼物，将于12月25日起开始举行，持续至2017年4月14日。 在活动的最后一天，印度政府还将抽取6份大奖，奖金从1.7万美元至头奖150万美元。<br />\r\n<br />\r\n印度总理莫迪11月8日突然废除了印度当前面额最大的两种货币，设法解决腐败和逃税问题。<br />\r\n<br />\r\n但废除现有500卢比和1000卢比，并将其替换为新的500卢比和2000卢比的举措，已经使印度86%的货币（按金额计）突然变得没有价值，并引发了经济混乱。<br />\r\n<br />\r\n现金在印度的经济运行中发挥着重要作用，新纸币的分放工作并不顺利，印度百姓因此在日常采购中面临着诸多麻烦。汽车和钻石等使用大额纸币的交易规模大幅下降。<br />\r\n<br />\r\n负责管理印度政府智库的Amitabh Kant表示：“目前在印度只有5%的个人消费支出使用数字货币。我们的目标是数字支付在印度得到迅速普及。”<br />\r\n<br />\r\n自从印度宣布废除大额货币以来，无现金支付公司和手机钱包获得了蓬勃发展，印度政府也正试图鼓励人们使用自己的电子支付系统。（翊海）<br />\r\n<div>\r\n	<br />\r\n</div>', '1484016495', '0', '', '0');
INSERT INTO `yang_article` VALUES ('338', '2', '金融技术大爆炸：中国正进入无现金时代，消费者弃银行', '年收入超过250亿美元的“全球审计四巨头之一”毕马威（KPMG）最近发布了Fintech100强榜名单，列出了全球金融技术市场中最大最赚钱的企业。<br />\r\n<br />\r\n中国蚂蚁金服和趣店分别榜上有名，分别位居第一和第二。而陆金所、众安保险和京东金融也都位居前十，中国企业在fintech100前十中占据了一半。<br />\r\n<br />\r\n数亿中国用户正在放弃银行系统<br />\r\n<br />\r\n正如各种研究调查结果显示的那样，如KPMG的Fintech 100和ITA的全球金融技术报告，中国正在引领全球金融技术行业的发展。数亿中国用户正在抛弃受到限制且有限的银行系统，并转向非银行服务和金融技术平台。<br />\r\n<br />\r\n事实上，根据《亚洲时报》记者Johan Nylander和深圳星巴克经理Lily Li所说，大量的中国消费者都是使用手机来结算日常费用和支出。很少有人使用现金甚至信用卡，因为那样会很不方便且手续费高。<br />\r\n<br />\r\n当购买一些简单的商品时，如从星巴克购买一杯拿铁，中国消费者只需要把手机靠近商店的NFC感应器就可以使用手机金融技术应用来进行交易。据报道，蚂蚁金服旗下的支付宝目前在中国整个在线支付行业占据的份额达到58%。<br />\r\n<br />\r\n快速发展的金融技术行业<br />\r\n<br />\r\n蚂蚁金服目前估值达到600亿美元，比全球数字货币比特币的市值还要高。<br />\r\n<br />\r\nMoney Bazaar创始人兼CEO Mofei Chen在采访中表示外国的投资者和用户几乎没有意识到中国金融服务行业的快速发展。Chen解释说，中国金融技术已经发展到人们甚至记不起上次是啥时候用的钱包。<br />\r\n<br />\r\n他说：“我几乎已经忘记上次使用钱包是什么时候的事了。外国人几乎没有意识到新的支付功能和手机金融服务在中国发展的是多么的迅速和先进。”<br />\r\n<br />\r\n选股服务ＭJL运营者Allen Yu的观点与Chen相似，他认为中国民众很少使用银行账户。有些公司甚至薪水都是通过支付宝来发放的。<br />\r\n<br />\r\n“我几乎已经不经常用银行账户了。我的薪水是通过支付宝发放，而大多数支付也使用支付宝来完成。并且支付宝的利率也要比普通的银行要好。”<br />\r\n<br />\r\n区块链迎上中国金融技术发展良机<br />\r\n<br />\r\n金融技术在中国的爆发式发展，正在推动中国支付行业模式的转变。新的金融技术正在不断涌现，而一直被认为会颠覆整个金融行业的区块链技术正在推动金融技术和支付领域的发展，如蚂蚁金服就已经表示他们的区块链技术即将上线，并且首先用于支付宝。京东金融也正与IBM和斯坦福大学合作探索区块链技术。毫无疑问，中国金融技术的蓬勃发展为区块链带来了发展良机。<br />', '1484016658', '0', '', '0');
INSERT INTO `yang_article` VALUES ('325', '7', '公司简介', '<p>\r\n	<span style=\"font-size:18px;\">      成都百年春网络科技股份有限公司，是在信息化时代，互联网深刻地影响和改变企业命运的大趋势下应运而生的一家专业的网络商城及虚拟货币交易平台，为商城会员提供物美价廉，实用性强，特色性产品。为代理商提供产品交易平台。为虚拟货币玩家提供交易服务。公司本着公平，公正的原则为会员提供满意的服务。</span>\r\n</p>\r\n<p>\r\n	<br />\r\n</p>', '1484533275', '0', '', '0');
INSERT INTO `yang_article` VALUES ('326', '7', '法律声明', '<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n	<span style=\"line-height:1.5;\"> </span> \r\n</p>\r\n<h1 style=\"margin-left:0.0000pt;text-indent:0.0000pt;\">\r\n	<br />\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		第一条\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		本网站的宗旨是在不违反中华人民共和国法律法规的前提下，尽可能地为广大数字资产爱好者及投资者提供专业的国际化水准的交易平台。禁止使用本网站从事洗钱、走私、商业贿赂等一切非法交易活动，若发现此类事件，本站将冻结账户，立即报送公安机关。\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		第二条\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		当公安机关、检察院、法院等有权机关出示相应的调查文件要求本站配合对指定用户进行调查时，或对用户账户采取查封、冻结或者划转措施时，本站将按照公安机关的要求协助提供相应的用户数据，或进行相应的操作。因此而造成的用户隐私泄露、账户不能操作及因此给所造成的损失等，本站不承担任何责任。\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		第三条\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		<span> 本网站使用者因为违反本声明的规定而触犯中华人民共和国法律的，本站做为服务的提供方，有义务对平台的规则及服务进行完善，</span> <span>但本站并无触犯中华人民共和国法律的动机和事实，对使用者的行为不承担任何连带责任。</span>\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		第四条\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		凡以任何方式登陆本网站或直接、间接使用本网站服务者，视为自愿接受本网站声明的约束。\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		第五条\r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:22.5000pt;\">\r\n		<span> 本声明未涉及的问题参见中华人民共和国有关法律法规，当本声明与中华人民共和国法律法规冲突时，以中华人民共和国法律法规为准。</span> <span>本网站使用者因为违反本声明的规定而触犯中华人民共和国法律的，一切后果自己负责，本网站不承担任何责任</span>。\r\n	</p>\r\n</h1>\r\n<p>\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:22.5000pt;text-indent:21.0000pt;\">\r\n	<span></span><span></span> \r\n</p>', '1483856175', '0', '', '0');
INSERT INTO `yang_article` VALUES ('327', '7', '免责声明', '<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span> </span> \r\n</p>\r\n<h1 style=\"margin-left:0pt;text-indent:0pt;\">\r\n	<b>\r\n	<p class=\"p\" style=\"margin-left:0pt;text-indent:0pt;background:#FFFFFF;\">\r\n		第一条\r\n	</p>\r\n</b>\r\n</h1>\r\n<h1 style=\"margin-left:0.0000pt;text-indent:0.0000pt;\">\r\n	<b></b>\r\n</h1>\r\n<h1 style=\"margin-left:0.0000pt;text-indent:0.0000pt;\">\r\n	<b> \r\n	<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n		<span> 请报名者详细阅读本申明，一经参与者报名确认即视为完全理解并同意本申明所载内容。</span> \r\n	</p>\r\n	<p class=\"MsoNormal\">\r\n		<b></b> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0pt;text-indent:0pt;background:#FFFFFF;\">\r\n		<span>第二条</span> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n		<span> 本次活动采取自愿参加、风险自担、责任自负、费用自理的原则，参与者应为年满</span>18周岁、具有我国法律规定的完全民事行为能力的自然人，并自愿接受和遵守活动发布内容中的规则和事项。\r\n	</p>\r\n	<p class=\"MsoNormal\">\r\n		<b></b> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0pt;text-indent:0pt;background:#FFFFFF;\">\r\n		<span>第三条</span> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n		<span> </span> <span>活动参与者应遵守国家相关法律、法规及赛事主办方的相关规定，应符合积极向上的社会道德风尚。一切因活动参与者直接或间接引起的法律责任由参与者自行承担。</span> \r\n	</p>\r\n	<p class=\"MsoNormal\">\r\n		<b></b> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0pt;text-indent:0pt;background:#FFFFFF;\">\r\n		<span>第四条</span> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n		<span> 本次活动的报名接受方</span><span>百</span><span>币</span><span>网交易</span><span>平台仅作为报名接口的提供者为参与者提供报名便利，不参与和提供本次活动的其他任何服务。参与者因活动过程中与第三方的纠纷，或因对活动组织者、主办方、活动规则或裁判判罚存在异议的，由参与者与相关方进行协商解决。上述纠纷和争议概与</span><span>百</span><span>币</span><span>网交易</span><span>平台无关，</span><span>百</span><span>币</span><span>网交易</span><span>平台对此亦不承担任何法律责任。</span> \r\n	</p>\r\n	<p class=\"MsoNormal\">\r\n		<b></b> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0pt;text-indent:0pt;background:#FFFFFF;\">\r\n		<span>第五条</span> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n		<span> 活动参与者已确认自己有充分的身体、心理和物质上的准备而参加活动，对活动中的一切风险及导致的各种后果均可自我承担，并承诺在活动中发生的一切有关自己人身、财产和精神的损失均不会向报名接受方、活动组织者、活动主办方或协会追究法律上的责任。</span> \r\n	</p>\r\n	<p class=\"MsoNormal\">\r\n		<b></b> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0pt;text-indent:0pt;background:#FFFFFF;\">\r\n		<span>第六条</span> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n		<span> 活动组织者在发布活动内容中的一切说明和安排均为设想，不排除在活动报名开始后因受人为或自然不可抗力因素影响导致的对原计划的变更和取消。活动组织者仅保证在取消活动或变更相关内容前，尽可能通知到报名参与者，并说明原因，但不负责承担由此给参与者造成的人身、财产和精神上的损失。</span> \r\n	</p>\r\n	<p class=\"MsoNormal\">\r\n		<b></b> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0pt;text-indent:0pt;background:#FFFFFF;\">\r\n		<span>第七条</span> \r\n	</p>\r\n	<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n		<span> 如恶意侵犯他人权利或有其它涉及犯罪行为，则不在此免责范围内，必须承担相应的法律责任。</span> \r\n	</p>\r\n</b> \r\n</h1>\r\n<p>\r\n	<br />\r\n</p>', '1483855019', '0', '', '0');
INSERT INTO `yang_article` VALUES ('328', '7', '注册协议', '<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1. 用户点击<span>百</span><span>币网注册页面的同意注册按钮并完成注册程序、获得</span><span>百</span><span>币网账号和密码时，视为用户与</span><span>百</span><span>币网已达成《用户协议》，就用户进入</span><span>百</span><span>币网使用</span><span>百</span><span>币网相应的交易服务达成本协议的全部约定。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2. <span>百</span><span>币网及用户均已认真阅读本《用户协议》中全部条款及</span><span>百</span><span>币网发布的法律声明和操作规则的内容，对本协议及前述服务条款、法律声明和操作规则均已知晓、理解并接受，同意将其作为确定双方权利义务的依据。</span><span>百</span><span>币网《法律声明》为本协议的必要组成部分，本协议内容包括本协议正文以及</span><span>百</span><span>币网已经发布的或将来可能发布的各类规则、声明、说明。所有规则、声明、说明为协议不可分割的一部分，与协议正文具有同等法律效力。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3. 本协议不涉及<span>百</span><span>币网用户与其他用户之间因虚拟币交易而产生的法律关系及法律纠纷。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>一、定义条款</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.“虚拟币”指高科技中代替实体货币流通的信息流或数据流（包括但不局限于BTC、LTC、YBC等)。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.“<span>百</span><span>币网</span>”<span>成都百年春网络</span><span>科技</span><span>股份</span><span>有限公司运营和管理的虚拟币交易平台，域名为</span>www.100bi.com, <span>成都百年春网络</span><span>科技</span><span>股份</span><span>有限公司</span><span>通过该网络交易平台为虚拟币玩家提供进行虚拟币的网络交易服务。本协议下文中</span>“<span>百</span><span>币网</span>”既指网络交易平台<span>。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.“用户”接受并同意本协议全部条款及<span>百</span><span>币网不时发布和更新的法律条款和操作规则、通过</span><span>百</span><span>币网进行虚拟币交易的</span><span>百</span><span>币网注册会员。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4.“用户注册”用户注册是指用户登录<span>百</span><span>币网，并按要求填写相关信息并确认同意履行相关用户协议的过程。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	5.“虚拟币交易”用户通过<span>百</span><span>币网进行的虚拟币交易活动。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	6.“充值款”用户为购买虚拟币/出售虚拟币而向<span>百</span><span>币网平台预充入的法币</span>/虚拟币的款项。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	7.“手续费”用户在<span>百</span><span>币网达成虚拟币交易而向</span><span>百</span><span>币网支付的交易服务费用。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>二、用户注册</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>1.注册资格</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户承诺：用户具有完全民事权利能力和行为能力，或虽不具有完全民事权利能力和行为能力</span>,但点击同意注册按钮，本网即视为经其法定代理人同意并由其法定代理人代理注册及应用<span>百</span><span>币网服务。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>2.注册目的</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户承诺：用户进行用户注册并非出于违反法律法规或破坏</span><span>百</span><span>币网虚拟币交易秩序的目的。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>3.注册流程</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.1.用户同意根据<span>百</span><span>币网用户注册页面的要求提供有效电子邮箱等信息，设置</span><span>百</span><span>币网账号及密码，用户应确保所提供全部信息的真实性、完整性和准确性。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.2.用户合法、完整并有效提供注册所需信息的，有权获得<span>百</span><span>币网账号和密码，</span><span>百</span><span>币网账号和密码用于用户在</span><span>百</span><span>币网进行会员登录。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.3.用户获得<span>百</span><span>币网账号及密码时视为用户注册成功，用户同意接收</span><span>百</span><span>币网发送的与</span><span>百</span><span>币网网站管理、运营相关的电子邮件和</span>/或短消息。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.4 用户注册成功后进行虚拟币交易，应当提供本人的真实身份证号码，进行实名认证。\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>三、用户服务</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>百</span><span>币网为用户通过</span><span>百</span><span>币网进行虚拟币交易活动提供网络交易平台服务。</span><span>百</span><span>币网不作为买家或是卖家参与买卖虚拟币行为本身。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>1.服务内容</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.1.用户有权在<span>百</span><span>币网浏览虚拟币实时行情及交易信息、有权通过</span><span>百</span><span>币网提交虚拟币交易指令并完成虚拟币交易。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.2.用户有权在<span>百</span><span>币网查看其</span><span>百</span><span>币网会员账号下的信息，有权应用</span><span>百</span><span>币网提供的功能进行操作。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.3.用户有权按照<span>百</span><span>币网发布的活动规则参与</span><span>百</span><span>币网组织的网站活动。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.4.<span>百</span><span>币网承诺为用户提供的其他服务。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>2.服务规则</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户承诺遵守下列</span><span>百</span><span>币网服务规则：</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.1.用户应当遵守法律法规、规章、规范性文件及政策要求的规定，保证账户中所有资金和虚拟币来源的合法性，不得在<span>百</span><span>币网或利用</span><span>百</span><span>币网服务从事非法或其他损害</span><span>百</span><span>币网或第三方权益的活动，如发送或接收任何违法、违规、违反公序良俗、侵犯他人权益的信息，发送或接收传销材料或存在其他危害的信息或言论，未经</span><span>百</span><span>币网授权使用或伪造</span><span>百</span><span>币网电子邮件题头信息等。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.2.用户应当遵守法律法规应当妥善使用和保管其<span>百</span><span>币网账号及密码、资金密码、和其注册时绑定的手机号码、以及手机接收的手机验证码的安全。用户对使用其</span><span>百</span><span>币网账号和密码、资金密码、手机验证码进行的任何操作和后果承担全部责任。当用户发现</span><span>百</span><span>币网账号、密码、或资金密码、验证码被未经其授权的第三方使用，或存在其他账号安全问题时，应立即有效通知</span><span>百</span><span>币网，要求</span><span>百</span><span>币网暂停该</span><span>百</span><span>币网账号的服务。</span><span>百</span><span>币网有权在合理时间内对用户的该等请求采取行动，但对</span><span>百</span><span>币网采取行动前用户已经遭受的损失不承担任何责任。用户在未经</span><span>百</span><span>币网同意的情况下不得将聚币网账号以赠与、借用、租用、转让或其他方式处分给他人。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.3.用户应当遵守<span>百</span><span>币网不时发布和更新的用户协议以及其他服务条款和操作规则。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>四、虚拟币交易规则</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户承诺在其进入</span><span>百</span><span>币网交易，通过</span><span>百</span><span>币网与其他用户进行虚拟币交易的过程中良好遵守如下</span><span>百</span><span>币网虚拟币交易规则。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>1.浏览交易信息</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户在</span><span>百</span><span>币网</span><span>浏览虚拟币交易信息时，应当仔细阅读交易信息中包含的全部内容，包括但不限于虚拟币价格、委托量、手续费、买入或卖出方向，用户完全接受交易信息中包含的全部内容后方可点击按钮进行交易。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>2.提交委托</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>在浏览完交易信息确认无误之后用户可以提交交易委托。用户提交交易委托后，即用户授权</span><span>百</span><span>币网</span><span>代理用户进行相应的交易撮合，</span><span>百</span><span>币网</span><span>在有满足用户委托价格的交易时将会自动完成撮合交易而无需提前通知用户。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>3 查看交易明细</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户可以通过管理中心的交易明细中查看相应的成交记录，确认自己的详情交易记录。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>4 撤销/修改委托</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>在委托未达成交易之前，用户有权随时撤销或修改委托。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>五、用户的权利和义务</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1用户有权按照本协议约定接受<span>百</span><span>币网</span><span>提供的虚拟币交易平台服务。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2 用户有权随时终止使用<span>百</span><span>币网</span><span>服务。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3 用户有权随时提取在<span>百</span><span>币网</span><span>的资金余额，包括人民币以及虚拟币，但需向</span><span>百</span><span>币网</span><span>支付相应的提现手续费用。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4 用户对注册时提供的个人资料的真实性、有效性及安全性负责。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	5用户在<span>百</span><span>币网</span><span>进行虚拟币交易时不得恶意干扰虚拟币交易的正常进行、破坏交易秩序。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	6用户不得以任何技术手段或其他方式干扰<span>百</span><span>币网</span><span>的正常运行或干扰其他用户对</span><span>百</span><span>币网</span><span>服务的使用。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	7如用户因网上交易与其他用户产生诉讼的，不得通过司法或行政以外的途径要求<span>百</span><span>币网</span><span>提供相关资料。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	8 用户不得以虚构事实等方式恶意诋毁<span>百</span><span>币网的商誉。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>六、</span></b><b><span>百</span></b><b><span>币网</span></b><b><span>的权利和义务</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1如用户不具备本协议约定的注册资格，则<span>百</span><span>币网</span><span>有权拒绝用户进行注册，对已注册的用户有权注销其</span><span>百</span><span>币网</span><span>会员账号，</span><span>百</span><span>币网</span><span>因此而遭受损失的有权向前述用户或其法定代理人主张赔偿。同时，</span><span>百</span><span>币网</span><span>保留其他任何情况下决定是否接受用户注册的权利。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2<span>百</span><span>币网</span><span>发现账户使用者并非账户初始注册人时，有权中止该账户的使用。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3<span>百</span><span>币网</span><span>通过技术检测、人工抽检等检测方式合理怀疑用户提供的信息错误、不实、失效或不完整时，有权通知用户更正、更新信息或中止、终止为其提供</span><span>百</span><span>币网</span><span>服务。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4<span>百</span><span>币网</span><span>有权在发现</span><span>百</span><span>币网</span><span>上显示的任何信息存在明显错误时，对信息予以更正。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	5<span>百</span><span>币网</span><span>保留随时修改、中止或终止</span><span>百</span><span>币网</span><span>服务的权利，</span><span>百</span><span>币网</span><span>行使修改或中止服务的权利不需事先告知用户，</span><span>百</span><span>币网终止</span><span>百</span><span>币网</span><span>一项或多项服务的，终止自</span><span>百</span><span>币网</span><span>在网站上发布终止公告之日生效。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	6<span>百</span><span>币网</span><span>应当采取必要的技术手段和管理措施保障</span><span>百</span><span>币网</span><span>的正常运行，并提供必要、可靠的交易环境和交易服务，维护虚拟币交易秩序。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	7如用户连续一年未使用<span>百</span><span>币网</span><span>会员账号和密码登录</span><span>百</span><span>币网</span><span>，则</span><span>百</span><span>币网</span><span>有权注销用户的</span><span>百</span><span>币网</span><span>账号。账号注销后，</span><span>百</span><span>币网</span><span>有权将相应的会员名开放给其他用户注册使用。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	8<span>百</span><span>币网</span><span>通过加强技术投入、提升安全防范等措施保障用户的人民币资金及虚拟币托管安全，有义务在用户资金出现可以预见的安全风险时提前通知用户。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	9<span>百</span><span>币网</span><span>有权在本协议履行期间及本协议终止后保留用户的注册信息及用户应用</span><span>百</span><span>币网</span><span>服务期间的全部交易信息，但不得非法使用该等信息。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>七、特别声明</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	    <span>在法律允许的范围内，不论在何种情况下，</span><span>百</span><span>币网</span><span>对由于信息网络设备维护、信息网络连接故障、电脑、通讯或其他系统的故障、电力故障、罢工、劳动争议、暴乱、起义、骚乱、生产力或生产资料不足、火灾、洪水、风暴、爆炸、战争、政府行为、司法行政机关的命令、其他不可抗力或第三方的不作为而造成的不能服务或延迟服务，以及用户因此而遭受的损失不承担责任。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>八、知识产权</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1<span>百</span><span>币网</span><span>所包含的全部智力成果包括但不限于网站标志、数据库、网站设计、文字和图表、软件、照片、录像、音乐、声音及其前述组合，软件编译、相关源代码和软件</span> (包括小应用程序和脚本) 的知识产权权利均归<span>百</span><span>币网所有。用户不得为商业目的复制、更改、拷贝、发送或使用前述任何材料或内容。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2<span>百</span><span>币网</span><span>名称中包含的所有权利</span> (包括但不限于商誉和商标、标志) 均归<span>成都百年春网络</span><span>科技</span><span>股份</span><span>有限公司所有。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3用户接受本协议即视为用户主动将其在<span>百</span><span>币网</span><span>发表的任何形式的信息的著作权，包括但不限于：复制权、发行权、出租权、展览权、表演权、放映权、广播权、信息网络传播权、摄制权、改编权、翻译权、汇编权以及应当由著作权人享有的其他可转让权利无偿独家转让给</span><span>百</span><span>币网</span><span>所有，</span><span>百</span><span>币网</span><span>有权利就任何主体侵权单独提起诉讼并获得全部赔偿。本协议属于《中华人民共和国著作权法》第二十五条规定的书面协议，其效力及于用户在</span><span>百</span><span>币网</span><span>发布的任何受著作权法保护的作品内容，无论该内容形成于本协议签订前还是本协议签订后。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4 用户在使用<span>百</span><span>币网</span><span>服务过程中不得非法使用或处分</span><span>百</span><span>币网</span><span>或他人的知识产权权利。用户不得将已发表于</span><span>百</span><span>币网</span><span>的信息以任何形式发布或授权其它网站（及媒体）使用。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>九、客户服务</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>百</span><span>币网</span><span>建立专业的客服团队，并建立完善的客户服务制度，从技术、人员和制度上保障用户提问及投诉渠道的畅通，为用户提供及时的疑难解答与投诉反馈。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十、协议的变更和终止</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.协议的变更：<span>百</span><span>币网</span><span>有权随时对本协议内容或</span><span>百</span><span>币网</span><span>发布的其他服务条款及操作规则的内容进行变更，变更时</span><span>百</span><span>币网</span><span>将在</span><span>百</span><span>币网</span><span>站内显著位置发布公告，变更自公告发布之时生效，如用户继续使用</span><span>百</span><span>币网</span><span>提供的服务即视为用户同意该等内容变更，如用户不同意变更后的内容则用户有权注销</span><span>百</span><span>币</span><span>网账户、停止使用</span><span>百</span><span>币网</span><span>网服务。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>2.协议的终止</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.1<span>百</span><span>币网有权依据本协议约定注销用户的</span><span>百</span><span>币网账号，本协议于账号注销之日终止。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.2<span>百</span><span>币网有权依据本协议约定终止全部</span><span>百</span><span>币网服务，本协议于</span><span>百</span><span>币网全部服务终止之日终止。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.3.本协议终止后，用户无权要求<span>百</span><span>币网继续向其提供任何服务或履行任何其他义务，包括但不限于要求</span><span>百</span><span>币网为用户保留或向用户披露其原</span><span>百</span><span>币网账号中的任何信息，向用户或第三方转发任何其未曾阅读或发送过的信息等。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.4.本协议的终止不影响守约方向违约方追究违约责任。\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	十一、<b><span>隐私权政策</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"background:#FFFFFF;\">\r\n	<b>1.适用范围</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.1.在用户注册<span>百</span><span>币网账号或者支付账户时，用户根据</span><span>百</span><span>币网要求提供的个人注册信息，包括但不限于身份证信息；</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.2.在用户使用<span>百</span><span>币网服务时，或访问</span><span>百</span><span>币网网页时，聚币网自动接收并记录的用户浏览器上的服务器数值，包括但不限于</span>IP地址等数据及用户要求取用的网页记录；\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.3.<span>百</span><span>币网收集到的用户在或许币网进行交易的有关数据，包括但不限于出价、购买等记录；</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.4.<span>百</span><span>币网通过合法途径取得的其他用户个人信息。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>11.2.信息使用</b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.<span>百</span><span>币网不会向任何人出售或出借用户的个人信息，除非事先得到用户的许可。聚</span><span>百</span><span>网</span><span>币</span><span>也不允许任何第三方以任何手段收集、编辑、出售或者无偿传播用户的个人信息。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.<span>百</span><span>币网对所获得的客户身份资料和交易信息进行保密，不得向任何单位和个人提供客户身份资料和交易信息，法律法规另有规定的除外。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十二、反洗钱</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1<span>百</span><span>币网遵守和执行《中华人民共和国反洗钱法》的规定，对用户进行身份识别、客户身份资料和交易记录保存制度，以及大额的和可疑交易报告的制度。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2用户注册、挂失交易密码或者资金密码时，应当提供并上传身份证复印件，<span>百</span><span>币网对用户提供的身份证信息进行识别和比对。</span><span>百</span><span>币网有合理的理由怀疑用户使用虚假身份注册时，有权拒绝注册或者注销已经注册的账户。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3<span>百</span><span>币网参照《金融机构大额交易和可疑交易报告管理办法》的规定，保存大额交易和有洗钱嫌疑的交易记录，在监管机构要求提供大额交易和可疑交易的记录时，向监管机构提供。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4<span>百</span><span>币网对用户身份信息以及大额交易、可疑交易记录进行保存，依法协助、配合司法机关和行政执法机关打击洗钱活动，依照法律法规的规定协助司法机关、海关、税务等部门查询、冻结和扣划客户存款。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十三、风险提示</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>虚拟币交易有极高的风险。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1虚拟币市场是全新的、未经确认的，而且可能不会增长。目前，虚拟币主要由投机者大量使用，零售和商业市场使用相对较少，因此虚拟币价格易产生波动，并进而对虚拟币投资产生不利影响。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2虚拟币市场没有像中国股市那样的涨跌停限制，同时交易是24小时开放的。虚拟币由于筹码较少，价格易受到庄家控制，有可能出现一天价格涨几倍的情况，同时也可能出现一天内价格跌去一半的情况。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3<span>参与虚拟币交易，用户应当自行控制风险，评估虚拟币投资价值和投资风险，承担损失全部投资的经济风险。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4<span>因国家法律、法规和规范性文件的制定或者修改，导致虚拟币的交易被暂停、或者禁止的，因此造成的经济损失全部由用户自行承担。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十四、违约责任</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1<span>百</span><span>币网或用户违反本协议的约定即构成违约，违约方应当向守约方承担违约责任。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2如因用户提供的信息不真实、不完整或不准确给<span>百</span><span>币网造成损失的，</span><span>百</span><span>币网有权要求用户对</span><span>百</span><span>币网进行损失的赔偿。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3如因用户违反法律法规规定或本协议约定，在<span>百</span><span>币网或利用</span><span>百</span><span>币网服务从事非法活动的，</span><span>百</span><span>币网有权立即终止继续对其提供</span><span>百</span><span>币网服务，注销其账号，并要求其赔偿由此给</span><span>百</span><span>币网造成的损失。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4如用户以技术手段干扰<span>百</span><span>币网的运行或干扰其他用户对</span><span>百</span><span>币网使用的，</span><span>百</span><span>币网有权立即注销其</span><span>百</span><span>币网账号，并有权要求其赔偿由此给</span><span>百</span><span>币网造成的损失。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	5如用户以虚构事实等方式恶意诋毁<span>百</span><span>币网的商誉，</span><span>百</span><span>币网有权要求用户向</span><span>百</span><span>币网公开道歉，赔偿其给</span><span>百</span><span>币网造成的损失，并有权终止对其提供</span><span>百</span><span>币网服务。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十五、争议解决</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	    <span>用户与</span><span>百</span><span>币网因本协议的履行发生争议的应通过友好协商解决，协商解决不成的，任一方有权将争议提交</span><span>成都</span><span>仲裁委员会依据该会仲裁规则进行仲裁。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十六、生效和解释</span></b><b></b>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1本协议于用户点击<span>百</span><span>币网注册页面的同意注册并完成注册程序、获得</span><span>百</span><span>币网账号和密码时生效，对</span><span>百</span><span>币网和用户均具有约束力。</span>\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2本协议的最终解释权归<span>百</span><span>币网所有。</span>\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	 \r\n</p>', '1483859771', '0', '', '0');
INSERT INTO `yang_article` VALUES ('343', '5', '百年通宝币资料', '<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	一、百年通宝币参数\r\n</p>\r\n<p>\r\n	    中文名：百年通宝币\r\n</p>\r\n<p>\r\n	    英文名：BNTB\r\n</p>\r\n<p>\r\n	    英文简称：BNTB\r\n</p>\r\n<p>\r\n	    开发团队：百年通宝币开发团队\r\n</p>\r\n<p>\r\n	    钱包发布日期：2016年10月9日\r\n</p>\r\n<p>\r\n	    货币总量：5亿\r\n</p>\r\n<p>\r\n	    认购价0.01元/个(认购6%)，开盘价 0.02元/个\r\n</p>\r\n<p>\r\n	    P2P端口：41612 RPC端口：41614\r\n</p>\r\n<p>\r\n	    核心算法：scrypt+pos\r\n</p>\r\n<p>\r\n	    成熟：10 交易确认：8\r\n</p>\r\n<p>\r\n	二、百年通宝币特点\r\n</p>\r\n<p>\r\n	    1、以“去中心化、去信任化”为标志\r\n</p>\r\n<p>\r\n	    2、交易速度快,确认时间平均为1-3分钟\r\n</p>\r\n<p>\r\n	    3、超快链接节点，支付转账速度更快，更新区块更加及时\r\n</p>\r\n<p>\r\n	    4、采用6位哈希算法，使用生日攻击寻找groestl哈希碰撞  \r\n</p>\r\n<p>\r\n	    5、源于区块链技术发展的去中心化数字资产\r\n</p>\r\n<p>\r\n	    6、区块链技术是“自由、开放、合作、共享”\r\n</p>\r\n<p>\r\n	    7、采用“分布式记账”对交易结果进行数字签名       \r\n</p>\r\n<p>\r\n	三、百年通宝币（BNTB）介绍：\r\n</p>\r\n<p>\r\n	   百年通宝币基于scrypt+pos算法，采用POW+POS挖矿。百年通宝币不依靠特定机构发行，它通过特定算法的大量计算产生，P2P的去中心化特性与算法本身可以确保无法通过大量制造币来人为操控，可在全网自由流通，百年通宝币采用最新的区块链技术构建的密码学数字资产，相较于比特币更具流通优势，弥补了比特币在商业流通、促进商业运转、文化传播等领域的短板。按照央行等五部委的定义，百年通宝属于虚拟商品的范畴。\r\n</p>\r\n<p>\r\n	五、百年通宝币网站：\r\n</p>\r\n<p>\r\n	 \r\n</p>\r\n<p>\r\n	官网：<a href=\"http://www.bntbcoin.org/\">http://www.bntbcoin.org</a>\r\n</p>\r\n<p>\r\n	区块查询：<a href=\"http://121.41.82.118:3001/\">http://121.41.82.118:3001/</a>\r\n</p>\r\n<div>\r\n	<br />\r\n</div>', '1484203242', '0', '', '0');

-- ----------------------------
-- Table structure for `yang_article_category`
-- ----------------------------
DROP TABLE IF EXISTS `yang_article_category`;
CREATE TABLE `yang_article_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `parent_id` int(10) NOT NULL,
  `keywords` varchar(128) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_article_category
-- ----------------------------
INSERT INTO `yang_article_category` VALUES ('1', '官方公告', '0', '11', '111', '1');
INSERT INTO `yang_article_category` VALUES ('2', '市场动态', '0', '首页文章', '2', '1');
INSERT INTO `yang_article_category` VALUES ('123', '买入指南', '61', '0', '0', '0');
INSERT INTO `yang_article_category` VALUES ('3', '媒体报道', '0', '媒体报道', '1', '1');
INSERT INTO `yang_article_category` VALUES ('32', '系统公告', '0', '系统公告', '10', '1');
INSERT INTO `yang_article_category` VALUES ('6', '帮助中心', '0', '', '0', '0');
INSERT INTO `yang_article_category` VALUES ('60', '注册指南', '6', '注册指南', '60', '1');
INSERT INTO `yang_article_category` VALUES ('61', '交易指南', '6', '交易指南', '61', '1');
INSERT INTO `yang_article_category` VALUES ('62', '充值指南', '6', '充值指南', '62', '1');
INSERT INTO `yang_article_category` VALUES ('64', '提现指南', '6', '提现指南', '64', '1');
INSERT INTO `yang_article_category` VALUES ('63', '转币指南', '6', '转币指南', '63', '1');
INSERT INTO `yang_article_category` VALUES ('4', '风险提示', '0', '', '0', '0');
INSERT INTO `yang_article_category` VALUES ('125', '银行转账充值', '62', '0', '0', '0');
INSERT INTO `yang_article_category` VALUES ('110', '转入币', '63', '转入币', '110', '1');
INSERT INTO `yang_article_category` VALUES ('111', '转出币', '63', '转出币', '111', '1');
INSERT INTO `yang_article_category` VALUES ('7', '团队信息', '0', '团队信息', '0', '1');
INSERT INTO `yang_article_category` VALUES ('118', '关于我们', '7', '', '0', '0');
INSERT INTO `yang_article_category` VALUES ('119', '加入我们', '7', '', '0', '0');
INSERT INTO `yang_article_category` VALUES ('120', '提现提示', '3', '', '0', '0');
INSERT INTO `yang_article_category` VALUES ('121', '邀请规则', '3', '', '0', '0');
INSERT INTO `yang_article_category` VALUES ('122', '邀请好友', '3', '', '0', '0');
INSERT INTO `yang_article_category` VALUES ('126', '新币上线申请', '3', '', '0', '0');
INSERT INTO `yang_article_category` VALUES ('5', '货币详情', '0', '货币详情', '127', '1');

-- ----------------------------
-- Table structure for `yang_bank`
-- ----------------------------
DROP TABLE IF EXISTS `yang_bank`;
CREATE TABLE `yang_bank` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `bankname` varchar(32) NOT NULL COMMENT '银行',
  `uid` int(32) NOT NULL,
  `cardname` varchar(32) NOT NULL COMMENT '用户名',
  `address` varchar(128) NOT NULL COMMENT '开户地',
  `cardnum` varchar(128) NOT NULL COMMENT '卡号',
  `bname` varchar(32) NOT NULL COMMENT '标签',
  `status` tinyint(2) NOT NULL COMMENT '状态',
  `bank_branch` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_bank
-- ----------------------------
INSERT INTO `yang_bank` VALUES ('1', '工商银行', '0', '', '252', '234', '1234', '0', '');
INSERT INTO `yang_bank` VALUES ('4', '中信银行', '60', '456', '9127', '756756756765', '7657657', '0', '');
INSERT INTO `yang_bank` VALUES ('5', '华夏银行', '60', '456', '15213', '23456', '456', '0', '');
INSERT INTO `yang_bank` VALUES ('6', '华夏银行', '60', '456', '18245', '23456', '456', '0', '');
INSERT INTO `yang_bank` VALUES ('7', '华夏银行', '60', '456', '广东0', '23456', '456', '0', '');
INSERT INTO `yang_bank` VALUES ('8', '华夏银行', '60', '456', '福建0', '23456', '456', '0', '');
INSERT INTO `yang_bank` VALUES ('9', '华夏银行', '60', '456', '福建0', '23456', '456', '0', '');
INSERT INTO `yang_bank` VALUES ('10', '华夏银行', '60', '456', '271', '2345', '456', '0', '');
INSERT INTO `yang_bank` VALUES ('16', '交通银行', '60', '456', '122', '666666', '6666', '0', '');
INSERT INTO `yang_bank` VALUES ('17', '兴业银行', '59', '姜鹏', '159', '666666', 'kk', '0', '');
INSERT INTO `yang_bank` VALUES ('18', '农业银行', '62', '打的', '55', '444444', '体系那', '0', '');
INSERT INTO `yang_bank` VALUES ('20', '工商银行', '62', '打的', '176', '4545665', '阿萨德', '0', '');
INSERT INTO `yang_bank` VALUES ('22', '招商银行', '62', '打的', '239', '5', '+6', '0', '');
INSERT INTO `yang_bank` VALUES ('25', '中国银行', '59', '我我14141', '236', '65885477855547884', 'asdf', '0', '');
INSERT INTO `yang_bank` VALUES ('26', '建设银行', '78', '周成微', '322', '6236683810000751545', '周成微', '0', '');
INSERT INTO `yang_bank` VALUES ('27', '工商银行', '82', '张三', '244', '6222023301028765676', '工商银行', '0', '');
INSERT INTO `yang_bank` VALUES ('28', '建设银行', '100', '周成微', '322', '6236683810000751545', '提现卡01', '0', '');

-- ----------------------------
-- Table structure for `yang_config`
-- ----------------------------
DROP TABLE IF EXISTS `yang_config`;
CREATE TABLE `yang_config` (
  `key` varchar(32) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_config
-- ----------------------------
INSERT INTO `yang_config` VALUES ('title', '百币网 | 数字资产交易平台');
INSERT INTO `yang_config` VALUES ('keywords', '虚拟币比特币');
INSERT INTO `yang_config` VALUES ('desc', '百币网友情提示：请控制风险，不要投入超过您风险承受能力的资金，不要投资您所不了解的数字货币，拒绝传销组织，警惕虚假宣传。');
INSERT INTO `yang_config` VALUES ('logo', '/Uploads/Public/Uploads/2017-01-04/586c67f8755a9.png');
INSERT INTO `yang_config` VALUES ('copyright', 'Copyright 2016-2017 数据库 All Rights Reserved. Powered by 成都百年春网络科技股份有限公司');
INSERT INTO `yang_config` VALUES ('record', '蜀ICP备16021370号-4');
INSERT INTO `yang_config` VALUES ('tel', '4008-367-667');
INSERT INTO `yang_config` VALUES ('email', 'baibiwang@163.com');
INSERT INTO `yang_config` VALUES ('qqcode', '2522');
INSERT INTO `yang_config` VALUES ('business_email', 'bncwlkj@163.com');
INSERT INTO `yang_config` VALUES ('name', '百币');
INSERT INTO `yang_config` VALUES ('address', '成都市青羊区光华东三路486号');
INSERT INTO `yang_config` VALUES ('qq', '4008367667');
INSERT INTO `yang_config` VALUES ('qqqun2', '537563724');
INSERT INTO `yang_config` VALUES ('qqqun3', '535625148');
INSERT INTO `yang_config` VALUES ('qqqun1', '536071030');
INSERT INTO `yang_config` VALUES ('pay_min_money', '100');
INSERT INTO `yang_config` VALUES ('pay_max_money', '1000000');
INSERT INTO `yang_config` VALUES ('pay_fee', '0');
INSERT INTO `yang_config` VALUES ('pay_get_name', '北京亚投科技有限责任公司');
INSERT INTO `yang_config` VALUES ('pay_get_card', '1100 1042 1000 5302 8052');
INSERT INTO `yang_config` VALUES ('pay_get_bank', '中国建设银行北京电子城科技园区支行');
INSERT INTO `yang_config` VALUES ('xnb', 'JMBd');
INSERT INTO `yang_config` VALUES ('xnb_name', '进盟币');
INSERT INTO `yang_config` VALUES ('bili', '0.01');
INSERT INTO `yang_config` VALUES ('weixin', '/Uploads/Public/Uploads/2017-01-04/586caaff03999.jpg');
INSERT INTO `yang_config` VALUES ('weibo', 'http://weibo.com/p/1006066104138742/home?from=page_100606&mod=TAB&is_all=1#place');
INSERT INTO `yang_config` VALUES ('time_limit', '20');
INSERT INTO `yang_config` VALUES ('localhost', 'www.100bi.com');
INSERT INTO `yang_config` VALUES ('worktime', '工作日:9-17时 节假日:9-17时');
INSERT INTO `yang_config` VALUES ('qqqun_url', 'http://shang.qq.com/wpa/qunwpa?\r\n\r\nidkey=6df577b6412b273c9171e0c204c633c0baa5c6e4301b2cc6171d10c3bcdc7c28');
INSERT INTO `yang_config` VALUES ('fee', '0.5');
INSERT INTO `yang_config` VALUES ('CODE_USER_NAME', 'bncwlkj@163.com');
INSERT INTO `yang_config` VALUES ('CODE_USER_PASS', 'bncwlkj888888');
INSERT INTO `yang_config` VALUES ('CODE_NAME', '11');
INSERT INTO `yang_config` VALUES ('', 'friendship_tips');
INSERT INTO `yang_config` VALUES ('', 'risk_warning');
INSERT INTO `yang_config` VALUES ('friendship_tips', '数字货币交易区');
INSERT INTO `yang_config` VALUES ('risk_warning', '   数字货币交易具有极高的风险（预挖、暴涨暴跌、庄家操控、团队解散、技术缺陷等），据国家五部委《关于防范比特币风险的通知》，百币网仅为数字货币的爱好者提供一个自由的网上交换平台，对币的投资价值不承担任何审查、担保、赔偿的责任，如果您不能接受，请不要进行交易！谢谢！举报邮箱：<span style=\"color:#E53333;\">baibiwang@163.com </span>（ 400-9665-100）');
INSERT INTO `yang_config` VALUES ('biaoge_url', '/Uploads/Public/Uploads/2016-03-31/新币申请表');
INSERT INTO `yang_config` VALUES ('jiedong_bili', '100');
INSERT INTO `yang_config` VALUES ('withdraw_warning', '<p>\r\n	1. 提现手续费0.5%，请仔细确认后再操作。\r\n</p>\r\n<p>\r\n	2. 单笔提现限额100元——5万元；单日累计最大提现限额50万元.\r\n</p>\r\n<p>\r\n	3. 人民币提现处理时间：工作日9：00-18:00，到账时间取决于银行间的处理速度，一般为1-24小时。\r\n</p>');
INSERT INTO `yang_config` VALUES ('invite_rule', '<p>\r\n	<span style=\"color:#333333;font-family:tahoma, arial, 宋体, sans-serif;font-size:14px;line-height:40px;background-color:#FFFFFF;\"> </span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	为了答谢持有明星币和百年通宝的会员，您只要获取邀请码在百币网注册开户，就会获得500<span>枚</span><span>百年通宝</span>5<span>枚</span><span>/</span><span>每天的奖励，连续奖励</span><span>100</span><span>天。如果您把自己的邀请码发给别人注册开户，您和您邀请的人会同时得到</span>500<span>枚</span><span>百年通宝</span>5<span>枚</span><span>/</span><span>每天的奖励，连续奖励</span><span>100</span><span>天。您推荐注册开户的会员越多，奖励的越多，</span><span>1</span><span>亿枚百年通宝等你拿。（注：①没有获取邀请码自己注册开户的不赠送百年通宝。②所有会员免费注册开户）</span>。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>');
INSERT INTO `yang_config` VALUES ('tcoin_fee', '0');
INSERT INTO `yang_config` VALUES ('jiaoyi_over_hour', '19');
INSERT INTO `yang_config` VALUES ('jiaoyi_start_minute', '30');
INSERT INTO `yang_config` VALUES ('jiaoyi_start_hour', '8');
INSERT INTO `yang_config` VALUES ('jiaoyi_over_minute', '30');
INSERT INTO `yang_config` VALUES ('zibenfen_bili', '100');
INSERT INTO `yang_config` VALUES ('VAP_rule', '推荐百币注册赚佣金');
INSERT INTO `yang_config` VALUES ('transaction_false', '500000');
INSERT INTO `yang_config` VALUES ('ZC_AWARDS_CURRENCY_ID', '26');
INSERT INTO `yang_config` VALUES ('ZC_AWARDS_ONE_RATIO', '100');
INSERT INTO `yang_config` VALUES ('ZC_AWARDS_TWO_RATIO', '50');
INSERT INTO `yang_config` VALUES ('ZC_AWARDS_STATUS', '1');
INSERT INTO `yang_config` VALUES ('qq2', '160496100');
INSERT INTO `yang_config` VALUES ('qq3', '657033100');
INSERT INTO `yang_config` VALUES ('qq1', '160495100');
INSERT INTO `yang_config` VALUES ('FWTK', '<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1. 用户点击<span>百</span><span>币网注册页面的同意注册按钮并完成注册程序、获得</span><span>百</span><span>币网账号和密码时，视为用户与</span><span>百</span><span>币网已达成《用户协议》，就用户进入</span><span>百</span><span>币网使用</span><span>百</span><span>币网相应的交易服务达成本协议的全部约定。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2. <span>百</span><span>币网及用户均已认真阅读本《用户协议》中全部条款及</span><span>百</span><span>币网发布的法律声明和操作规则的内容，对本协议及前述服务条款、法律声明和操作规则均已知晓、理解并接受，同意将其作为确定双方权利义务的依据。</span><span>百</span><span>币网《法律声明》为本协议的必要组成部分，本协议内容包括本协议正文以及</span><span>百</span><span>币网已经发布的或将来可能发布的各类规则、声明、说明。所有规则、声明、说明为协议不可分割的一部分，与协议正文具有同等法律效力。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3. 本协议不涉及<span>百</span><span>币网用户与其他用户之间因虚拟币交易而产生的法律关系及法律纠纷。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>一、定义条款</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.“虚拟币”指高科技中代替实体货币流通的信息流或数据流（包括但不局限于BTC、LTC、YBC等)。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.“<span>百</span><span>币网</span>”<span>成都百年春网络</span><span>科技</span><span>股份</span><span>有限公司运营和管理的虚拟币交易平台，域名为</span>www.100bi.com, <span>成都百年春网络</span><span>科技</span><span>股份</span><span>有限公司</span><span>通过该网络交易平台为虚拟币玩家提供进行虚拟币的网络交易服务。本协议下文中</span>“<span>百</span><span>币网</span>”既指网络交易平台<span>。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.“用户”接受并同意本协议全部条款及<span>百</span><span>币网不时发布和更新的法律条款和操作规则、通过</span><span>百</span><span>币网进行虚拟币交易的</span><span>百</span><span>币网注册会员。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4.“用户注册”用户注册是指用户登录<span>百</span><span>币网，并按要求填写相关信息并确认同意履行相关用户协议的过程。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	5.“虚拟币交易”用户通过<span>百</span><span>币网进行的虚拟币交易活动。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	6.“充值款”用户为购买虚拟币/出售虚拟币而向<span>百</span><span>币网平台预充入的法币</span>/虚拟币的款项。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	7.“手续费”用户在<span>百</span><span>币网达成虚拟币交易而向</span><span>百</span><span>币网支付的交易服务费用。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>二、用户注册</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>1.注册资格</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户承诺：用户具有完全民事权利能力和行为能力，或虽不具有完全民事权利能力和行为能力</span>,但点击同意注册按钮，本网即视为经其法定代理人同意并由其法定代理人代理注册及应用<span>百</span><span>币网服务。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>2.注册目的</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户承诺：用户进行用户注册并非出于违反法律法规或破坏</span><span>百</span><span>币网虚拟币交易秩序的目的。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>3.注册流程</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.1.用户同意根据<span>百</span><span>币网用户注册页面的要求提供有效电子邮箱等信息，设置</span><span>百</span><span>币网账号及密码，用户应确保所提供全部信息的真实性、完整性和准确性。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.2.用户合法、完整并有效提供注册所需信息的，有权获得<span>百</span><span>币网账号和密码，</span><span>百</span><span>币网账号和密码用于用户在</span><span>百</span><span>币网进行会员登录。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.3.用户获得<span>百</span><span>币网账号及密码时视为用户注册成功，用户同意接收</span><span>百</span><span>币网发送的与</span><span>百</span><span>币网网站管理、运营相关的电子邮件和</span>/或短消息。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3.4 用户注册成功后进行虚拟币交易，应当提供本人的真实身份证号码，进行实名认证。\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>三、用户服务</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>百</span><span>币网为用户通过</span><span>百</span><span>币网进行虚拟币交易活动提供网络交易平台服务。</span><span>百</span><span>币网不作为买家或是卖家参与买卖虚拟币行为本身。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>1.服务内容</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.1.用户有权在<span>百</span><span>币网浏览虚拟币实时行情及交易信息、有权通过</span><span>百</span><span>币网提交虚拟币交易指令并完成虚拟币交易。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.2.用户有权在<span>百</span><span>币网查看其</span><span>百</span><span>币网会员账号下的信息，有权应用</span><span>百</span><span>币网提供的功能进行操作。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.3.用户有权按照<span>百</span><span>币网发布的活动规则参与</span><span>百</span><span>币网组织的网站活动。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.4.<span>百</span><span>币网承诺为用户提供的其他服务。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>2.服务规则</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户承诺遵守下列</span><span>百</span><span>币网服务规则：</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.1.用户应当遵守法律法规、规章、规范性文件及政策要求的规定，保证账户中所有资金和虚拟币来源的合法性，不得在<span>百</span><span>币网或利用</span><span>百</span><span>币网服务从事非法或其他损害</span><span>百</span><span>币网或第三方权益的活动，如发送或接收任何违法、违规、违反公序良俗、侵犯他人权益的信息，发送或接收传销材料或存在其他危害的信息或言论，未经</span><span>百</span><span>币网授权使用或伪造</span><span>百</span><span>币网电子邮件题头信息等。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.2.用户应当遵守法律法规应当妥善使用和保管其<span>百</span><span>币网账号及密码、资金密码、和其注册时绑定的手机号码、以及手机接收的手机验证码的安全。用户对使用其</span><span>百</span><span>币网账号和密码、资金密码、手机验证码进行的任何操作和后果承担全部责任。当用户发现</span><span>百</span><span>币网账号、密码、或资金密码、验证码被未经其授权的第三方使用，或存在其他账号安全问题时，应立即有效通知</span><span>百</span><span>币网，要求</span><span>百</span><span>币网暂停该</span><span>百</span><span>币网账号的服务。</span><span>百</span><span>币网有权在合理时间内对用户的该等请求采取行动，但对</span><span>百</span><span>币网采取行动前用户已经遭受的损失不承担任何责任。用户在未经</span><span>百</span><span>币网同意的情况下不得将聚币网账号以赠与、借用、租用、转让或其他方式处分给他人。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.3.用户应当遵守<span>百</span><span>币网不时发布和更新的用户协议以及其他服务条款和操作规则。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>四、虚拟币交易规则</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户承诺在其进入</span><span>百</span><span>币网交易，通过</span><span>百</span><span>币网与其他用户进行虚拟币交易的过程中良好遵守如下</span><span>百</span><span>币网虚拟币交易规则。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>1.浏览交易信息</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户在</span><span>百</span><span>币网</span><span>浏览虚拟币交易信息时，应当仔细阅读交易信息中包含的全部内容，包括但不限于虚拟币价格、委托量、手续费、买入或卖出方向，用户完全接受交易信息中包含的全部内容后方可点击按钮进行交易。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>2.提交委托</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>在浏览完交易信息确认无误之后用户可以提交交易委托。用户提交交易委托后，即用户授权</span><span>百</span><span>币网</span><span>代理用户进行相应的交易撮合，</span><span>百</span><span>币网</span><span>在有满足用户委托价格的交易时将会自动完成撮合交易而无需提前通知用户。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>3 查看交易明细</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户可以通过管理中心的交易明细中查看相应的成交记录，确认自己的详情交易记录。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>4 撤销/修改委托</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>在委托未达成交易之前，用户有权随时撤销或修改委托。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>五、用户的权利和义务</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1用户有权按照本协议约定接受<span>百</span><span>币网</span><span>提供的虚拟币交易平台服务。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2 用户有权随时终止使用<span>百</span><span>币网</span><span>服务。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3 用户有权随时提取在<span>百</span><span>币网</span><span>的资金余额，包括人民币以及虚拟币，但需向</span><span>百</span><span>币网</span><span>支付相应的提现手续费用。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4 用户对注册时提供的个人资料的真实性、有效性及安全性负责。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	5用户在<span>百</span><span>币网</span><span>进行虚拟币交易时不得恶意干扰虚拟币交易的正常进行、破坏交易秩序。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	6用户不得以任何技术手段或其他方式干扰<span>百</span><span>币网</span><span>的正常运行或干扰其他用户对</span><span>百</span><span>币网</span><span>服务的使用。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	7如用户因网上交易与其他用户产生诉讼的，不得通过司法或行政以外的途径要求<span>百</span><span>币网</span><span>提供相关资料。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	8 用户不得以虚构事实等方式恶意诋毁<span>百</span><span>币网的商誉。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>六、</span></b><b><span>百</span></b><b><span>币网</span></b><b><span>的权利和义务</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1如用户不具备本协议约定的注册资格，则<span>百</span><span>币网</span><span>有权拒绝用户进行注册，对已注册的用户有权注销其</span><span>百</span><span>币网</span><span>会员账号，</span><span>百</span><span>币网</span><span>因此而遭受损失的有权向前述用户或其法定代理人主张赔偿。同时，</span><span>百</span><span>币网</span><span>保留其他任何情况下决定是否接受用户注册的权利。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2<span>百</span><span>币网</span><span>发现账户使用者并非账户初始注册人时，有权中止该账户的使用。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3<span>百</span><span>币网</span><span>通过技术检测、人工抽检等检测方式合理怀疑用户提供的信息错误、不实、失效或不完整时，有权通知用户更正、更新信息或中止、终止为其提供</span><span>百</span><span>币网</span><span>服务。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4<span>百</span><span>币网</span><span>有权在发现</span><span>百</span><span>币网</span><span>上显示的任何信息存在明显错误时，对信息予以更正。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	5<span>百</span><span>币网</span><span>保留随时修改、中止或终止</span><span>百</span><span>币网</span><span>服务的权利，</span><span>百</span><span>币网</span><span>行使修改或中止服务的权利不需事先告知用户，</span><span>百</span><span>币网终止</span><span>百</span><span>币网</span><span>一项或多项服务的，终止自</span><span>百</span><span>币网</span><span>在网站上发布终止公告之日生效。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	6<span>百</span><span>币网</span><span>应当采取必要的技术手段和管理措施保障</span><span>百</span><span>币网</span><span>的正常运行，并提供必要、可靠的交易环境和交易服务，维护虚拟币交易秩序。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	7如用户连续一年未使用<span>百</span><span>币网</span><span>会员账号和密码登录</span><span>百</span><span>币网</span><span>，则</span><span>百</span><span>币网</span><span>有权注销用户的</span><span>百</span><span>币网</span><span>账号。账号注销后，</span><span>百</span><span>币网</span><span>有权将相应的会员名开放给其他用户注册使用。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	8<span>百</span><span>币网</span><span>通过加强技术投入、提升安全防范等措施保障用户的人民币资金及虚拟币托管安全，有义务在用户资金出现可以预见的安全风险时提前通知用户。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	9<span>百</span><span>币网</span><span>有权在本协议履行期间及本协议终止后保留用户的注册信息及用户应用</span><span>百</span><span>币网</span><span>服务期间的全部交易信息，但不得非法使用该等信息。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>七、特别声明</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>在法律允许的范围内，不论在何种情况下，</span><span>百</span><span>币网</span><span>对由于信息网络设备维护、信息网络连接故障、电脑、通讯或其他系统的故障、电力故障、罢工、劳动争议、暴乱、起义、骚乱、生产力或生产资料不足、火灾、洪水、风暴、爆炸、战争、政府行为、司法行政机关的命令、其他不可抗力或第三方的不作为而造成的不能服务或延迟服务，以及用户因此而遭受的损失不承担责任。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>八、知识产权</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1<span>百</span><span>币网</span><span>所包含的全部智力成果包括但不限于网站标志、数据库、网站设计、文字和图表、软件、照片、录像、音乐、声音及其前述组合，软件编译、相关源代码和软件</span> (包括小应用程序和脚本) 的知识产权权利均归<span>百</span><span>币网所有。用户不得为商业目的复制、更改、拷贝、发送或使用前述任何材料或内容。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2<span>百</span><span>币网</span><span>名称中包含的所有权利</span> (包括但不限于商誉和商标、标志) 均归<span>成都百年春网络</span><span>科技</span><span>股份</span><span>有限公司所有。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3用户接受本协议即视为用户主动将其在<span>百</span><span>币网</span><span>发表的任何形式的信息的著作权，包括但不限于：复制权、发行权、出租权、展览权、表演权、放映权、广播权、信息网络传播权、摄制权、改编权、翻译权、汇编权以及应当由著作权人享有的其他可转让权利无偿独家转让给</span><span>百</span><span>币网</span><span>所有，</span><span>百</span><span>币网</span><span>有权利就任何主体侵权单独提起诉讼并获得全部赔偿。本协议属于《中华人民共和国著作权法》第二十五条规定的书面协议，其效力及于用户在</span><span>百</span><span>币网</span><span>发布的任何受著作权法保护的作品内容，无论该内容形成于本协议签订前还是本协议签订后。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4 用户在使用<span>百</span><span>币网</span><span>服务过程中不得非法使用或处分</span><span>百</span><span>币网</span><span>或他人的知识产权权利。用户不得将已发表于</span><span>百</span><span>币网</span><span>的信息以任何形式发布或授权其它网站（及媒体）使用。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>九、客户服务</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>百</span><span>币网</span><span>建立专业的客服团队，并建立完善的客户服务制度，从技术、人员和制度上保障用户提问及投诉渠道的畅通，为用户提供及时的疑难解答与投诉反馈。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十、协议的变更和终止</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.协议的变更：<span>百</span><span>币网</span><span>有权随时对本协议内容或</span><span>百</span><span>币网</span><span>发布的其他服务条款及操作规则的内容进行变更，变更时</span><span>百</span><span>币网</span><span>将在</span><span>百</span><span>币网</span><span>站内显著位置发布公告，变更自公告发布之时生效，如用户继续使用</span><span>百</span><span>币网</span><span>提供的服务即视为用户同意该等内容变更，如用户不同意变更后的内容则用户有权注销</span><span>百</span><span>币</span><span>网账户、停止使用</span><span>百</span><span>币网</span><span>网服务。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>2.协议的终止</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.1<span>百</span><span>币网有权依据本协议约定注销用户的</span><span>百</span><span>币网账号，本协议于账号注销之日终止。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.2<span>百</span><span>币网有权依据本协议约定终止全部</span><span>百</span><span>币网服务，本协议于</span><span>百</span><span>币网全部服务终止之日终止。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.3.本协议终止后，用户无权要求<span>百</span><span>币网继续向其提供任何服务或履行任何其他义务，包括但不限于要求</span><span>百</span><span>币网为用户保留或向用户披露其原</span><span>百</span><span>币网账号中的任何信息，向用户或第三方转发任何其未曾阅读或发送过的信息等。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.4.本协议的终止不影响守约方向违约方追究违约责任。\r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	十一、<b><span>隐私权政策</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"background:#FFFFFF;\">\r\n	<b>1.适用范围</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.1.在用户注册<span>百</span><span>币网账号或者支付账户时，用户根据</span><span>百</span><span>币网要求提供的个人注册信息，包括但不限于身份证信息；</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.2.在用户使用<span>百</span><span>币网服务时，或访问</span><span>百</span><span>币网网页时，聚币网自动接收并记录的用户浏览器上的服务器数值，包括但不限于</span>IP地址等数据及用户要求取用的网页记录；\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.3.<span>百</span><span>币网收集到的用户在或许币网进行交易的有关数据，包括但不限于出价、购买等记录；</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.4.<span>百</span><span>币网通过合法途径取得的其他用户个人信息。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b>11.2.信息使用</b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1.<span>百</span><span>币网不会向任何人出售或出借用户的个人信息，除非事先得到用户的许可。聚</span><span>百</span><span>网</span><span>币</span><span>也不允许任何第三方以任何手段收集、编辑、出售或者无偿传播用户的个人信息。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2.<span>百</span><span>币网对所获得的客户身份资料和交易信息进行保密，不得向任何单位和个人提供客户身份资料和交易信息，法律法规另有规定的除外。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十二、反洗钱</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1<span>百</span><span>币网遵守和执行《中华人民共和国反洗钱法》的规定，对用户进行身份识别、客户身份资料和交易记录保存制度，以及大额的和可疑交易报告的制度。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2用户注册、挂失交易密码或者资金密码时，应当提供并上传身份证复印件，<span>百</span><span>币网对用户提供的身份证信息进行识别和比对。</span><span>百</span><span>币网有合理的理由怀疑用户使用虚假身份注册时，有权拒绝注册或者注销已经注册的账户。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3<span>百</span><span>币网参照《金融机构大额交易和可疑交易报告管理办法》的规定，保存大额交易和有洗钱嫌疑的交易记录，在监管机构要求提供大额交易和可疑交易的记录时，向监管机构提供。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4<span>百</span><span>币网对用户身份信息以及大额交易、可疑交易记录进行保存，依法协助、配合司法机关和行政执法机关打击洗钱活动，依照法律法规的规定协助司法机关、海关、税务等部门查询、冻结和扣划客户存款。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十三、风险提示</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>虚拟币交易有极高的风险。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1虚拟币市场是全新的、未经确认的，而且可能不会增长。目前，虚拟币主要由投机者大量使用，零售和商业市场使用相对较少，因此虚拟币价格易产生波动，并进而对虚拟币投资产生不利影响。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2虚拟币市场没有像中国股市那样的涨跌停限制，同时交易是24小时开放的。虚拟币由于筹码较少，价格易受到庄家控制，有可能出现一天价格涨几倍的情况，同时也可能出现一天内价格跌去一半的情况。\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3<span>参与虚拟币交易，用户应当自行控制风险，评估虚拟币投资价值和投资风险，承担损失全部投资的经济风险。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4<span>因国家法律、法规和规范性文件的制定或者修改，导致虚拟币的交易被暂停、或者禁止的，因此造成的经济损失全部由用户自行承担。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十四、违约责任</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1<span>百</span><span>币网或用户违反本协议的约定即构成违约，违约方应当向守约方承担违约责任。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2如因用户提供的信息不真实、不完整或不准确给<span>百</span><span>币网造成损失的，</span><span>百</span><span>币网有权要求用户对</span><span>百</span><span>币网进行损失的赔偿。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	3如因用户违反法律法规规定或本协议约定，在<span>百</span><span>币网或利用</span><span>百</span><span>币网服务从事非法活动的，</span><span>百</span><span>币网有权立即终止继续对其提供</span><span>百</span><span>币网服务，注销其账号，并要求其赔偿由此给</span><span>百</span><span>币网造成的损失。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	4如用户以技术手段干扰<span>百</span><span>币网的运行或干扰其他用户对</span><span>百</span><span>币网使用的，</span><span>百</span><span>币网有权立即注销其</span><span>百</span><span>币网账号，并有权要求其赔偿由此给</span><span>百</span><span>币网造成的损失。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	5如用户以虚构事实等方式恶意诋毁<span>百</span><span>币网的商誉，</span><span>百</span><span>币网有权要求用户向</span><span>百</span><span>币网公开道歉，赔偿其给</span><span>百</span><span>币网造成的损失，并有权终止对其提供</span><span>百</span><span>币网服务。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十五、争议解决</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<span>用户与</span><span>百</span><span>币网因本协议的履行发生争议的应通过友好协商解决，协商解决不成的，任一方有权将争议提交</span><span>成都</span><span>仲裁委员会依据该会仲裁规则进行仲裁。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	<b><span>十六、生效和解释</span></b><b></b> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	1本协议于用户点击<span>百</span><span>币网注册页面的同意注册并完成注册程序、获得</span><span>百</span><span>币网账号和密码时生效，对</span><span>百</span><span>币网和用户均具有约束力。</span> \r\n</p>\r\n<p class=\"p\" style=\"margin-left:0.0000pt;text-indent:0.0000pt;background:#FFFFFF;\">\r\n	2本协议的最终解释权归<span>百</span><span>币网所有。</span> \r\n</p>\r\n<p class=\"MsoNormal\">\r\n	<br />\r\n</p>');
INSERT INTO `yang_config` VALUES ('disclaimer', '阿德分手大师');
INSERT INTO `yang_config` VALUES ('EMAIL_USERNAME', 'mail@koumang.com');
INSERT INTO `yang_config` VALUES ('EMAIL_PASSWORD', 'xiaowei7758258');
INSERT INTO `yang_config` VALUES ('EMAIL_HOST', 'smtp.qq.com');
INSERT INTO `yang_config` VALUES ('zhuce_jiangli', '100');
INSERT INTO `yang_config` VALUES ('new_coin_up', '<span><span> 若您是加密数字货币开发者或者官方团队成员，有意向将您开发的币种上线百币网，欢迎您通过以下方式和流程递交申请。</span> \r\n<p>\r\n	申请流程：\r\n</p>\r\n<ol>\r\n	<li>\r\n		下载 <a href=\"http://www.jubi.com/newcoin-sheet.docx\" target=\"_blank\">“新币上线申请表”</a>，按要求填写并发送至邮箱bncwlkj@163.com\r\n	</li>\r\n	<li>\r\n		若申请资料填写齐全并符合百币网上线条件，我们将和申请人或官方团队联系并确定币种上线可行性。\r\n	</li>\r\n	<li>\r\n		确定上线币种后，百币网会安排技术进行钱包、交易相关开发，我们将至少提前1天通知上线新币 。\r\n	</li>\r\n</ol>\r\n<p>\r\n	另：若在百币网已上线的币种出现下列情况之一，百币网将考虑下线该币种。\r\n</p>\r\n<ol>\r\n	<li>\r\n		官方团队解散或者不再有正常维护更新，导致该币种不能进行正常挖矿、转币、区块查询等；\r\n	</li>\r\n	<li>\r\n		该币种已经没有用户进行交易或者使用、持有；\r\n	</li>\r\n	<li>\r\n		该币种出现重大技术故障，影响到区块查询、挖矿转币等正常功能；\r\n	</li>\r\n	<li>\r\n		官方团队涉嫌恶意预挖、传销诈骗、坐庄圈钱等欺诈行为。\r\n	</li>\r\n</ol>\r\n<p>\r\n	<a>为了维护广大用户的财产安全我们将尽量避免币种下线。</a> \r\n</p>\r\n</span>');
INSERT INTO `yang_config` VALUES ('list_switch', '1');
INSERT INTO `yang_config` VALUES ('reward_start_time', '1484236800');
INSERT INTO `yang_config` VALUES ('reward_end_time', '1484668800');
INSERT INTO `yang_config` VALUES ('list_reward_rule', '');
INSERT INTO `yang_config` VALUES ('wenxin_tishi', '<span style=\"color:#FF0000;font-family:Arial, 微软雅黑;font-size:14px;line-height:36px;background-color:#FFFAEB;\">百币网 ：请控制风险，不要投入超过您风险承受能力的资金，拒绝传销组织，警惕虚假宣传。<a href=\"mailto:bncwlkj@163.com\"><span style=\"color:#000000;background-color:#FFFFFF;\">400-9665-100</span></a><span style=\"color:#000000;background-color:#FFFFFF;\"></span></span>');
INSERT INTO `yang_config` VALUES ('index_logo_footer', '/Uploads/Public/Uploads/2017-01-06/586f21c59514c.png');
INSERT INTO `yang_config` VALUES ('suggest_email', '400-9665-100');
INSERT INTO `yang_config` VALUES ('qqqun4', '348146971');
INSERT INTO `yang_config` VALUES ('weixin_pay', '/Uploads/Public/Uploads/2017-01-10/58748c8f791d4.png');
INSERT INTO `yang_config` VALUES ('huanxun', '1');
INSERT INTO `yang_config` VALUES ('rengong', '1');
INSERT INTO `yang_config` VALUES ('huanxun_fee', '0.005');
INSERT INTO `yang_config` VALUES ('renmin_fill', '<p>\r\n	1.人工充值需人工处理。在工作日的2小时内到账。\r\n</p>\r\n<p>\r\n	2、您需先填写汇款金额和账号并提交订单，然后向我们指定的银行账号汇款。\r\n</p>');
INSERT INTO `yang_config` VALUES ('btc_address', 'asdf');

-- ----------------------------
-- Table structure for `yang_currency`
-- ----------------------------
DROP TABLE IF EXISTS `yang_currency`;
CREATE TABLE `yang_currency` (
  `currency_id` int(32) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(32) NOT NULL COMMENT '货币名称',
  `currency_logo` varchar(255) NOT NULL COMMENT '货币logo',
  `currency_mark` varchar(32) NOT NULL COMMENT '英文标识',
  `currency_unit` varchar(25) DEFAULT NULL COMMENT '单位',
  `currency_content` text NOT NULL,
  `currency_all_money` decimal(40,6) NOT NULL COMMENT '总市值',
  `currency_all_num` decimal(40,4) DEFAULT '0.0000' COMMENT '币总数量',
  `currency_buy_fee` float(64,4) NOT NULL COMMENT '买入手续费',
  `currency_sell_fee` float(64,4) NOT NULL COMMENT '卖出手续费',
  `currency_url` varchar(128) NOT NULL COMMENT '该币种的链接地址',
  `trade_currency_id` int(10) NOT NULL DEFAULT '1' COMMENT '可以进行交易的币种',
  `is_line` tinyint(4) NOT NULL DEFAULT '0',
  `is_lock` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否交易 0 是交易许可 1是交易不许可',
  `port_number` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `rpc_url` varchar(255) NOT NULL COMMENT 'rpc路径',
  `rpc_pwd` varchar(255) NOT NULL COMMENT 'rpc密码',
  `rpc_user` varchar(255) NOT NULL COMMENT 'rpc账号（用户名）',
  `currency_all_tibi` int(10) NOT NULL DEFAULT '0' COMMENT '最大提币额',
  `detail_url` varchar(64) NOT NULL COMMENT '详情跳转链接',
  `qianbao_url` varchar(64) NOT NULL COMMENT '钱包储存路径',
  `qianbao_key` varchar(64) NOT NULL COMMENT '钱包密钥',
  `price_up` float(64,4) NOT NULL COMMENT '涨停',
  `price_down` float(64,4) NOT NULL COMMENT '跌停',
  `sort` int(10) NOT NULL DEFAULT '0',
  `currency_digit_num` int(10) NOT NULL COMMENT '限制位数',
  `guanwang_url` varchar(128) DEFAULT NULL,
  `currency_fee_reward` int(5) NOT NULL DEFAULT '0' COMMENT '给上级的奖励百分比',
  `start_time_h` tinyint(2) DEFAULT NULL,
  `start_time_m` tinyint(2) DEFAULT NULL,
  `end_time_h` tinyint(2) DEFAULT NULL,
  `end_time_m` tinyint(2) DEFAULT NULL,
  `is_lock_6` tinyint(2) DEFAULT NULL,
  `is_lock_7` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`currency_id`),
  KEY `currency_id` (`currency_id`),
  KEY `currency_mark` (`currency_mark`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_currency
-- ----------------------------
INSERT INTO `yang_currency` VALUES ('25', '明星币', '/Uploads/Public/Uploads/2017-01-08/587202b1a5e40.jpg', 'MXI', 'M', '元宝币元宝币元宝币元宝币', '2147483648.000000', '10000.0000', '0.0000', '0.2000', '', '0', '1', '0', '29992', '1484532410', '0', '139.196.186.151', 'MXI321', 'MXI123', '100', 'http://139.196.17.237/Home/Art/details/id/342.html', '/Uploads/Public/Uploads2016-03-31/56fc9bfeb3b13.zip', 'zcw930306', '0.0000', '0.0000', '3', '0', null, '30', '9', '30', '19', '40', '0', '0');
INSERT INTO `yang_currency` VALUES ('26', '百年通宝', '/Uploads/Public/Uploads/2017-01-08/587202bed88e3.png', 'BNTB', 'B', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAa', '5000000000.000000', '10000.0000', '0.0000', '0.2000', '', '0', '1', '0', '29991', '1484457804', '0', '139.196.186.151', 'bntb321', 'bntb123', '10000000', 'http://139.196.17.237/Home/Art/details/id/343.html', '/Uploads/Public/Uploads2016-03-31/56fc9dbd18618.zip', 'zcw930306', '0.0000', '0.0000', '2', '0', null, '30', '0', '0', '19', '10', '0', '0');
INSERT INTO `yang_currency` VALUES ('27', '莱特币', '/Uploads/Public/Uploads/2016-03-25/56f52838632ea.png', 'LTC', null, '进盟币进盟币进盟币', '2147483648.000000', '500007.0000', '0.1000', '0.1000', '', '0', '1', '1', '29991', '1483866827', '0', '47.89.49.145', '123456', 'green', '0', '', '/Uploads/Public/Uploads2016-03-31/56fc9da499d01.zip', '', '0.0000', '0.0000', '4', '0', null, '0', '0', '0', '0', '0', null, null);
INSERT INTO `yang_currency` VALUES ('28', '比特币', '/Uploads/Public/Uploads/2017-01-08/587202d84948e.png', 'BTC', '฿', '比特币比特币比特币', '0.000000', '0.0000', '0.0000', '0.2000', '', '0', '1', '0', '29992', '1484023153', '0', '47.89.49.145', '123456', 'green', '0', '', '/Uploads/Public/Uploads2016-03-31/56fc9d92272b8.zip', '', '0.0000', '0.0000', '1', '0', null, '0', '0', '0', '0', '0', null, null);
INSERT INTO `yang_currency` VALUES ('29', '小周币', '/Uploads/Public/Uploads/2017-01-10/5874a4cdcd191.png', 'DAWEI', 'Z', '', '0.000000', '20000.0000', '0.0000', '0.0000', '', '0', '1', '0', '0', '1484202686', '0', '', '', '', '0', '', '', '', '0.0000', '0.0000', '0', '0', null, '0', '0', '0', '24', '0', null, null);

-- ----------------------------
-- Table structure for `yang_currency_comment`
-- ----------------------------
DROP TABLE IF EXISTS `yang_currency_comment`;
CREATE TABLE `yang_currency_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '币种评论表',
  `currency_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `content` varchar(256) NOT NULL,
  `add_time` varchar(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `币种id` (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_currency_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_currency_concern`
-- ----------------------------
DROP TABLE IF EXISTS `yang_currency_concern`;
CREATE TABLE `yang_currency_concern` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '币种关注表',
  `currency_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `add_time` varchar(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `币种id` (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_currency_concern
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_currency_pic`
-- ----------------------------
DROP TABLE IF EXISTS `yang_currency_pic`;
CREATE TABLE `yang_currency_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '币种详情图片',
  `currency_id` int(11) NOT NULL,
  `pic` varchar(128) NOT NULL COMMENT '图片路径',
  `add_time` varchar(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_currency_pic
-- ----------------------------
INSERT INTO `yang_currency_pic` VALUES ('1', '25', '/Uploads/Public/Uploads/2016-03-29/56f9eacacdfe6.jpg', '1459219262', '0');
INSERT INTO `yang_currency_pic` VALUES ('2', '25', '/Uploads/Public/Uploads/2016-03-29/56f9ead02625a.jpg', '1459219263', '0');

-- ----------------------------
-- Table structure for `yang_currency_user`
-- ----------------------------
DROP TABLE IF EXISTS `yang_currency_user`;
CREATE TABLE `yang_currency_user` (
  `cu_id` int(32) NOT NULL AUTO_INCREMENT,
  `member_id` int(32) NOT NULL COMMENT '用户id',
  `currency_id` int(32) NOT NULL COMMENT '货币id',
  `num` decimal(40,4) NOT NULL COMMENT '数量',
  `forzen_num` decimal(40,4) NOT NULL COMMENT '冻结数量',
  `status` tinyint(4) NOT NULL,
  `chongzhi_url` varchar(128) NOT NULL COMMENT '钱包充值地址',
  PRIMARY KEY (`cu_id`),
  KEY `member_id_2` (`member_id`,`currency_id`),
  KEY `cu_id` (`cu_id`,`member_id`,`currency_id`,`num`,`forzen_num`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yang_currency_user
-- ----------------------------
INSERT INTO `yang_currency_user` VALUES ('151', '96', '25', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('152', '96', '26', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('153', '96', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('154', '96', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('155', '96', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('156', '100', '25', '14.0000', '0.0000', '0', 'mWG2JU1AxcKk4v6MNMNJZP288GDjrtTX48');
INSERT INTO `yang_currency_user` VALUES ('157', '100', '26', '12.0000', '0.0000', '0', 'bJRE15GmuJSftND64JtGYYqWkhiC9w6zkY');
INSERT INTO `yang_currency_user` VALUES ('158', '100', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('159', '100', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('160', '100', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('161', '101', '25', '0.9980', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('162', '101', '26', '4.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('163', '101', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('164', '101', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('165', '101', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('166', '102', '25', '4.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('167', '102', '26', '4.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('168', '102', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('169', '102', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('170', '102', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('171', '103', '25', '3.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('172', '103', '26', '12.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('173', '103', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('174', '103', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('175', '103', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('176', '104', '25', '104.0000', '0.0000', '0', 'mXpNcx4AP3uyVqNJrg3ZWbfpKdybi9S6oc');
INSERT INTO `yang_currency_user` VALUES ('177', '104', '26', '2.0000', '0.0000', '0', 'bUiD7qeRSLD4MCWUC8QRwTF4HAKscPSGj1');
INSERT INTO `yang_currency_user` VALUES ('178', '104', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('179', '104', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('180', '104', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('181', '105', '25', '4.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('182', '105', '26', '4.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('183', '105', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('184', '105', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('185', '105', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('186', '106', '25', '4.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('187', '106', '26', '4.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('188', '106', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('189', '106', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('190', '106', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('191', '107', '25', '2.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('192', '107', '26', '2.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('193', '107', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('194', '107', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('195', '107', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('196', '108', '25', '2.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('197', '108', '26', '2.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('198', '108', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('199', '108', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('200', '108', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('201', '109', '25', '2.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('202', '109', '26', '2.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('203', '109', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('204', '109', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('205', '109', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('206', '110', '25', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('207', '110', '26', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('208', '110', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('209', '110', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('210', '110', '29', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('211', '111', '25', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('212', '111', '26', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('213', '111', '27', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('214', '111', '28', '0.0000', '0.0000', '0', '');
INSERT INTO `yang_currency_user` VALUES ('215', '111', '29', '0.0000', '0.0000', '0', '');

-- ----------------------------
-- Table structure for `yang_currency_voted`
-- ----------------------------
DROP TABLE IF EXISTS `yang_currency_voted`;
CREATE TABLE `yang_currency_voted` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '币种投票表',
  `currency_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `add_time` varchar(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `币种id` (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_currency_voted
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_dividend_config`
-- ----------------------------
DROP TABLE IF EXISTS `yang_dividend_config`;
CREATE TABLE `yang_dividend_config` (
  `name` varchar(40) NOT NULL DEFAULT '',
  `value` varchar(40) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_dividend_config
-- ----------------------------
INSERT INTO `yang_dividend_config` VALUES ('dividend_id', '0', '0');
INSERT INTO `yang_dividend_config` VALUES ('num1', '0', '0');
INSERT INTO `yang_dividend_config` VALUES ('num2', '50', '0');
INSERT INTO `yang_dividend_config` VALUES ('num3', '100', '0');
INSERT INTO `yang_dividend_config` VALUES ('num4', '150', '0');
INSERT INTO `yang_dividend_config` VALUES ('money1', '1', '0');
INSERT INTO `yang_dividend_config` VALUES ('money2', '2000', '0');
INSERT INTO `yang_dividend_config` VALUES ('money3', '3000', '0');

-- ----------------------------
-- Table structure for `yang_entrust`
-- ----------------------------
DROP TABLE IF EXISTS `yang_entrust`;
CREATE TABLE `yang_entrust` (
  `entrust_id` int(32) NOT NULL AUTO_INCREMENT COMMENT '委托记录表（委托管理）',
  `member_id` int(32) NOT NULL COMMENT '用户id',
  `currency_id` int(32) NOT NULL COMMENT '货币id',
  `all_num` decimal(10,4) NOT NULL COMMENT '总数量',
  `surplus_num` decimal(10,4) NOT NULL COMMENT '剩余数量',
  `price` decimal(10,2) NOT NULL COMMENT '实际价格(卖出价格）',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `type` tinyint(4) NOT NULL COMMENT '类型 卖出单1 还是买入单2',
  `on_price` decimal(10,2) NOT NULL COMMENT '委托价格(挂单价格全价格 卖出价格是扣除手续费的）',
  `fee` decimal(10,2) NOT NULL COMMENT '手续费比例',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  PRIMARY KEY (`entrust_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_entrust
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_examine_pwdtrade`
-- ----------------------------
DROP TABLE IF EXISTS `yang_examine_pwdtrade`;
CREATE TABLE `yang_examine_pwdtrade` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `u_id` int(32) NOT NULL COMMENT '用户ID',
  `pwdtrade` varchar(64) NOT NULL COMMENT '修改支付密码',
  `idcard` varchar(20) NOT NULL COMMENT '身份证号',
  `idcardPositive` varchar(64) NOT NULL COMMENT '身份证正面',
  `idcardSide` varchar(64) NOT NULL COMMENT '身份证反面',
  `idcardHold` varchar(64) NOT NULL COMMENT '手持身份证',
  `add_time` int(32) NOT NULL COMMENT '添加时间',
  `examine_time` int(32) NOT NULL COMMENT '审核通过时间',
  `examine_name` varchar(32) DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0未审核1通过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_examine_pwdtrade
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_fill`
-- ----------------------------
DROP TABLE IF EXISTS `yang_fill`;
CREATE TABLE `yang_fill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `num` decimal(20,2) NOT NULL DEFAULT '0.00',
  `bank` int(5) NOT NULL,
  `banknum` varchar(36) NOT NULL,
  `uname` varchar(36) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `bankname` varchar(36) NOT NULL,
  `ctime` int(11) NOT NULL,
  `random` int(10) NOT NULL,
  `tradeno` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL COMMENT '1:网上支付 2：支付宝手动',
  `fee` float(30,2) NOT NULL COMMENT '充值手续费',
  `actual` decimal(20,2) NOT NULL COMMENT '实际到账',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=272 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_fill
-- ----------------------------
INSERT INTO `yang_fill` VALUES ('270', '104', '100.00', '0', '', '李万彬', '', '1106', '1484459373', '6613', '20170115134933435796', '0', '1', '0.00', '99.50');
INSERT INTO `yang_fill` VALUES ('271', '104', '100.00', '0', '', '李万彬', '', '1106', '1484459444', '9002', '20170115135044569755', '1', '1', '0.00', '99.50');

-- ----------------------------
-- Table structure for `yang_finance`
-- ----------------------------
DROP TABLE IF EXISTS `yang_finance`;
CREATE TABLE `yang_finance` (
  `finance_id` int(32) NOT NULL AUTO_INCREMENT COMMENT '财务日志表',
  `member_id` int(32) NOT NULL COMMENT '用户id',
  `type` tinyint(4) NOT NULL COMMENT '类型',
  `content` text NOT NULL COMMENT '内容',
  `money_type` tinyint(4) NOT NULL COMMENT '收入=1/支出=2',
  `money` decimal(10,4) NOT NULL COMMENT '价格',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `currency_id` int(10) NOT NULL COMMENT '币种',
  `ip` varchar(64) NOT NULL,
  PRIMARY KEY (`finance_id`),
  KEY `种类` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=743 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_finance
-- ----------------------------
INSERT INTO `yang_finance` VALUES ('660', '101', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('661', '101', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('662', '102', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('663', '102', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('664', '103', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('665', '103', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('666', '104', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('667', '104', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('668', '100', '25', '推荐luo9**奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('669', '100', '25', '推荐luo9**奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('670', '100', '25', '推荐liumin2**奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('671', '100', '25', '推荐liumin2**奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('672', '100', '25', '推荐LM7896**奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('673', '100', '25', '推荐LM7896**奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('674', '100', '25', '推荐star**奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('675', '100', '25', '推荐star**奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('676', '105', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('677', '105', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('678', '100', '3', '管理员充值', '1', '20.0000', '1484457371', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('679', '100', '11', '交易手续费', '2', '0.0000', '1484457405', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('680', '100', '11', '交易手续费', '2', '0.0080', '1484457405', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('681', '100', '11', '交易手续费', '2', '0.0000', '1484457405', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('682', '103', '11', '交易手续费', '2', '0.0080', '1484457405', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('683', '100', '11', '交易手续费', '2', '0.0000', '1484457405', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('684', '101', '11', '交易手续费', '2', '0.0080', '1484457405', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('685', '101', '3', '管理员充值', '1', '20.0000', '1484457709', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('686', '101', '3', '管理员充值', '1', '10.0000', '1484457730', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('687', '103', '11', '交易手续费', '2', '0.0000', '1484457889', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('688', '101', '11', '交易手续费', '2', '0.0020', '1484457889', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('689', '103', '11', '交易手续费', '2', '0.0000', '1484457908', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('690', '101', '11', '交易手续费', '2', '0.0020', '1484457908', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('691', '101', '11', '交易手续费', '2', '0.0000', '1484458008', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('692', '103', '11', '交易手续费', '2', '0.0400', '1484458008', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('693', '106', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('694', '106', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('695', '104', '3', '管理员充值', '1', '20.0000', '1484458987', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('696', '104', '3', '管理员充值', '1', '100.0000', '1484459133', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('697', '104', '11', '交易手续费', '2', '0.0000', '1484459231', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('698', '104', '11', '交易手续费', '2', '0.0024', '1484459231', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('699', '108', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('700', '108', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('701', '100', '3', '管理员充值', '1', '100.0000', '1484459913', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('702', '100', '23', '提现100.00', '2', '99.5000', '1484459998', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('703', '107', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '119.4.254.39');
INSERT INTO `yang_finance` VALUES ('704', '107', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '119.4.254.39');
INSERT INTO `yang_finance` VALUES ('705', '109', '25', '被推荐奖励', '1', '2.0000', '1484409600', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('706', '109', '25', '被推荐奖励', '1', '2.0000', '1484409600', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('707', '100', '25', '推荐luo9**奖励', '1', '2.0000', '1484496000', '25', '113.233.31.24');
INSERT INTO `yang_finance` VALUES ('708', '103', '25', '被推荐奖励', '1', '2.0000', '1484496000', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('709', '102', '25', '被推荐奖励', '1', '2.0000', '1484496000', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('710', '101', '25', '被推荐奖励', '1', '2.0000', '1484496000', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('711', '100', '25', '推荐luo9**奖励', '1', '2.0000', '1484496000', '26', '118.113.32.121');
INSERT INTO `yang_finance` VALUES ('712', '104', '25', '被推荐奖励', '1', '2.0000', '1484496000', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('713', '101', '11', '交易手续费', '2', '0.0000', '1484530644', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('714', '103', '11', '交易手续费', '2', '0.0020', '1484530644', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('715', '101', '25', '被推荐奖励', '1', '2.0000', '1484496000', '26', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('716', '103', '3', '管理员充值', '1', '5000.0000', '1484531925', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('717', '103', '11', '交易手续费', '2', '0.0000', '1484531949', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('718', '101', '11', '交易手续费', '2', '6.0000', '1484531949', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('719', '106', '25', '被推荐奖励', '1', '2.0000', '1484496000', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('720', '106', '25', '被推荐奖励', '1', '2.0000', '1484496000', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('721', '105', '25', '被推荐奖励', '1', '2.0000', '1484496000', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('722', '105', '25', '被推荐奖励', '1', '2.0000', '1484496000', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('723', '101', '11', '交易手续费', '2', '0.0000', '1484532452', '25', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('724', '101', '11', '交易手续费', '2', '0.0020', '1484532452', '0', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('725', '101', '11', '交易手续费', '2', '0.0000', '1484532564', '25', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('726', '101', '11', '交易手续费', '2', '0.0020', '1484532564', '0', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('727', '101', '11', '交易手续费', '2', '0.0000', '1484532591', '25', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('728', '101', '11', '交易手续费', '2', '0.0020', '1484532591', '0', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('729', '101', '11', '交易手续费', '2', '0.0000', '1484532619', '25', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('730', '101', '11', '交易手续费', '2', '0.0040', '1484532619', '0', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('731', '101', '11', '交易手续费', '2', '0.0020', '1484532684', '0', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('732', '101', '11', '交易手续费', '2', '0.0000', '1484532684', '25', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('733', '103', '25', '被推荐奖励', '1', '2.0000', '1484496000', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('734', '101', '11', '交易手续费', '2', '0.0020', '1484532801', '0', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('735', '101', '11', '交易手续费', '2', '0.0000', '1484532801', '25', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('736', '100', '24', 'luo9**产生的交易奖励', '1', '0.0006', '1484532801', '0', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('737', '100', '24', 'luo9**产生的交易奖励', '1', '0.0006', '1484532801', '0', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('738', '102', '25', '被推荐奖励', '1', '2.0000', '1484496000', '26', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('739', '103', '11', '交易手续费', '2', '0.0000', '1484533004', '25', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('740', '101', '11', '交易手续费', '2', '0.4000', '1484533004', '0', '110.184.21.250');
INSERT INTO `yang_finance` VALUES ('741', '101', '11', '交易手续费', '2', '0.0000', '1484533091', '26', '113.233.26.79');
INSERT INTO `yang_finance` VALUES ('742', '101', '11', '交易手续费', '2', '0.0020', '1484533091', '0', '113.233.26.79');

-- ----------------------------
-- Table structure for `yang_finance_type`
-- ----------------------------
DROP TABLE IF EXISTS `yang_finance_type`;
CREATE TABLE `yang_finance_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_finance_type
-- ----------------------------
INSERT INTO `yang_finance_type` VALUES ('1', '会员升级', '1');
INSERT INTO `yang_finance_type` VALUES ('3', '管理员充值', '1');
INSERT INTO `yang_finance_type` VALUES ('4', '管理员扣款', '1');
INSERT INTO `yang_finance_type` VALUES ('5', '升级会员', '1');
INSERT INTO `yang_finance_type` VALUES ('6', '充值', '1');
INSERT INTO `yang_finance_type` VALUES ('8', '众筹扣款', '1');
INSERT INTO `yang_finance_type` VALUES ('9', '众筹获取', '1');
INSERT INTO `yang_finance_type` VALUES ('10', '分红奖励', '1');
INSERT INTO `yang_finance_type` VALUES ('11', '交易手续费', '1');
INSERT INTO `yang_finance_type` VALUES ('12', '推荐奖励', '1');
INSERT INTO `yang_finance_type` VALUES ('13', '分红股奖励', '1');
INSERT INTO `yang_finance_type` VALUES ('23', '提现', '1');
INSERT INTO `yang_finance_type` VALUES ('24', '下级交易奖励', '1');
INSERT INTO `yang_finance_type` VALUES ('25', '推广奖励', '0');

-- ----------------------------
-- Table structure for `yang_findpwd`
-- ----------------------------
DROP TABLE IF EXISTS `yang_findpwd`;
CREATE TABLE `yang_findpwd` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `member_id` int(32) NOT NULL,
  `token` varchar(100) NOT NULL,
  `add_time` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_findpwd
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_flash`
-- ----------------------------
DROP TABLE IF EXISTS `yang_flash`;
CREATE TABLE `yang_flash` (
  `flash_id` int(32) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL COMMENT '标题',
  `pic` varchar(128) NOT NULL,
  `jump_url` varchar(128) NOT NULL COMMENT '跳转地址',
  `sort` int(16) NOT NULL COMMENT '排序',
  `type` varchar(16) NOT NULL,
  `add_time` varchar(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`flash_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_flash
-- ----------------------------
INSERT INTO `yang_flash` VALUES ('10', '', '/Uploads/Public/Uploads/2017-01-07/5870a952b13ff.jpg', '', '0', '', '1483778386', '0');
INSERT INTO `yang_flash` VALUES ('9', '', '/Uploads/Public/Uploads/2017-01-07/5870a9622afab.jpg', '', '0', '', '1483778402', '0');
INSERT INTO `yang_flash` VALUES ('8', '标题2 ', '/Uploads/Public/Uploads/2017-01-05/586e04e31fbe4.jpg', '', '0', '', '1483605219', '0');
INSERT INTO `yang_flash` VALUES ('7', '标题1', '/Uploads/Public/Uploads/2017-01-05/586e04f158ae9.jpg', '', '0', '', '1483605233', '0');

-- ----------------------------
-- Table structure for `yang_follow`
-- ----------------------------
DROP TABLE IF EXISTS `yang_follow`;
CREATE TABLE `yang_follow` (
  `follow_id` int(32) NOT NULL AUTO_INCREMENT COMMENT '关注表',
  `member_id` int(32) NOT NULL COMMENT '用户id',
  `uid` int(32) NOT NULL COMMENT '关注人id',
  `add_time` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`follow_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_follow
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_issue`
-- ----------------------------
DROP TABLE IF EXISTS `yang_issue`;
CREATE TABLE `yang_issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '众筹表',
  `currency_id` int(11) NOT NULL,
  `num` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '发行数量',
  `price` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '发行价格',
  `deal` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '剩余数量',
  `ctime` varchar(32) NOT NULL COMMENT '添加时间',
  `money` decimal(32,2) NOT NULL COMMENT '总金额',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `min_limit` decimal(32,2) NOT NULL DEFAULT '0.00' COMMENT '最低购买',
  `limit` decimal(32,2) NOT NULL DEFAULT '0.00' COMMENT '限购数量',
  `limit_count` int(32) NOT NULL DEFAULT '1' COMMENT '限购次数',
  `title` varchar(32) NOT NULL,
  `info` text NOT NULL,
  `url1` varchar(64) NOT NULL,
  `url2` varchar(64) NOT NULL,
  `wenjian_url` varchar(64) NOT NULL COMMENT '上传文件路径',
  `num_nosell` decimal(64,0) NOT NULL,
  `add_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `zhongchou_success_bili` decimal(20,4) NOT NULL COMMENT '众筹成功比例',
  `buy_currency_id` int(11) NOT NULL,
  `is_forzen` tinyint(4) NOT NULL COMMENT '0冻结1是可用',
  `remove_forzen_bili` tinyint(4) NOT NULL,
  `remove_forzen_cycle` int(20) NOT NULL COMMENT 's解冻周期',
  `zc_awards_currency_id` varchar(32) NOT NULL,
  `zc_awards_one_ratio` varchar(32) NOT NULL,
  `zc_awards_two_ratio` varchar(32) NOT NULL,
  `zc_awards_status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yang_issue
-- ----------------------------
INSERT INTO `yang_issue` VALUES ('7', '25', '5000000000.000', '0.100', '4999000000.000', '1458316600', '0.00', '2', '0.00', '200000.00', '12', '众筹项目--1', '<div class=\"n5box1\" style=\"margin:0px auto;padding:50px 0px 70px;text-align:center;font-size:16px;color:#333333;font-family:\'microsoft yahei\';background-color:#EFEFEF;\">\r\n	<p>\r\n		本次众筹资金将全部用于项目的\r\n	</p>\r\n	<p>\r\n		租赁费、税费、装修费、招商及物业管理费，\r\n	</p>\r\n	<p>\r\n		众筹成功之后项目方将以投资数额为基准分配收益权，\r\n	</p>\r\n	<p>\r\n		2016年1月27日正式项目预热，\r\n	</p>\r\n	<p>\r\n		项目正式启动后开始计算收益，每三个月分红一次，\r\n	</p>\r\n	<p>\r\n		分红金额将通过投资者在平台开立的账户进行分配。\r\n	</p>\r\n	<p class=\"n5bg1\" style=\"color:#FFFFFF;background-color:#193854;\">\r\n		众筹用户总占股比例前三年80%，后三年67%，预期年回报率17.19%。\r\n	</p>\r\n</div>\r\n<div class=\"n5t2\" style=\"margin:0px;padding:0px;text-align:center;font-family:\'microsoft yahei\';\">\r\n	<img src=\"http://www.ybb.com/Public/Home/images/n5_5_w.png\" alt=\"\" />\r\n</div>\r\n<div class=\"n5box1 n5pt1\" style=\"margin:0px auto;padding:15px 0px 70px;text-align:center;font-size:16px;color:#333333;font-family:\'microsoft yahei\';background-color:#EFEFEF;\">\r\n	<ul>\r\n		<li style=\"text-align:left;background:url(&quot;\">\r\n			项目地块所处的科技园中区，随着高新区日益繁荣和成熟，<br />\r\n基于后续发展优势和自身区位等优势，在产业发展的带动下，<br />\r\n发展为以新材料、计算机、生物医药工程、新一代信息技术为主的高新技术产业密集区，区内物业价值具备强势的上升空间。未来科研办公的需求非常旺盛，平均出租率达9成以上，<br />\r\n项目所在物业一期园区出租率高于95%。\r\n		</li>\r\n		<li style=\"text-align:left;background:url(&quot;\">\r\n			爱创•StarUp创客中心由物业7-12层改造，共6层（物业总共25层）。<br />\r\n旨在为中小企业提供开放，融合的办公环境。<br />\r\n本次众筹标的将分为3期进行，<br />\r\n本次众筹为项目第1期，众筹金额160万。\r\n		</li>\r\n	</ul>\r\n</div>\r\n<div class=\"n5box1 n5pt3\" style=\"margin:0px auto;padding:0px;text-align:center;font-size:16px;color:#333333;font-family:\'microsoft yahei\';background-color:#EFEFEF;\">\r\n	<img src=\"http://www.ybb.com/Public/Home/images/n5_6_w.png\" alt=\"\" />\r\n</div>', '/Uploads/Public/Uploads/2016-03-18/56eb9fdd501bd.jpg', '/Uploads/Public/Uploads/2016-03-18/56eb9f13487ab.jpg', '', '1000000', '1458316800', '1482827329', '0.2000', '0', '0', '0', '0', '', '', '', '0');
INSERT INTO `yang_issue` VALUES ('9', '25', '10000.000', '0.100', '8000.000', '1459910808', '0.00', '2', '0.00', '1000.00', '50', '测试', '内容', '/Uploads/Public/Uploads/2016-03-25/56f528fb0b71b.jpg', '/Uploads/Public/Uploads/2016-03-18/56ebddd33d090.jpg', '', '2000', '1458144000', '1482827329', '0.5000', '0', '0', '0', '0', '', '', '', '0');
INSERT INTO `yang_issue` VALUES ('10', '26', '100000.000', '0.100', '98500.000', '1460602023', '0.00', '2', '500.00', '5000.00', '2', '重酬测试', '44545544545544554878744587554458754', '/Uploads/Public/Uploads/2016-04-14/570efebfb71b0.png', '/Uploads/Public/Uploads/2016-04-14/570efebfd1cef.png', '', '500', '1460600628', '1482827329', '0.2000', '27', '0', '10', '0', '26', '10', '5', '1');

-- ----------------------------
-- Table structure for `yang_issue_log`
-- ----------------------------
DROP TABLE IF EXISTS `yang_issue_log`;
CREATE TABLE `yang_issue_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '认筹日志表',
  `iid` int(11) NOT NULL COMMENT '认筹表id',
  `uid` int(11) NOT NULL,
  `num` decimal(32,0) NOT NULL,
  `price` decimal(32,3) NOT NULL,
  `add_time` varchar(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `buy_currency_id` int(11) NOT NULL,
  `deal` decimal(32,0) NOT NULL COMMENT '冻结剩余量',
  `cid` int(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `iid` (`iid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_issue_log
-- ----------------------------
INSERT INTO `yang_issue_log` VALUES ('30', '10', '59', '500', '0.100', '1483515175', '0', '27', '0', '26');
INSERT INTO `yang_issue_log` VALUES ('31', '10', '59', '500', '0.100', '1483515175', '0', '27', '0', '26');

-- ----------------------------
-- Table structure for `yang_link`
-- ----------------------------
DROP TABLE IF EXISTS `yang_link`;
CREATE TABLE `yang_link` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `tupian` varchar(125) NOT NULL,
  `url` varchar(125) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_link
-- ----------------------------
INSERT INTO `yang_link` VALUES ('1', '百币网', '/Uploads/Public/Uploads/2017-01-06/586ef16c7db2f.jpg', 'www.100bi.com', '1', '1483680801');
INSERT INTO `yang_link` VALUES ('3', '聚币网', '/Uploads/Public/Uploads/2017-01-06/586f2a34a14f2.jpg', 'www.jubi.com/', '1', '1483680308');
INSERT INTO `yang_link` VALUES ('6', '比特币中文网', '/Uploads/Public/Uploads/2017-01-06/586ef5a177e46.jpg', 'www.bitecoin.com', '1', '1483666879');
INSERT INTO `yang_link` VALUES ('7', '元宝网', '/Uploads/Public/Uploads/2017-01-06/586ef55abb8f3.jpg', 'www.yuanbao.com', '1', '1483680790');
INSERT INTO `yang_link` VALUES ('8', '玩币族', '/Uploads/Public/Uploads/2017-01-06/586ef538d61a1.jpg', 'http://www.wanbizu.com/', '1', '1483679312');
INSERT INTO `yang_link` VALUES ('9', 'BTC100', '/Uploads/Public/Uploads/2017-01-06/586ef1f837943.jpg', 'www.btc100.com', '1', '1483680782');
INSERT INTO `yang_link` VALUES ('10', '火币', '/Uploads/Public/Uploads/2017-01-06/586ef47a87cd2.jpg', 'www.huobi.com', '1', '1483680796');
INSERT INTO `yang_link` VALUES ('11', '蚂蚁矿池', '/Uploads/Public/Uploads/2017-01-06/586ef194b5053.jpg', 'www.antpool.com', '1', '1483666912');
INSERT INTO `yang_link` VALUES ('12', '比特币交易网', '/Uploads/Public/Uploads/2017-01-06/586ef4c143433.jpg', 'www.btctrade.com', '1', '1483666900');
INSERT INTO `yang_link` VALUES ('13', '比特币资讯网', '/Uploads/Public/Uploads/2017-01-06/586ef1bd9063b.jpg', 'www.bitcoin86.com', '1', '1483666918');
INSERT INTO `yang_link` VALUES ('14', '亚洲区块链基金会', '/Uploads/Public/Uploads/2017-01-06/586ef44342c46.jpg', 'https://asiablockchainfoundation.org//', '1', '1483679108');
INSERT INTO `yang_link` VALUES ('15', '币看', '/Uploads/Public/Uploads/2017-01-06/586ef1a1a102a.jpg', 'www.bitkan.com', '1', '1483666924');

-- ----------------------------
-- Table structure for `yang_member`
-- ----------------------------
DROP TABLE IF EXISTS `yang_member`;
CREATE TABLE `yang_member` (
  `member_id` int(32) NOT NULL AUTO_INCREMENT,
  `openid` varchar(128) NOT NULL,
  `email` varchar(32) NOT NULL COMMENT '邮箱',
  `pwd` varchar(64) NOT NULL COMMENT '密码',
  `pid` varchar(64) NOT NULL COMMENT '邀请人',
  `pwdtrade` varchar(64) NOT NULL COMMENT '支付密码',
  `nick` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `name` varchar(32) NOT NULL COMMENT '真实姓名',
  `cardtype` varchar(4) NOT NULL DEFAULT '1' COMMENT '1=身份证2=护照',
  `idcard` varchar(20) NOT NULL COMMENT '身份证',
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `ip` varchar(64) NOT NULL COMMENT '注册IP',
  `reg_time` int(32) NOT NULL COMMENT '注册时间',
  `login_ip` varchar(64) NOT NULL COMMENT '本次登录IP',
  `login_time` int(10) NOT NULL COMMENT '登录时间',
  `vip_level` int(10) NOT NULL COMMENT 'vip等级',
  `vip_end_time` int(10) NOT NULL COMMENT 'vip到期时间',
  `rmb` decimal(20,4) NOT NULL COMMENT '人民币',
  `forzen_rmb` decimal(20,4) NOT NULL COMMENT 'forzen_rmb',
  `head` varchar(64) NOT NULL COMMENT 'head',
  `profile` text NOT NULL COMMENT '个人简介',
  `province` int(10) NOT NULL COMMENT '省市',
  `city` int(10) NOT NULL COMMENT '城市',
  `job` varchar(64) NOT NULL COMMENT '职位/头衔',
  `is_lock` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0是正常 1是锁定',
  `status` tinyint(4) NOT NULL COMMENT '0=有效未填写个人信息1=有效并且填写完个人信息2=无效',
  `dividend_num` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `threepwd` varchar(64) DEFAULT '',
  PRIMARY KEY (`member_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_member
-- ----------------------------
INSERT INTO `yang_member` VALUES ('100', '', 'zcw222', 'e10adc3949ba59abbe56e057f20f883e', '', 'fcea920f7412b5da7be0cf42b8c93759', 'zcw222', '周成微', '1', '511381199404055101', '18783882785', '110.184.21.250', '1484448490', '', '1484533386', '0', '0', '11.9920', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('101', '', 'luo999', 'e10adc3949ba59abbe56e057f20f883e', '100', '18e27973a434326ff5eb837fee86b855', 'luo999', '罗霞', '1', '51072219920417', '18200386616', '110.184.21.250', '1484448656', '', '1484533175', '0', '0', '3198.5852', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('102', '', 'liumin222', '96fd595341b5053a985e30aae3456f6b', '100', 'b4334bc740666de767541ea8e8cdf0ac', 'liumin222', '刘敏', '1', '513029199304086103', '18349263271', '110.184.21.250', '1484448749', '', '1484532885', '0', '0', '0.0000', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('103', '', 'LM789632', '5cd885adda1894188e18f4624357eae5', '100', 'f38da720416ad5428c58a03bdaf99d75', '敏儿', '李敏', '1', '511025197602118242', '15883251417', '110.184.21.250', '1484449337', '', '1484532783', '0', '0', '1822.9500', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('104', '', 'starry', '0b3bf54e150243f779cd941b1c9ac5ad', '100', 'f1c11ec1b904d563068db8709de1d710', 'starry', '李万彬', '1', '51152319921216559X', '18882372781', '110.184.21.250', '1484449922', '', '1484529788', '0', '0', '119.4976', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('105', '', 'Ln8581', 'e10adc3949ba59abbe56e057f20f883e', '100', 'df10ef8509dc176d733d59549e7dbfaf', '大圣', '张三', '1', '211322198306256745', '18642034365', '110.184.21.250', '1484450331', '', '1484532200', '0', '0', '0.0000', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('106', '', 'zhangzhimei', '69db25c414ed261ae38de40cd5ae5256', '100', '195d91be1e3ba6f1c857d46f24c5a454', 'zhangzhimei', '张志梅', '1', '512930197501250743', '13880150908', '110.184.21.250', '1484457870', '', '1484532049', '0', '0', '0.0000', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('107', '', 'luoyiyy', '2675e01bc433f7ffcfed0c4bce59f40c', '100', '058786e44f9a665070795b3daa793717', '尼古拉斯二娃不二', '罗毅', '1', '511623199106295716', '18684025725', '119.4.254.39', '1484458915', '', '1484461396', '0', '0', '0.0000', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('108', '', 'cassie', '670b14728ad9902aecba32e22fa4f6bd', '100', '54c3998236e5d08cb15ddb4b50700eb2', 'cassie', 'lu', '1', '510105199010243523', '13688452294', '110.184.21.250', '1484459122', '', '1484459345', '0', '0', '0.0000', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('109', '', 'fyj123', 'e10adc3949ba59abbe56e057f20f883e', '100', 'c33367701511b4f6020ec61ded352059', 'fyj123', '冯勇军', '1', '510232197603010632', '17760330819', '110.184.21.250', '1484463018', '', '1484463175', '0', '0', '0.0000', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('110', '', 'Lsq2679653', '12d51f5d8428fa9cb4b2df17cfdbe5d6', '100', '0f451b3b3182a2de1890ca0028e68340', 'Lsq2679653', '林顺群', '1', '512530197101252960', '17711016381', '171.220.56.5', '1484477341', '', '0', '0', '0', '0.0000', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');
INSERT INTO `yang_member` VALUES ('111', '', 'asdf22', '6a204bd89f3c8348afd5c77c717a097a', '100', 'a95c530a7af5f492a74499e70578d150', 'aaa', 'sss', '1', '211481199311306439', '13352466960', '113.233.31.24', '1484528818', '', '1484530303', '0', '0', '0.0000', '0.0000', '', '', '0', '0', '', '0', '1', '0.0000', '');

-- ----------------------------
-- Table structure for `yang_member_comment`
-- ----------------------------
DROP TABLE IF EXISTS `yang_member_comment`;
CREATE TABLE `yang_member_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL,
  `currency_id` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `comment` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_member_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_member_old`
-- ----------------------------
DROP TABLE IF EXISTS `yang_member_old`;
CREATE TABLE `yang_member_old` (
  `member_id` int(32) NOT NULL AUTO_INCREMENT,
  `email` varchar(32) NOT NULL COMMENT '邮箱',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `paypwd` varchar(64) NOT NULL COMMENT '邀请人',
  `nike` varchar(32) NOT NULL,
  `realname` varchar(32) NOT NULL,
  `document_type` tinyint(4) NOT NULL,
  `document_num` varchar(32) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `reg_time` int(32) NOT NULL,
  `login_time` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `vip_end_time` int(10) NOT NULL,
  `rmb` decimal(10,2) NOT NULL,
  `forzen_rmb` decimal(10,2) NOT NULL,
  `head` varchar(64) NOT NULL,
  `profile` text NOT NULL,
  `city` int(10) NOT NULL,
  `district` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_member_old
-- ----------------------------
INSERT INTO `yang_member_old` VALUES ('1', 'admin@qq.com', '', '', '', '', '0', '', '', '0', '0', '0', '0', '0.00', '0.00', '', '', '0', '0', '0');

-- ----------------------------
-- Table structure for `yang_message`
-- ----------------------------
DROP TABLE IF EXISTS `yang_message`;
CREATE TABLE `yang_message` (
  `message_id` int(32) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL COMMENT '消息标题',
  `member_id` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `content` text NOT NULL,
  `add_time` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `message_all_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_message
-- ----------------------------
INSERT INTO `yang_message` VALUES ('193', '管理员充值', '100', '-2', '管理员充值人民币:20', '1484457371', '1', '161');
INSERT INTO `yang_message` VALUES ('194', '百币网交易平台上线送百年通宝', '104', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458289', '1', '164');
INSERT INTO `yang_message` VALUES ('195', '百币网交易平台上线送百年通宝', '104', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458423', '1', '165');
INSERT INTO `yang_message` VALUES ('196', '百币网交易平台上线送百年通宝', '104', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458666', '1', '166');
INSERT INTO `yang_message` VALUES ('197', '百币网交易平台上线送百年通宝', '100', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458289', '0', '164');
INSERT INTO `yang_message` VALUES ('198', '百币网交易平台上线送百年通宝', '100', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458423', '0', '165');
INSERT INTO `yang_message` VALUES ('199', '百币网交易平台上线送百年通宝', '100', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458666', '0', '166');
INSERT INTO `yang_message` VALUES ('200', '管理员充值', '100', '-2', '管理员充值人民币:100', '1484459913', '0', '169');
INSERT INTO `yang_message` VALUES ('201', 'CNY提现成功', '100', '-2', '恭喜您提现100.00成功！', '1484459998', '0', '170');
INSERT INTO `yang_message` VALUES ('202', '管理员充值', '104', '-2', '管理员充值人民币:20', '1484458987', '1', '167');
INSERT INTO `yang_message` VALUES ('203', '管理员充值', '104', '-2', '管理员充值明星币:100', '1484459133', '1', '168');
INSERT INTO `yang_message` VALUES ('204', '百币网交易平台上线送百年通宝', '103', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458289', '0', '164');
INSERT INTO `yang_message` VALUES ('205', '百币网交易平台上线送百年通宝', '103', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458423', '0', '165');
INSERT INTO `yang_message` VALUES ('206', '百币网交易平台上线送百年通宝', '103', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458666', '0', '166');
INSERT INTO `yang_message` VALUES ('207', '百币网交易平台上线送百年通宝', '102', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458289', '0', '164');
INSERT INTO `yang_message` VALUES ('208', '百币网交易平台上线送百年通宝', '102', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458423', '0', '165');
INSERT INTO `yang_message` VALUES ('209', '百币网交易平台上线送百年通宝', '102', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458666', '0', '166');
INSERT INTO `yang_message` VALUES ('210', '管理员充值', '101', '-2', '管理员充值人民币:20', '1484457709', '0', '162');
INSERT INTO `yang_message` VALUES ('211', '管理员充值', '101', '-2', '管理员充值百年通宝:10', '1484457730', '0', '163');
INSERT INTO `yang_message` VALUES ('212', '百币网交易平台上线送百年通宝', '101', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458289', '0', '164');
INSERT INTO `yang_message` VALUES ('213', '百币网交易平台上线送百年通宝', '101', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458423', '1', '165');
INSERT INTO `yang_message` VALUES ('214', '百币网交易平台上线送百年通宝', '101', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458666', '0', '166');
INSERT INTO `yang_message` VALUES ('215', '百币网交易平台上线送百年通宝', '106', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458289', '0', '164');
INSERT INTO `yang_message` VALUES ('216', '百币网交易平台上线送百年通宝', '106', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458423', '0', '165');
INSERT INTO `yang_message` VALUES ('217', '百币网交易平台上线送百年通宝', '106', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458666', '1', '166');
INSERT INTO `yang_message` VALUES ('218', '百币网交易平台上线送百年通宝', '105', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458289', '0', '164');
INSERT INTO `yang_message` VALUES ('219', '百币网交易平台上线送百年通宝', '105', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458423', '0', '165');
INSERT INTO `yang_message` VALUES ('220', '百币网交易平台上线送百年通宝', '105', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458666', '0', '166');
INSERT INTO `yang_message` VALUES ('221', '百币网交易平台获得合法手续', '101', '-1', '百币网交易平台获得合法手续<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532465', '0', '172');
INSERT INTO `yang_message` VALUES ('222', '百币网交易平台上线送百年通宝', '101', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532637', '0', '173');
INSERT INTO `yang_message` VALUES ('223', '百币网加强反洗钱措施的公告', '101', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532727', '0', '174');
INSERT INTO `yang_message` VALUES ('224', '管理员充值', '103', '-2', '管理员充值人民币:5000', '1484531925', '0', '171');
INSERT INTO `yang_message` VALUES ('225', '百币网交易平台获得合法手续', '103', '-1', '百币网交易平台获得合法手续<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532465', '0', '172');
INSERT INTO `yang_message` VALUES ('226', '百币网交易平台上线送百年通宝', '103', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532637', '0', '173');
INSERT INTO `yang_message` VALUES ('227', '百币网加强反洗钱措施的公告', '103', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532727', '0', '174');
INSERT INTO `yang_message` VALUES ('228', '百币网交易平台获得合法手续', '100', '-1', '百币网交易平台获得合法手续<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532465', '0', '172');
INSERT INTO `yang_message` VALUES ('229', '百币网交易平台上线送百年通宝', '100', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532637', '0', '173');
INSERT INTO `yang_message` VALUES ('230', '百币网加强反洗钱措施的公告', '100', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532727', '0', '174');
INSERT INTO `yang_message` VALUES ('231', '百币网加强反洗钱措施的公告', '100', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532807', '0', '175');
INSERT INTO `yang_message` VALUES ('232', '百币网交易平台获得合法手续', '102', '-1', '百币网交易平台获得合法手续<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532465', '0', '172');
INSERT INTO `yang_message` VALUES ('233', '百币网交易平台上线送百年通宝', '102', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532637', '0', '173');
INSERT INTO `yang_message` VALUES ('234', '百币网加强反洗钱措施的公告', '102', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532727', '0', '174');
INSERT INTO `yang_message` VALUES ('235', '百币网加强反洗钱措施的公告', '102', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532807', '0', '175');
INSERT INTO `yang_message` VALUES ('236', '春节百币网充值送大礼', '102', '-1', '春节百币网充值送大礼<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532873', '0', '176');
INSERT INTO `yang_message` VALUES ('237', '百币网加强反洗钱措施的公告', '101', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532807', '0', '175');
INSERT INTO `yang_message` VALUES ('238', '春节百币网充值送大礼', '101', '-1', '春节百币网充值送大礼<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532873', '0', '176');
INSERT INTO `yang_message` VALUES ('239', '春节百币网充值送大礼', '100', '-1', '春节百币网充值送大礼<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532873', '0', '176');
INSERT INTO `yang_message` VALUES ('240', '虚拟数字资产转换积分商城购物', '101', '-1', '虚拟数字资产转换积分商城购物<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484533163', '0', '177');
INSERT INTO `yang_message` VALUES ('241', '虚拟数字资产转换积分商城购物', '100', '-1', '虚拟数字资产转换积分商城购物<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484533163', '0', '177');

-- ----------------------------
-- Table structure for `yang_message_all`
-- ----------------------------
DROP TABLE IF EXISTS `yang_message_all`;
CREATE TABLE `yang_message_all` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL COMMENT '消息标题',
  `u_id` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `content` text NOT NULL,
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_message_all
-- ----------------------------
INSERT INTO `yang_message_all` VALUES ('161', '管理员充值', '100', '-2', '管理员充值人民币:20', '1484457371');
INSERT INTO `yang_message_all` VALUES ('162', '管理员充值', '101', '-2', '管理员充值人民币:20', '1484457709');
INSERT INTO `yang_message_all` VALUES ('163', '管理员充值', '101', '-2', '管理员充值百年通宝:10', '1484457730');
INSERT INTO `yang_message_all` VALUES ('164', '百币网交易平台上线送百年通宝', '-1', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458289');
INSERT INTO `yang_message_all` VALUES ('165', '百币网交易平台上线送百年通宝', '-1', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458423');
INSERT INTO `yang_message_all` VALUES ('166', '百币网交易平台上线送百年通宝', '-1', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484458666');
INSERT INTO `yang_message_all` VALUES ('167', '管理员充值', '104', '-2', '管理员充值人民币:20', '1484458987');
INSERT INTO `yang_message_all` VALUES ('168', '管理员充值', '104', '-2', '管理员充值明星币:100', '1484459133');
INSERT INTO `yang_message_all` VALUES ('169', '管理员充值', '100', '-2', '管理员充值人民币:100', '1484459913');
INSERT INTO `yang_message_all` VALUES ('170', 'CNY提现成功', '100', '-2', '恭喜您提现100.00成功！', '1484459998');
INSERT INTO `yang_message_all` VALUES ('171', '管理员充值', '103', '-2', '管理员充值人民币:5000', '1484531925');
INSERT INTO `yang_message_all` VALUES ('172', '百币网交易平台获得合法手续', '-1', '-1', '百币网交易平台获得合法手续<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532465');
INSERT INTO `yang_message_all` VALUES ('173', '百币网交易平台上线送百年通宝', '-1', '-1', '百币网交易平台上线送百年通宝<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532637');
INSERT INTO `yang_message_all` VALUES ('174', '百币网加强反洗钱措施的公告', '-1', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532727');
INSERT INTO `yang_message_all` VALUES ('175', '百币网加强反洗钱措施的公告', '-1', '-1', '百币网加强反洗钱措施的公告<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532807');
INSERT INTO `yang_message_all` VALUES ('176', '春节百币网充值送大礼', '-1', '-1', '春节百币网充值送大礼<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484532873');
INSERT INTO `yang_message_all` VALUES ('177', '虚拟数字资产转换积分商城购物', '-1', '-1', '虚拟数字资产转换积分商城购物<br/><a href=/Home/Art/details/id/1.html>点击显示详情</a>', '1484533163');

-- ----------------------------
-- Table structure for `yang_message_category`
-- ----------------------------
DROP TABLE IF EXISTS `yang_message_category`;
CREATE TABLE `yang_message_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_message_category
-- ----------------------------
INSERT INTO `yang_message_category` VALUES ('-2', '个人信息');
INSERT INTO `yang_message_category` VALUES ('4', '系统消息');
INSERT INTO `yang_message_category` VALUES ('-1', '系统公告');

-- ----------------------------
-- Table structure for `yang_nav`
-- ----------------------------
DROP TABLE IF EXISTS `yang_nav`;
CREATE TABLE `yang_nav` (
  `nav_id` int(32) NOT NULL AUTO_INCREMENT COMMENT '权限表',
  `nav_name` varchar(32) NOT NULL COMMENT '列表名称',
  `nav_e` varchar(32) NOT NULL COMMENT '英文标识',
  `nav_url` varchar(64) NOT NULL COMMENT 'url路径',
  `cat_id` varchar(32) NOT NULL COMMENT '类别',
  PRIMARY KEY (`nav_id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_nav
-- ----------------------------
INSERT INTO `yang_nav` VALUES ('1', '系统设置', '&#xe642;', '/Config/index', 'sys');
INSERT INTO `yang_nav` VALUES ('2', '幻灯管理', '&#xf002c;', '/Flash/index', 'common');
INSERT INTO `yang_nav` VALUES ('3', '币种管理', '&#xe756;', '/Currency/index', 'wallet');
INSERT INTO `yang_nav` VALUES ('4', '官方公告管理', '&#xe6f7;', '/Art/index/article_category_id/1', 'article');
INSERT INTO `yang_nav` VALUES ('6', '友情链接', '&#xe602;', '/Link/index', 'common');
INSERT INTO `yang_nav` VALUES ('7', '会员列表', '&#x3434;', '/Member/index', 'user');
INSERT INTO `yang_nav` VALUES ('8', '添加会员', '&#xe62f;', '/Member/addMember', 'user');
INSERT INTO `yang_nav` VALUES ('15', '提现审核', '&#xe6a7;', '/Pending/index', 'finance');
INSERT INTO `yang_nav` VALUES ('9', '系统消息', '&#xe627;', '/Message/index', 'user');
INSERT INTO `yang_nav` VALUES ('71', '媒体公告', '&#xe6f7;', '/Art/index/article_category_id/3', 'article');
INSERT INTO `yang_nav` VALUES ('11', '人工充值管理', '&#xe61e;', '/Pay/payByMan', 'finance');
INSERT INTO `yang_nav` VALUES ('12', '财务日志', '&#xe659;', '/Finance/index', 'finance');
INSERT INTO `yang_nav` VALUES ('65', '推荐奖励设置', '&#xe6f7;', '/Reward/index', 'sys');
INSERT INTO `yang_nav` VALUES ('66', '推荐奖励设置', '&#xe6f7;', '/Reward/index', 'sys');
INSERT INTO `yang_nav` VALUES ('17', '委托记录', '&#xecf6;', '/Trade/orders', 'trade');
INSERT INTO `yang_nav` VALUES ('16', '交易记录', '&#xe608;', '/Trade/trade', 'trade');
INSERT INTO `yang_nav` VALUES ('21', '充币记录', '&#xe604;', '/Currency/chongzhi_index', 'wallet');
INSERT INTO `yang_nav` VALUES ('22', '提币记录', '&#xe601;', '/Currency/tibi_index', 'wallet');
INSERT INTO `yang_nav` VALUES ('26', '添加分红奖励', '&#xe617;', '/Bonus/index', 'bonus');
INSERT INTO `yang_nav` VALUES ('27', '信息设置', '&#xe642;', '/Config/information', 'sys');
INSERT INTO `yang_nav` VALUES ('28', '财务设置', '&#xe61d;', '/Config/finance', 'sys');
INSERT INTO `yang_nav` VALUES ('29', '客服设置', '&#xe77f;', '/Config/customerService', 'sys');
INSERT INTO `yang_nav` VALUES ('30', '短信邮箱设置', '&#xe6ef;', '/Config/shortMessage', 'sys');
INSERT INTO `yang_nav` VALUES ('18', '全站统计信息', '&#xe73e;', '/Index/infoStatistics', 'common');
INSERT INTO `yang_nav` VALUES ('69', '比特币充币审核', '&#xe6f7;', '/Pay/btc_recharge', 'finance');
INSERT INTO `yang_nav` VALUES ('70', '比特币提币审核', '&#xe6f7;', '/Pay/btc_tibi', 'finance');
INSERT INTO `yang_nav` VALUES ('35', '会员钱包充值列表', '&#xe60e;', '/CurrencyUser/MemberQianbaoChongzhiUrl', 'wallet');
INSERT INTO `yang_nav` VALUES ('36', '会员钱包提币列表', '&#xe640;', '/CurrencyUser/MemberQianbaoTibiUrl', 'wallet');
INSERT INTO `yang_nav` VALUES ('40', '分红列表', '&#xe617;', '/Bonus/bonusList', 'bonus');
INSERT INTO `yang_nav` VALUES ('44', '下载管理', '&#xe601;', '/Download/index', 'wallet');
INSERT INTO `yang_nav` VALUES ('41', '管理员管理', '&#xe64d;', '/Manage/index', 'admin');
INSERT INTO `yang_nav` VALUES ('52', '财务明细', '&#xe73e;', '/Finance/count', 'finance');
INSERT INTO `yang_nav` VALUES ('67', '推荐奖励设置', '&#xe73e;', '/Reward/index', 'sys');
INSERT INTO `yang_nav` VALUES ('45', '管理员充值管理', '&#xe61e;', '/Pay/admRecharge', 'finance');
INSERT INTO `yang_nav` VALUES ('46', '后台入口配置管理', '&#xe642;', '/Fileconfigoperation/saveEntrance', 'sys');
INSERT INTO `yang_nav` VALUES ('47', '数据库配置管理', '&#xe642;', '/Fileconfigoperation/saveDb', 'sys');
INSERT INTO `yang_nav` VALUES ('48', '分红股管理', '&#xe617;', '/Dividend/index', 'bonus');
INSERT INTO `yang_nav` VALUES ('49', '市场动态管理', '&#xe6f7;', '/Art/index/article_category_id/2', 'article');
INSERT INTO `yang_nav` VALUES ('50', '帮助中心管理', '&#xe6f7;', '/Art/helpindex/article_category_id/6', 'article');
INSERT INTO `yang_nav` VALUES ('51', '团队信息管理', '&#xe6f7;', '/Art/index/article_category_id/7', 'article');
INSERT INTO `yang_nav` VALUES ('56', '第三方充值记录', '&#xe6f7;', '/Pay/fill', 'finance');
INSERT INTO `yang_nav` VALUES ('13', '众筹管理', '&#xe73e;', '/Zhongchou/index', 'zhongchou');
INSERT INTO `yang_nav` VALUES ('14', '众筹记录', '&#xe73e;', '/Zhongchou/log', 'zhongchou');
INSERT INTO `yang_nav` VALUES ('43', '众筹推荐奖励列表', '&#xe601;', '/Zhongchou/awardsList', 'zhongchou');
INSERT INTO `yang_nav` VALUES ('61', '餐厅管理', '&#xe6f7;', '/Restaurant/index', 'restaurant');
INSERT INTO `yang_nav` VALUES ('62', '添加餐厅', '&#xe6f7;', '/Restaurant/add', 'restaurant');
INSERT INTO `yang_nav` VALUES ('63', '餐厅轮播', '&#xe6f7;', '/Restaurant/flash', 'restaurant');
INSERT INTO `yang_nav` VALUES ('64', '推荐奖励设置', '&#xe6f7;', '/Reward/index', 'sys');
INSERT INTO `yang_nav` VALUES ('72', '充值银行账户', '&#xe6f7;', '/Websitebank/index', 'bank');
INSERT INTO `yang_nav` VALUES ('73', '货币详情', '&#xe6f7;', '/Art/index/article_category_id/5', 'article');

-- ----------------------------
-- Table structure for `yang_orders`
-- ----------------------------
DROP TABLE IF EXISTS `yang_orders`;
CREATE TABLE `yang_orders` (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `currency_id` int(10) NOT NULL COMMENT '主币种ID',
  `currency_trade_id` int(10) NOT NULL COMMENT '对应交易币种ID',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `num` decimal(20,4) NOT NULL DEFAULT '0.0000' COMMENT '挂单数量',
  `trade_num` decimal(20,4) NOT NULL COMMENT '成交数量',
  `fee` decimal(20,4) NOT NULL DEFAULT '0.0000' COMMENT '记录的是比例',
  `type` char(4) NOT NULL DEFAULT '0' COMMENT 'buy sell',
  `add_time` int(10) NOT NULL,
  `trade_time` int(10) NOT NULL COMMENT '成交时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0是挂单，1是部分成交,2成交， -1撤销',
  PRIMARY KEY (`orders_id`),
  KEY `add_time` (`add_time`),
  KEY `cid` (`currency_id`),
  KEY `id` (`orders_id`),
  KEY `member_id` (`member_id`),
  KEY `trade_id` (`currency_trade_id`),
  KEY `member_id_2` (`member_id`,`currency_id`,`currency_trade_id`,`price`,`num`,`trade_num`,`type`,`status`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `currency_trade_id` (`currency_trade_id`),
  KEY `currency_id` (`currency_id`,`type`,`add_time`) USING BTREE,
  KEY `price` (`price`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_orders
-- ----------------------------
INSERT INTO `yang_orders` VALUES ('165', '102', '25', '0', '1.1000', '2.0000', '0.0000', '0.0020', 'sell', '1484449606', '0', '-1');
INSERT INTO `yang_orders` VALUES ('166', '101', '25', '0', '1.0000', '2.0000', '0.0000', '0.0020', 'sell', '1484457203', '0', '-1');
INSERT INTO `yang_orders` VALUES ('167', '100', '25', '0', '2.0000', '2.0000', '2.0000', '0.0020', 'sell', '1484457270', '1484457405', '2');
INSERT INTO `yang_orders` VALUES ('168', '103', '25', '0', '2.0000', '2.0000', '2.0000', '0.0020', 'sell', '1484457303', '1484457405', '2');
INSERT INTO `yang_orders` VALUES ('169', '101', '25', '0', '2.0000', '2.0000', '2.0000', '0.0020', 'sell', '1484457392', '1484457405', '2');
INSERT INTO `yang_orders` VALUES ('170', '100', '25', '0', '2.0000', '6.0000', '6.0000', '0.0000', 'buy', '1484457405', '1484457405', '2');
INSERT INTO `yang_orders` VALUES ('171', '101', '26', '0', '1.0000', '2.0000', '0.0000', '0.0020', 'sell', '1484457773', '0', '-1');
INSERT INTO `yang_orders` VALUES ('172', '101', '26', '0', '1.0000', '2.0000', '2.0000', '0.0020', 'sell', '1484457836', '1484457908', '2');
INSERT INTO `yang_orders` VALUES ('173', '103', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484457889', '1484457889', '2');
INSERT INTO `yang_orders` VALUES ('174', '103', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484457908', '1484457908', '2');
INSERT INTO `yang_orders` VALUES ('175', '103', '26', '0', '5.0000', '4.0000', '4.0000', '0.0020', 'sell', '1484457978', '1484458008', '2');
INSERT INTO `yang_orders` VALUES ('176', '101', '26', '0', '5.0000', '4.0000', '4.0000', '0.0000', 'buy', '1484458008', '1484458008', '2');
INSERT INTO `yang_orders` VALUES ('177', '104', '25', '0', '0.0120', '102.0000', '102.0000', '0.0020', 'sell', '1484459177', '1484459231', '2');
INSERT INTO `yang_orders` VALUES ('178', '104', '25', '0', '0.0120', '102.0000', '102.0000', '0.0000', 'buy', '1484459231', '1484459231', '2');
INSERT INTO `yang_orders` VALUES ('179', '104', '25', '0', '0.0110', '200.0000', '0.0000', '0.0000', 'buy', '1484466240', '0', '-1');
INSERT INTO `yang_orders` VALUES ('180', '103', '25', '0', '1.0000', '1.0000', '1.0000', '0.0020', 'sell', '1484530628', '1484530644', '2');
INSERT INTO `yang_orders` VALUES ('181', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484530644', '1484530644', '2');
INSERT INTO `yang_orders` VALUES ('182', '101', '26', '0', '2.0000', '2.0000', '0.0000', '0.0020', 'sell', '1484530806', '0', '-1');
INSERT INTO `yang_orders` VALUES ('183', '101', '26', '0', '300.0000', '10.0000', '10.0000', '0.0020', 'sell', '1484531884', '1484531949', '2');
INSERT INTO `yang_orders` VALUES ('184', '103', '26', '0', '300.0000', '10.0000', '10.0000', '0.0000', 'buy', '1484531949', '1484531949', '2');
INSERT INTO `yang_orders` VALUES ('185', '101', '25', '0', '1.0000', '2.0000', '0.0000', '0.0020', 'sell', '1484532414', '0', '-1');
INSERT INTO `yang_orders` VALUES ('186', '101', '25', '0', '1.0000', '1.0000', '0.0000', '0.0020', 'sell', '1484532435', '0', '-1');
INSERT INTO `yang_orders` VALUES ('191', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484532675', '1484532801', '2');
INSERT INTO `yang_orders` VALUES ('193', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0020', 'sell', '1484532801', '1484532801', '2');
INSERT INTO `yang_orders` VALUES ('194', '101', '25', '0', '100.0000', '2.0000', '2.0000', '0.0020', 'sell', '1484532982', '1484533004', '2');
INSERT INTO `yang_orders` VALUES ('195', '103', '25', '0', '100.0000', '2.0000', '2.0000', '0.0000', 'buy', '1484533004', '1484533004', '2');
INSERT INTO `yang_orders` VALUES ('196', '101', '26', '0', '1.0000', '1.0000', '1.0000', '0.0020', 'sell', '1484533084', '1484533091', '2');
INSERT INTO `yang_orders` VALUES ('197', '101', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484533091', '1484533091', '2');

-- ----------------------------
-- Table structure for `yang_pay`
-- ----------------------------
DROP TABLE IF EXISTS `yang_pay`;
CREATE TABLE `yang_pay` (
  `pay_id` int(32) NOT NULL AUTO_INCREMENT COMMENT '充值表',
  `member_name` varchar(32) NOT NULL COMMENT '汇款人',
  `add_time` int(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `account` varchar(128) NOT NULL COMMENT '汇出银行账号',
  `type` int(4) NOT NULL COMMENT '1是银行   2是支付宝 ,3管理员充值',
  `money` int(64) NOT NULL COMMENT '要充值钱数',
  `count` float(64,4) NOT NULL COMMENT '总量，等于充值数+手续费',
  `currency_id` int(32) DEFAULT '0',
  `member_id` varchar(32) NOT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_pay
-- ----------------------------
INSERT INTO `yang_pay` VALUES ('72', '周成微', '1484457371', '1', '', '3', '20', '0.0000', '0', '100', '0');
INSERT INTO `yang_pay` VALUES ('73', '罗霞', '1484457709', '1', '', '3', '20', '0.0000', '0', '101', '0');
INSERT INTO `yang_pay` VALUES ('74', '罗霞', '1484457730', '1', '', '3', '10', '0.0000', '26', '101', '0');
INSERT INTO `yang_pay` VALUES ('75', '李万彬', '1484458987', '1', '', '3', '20', '0.0000', '0', '104', '0');
INSERT INTO `yang_pay` VALUES ('76', '李万彬', '1484459133', '1', '', '3', '100', '0.0000', '25', '104', '0');
INSERT INTO `yang_pay` VALUES ('77', '周成微', '1484459913', '1', '', '3', '100', '0.0000', '0', '100', '0');
INSERT INTO `yang_pay` VALUES ('78', '周成微', '1484460090', '0', '18783882785', '0', '100', '100.0000', '0', '100', '0');
INSERT INTO `yang_pay` VALUES ('79', '李万彬', '1484464561', '0', '18882372781', '0', '100', '100.0000', '0', '104', '0');
INSERT INTO `yang_pay` VALUES ('80', '李敏', '1484531925', '1', '', '3', '5000', '0.0000', '0', '103', '0');
INSERT INTO `yang_pay` VALUES ('81', '李万彬', '1484533774', '0', '13693473921', '0', '1111', '1111.0000', '0', '104', '0');

-- ----------------------------
-- Table structure for `yang_payment`
-- ----------------------------
DROP TABLE IF EXISTS `yang_payment`;
CREATE TABLE `yang_payment` (
  `payment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `type` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `pay_no` varchar(32) NOT NULL,
  `add_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=352 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yang_payment
-- ----------------------------
INSERT INTO `yang_payment` VALUES ('1', '1011', '100.00', 'weixin', '0', '72277678', '1476861348', null);
INSERT INTO `yang_payment` VALUES ('2', '1014', '100.00', 'weixin', '0', '91513138', '1476861470', null);
INSERT INTO `yang_payment` VALUES ('3', '1011', '100.00', 'zhifubao', '1', '39984107825360074', '1476866247', '1476866338');
INSERT INTO `yang_payment` VALUES ('4', '21763', '1.00', 'zhifubao', '1', '66072104680630327', '1476874468', '1476874545');
INSERT INTO `yang_payment` VALUES ('5', '16807', '500.00', 'zhifubao', '0', '55153359677160541', '1476875055', null);
INSERT INTO `yang_payment` VALUES ('6', '224675', '100.00', 'zhifubao', '1', '10123801689727184', '1476876311', null);
INSERT INTO `yang_payment` VALUES ('7', '20168', '1.00', 'zhifubao', '1', '5281410946268572', '1476881690', '1476881748');
INSERT INTO `yang_payment` VALUES ('8', '147889', '100.00', 'zhifubao', '0', '41298199686285578', '1476884896', null);
INSERT INTO `yang_payment` VALUES ('9', '16807', '10.00', 'zhifubao', '1', '91001294882969613', '1476890273', '1476890309');
INSERT INTO `yang_payment` VALUES ('10', '81323', '10.00', 'zhifubao', '1', '89790730291661817', '1476928306', '1476928357');
INSERT INTO `yang_payment` VALUES ('11', '1014', '1.00', 'zhifubao', '1', '56892030318448123', '1476928512', '1476928556');
INSERT INTO `yang_payment` VALUES ('12', '16807', '68.00', 'zhifubao', '0', '96571835947815148', '1476929204', null);
INSERT INTO `yang_payment` VALUES ('13', '87941', '500.00', 'zhifubao', '1', '80469977964923116', '1476929279', '1476929304');
INSERT INTO `yang_payment` VALUES ('14', '16807', '68.00', 'zhifubao', '0', '31661111898368191', '1476929381', null);
INSERT INTO `yang_payment` VALUES ('15', '87941', '1500.00', 'zhifubao', '1', '59594728507619390', '1476929386', '1476929402');
INSERT INTO `yang_payment` VALUES ('16', '1504', '5.00', 'zhifubao', '1', '98380791353914059', '1476929750', '1476929782');
INSERT INTO `yang_payment` VALUES ('17', '126015', '10.00', 'zhifubao', '0', '92531637186642746', '1476929764', null);
INSERT INTO `yang_payment` VALUES ('18', '151160', '20.00', 'zhifubao', '0', '23058045606007860', '1476929931', null);
INSERT INTO `yang_payment` VALUES ('19', '151160', '10.00', 'zhifubao', '1', '34261732906515647', '1476929948', '1476929977');
INSERT INTO `yang_payment` VALUES ('20', '124470', '100.00', 'zhifubao', '0', '5651612939521386', '1476930326', null);
INSERT INTO `yang_payment` VALUES ('21', '1506', '10.00', 'zhifubao', '1', '24476363642370466', '1476930447', '1476930489');
INSERT INTO `yang_payment` VALUES ('22', '223907', '50.00', 'zhifubao', '1', '78371657984721630', '1476930554', '1476930644');
INSERT INTO `yang_payment` VALUES ('23', '3580', '100.00', 'zhifubao', '0', '66660382615816930', '1476930680', null);
INSERT INTO `yang_payment` VALUES ('24', '79659', '500.00', 'zhifubao', '1', '47665541674177743', '1476930791', '1476930843');
INSERT INTO `yang_payment` VALUES ('25', '62119', '1000.00', 'zhifubao', '0', '2435882985904311', '1476931194', null);
INSERT INTO `yang_payment` VALUES ('26', '62119', '100.00', 'zhifubao', '1', '9378339776837245', '1476931205', '1476931271');
INSERT INTO `yang_payment` VALUES ('27', '217444', '200.00', 'zhifubao', '1', '75675023940837519', '1476931222', '1476932107');
INSERT INTO `yang_payment` VALUES ('28', '177189', '200.00', 'zhifubao', '1', '10326566712921211', '1476931521', '1476932065');
INSERT INTO `yang_payment` VALUES ('29', '156965', '10.00', 'zhifubao', '0', '12394989107517299', '1476931557', null);
INSERT INTO `yang_payment` VALUES ('30', '156965', '10.00', 'zhifubao', '1', '71884892432030034', '1476931685', '1476931712');
INSERT INTO `yang_payment` VALUES ('31', '16685', '100.00', 'zhifubao', '1', '72385279957522269', '1476932681', '1476932742');
INSERT INTO `yang_payment` VALUES ('32', '178150', '100.00', 'zhifubao', '0', '63725286353088400', '1476933295', null);
INSERT INTO `yang_payment` VALUES ('33', '178150', '50.00', 'zhifubao', '1', '9242904991189675', '1476933312', '1476933355');
INSERT INTO `yang_payment` VALUES ('34', '22466', '1.00', 'zhifubao', '0', '17621084919650743', '1476933416', null);
INSERT INTO `yang_payment` VALUES ('35', '20343', '1.00', 'zhifubao', '0', '53712648714275375', '1476933425', null);
INSERT INTO `yang_payment` VALUES ('36', '22466', '1.00', 'zhifubao', '0', '57851026701777188', '1476933451', null);
INSERT INTO `yang_payment` VALUES ('37', '145527', '100.00', 'zhifubao', '1', '83148123315137038', '1476933583', '1476933710');
INSERT INTO `yang_payment` VALUES ('38', '15647', '20.00', 'zhifubao', '1', '74255481350853277', '1476933698', '1476933873');
INSERT INTO `yang_payment` VALUES ('39', '167592', '10.00', 'zhifubao', '1', '99931408110933109', '1476933881', '1476933926');
INSERT INTO `yang_payment` VALUES ('40', '79659', '500.00', 'zhifubao', '1', '56944381788372542', '1476934227', '1476934266');
INSERT INTO `yang_payment` VALUES ('41', '125897', '100.00', 'zhifubao', '0', '8904099125543170', '1476934322', null);
INSERT INTO `yang_payment` VALUES ('42', '16885', '1004.60', 'zhifubao', '0', '59963144550730545', '1476934336', null);
INSERT INTO `yang_payment` VALUES ('43', '115504', '20.00', 'zhifubao', '1', '41453202741688041', '1476934444', '1476934561');
INSERT INTO `yang_payment` VALUES ('44', '24497', '53.00', 'zhifubao', '1', '13574723862827380', '1476934481', '1476934652');
INSERT INTO `yang_payment` VALUES ('45', '224679', '100.00', 'zhifubao', '0', '73037770509069427', '1476934504', null);
INSERT INTO `yang_payment` VALUES ('46', '125897', '100.00', 'zhifubao', '1', '87859624407260455', '1476934576', '1476934622');
INSERT INTO `yang_payment` VALUES ('47', '1511', '100.00', 'zhifubao', '1', '81175610278469177', '1476934598', '1476934705');
INSERT INTO `yang_payment` VALUES ('48', '167592', '50.00', 'zhifubao', '1', '85188283356743280', '1476934686', '1476934722');
INSERT INTO `yang_payment` VALUES ('49', '16982', '50.00', 'zhifubao', '1', '29265099489193672', '1476934788', '1476934826');
INSERT INTO `yang_payment` VALUES ('50', '224679', '100.00', 'zhifubao', '0', '53831470191604907', '1476935104', null);
INSERT INTO `yang_payment` VALUES ('51', '224680', '100.00', 'zhifubao', '1', '9561601115502663', '1476936034', '1476936082');
INSERT INTO `yang_payment` VALUES ('52', '19487', '10.00', 'zhifubao', '0', '76557578319214036', '1476936147', null);
INSERT INTO `yang_payment` VALUES ('53', '62119', '200.00', 'zhifubao', '1', '8404739472500203', '1476937377', '1476937421');
INSERT INTO `yang_payment` VALUES ('54', '8702', '2.00', 'zhifubao', '1', '13168005491579573', '1476937512', '1476937596');
INSERT INTO `yang_payment` VALUES ('55', '62119', '300.00', 'zhifubao', '1', '15489475327545681', '1476937656', '1476937689');
INSERT INTO `yang_payment` VALUES ('56', '24011', '100.00', 'zhifubao', '1', '14580192105374569', '1476938369', '1476938416');
INSERT INTO `yang_payment` VALUES ('57', '224677', '100.00', 'zhifubao', '1', '84355195709373329', '1476938696', '1476938750');
INSERT INTO `yang_payment` VALUES ('58', '62119', '400.00', 'zhifubao', '1', '47218196345498414', '1476939050', null);
INSERT INTO `yang_payment` VALUES ('59', '51351', '180.00', 'zhifubao', '0', '50186670707235980', '1476939737', null);
INSERT INTO `yang_payment` VALUES ('60', '38038', '100.00', 'zhifubao', '0', '1692737464413242', '1476940258', null);
INSERT INTO `yang_payment` VALUES ('61', '100475', '100.00', 'zhifubao', '0', '54231562483638129', '1476943871', null);
INSERT INTO `yang_payment` VALUES ('62', '130067', '30.00', 'zhifubao', '1', '14176672742605898', '1476951474', '1476951516');
INSERT INTO `yang_payment` VALUES ('63', '167592', '40.00', 'zhifubao', '1', '14611290910396344', '1476952279', '1476952325');
INSERT INTO `yang_payment` VALUES ('64', '221512', '100.00', 'zhifubao', '0', '34777968179194695', '1476952674', null);
INSERT INTO `yang_payment` VALUES ('65', '136334', '30.00', 'zhifubao', '1', '45730519884028108', '1476953070', '1476953128');
INSERT INTO `yang_payment` VALUES ('66', '24497', '100000.00', 'zhifubao', '0', '70284109401557803', '1476954407', null);
INSERT INTO `yang_payment` VALUES ('67', '24497', '100000000.00', 'zhifubao', '0', '67842612872875465', '1476954485', null);
INSERT INTO `yang_payment` VALUES ('68', '221512', '100.00', 'zhifubao', '1', '15225746130768226', '1476956474', '1476956531');
INSERT INTO `yang_payment` VALUES ('69', '162409', '100.00', 'zhifubao', '0', '81637897502410923', '1476965757', null);
INSERT INTO `yang_payment` VALUES ('70', '31901', '100.00', 'zhifubao', '0', '21154989435620030', '1476979058', null);
INSERT INTO `yang_payment` VALUES ('71', '31901', '50.00', 'zhifubao', '1', '65354229687462724', '1476979620', '1476979672');
INSERT INTO `yang_payment` VALUES ('72', '125017', '5.00', 'zhifubao', '1', '17688979581508952', '1476982587', '1476982720');
INSERT INTO `yang_payment` VALUES ('73', '125017', '20.00', 'zhifubao', '1', '91587474376062671', '1476986850', '1476986944');
INSERT INTO `yang_payment` VALUES ('74', '10107', '1.00', 'zhifubao', '0', '80911773840304290', '1477008417', null);
INSERT INTO `yang_payment` VALUES ('75', '162409', '100.00', 'zhifubao', '0', '27702642864602695', '1477011004', null);
INSERT INTO `yang_payment` VALUES ('76', '7568', '100.00', 'zhifubao', '1', '32917063453341044', '1477011688', '1477011741');
INSERT INTO `yang_payment` VALUES ('77', '62119', '500.00', 'zhifubao', '1', '12295043415854343', '1477017815', '1477017856');
INSERT INTO `yang_payment` VALUES ('78', '178150', '50.00', 'zhifubao', '1', '58323515804099683', '1477019715', '1477020715');
INSERT INTO `yang_payment` VALUES ('79', '130067', '30.00', 'zhifubao', '1', '9396994908156983', '1477021520', '1477021551');
INSERT INTO `yang_payment` VALUES ('80', '7568', '50.00', 'zhifubao', '1', '80433063771879668', '1477021549', '1477021586');
INSERT INTO `yang_payment` VALUES ('81', '16807', '400.00', 'zhifubao', '1', '19726044484424951', '1477021569', '1477021609');
INSERT INTO `yang_payment` VALUES ('82', '1269', '100.00', 'zhifubao', '1', '88130596425949245', '1477021582', '1477021771');
INSERT INTO `yang_payment` VALUES ('83', '162409', '100.00', 'zhifubao', '1', '43071065324813687', '1477021655', '1477021689');
INSERT INTO `yang_payment` VALUES ('84', '1501', '50.00', 'zhifubao', '1', '9592846182515679', '1477021802', '1477021894');
INSERT INTO `yang_payment` VALUES ('85', '167592', '70.00', 'zhifubao', '1', '90044872877213427', '1477021899', null);
INSERT INTO `yang_payment` VALUES ('86', '1014', '1.00', 'zhifubao', '1', '83107570329183448', '1477021979', '1477022014');
INSERT INTO `yang_payment` VALUES ('87', '152496', '100.00', 'zhifubao', '0', '21524878681435058', '1477022491', null);
INSERT INTO `yang_payment` VALUES ('88', '100501', '200.00', 'zhifubao', '1', '30913700784519374', '1477023153', '1477023240');
INSERT INTO `yang_payment` VALUES ('89', '16885', '100.00', 'zhifubao', '1', '97113781671490413', '1477023945', null);
INSERT INTO `yang_payment` VALUES ('90', '16788', '1000.00', 'zhifubao', '1', '47425982815781083', '1477024024', '1477024072');
INSERT INTO `yang_payment` VALUES ('91', '39074', '2.00', 'zhifubao', '0', '99808517590400521', '1477024358', null);
INSERT INTO `yang_payment` VALUES ('92', '152496', '100.00', 'zhifubao', '0', '63844338947308880', '1477024459', null);
INSERT INTO `yang_payment` VALUES ('93', '136334', '10.00', 'zhifubao', '1', '5313107108817103', '1477024690', '1477024814');
INSERT INTO `yang_payment` VALUES ('94', '22466', '1.00', 'zhifubao', '0', '5706857516285967', '1477025048', null);
INSERT INTO `yang_payment` VALUES ('95', '46020', '500.00', 'zhifubao', '1', '8982957815671049', '1477027176', '1477027253');
INSERT INTO `yang_payment` VALUES ('96', '95581', '20.00', 'zhifubao', '1', '92688089606537760', '1477027622', '1477027674');
INSERT INTO `yang_payment` VALUES ('97', '27813', '30.00', 'zhifubao', '1', '32782716537894818', '1477028286', '1477028332');
INSERT INTO `yang_payment` VALUES ('98', '207734', '100.00', 'zhifubao', '1', '43069618564037846', '1477029565', '1477029616');
INSERT INTO `yang_payment` VALUES ('99', '1506', '11.00', 'zhifubao', '1', '44649149746595707', '1477029827', '1477034947');
INSERT INTO `yang_payment` VALUES ('100', '136334', '20.00', 'zhifubao', '1', '88025241991686900', '1477030180', '1477030224');
INSERT INTO `yang_payment` VALUES ('101', '177189', '200.00', 'zhifubao', '1', '37988261747364182', '1477042607', '1477042634');
INSERT INTO `yang_payment` VALUES ('102', '118802', '10.00', 'zhifubao', '1', '62856712364003886', '1477047273', '1477047391');
INSERT INTO `yang_payment` VALUES ('103', '194811', '100.00', 'zhifubao', '0', '90905229738517981', '1477047445', null);
INSERT INTO `yang_payment` VALUES ('104', '1517', '2000.00', 'zhifubao', '1', '46449447186256942', '1477050042', '1477050115');
INSERT INTO `yang_payment` VALUES ('105', '23357', '50.00', 'zhifubao', '1', '38080983136326712', '1477051574', '1477051647');
INSERT INTO `yang_payment` VALUES ('106', '6021', '100.00', 'zhifubao', '0', '3943102117585862', '1477052487', null);
INSERT INTO `yang_payment` VALUES ('107', '6021', '100.00', 'zhifubao', '0', '2700464707325299', '1477052699', null);
INSERT INTO `yang_payment` VALUES ('108', '6021', '100.00', 'zhifubao', '0', '76576294361642156', '1477052713', null);
INSERT INTO `yang_payment` VALUES ('109', '1263', '200.00', 'zhifubao', '1', '42625569869776251', '1477055666', '1477055696');
INSERT INTO `yang_payment` VALUES ('110', '43822', '200.00', 'zhifubao', '1', '96665389268419087', '1477056401', '1477056439');
INSERT INTO `yang_payment` VALUES ('111', '46020', '1300.00', 'zhifubao', '1', '44800949858831357', '1477058523', '1477058602');
INSERT INTO `yang_payment` VALUES ('112', '16788', '500.00', 'zhifubao', '1', '79712700954662678', '1477059471', '1477059493');
INSERT INTO `yang_payment` VALUES ('113', '152496', '200.00', 'zhifubao', '0', '63412390950015049', '1477079108', null);
INSERT INTO `yang_payment` VALUES ('114', '152496', '100.00', 'zhifubao', '1', '54295488142907237', '1477079187', '1477079251');
INSERT INTO `yang_payment` VALUES ('115', '39074', '300.00', 'zhifubao', '1', '86900114900069644', '1477097258', '1477097304');
INSERT INTO `yang_payment` VALUES ('116', '221512', '600.00', 'zhifubao', '1', '65300965720012369', '1477098957', '1477099017');
INSERT INTO `yang_payment` VALUES ('117', '135670', '1000.00', 'zhifubao', '1', '3842855385962776', '1477100081', '1477100148');
INSERT INTO `yang_payment` VALUES ('118', '205283', '2.00', 'zhifubao', '0', '16097625994281803', '1477100593', null);
INSERT INTO `yang_payment` VALUES ('119', '109944', '100.00', 'zhifubao', '1', '98363950108598764', '1477101003', '1477101035');
INSERT INTO `yang_payment` VALUES ('120', '30427', '5.00', 'zhifubao', '0', '75739380552850996', '1477103301', null);
INSERT INTO `yang_payment` VALUES ('121', '30427', '5.00', 'zhifubao', '0', '33268286104903645', '1477103307', null);
INSERT INTO `yang_payment` VALUES ('122', '30427', '5.00', 'zhifubao', '0', '63001324308452906', '1477103309', null);
INSERT INTO `yang_payment` VALUES ('123', '30427', '5.00', 'zhifubao', '0', '77932641492692748', '1477103312', null);
INSERT INTO `yang_payment` VALUES ('124', '205368', '20.00', 'zhifubao', '1', '82152316312390609', '1477103839', '1477103861');
INSERT INTO `yang_payment` VALUES ('125', '24736', '200.00', 'zhifubao', '0', '65656173135378901', '1477104535', null);
INSERT INTO `yang_payment` VALUES ('126', '62119', '2000.00', 'zhifubao', '1', '78133849100985449', '1477104730', '1477104769');
INSERT INTO `yang_payment` VALUES ('127', '62119', '2000.00', 'zhifubao', '1', '5738391601501040', '1477105882', '1477105913');
INSERT INTO `yang_payment` VALUES ('128', '47832', '50.00', 'zhifubao', '0', '91319151156342530', '1477108197', null);
INSERT INTO `yang_payment` VALUES ('129', '22466', '10.00', 'zhifubao', '1', '44296809848076552', '1477110206', '1477110232');
INSERT INTO `yang_payment` VALUES ('130', '43822', '500.00', 'zhifubao', '1', '34083227698839450', '1477110498', '1477110522');
INSERT INTO `yang_payment` VALUES ('131', '55869', '74.00', 'zhifubao', '1', '12447847459838418', '1477110637', '1477110668');
INSERT INTO `yang_payment` VALUES ('132', '167592', '30.00', 'zhifubao', '0', '43683214496912791', '1477110910', null);
INSERT INTO `yang_payment` VALUES ('133', '79501', '1000.00', 'zhifubao', '1', '88734039116613635', '1477110928', '1477115715');
INSERT INTO `yang_payment` VALUES ('134', '167592', '30.00', 'zhifubao', '0', '20198861301770219', '1477111071', null);
INSERT INTO `yang_payment` VALUES ('135', '167592', '20.00', 'zhifubao', '1', '36959052195745078', '1477111114', '1477111137');
INSERT INTO `yang_payment` VALUES ('136', '167592', '100.00', 'zhifubao', '1', '75558702333432361', '1477113253', '1477113270');
INSERT INTO `yang_payment` VALUES ('137', '43822', '100.00', 'zhifubao', '1', '2687898314174609', '1477115219', '1477115253');
INSERT INTO `yang_payment` VALUES ('138', '24736', '300.00', 'zhifubao', '0', '36067063709811815', '1477117928', null);
INSERT INTO `yang_payment` VALUES ('139', '24736', '300.00', 'zhifubao', '0', '89562719561932194', '1477118893', null);
INSERT INTO `yang_payment` VALUES ('140', '24736', '300.00', 'zhifubao', '1', '39631771509517574', '1477119885', '1477119911');
INSERT INTO `yang_payment` VALUES ('141', '37345', '1500.00', 'zhifubao', '0', '42211388466602376', '1477120097', null);
INSERT INTO `yang_payment` VALUES ('142', '37345', '3000.00', 'zhifubao', '1', '64273404441164714', '1477120115', '1477120345');
INSERT INTO `yang_payment` VALUES ('143', '21763', '100.00', 'zhifubao', '1', '55252865509733034', '1477121961', '1477122032');
INSERT INTO `yang_payment` VALUES ('144', '21763', '200.00', 'zhifubao', '1', '86161491525205890', '1477122305', '1477122323');
INSERT INTO `yang_payment` VALUES ('145', '39074', '950.00', 'zhifubao', '1', '18727233293506633', '1477122657', '1477122711');
INSERT INTO `yang_payment` VALUES ('146', '162409', '200.00', 'zhifubao', '1', '93292210780824400', '1477123933', '1477123962');
INSERT INTO `yang_payment` VALUES ('147', '22006', '2000.00', 'zhifubao', '0', '51739288681481278', '1477125686', null);
INSERT INTO `yang_payment` VALUES ('148', '32129', '100.00', 'zhifubao', '0', '96643595586971353', '1477126092', null);
INSERT INTO `yang_payment` VALUES ('149', '32129', '100.00', 'zhifubao', '0', '18910652537392929', '1477126105', null);
INSERT INTO `yang_payment` VALUES ('150', '32129', '100.00', 'zhifubao', '0', '32703102460957395', '1477126119', null);
INSERT INTO `yang_payment` VALUES ('151', '32129', '100.00', 'zhifubao', '1', '30281294411743688', '1477126125', '1477126173');
INSERT INTO `yang_payment` VALUES ('152', '32129', '100.00', 'zhifubao', '0', '99999595470737123', '1477126236', null);
INSERT INTO `yang_payment` VALUES ('153', '32129', '100.00', 'zhifubao', '0', '1500794135050665', '1477126239', null);
INSERT INTO `yang_payment` VALUES ('154', '32129', '100.00', 'zhifubao', '0', '28584625694695766', '1477126245', null);
INSERT INTO `yang_payment` VALUES ('155', '22006', '1000.00', 'zhifubao', '1', '95529359789195936', '1477126315', '1477126349');
INSERT INTO `yang_payment` VALUES ('156', '167592', '101.00', 'zhifubao', '1', '65585830566613471', '1477127377', '1477127397');
INSERT INTO `yang_payment` VALUES ('157', '217444', '1000.00', 'zhifubao', '1', '70727389973364758', '1477127396', '1477127438');
INSERT INTO `yang_payment` VALUES ('158', '1505', '10.00', 'zhifubao', '1', '45335318122665819', '1477127524', '1477127585');
INSERT INTO `yang_payment` VALUES ('159', '1501', '200.00', 'zhifubao', '1', '20652454317954821', '1477127810', '1477127843');
INSERT INTO `yang_payment` VALUES ('160', '6106', '100.00', 'zhifubao', '1', '45570654291029358', '1477128107', '1477128182');
INSERT INTO `yang_payment` VALUES ('161', '217444', '3000.00', 'zhifubao', '1', '66171399516398930', '1477128590', '1477128680');
INSERT INTO `yang_payment` VALUES ('162', '217444', '3000.00', 'zhifubao', '0', '64077580150282497', '1477128692', null);
INSERT INTO `yang_payment` VALUES ('163', '224692', '100.00', 'zhifubao', '0', '64634022114070441', '1477128713', null);
INSERT INTO `yang_payment` VALUES ('164', '6106', '200.00', 'zhifubao', '1', '91497167657001742', '1477128761', '1477128772');
INSERT INTO `yang_payment` VALUES ('165', '43822', '1000.00', 'zhifubao', '1', '16301923275267739', '1477129109', '1477129122');
INSERT INTO `yang_payment` VALUES ('166', '1511', '1000.00', 'zhifubao', '1', '72978197767834869', '1477129182', '1477129240');
INSERT INTO `yang_payment` VALUES ('167', '1511', '1000.00', 'zhifubao', '1', '64850145944523432', '1477129715', '1477129729');
INSERT INTO `yang_payment` VALUES ('168', '166912', '200.00', 'zhifubao', '1', '45759013644942802', '1477130080', '1477130156');
INSERT INTO `yang_payment` VALUES ('169', '1511', '400.00', 'zhifubao', '1', '52558443932144059', '1477130231', '1477130255');
INSERT INTO `yang_payment` VALUES ('170', '196179', '10.00', 'zhifubao', '0', '6048455211135706', '1477130282', null);
INSERT INTO `yang_payment` VALUES ('171', '196179', '10.00', 'zhifubao', '1', '59525353129733376', '1477130423', '1477130443');
INSERT INTO `yang_payment` VALUES ('172', '1269', '200.00', 'zhifubao', '0', '54087605387177252', '1477130587', null);
INSERT INTO `yang_payment` VALUES ('173', '1269', '200.00', 'zhifubao', '1', '9876713258280755', '1477130750', '1477130792');
INSERT INTO `yang_payment` VALUES ('174', '1269', '200.00', 'zhifubao', '0', '1064843586328516', '1477130819', null);
INSERT INTO `yang_payment` VALUES ('175', '1269', '200.00', 'zhifubao', '0', '48680770853637666', '1477130823', null);
INSERT INTO `yang_payment` VALUES ('176', '1269', '200.00', 'zhifubao', '0', '32251003199525942', '1477130826', null);
INSERT INTO `yang_payment` VALUES ('177', '48157', '1000.00', 'zhifubao', '1', '50201133921654267', '1477130857', '1477131014');
INSERT INTO `yang_payment` VALUES ('178', '48157', '100.00', 'zhifubao', '1', '85618098355748730', '1477131413', '1477131429');
INSERT INTO `yang_payment` VALUES ('179', '68679', '8.00', 'zhifubao', '1', '65079164511596314', '1477131837', '1477131907');
INSERT INTO `yang_payment` VALUES ('180', '196179', '34.00', 'zhifubao', '1', '8461704320151590', '1477132030', '1477132045');
INSERT INTO `yang_payment` VALUES ('181', '1501', '50.00', 'zhifubao', '1', '32310931363594484', '1477132040', '1477132098');
INSERT INTO `yang_payment` VALUES ('182', '196179', '70.00', 'zhifubao', '1', '34459911743319785', '1477132796', '1477132810');
INSERT INTO `yang_payment` VALUES ('183', '69496', '1.00', 'zhifubao', '0', '77306417869221828', '1477133120', null);
INSERT INTO `yang_payment` VALUES ('184', '69496', '1.00', 'zhifubao', '0', '49774695413992489', '1477133125', null);
INSERT INTO `yang_payment` VALUES ('185', '69496', '1.00', 'zhifubao', '0', '58943965549498921', '1477133127', null);
INSERT INTO `yang_payment` VALUES ('186', '69496', '1.00', 'zhifubao', '0', '69747905283022294', '1477133149', null);
INSERT INTO `yang_payment` VALUES ('187', '69496', '1.00', 'zhifubao', '0', '83782258243263599', '1477133152', null);
INSERT INTO `yang_payment` VALUES ('188', '177189', '100.00', 'zhifubao', '1', '9087096800031458', '1477134030', '1477134054');
INSERT INTO `yang_payment` VALUES ('189', '107323', '50.00', 'zhifubao', '0', '88298124917143069', '1477134109', null);
INSERT INTO `yang_payment` VALUES ('190', '19487', '10.00', 'zhifubao', '1', '24187456337137043', '1477134412', '1477134484');
INSERT INTO `yang_payment` VALUES ('191', '69496', '1.00', 'zhifubao', '0', '82954559713089447', '1477134429', null);
INSERT INTO `yang_payment` VALUES ('192', '69496', '1.00', 'zhifubao', '0', '49801678518944130', '1477134434', null);
INSERT INTO `yang_payment` VALUES ('193', '69496', '1.00', 'zhifubao', '0', '49104346288401957', '1477134437', null);
INSERT INTO `yang_payment` VALUES ('194', '69496', '1.00', 'zhifubao', '0', '41487763835704069', '1477134440', null);
INSERT INTO `yang_payment` VALUES ('195', '69496', '1.00', 'zhifubao', '0', '16219122246646682', '1477134442', null);
INSERT INTO `yang_payment` VALUES ('196', '69496', '1.00', 'zhifubao', '0', '32952050371634669', '1477134444', null);
INSERT INTO `yang_payment` VALUES ('197', '69496', '1.00', 'zhifubao', '0', '31160413432733718', '1477134446', null);
INSERT INTO `yang_payment` VALUES ('198', '19487', '300.00', 'zhifubao', '1', '8703662273543551', '1477134661', '1477134825');
INSERT INTO `yang_payment` VALUES ('199', '100591', '1000.00', 'zhifubao', '0', '25696183850439875', '1477134768', null);
INSERT INTO `yang_payment` VALUES ('200', '95581', '10.00', 'zhifubao', '1', '67659629658114946', '1477135060', '1477135130');
INSERT INTO `yang_payment` VALUES ('201', '73094', '100.00', 'zhifubao', '0', '9761642556161495', '1477135374', null);
INSERT INTO `yang_payment` VALUES ('202', '73094', '100.00', 'zhifubao', '1', '36567885579449066', '1477135466', '1477135477');
INSERT INTO `yang_payment` VALUES ('203', '100501', '200.00', 'zhifubao', '1', '49079575848745664', '1477136029', '1477136116');
INSERT INTO `yang_payment` VALUES ('204', '194332', '5.00', 'zhifubao', '1', '26896471848392672', '1477136298', '1477136381');
INSERT INTO `yang_payment` VALUES ('205', '208871', '400.00', 'zhifubao', '0', '78239098465167908', '1477136405', null);
INSERT INTO `yang_payment` VALUES ('206', '194332', '10.00', 'zhifubao', '1', '87176357214447518', '1477136895', '1477136930');
INSERT INTO `yang_payment` VALUES ('207', '129431', '500.00', 'zhifubao', '1', '48470296360186128', '1477138236', '1477138282');
INSERT INTO `yang_payment` VALUES ('208', '43822', '500.00', 'zhifubao', '1', '25588106360121874', '1477138556', '1477138574');
INSERT INTO `yang_payment` VALUES ('209', '194332', '100.00', 'zhifubao', '1', '11989110590332179', '1477139157', '1477139198');
INSERT INTO `yang_payment` VALUES ('210', '100591', '1000.00', 'zhifubao', '0', '60121571915560157', '1477139569', null);
INSERT INTO `yang_payment` VALUES ('211', '24736', '200.00', 'zhifubao', '1', '61182352686333731', '1477139770', '1477139934');
INSERT INTO `yang_payment` VALUES ('212', '220784', '100.00', 'zhifubao', '1', '51358443836053646', '1477140631', '1477140666');
INSERT INTO `yang_payment` VALUES ('213', '16788', '200.00', 'zhifubao', '1', '87867327760273315', '1477141009', '1477141028');
INSERT INTO `yang_payment` VALUES ('214', '69496', '5.00', 'zhifubao', '0', '73815712435444691', '1477141051', null);
INSERT INTO `yang_payment` VALUES ('215', '69496', '5.00', 'zhifubao', '1', '76459385329140705', '1477141319', '1477141355');
INSERT INTO `yang_payment` VALUES ('216', '196179', '100.00', 'zhifubao', '1', '25302630375768907', '1477142681', '1477142709');
INSERT INTO `yang_payment` VALUES ('217', '220926', '400.00', 'zhifubao', '0', '52873704147487691', '1477145179', null);
INSERT INTO `yang_payment` VALUES ('218', '220926', '400.00', 'zhifubao', '0', '74086666422130511', '1477145234', null);
INSERT INTO `yang_payment` VALUES ('219', '220926', '400.00', 'zhifubao', '1', '31787037189411278', '1477145342', '1477145402');
INSERT INTO `yang_payment` VALUES ('220', '170906', '200.00', 'zhifubao', '0', '96772237501611898', '1477148731', null);
INSERT INTO `yang_payment` VALUES ('221', '170906', '200.00', 'zhifubao', '0', '56284779398201297', '1477148738', null);
INSERT INTO `yang_payment` VALUES ('222', '170906', '200.00', 'zhifubao', '0', '89574531225138321', '1477148740', null);
INSERT INTO `yang_payment` VALUES ('223', '170906', '200.00', 'zhifubao', '0', '39187717397505101', '1477148743', null);
INSERT INTO `yang_payment` VALUES ('224', '170906', '200.00', 'zhifubao', '0', '58684688437489057', '1477148745', null);
INSERT INTO `yang_payment` VALUES ('225', '170906', '200.00', 'zhifubao', '0', '39944097104578495', '1477148748', null);
INSERT INTO `yang_payment` VALUES ('226', '170906', '200.00', 'zhifubao', '0', '69146686307372410', '1477148777', null);
INSERT INTO `yang_payment` VALUES ('227', '170906', '200.00', 'zhifubao', '0', '59232008896372866', '1477148795', null);
INSERT INTO `yang_payment` VALUES ('228', '170906', '200.00', 'zhifubao', '0', '22177939425669085', '1477148796', null);
INSERT INTO `yang_payment` VALUES ('229', '170906', '200.00', 'zhifubao', '0', '4384428165772108', '1477150352', null);
INSERT INTO `yang_payment` VALUES ('230', '167592', '500.00', 'zhifubao', '0', '72731366551841522', '1477186050', null);
INSERT INTO `yang_payment` VALUES ('231', '167592', '500.00', 'zhifubao', '1', '98200878696875736', '1477186113', '1477186133');
INSERT INTO `yang_payment` VALUES ('232', '31901', '100.00', 'zhifubao', '0', '17108013719719760', '1477190826', null);
INSERT INTO `yang_payment` VALUES ('233', '31901', '100.00', 'zhifubao', '0', '55497120473054908', '1477190884', null);
INSERT INTO `yang_payment` VALUES ('234', '31901', '100.00', 'zhifubao', '1', '23994323855759740', '1477190916', '1477190949');
INSERT INTO `yang_payment` VALUES ('235', '31901', '100.00', 'zhifubao', '0', '50557793941251444', '1477190984', null);
INSERT INTO `yang_payment` VALUES ('236', '122271', '20.00', 'zhifubao', '0', '23331381281169688', '1477192415', null);
INSERT INTO `yang_payment` VALUES ('237', '122271', '20.00', 'zhifubao', '0', '67205415455889756', '1477192612', null);
INSERT INTO `yang_payment` VALUES ('238', '122271', '20.00', 'zhifubao', '1', '78346636227997551', '1477193556', '1477193663');
INSERT INTO `yang_payment` VALUES ('239', '95581', '30.00', 'zhifubao', '1', '48357448968494936', '1477195046', '1477195072');
INSERT INTO `yang_payment` VALUES ('240', '31901', '100.00', 'zhifubao', '0', '33581898302821045', '1477200928', null);
INSERT INTO `yang_payment` VALUES ('241', '31901', '100.00', 'zhifubao', '0', '98985506148930591', '1477200950', null);
INSERT INTO `yang_payment` VALUES ('242', '220432', '200.00', 'zhifubao', '1', '96044107207218746', '1477201125', '1477201164');
INSERT INTO `yang_payment` VALUES ('243', '220432', '200.00', 'zhifubao', '0', '23082970719534015', '1477201280', null);
INSERT INTO `yang_payment` VALUES ('244', '146967', '20.00', 'zhifubao', '1', '14675739814650296', '1477201639', '1477201710');
INSERT INTO `yang_payment` VALUES ('245', '62119', '3500.00', 'zhifubao', '1', '41554826649792420', '1477201975', '1477201997');
INSERT INTO `yang_payment` VALUES ('246', '162409', '1.00', 'zhifubao', '0', '63487935763118557', '1477203437', null);
INSERT INTO `yang_payment` VALUES ('247', '162409', '1.00', 'zhifubao', '1', '92703871645787500', '1477203708', '1477203727');
INSERT INTO `yang_payment` VALUES ('248', '30427', '5.00', 'zhifubao', '1', '80415585293475625', '1477203801', '1477203946');
INSERT INTO `yang_payment` VALUES ('249', '42438', '5.00', 'zhifubao', '1', '38268922502808171', '1477203873', '1477203903');
INSERT INTO `yang_payment` VALUES ('250', '162409', '99.00', 'zhifubao', '1', '78727582157624290', '1477204069', '1477204096');
INSERT INTO `yang_payment` VALUES ('251', '6915', '1.00', 'zhifubao', '0', '87478873994431142', '1477207530', null);
INSERT INTO `yang_payment` VALUES ('252', '47832', '50.00', 'zhifubao', '1', '65525122944905709', '1477211532', '1477211575');
INSERT INTO `yang_payment` VALUES ('253', '47832', '150.00', 'zhifubao', '1', '14164454268866308', '1477211608', '1477211620');
INSERT INTO `yang_payment` VALUES ('254', '46020', '1200.00', 'zhifubao', '0', '82537687105816656', '1477212044', null);
INSERT INTO `yang_payment` VALUES ('255', '3994', '1000.00', 'zhifubao', '0', '9766056353754115', '1477212601', null);
INSERT INTO `yang_payment` VALUES ('256', '46020', '1200.00', 'zhifubao', '1', '38822963903745381', '1477213127', '1477213170');
INSERT INTO `yang_payment` VALUES ('257', '42285', '5.00', 'zhifubao', '0', '10171725612585135', '1477213172', null);
INSERT INTO `yang_payment` VALUES ('258', '42285', '5.00', 'zhifubao', '0', '37172488173336142', '1477213193', null);
INSERT INTO `yang_payment` VALUES ('259', '42285', '5.00', 'zhifubao', '0', '42380460124668955', '1477213199', null);
INSERT INTO `yang_payment` VALUES ('260', '42285', '5.00', 'zhifubao', '1', '35660684998033208', '1477213212', '1477213345');
INSERT INTO `yang_payment` VALUES ('261', '176587', '1.00', 'zhifubao', '0', '35343360844746805', '1477215920', null);
INSERT INTO `yang_payment` VALUES ('262', '37680', '1.00', 'zhifubao', '1', '42155974238028425', '1477216361', '1477216414');
INSERT INTO `yang_payment` VALUES ('263', '37680', '1.00', 'zhifubao', '0', '2119285401952440', '1477216559', null);
INSERT INTO `yang_payment` VALUES ('264', '37680', '4.00', 'zhifubao', '1', '78673347239599312', '1477216974', '1477217005');
INSERT INTO `yang_payment` VALUES ('265', '224695', '200.00', 'zhifubao', '1', '96104420955775170', '1477218201', '1477218260');
INSERT INTO `yang_payment` VALUES ('266', '135670', '2000.00', 'zhifubao', '1', '12494798104953479', '1477221407', '1477221454');
INSERT INTO `yang_payment` VALUES ('267', '205962', '100.00', 'zhifubao', '1', '62347246769670868', '1477222722', '1477222782');
INSERT INTO `yang_payment` VALUES ('268', '125967', '800.00', 'zhifubao', '1', '17354193455068798', '1477222927', '1477223141');
INSERT INTO `yang_payment` VALUES ('269', '6729', '100.00', 'zhifubao', '1', '99011417650943881', '1477225159', '1477225540');
INSERT INTO `yang_payment` VALUES ('270', '201352', '10.00', 'zhifubao', '1', '33117935801060463', '1477227300', '1477227341');
INSERT INTO `yang_payment` VALUES ('271', '17625', '100.00', 'zhifubao', '0', '41572138476378483', '1477227605', null);
INSERT INTO `yang_payment` VALUES ('272', '201352', '300.00', 'zhifubao', '1', '8311753573321371', '1477231687', '1477231716');
INSERT INTO `yang_payment` VALUES ('273', '201352', '700.00', 'zhifubao', '1', '33018673733490124', '1477233189', '1477233204');
INSERT INTO `yang_payment` VALUES ('274', '37345', '7000.00', 'zhifubao', '1', '72334239952798649', '1477234140', '1477234201');
INSERT INTO `yang_payment` VALUES ('275', '120002', '1000.00', 'zhifubao', '0', '57467597991888489', '1477234254', null);
INSERT INTO `yang_payment` VALUES ('276', '224693', '10.00', 'zhifubao', '1', '3534757844364721', '1477235922', '1477235958');
INSERT INTO `yang_payment` VALUES ('277', '224693', '10.00', 'zhifubao', '0', '21547838953021485', '1477236000', null);
INSERT INTO `yang_payment` VALUES ('278', '35254', '100.00', 'zhifubao', '0', '19712245168037350', '1477237108', null);
INSERT INTO `yang_payment` VALUES ('279', '4380', '20.00', 'zhifubao', '1', '17090168476217704', '1477237955', '1477237975');
INSERT INTO `yang_payment` VALUES ('280', '4380', '80.00', 'zhifubao', '1', '26410577190911862', '1477238683', '1477238700');
INSERT INTO `yang_payment` VALUES ('281', '205157', '50000.00', 'zhifubao', '0', '11331772283898059', '1477242616', null);
INSERT INTO `yang_payment` VALUES ('282', '126015', '100.00', 'zhifubao', '0', '85738373765255200', '1477245089', null);
INSERT INTO `yang_payment` VALUES ('283', '35254', '400.00', 'zhifubao', '1', '47595309456657123', '1477266622', '1477266718');
INSERT INTO `yang_payment` VALUES ('284', '41805', '50.00', 'zhifubao', '1', '35534359299497188', '1477267808', '1477267850');
INSERT INTO `yang_payment` VALUES ('285', '167945', '10.00', 'zhifubao', '1', '68374810891342496', '1477268769', '1477268787');
INSERT INTO `yang_payment` VALUES ('286', '41805', '100.00', 'zhifubao', '1', '66354001769735202', '1477270133', '1477270167');
INSERT INTO `yang_payment` VALUES ('287', '167808', '100.00', 'zhifubao', '1', '86413969890756828', '1477271579', '1477271620');
INSERT INTO `yang_payment` VALUES ('288', '123233', '100.00', 'zhifubao', '1', '93047073822113228', '1477271974', '1477272264');
INSERT INTO `yang_payment` VALUES ('289', '224679', '1000.00', 'zhifubao', '1', '77068757872169251', '1477272501', '1477272586');
INSERT INTO `yang_payment` VALUES ('290', '24011', '200.00', 'zhifubao', '1', '29842448573333884', '1477273116', '1477273129');
INSERT INTO `yang_payment` VALUES ('291', '135670', '5000.00', 'zhifubao', '1', '26089501863080732', '1477276526', '1477276821');
INSERT INTO `yang_payment` VALUES ('292', '130669', '300.00', 'zhifubao', '0', '9691769301436300', '1477282944', null);
INSERT INTO `yang_payment` VALUES ('293', '130669', '300.00', 'zhifubao', '1', '37250748287222263', '1477283874', '1477283942');
INSERT INTO `yang_payment` VALUES ('294', '100591', '3400.00', 'zhifubao', '0', '75252282730613635', '1477284866', null);
INSERT INTO `yang_payment` VALUES ('295', '80282', '32.00', 'zhifubao', '0', '14449566201256838', '1477284981', null);
INSERT INTO `yang_payment` VALUES ('296', '20168', '200.00', 'zhifubao', '1', '33615040418399489', '1477285161', '1477285202');
INSERT INTO `yang_payment` VALUES ('297', '199644', '680.00', 'zhifubao', '0', '21214223393673821', '1477285637', null);
INSERT INTO `yang_payment` VALUES ('298', '40269', '10.00', 'zhifubao', '1', '28761282850078479', '1477288180', '1477288311');
INSERT INTO `yang_payment` VALUES ('299', '201352', '1000.00', 'zhifubao', '1', '33319518522806297', '1477291812', '1477291853');
INSERT INTO `yang_payment` VALUES ('300', '162409', '31.00', 'zhifubao', '1', '37283024359722188', '1477291870', '1477291890');
INSERT INTO `yang_payment` VALUES ('301', '167945', '38.00', 'zhifubao', '1', '11538240211417815', '1477292628', '1477292648');
INSERT INTO `yang_payment` VALUES ('302', '205962', '87.00', 'zhifubao', '1', '54612808822117185', '1477293124', '1477293148');
INSERT INTO `yang_payment` VALUES ('303', '102786', '30.00', 'zhifubao', '0', '75709024875154524', '1477293530', null);
INSERT INTO `yang_payment` VALUES ('304', '224693', '100.00', 'zhifubao', '1', '12336626945646127', '1477293635', '1477293671');
INSERT INTO `yang_payment` VALUES ('305', '102786', '30.00', 'zhifubao', '1', '86106510765165787', '1477293663', '1477293677');
INSERT INTO `yang_payment` VALUES ('306', '224693', '100.00', 'zhifubao', '0', '87099586889119689', '1477293680', null);
INSERT INTO `yang_payment` VALUES ('307', '1082', '100.00', 'zhifubao', '0', '31225815858232909', '1477295681', null);
INSERT INTO `yang_payment` VALUES ('308', '1082', '100.00', 'zhifubao', '0', '34730165126177007', '1477295725', null);
INSERT INTO `yang_payment` VALUES ('309', '102786', '100.00', 'zhifubao', '1', '93555336972983603', '1477296592', '1477296620');
INSERT INTO `yang_payment` VALUES ('310', '10107', '20.00', 'zhifubao', '1', '7064759211142850', '1477298632', '1477298689');
INSERT INTO `yang_payment` VALUES ('311', '151072', '500.00', 'zhifubao', '1', '2964571637154251', '1477300969', '1477301031');
INSERT INTO `yang_payment` VALUES ('312', '157251', '200.00', 'zhifubao', '0', '79254996229156640', '1477301075', null);
INSERT INTO `yang_payment` VALUES ('313', '40269', '600.00', 'zhifubao', '0', '57936470507101032', '1477309486', null);
INSERT INTO `yang_payment` VALUES ('314', '69496', '5.00', 'zhifubao', '1', '3357142489925964', '1477310025', '1477310080');
INSERT INTO `yang_payment` VALUES ('315', '224693', '100.00', 'zhifubao', '1', '7319196869009880', '1477310031', '1477310065');
INSERT INTO `yang_payment` VALUES ('316', '16885', '1000.00', 'zhifubao', '1', '59046333545306931', '1477310045', '1477310182');
INSERT INTO `yang_payment` VALUES ('317', '224693', '100.00', 'zhifubao', '0', '3446059667190014', '1477310116', null);
INSERT INTO `yang_payment` VALUES ('318', '1015', '500.00', 'zhifubao', '1', '59651090299899858', '1477310602', '1477310742');
INSERT INTO `yang_payment` VALUES ('319', '16885', '1000.00', 'zhifubao', '1', '11042696231061975', '1477312426', '1477312475');
INSERT INTO `yang_payment` VALUES ('320', '167592', '500.00', 'zhifubao', '1', '65012400883631014', '1477313013', '1477313030');
INSERT INTO `yang_payment` VALUES ('321', '109944', '100.00', 'zhifubao', '1', '76654186432019181', '1477313014', '1477313067');
INSERT INTO `yang_payment` VALUES ('322', '10107', '200.00', 'zhifubao', '1', '56461678727777556', '1477313111', '1477313153');
INSERT INTO `yang_payment` VALUES ('323', '31901', '200.00', 'zhifubao', '1', '77030242226448851', '1477313499', '1477313540');
INSERT INTO `yang_payment` VALUES ('324', '31901', '200.00', 'zhifubao', '0', '49513075386004952', '1477313557', null);
INSERT INTO `yang_payment` VALUES ('325', '31901', '200.00', 'zhifubao', '0', '77160746756290999', '1477313559', null);
INSERT INTO `yang_payment` VALUES ('326', '31901', '200.00', 'zhifubao', '0', '8307166335655493', '1477313562', null);
INSERT INTO `yang_payment` VALUES ('327', '35254', '599.00', 'zhifubao', '0', '94808569891883358', '1477319215', null);
INSERT INTO `yang_payment` VALUES ('328', '35254', '500.00', 'zhifubao', '1', '49673354179924241', '1477319247', '1477319274');
INSERT INTO `yang_payment` VALUES ('329', '35254', '1700.00', 'zhifubao', '1', '90104041267657830', '1477319315', '1477319342');
INSERT INTO `yang_payment` VALUES ('330', '35254', '500.00', 'zhifubao', '1', '95588734766935943', '1477319722', '1477319768');
INSERT INTO `yang_payment` VALUES ('331', '20168', '801.00', 'zhifubao', '1', '54516635278173308', '1477324512', '1477324557');
INSERT INTO `yang_payment` VALUES ('332', '130067', '30.00', 'zhifubao', '0', '93479274649295296', '1477357589', null);
INSERT INTO `yang_payment` VALUES ('333', '130067', '37.00', 'zhifubao', '1', '38380957462492765', '1477357624', '1477357656');
INSERT INTO `yang_payment` VALUES ('334', '220432', '200.00', 'zhifubao', '1', '11719122356292570', '1477358936', '1477358972');
INSERT INTO `yang_payment` VALUES ('335', '220432', '200.00', 'zhifubao', '0', '3331852557043304', '1477359000', null);
INSERT INTO `yang_payment` VALUES ('336', '224687', '1.00', 'zhifubao', '1', '41970220646193295', '1477361604', '1477361619');
INSERT INTO `yang_payment` VALUES ('337', '31901', '100.00', 'zhifubao', '1', '72744968421690458', '1477371124', '1477371169');
INSERT INTO `yang_payment` VALUES ('338', '31901', '100.00', 'zhifubao', '0', '50875600826985695', '1477371182', null);
INSERT INTO `yang_payment` VALUES ('339', '31901', '200.00', 'zhifubao', '1', '5673868950052308', '1477371358', '1477371391');
INSERT INTO `yang_payment` VALUES ('340', '31901', '200.00', 'zhifubao', '0', '66501027533484072', '1477371396', null);
INSERT INTO `yang_payment` VALUES ('341', '31901', '200.00', 'zhifubao', '0', '90361447828981586', '1477371398', null);
INSERT INTO `yang_payment` VALUES ('342', '40269', '1000.00', 'zhifubao', '1', '23992508322607511', '1477378690', '1477379247');
INSERT INTO `yang_payment` VALUES ('343', '69496', '10.00', 'zhifubao', '1', '74129317677845032', '1477382364', '1477382404');
INSERT INTO `yang_payment` VALUES ('344', '42285', '100.00', 'zhifubao', '1', '60248993815754666', '1477383198', '1477383259');
INSERT INTO `yang_payment` VALUES ('345', '162409', '110.00', 'zhifubao', '1', '12064384968000260', '1477386356', '1477386376');
INSERT INTO `yang_payment` VALUES ('346', '78907', '100.00', 'zhifubao', '0', '56537443210938712', '1477387069', null);
INSERT INTO `yang_payment` VALUES ('347', '78907', '100.00', 'zhifubao', '0', '33006767165997718', '1477387125', null);
INSERT INTO `yang_payment` VALUES ('348', '205368', '200.00', 'zhifubao', '1', '87408167911149656', '1477387297', '1477387320');
INSERT INTO `yang_payment` VALUES ('349', '69496', '100.00', 'zhifubao', '0', '12278467200004056', '1477387969', null);
INSERT INTO `yang_payment` VALUES ('350', '69496', '100.00', 'zhifubao', '0', '7822749357274859', '1477387972', null);
INSERT INTO `yang_payment` VALUES ('351', '69496', '100.00', 'zhifubao', '1', '78257070554688151', '1477388059', '1477388086');

-- ----------------------------
-- Table structure for `yang_position`
-- ----------------------------
DROP TABLE IF EXISTS `yang_position`;
CREATE TABLE `yang_position` (
  `position_id` int(32) NOT NULL AUTO_INCREMENT,
  `position_name` varchar(128) NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_position
-- ----------------------------
INSERT INTO `yang_position` VALUES ('1', '首页左侧导航banner');

-- ----------------------------
-- Table structure for `yang_qianbao_address`
-- ----------------------------
DROP TABLE IF EXISTS `yang_qianbao_address`;
CREATE TABLE `yang_qianbao_address` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `user_id` int(32) NOT NULL,
  `name` varchar(32) NOT NULL COMMENT '姓名',
  `qianbao_url` varchar(128) NOT NULL COMMENT '钱包地址',
  `status` tinyint(4) NOT NULL,
  `add_time` int(10) NOT NULL,
  `currency_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yang_qianbao_address
-- ----------------------------
INSERT INTO `yang_qianbao_address` VALUES ('9', '101', '周成维', 'bJRE15GmuJSftND64JtGYYqWkhiC9w6zkY', '1', '1484450778', '26');
INSERT INTO `yang_qianbao_address` VALUES ('10', '100', '小周', 'mWG2JU1AxcKk4v6MNMNJZP288GDjrtTX48', '1', '1484469251', '25');

-- ----------------------------
-- Table structure for `yang_restaurant`
-- ----------------------------
DROP TABLE IF EXISTS `yang_restaurant`;
CREATE TABLE `yang_restaurant` (
  `restaurant_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '餐厅表',
  `restaurant_name` varchar(64) NOT NULL COMMENT '餐厅名称',
  `restaurant_logo` text NOT NULL COMMENT 'logo',
  `restaurant_address` varchar(128) NOT NULL COMMENT '地址',
  `start_time` varchar(128) NOT NULL COMMENT '营业时间',
  `bus` varchar(128) NOT NULL COMMENT '公交路线',
  `phone` varchar(128) NOT NULL COMMENT '联系方式',
  `centent` text NOT NULL COMMENT '简介内容',
  `status` tinyint(4) NOT NULL,
  `b_host_city` tinyint(4) NOT NULL COMMENT '餐厅所在城市',
  `c_host_city` tinyint(4) NOT NULL COMMENT '厂家所在城市',
  `add_time` varchar(11) NOT NULL COMMENT '添加时间',
  `check_time` varchar(11) NOT NULL COMMENT '确认时间（开启前台展示的时间）',
  `update_time` varchar(11) NOT NULL COMMENT '修改时间',
  `click_number` int(10) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `position` int(4) NOT NULL DEFAULT '0' COMMENT '是否热推 0不推 1热推',
  PRIMARY KEY (`restaurant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_restaurant
-- ----------------------------

-- ----------------------------
-- Table structure for `yang_restaurant_flash`
-- ----------------------------
DROP TABLE IF EXISTS `yang_restaurant_flash`;
CREATE TABLE `yang_restaurant_flash` (
  `flash_id` int(32) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL COMMENT '标题',
  `pic` varchar(128) NOT NULL,
  `jump_url` varchar(128) NOT NULL COMMENT '跳转地址',
  `sort` int(16) NOT NULL COMMENT '排序',
  `type` varchar(16) NOT NULL,
  `add_time` varchar(32) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`flash_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_restaurant_flash
-- ----------------------------
INSERT INTO `yang_restaurant_flash` VALUES ('9', '好吃吃!!!!!!', '/Uploads/Public/Uploads/2016-05-19/573db49f5b88d.png', 'www.baidu.com', '0', '', '1463661727', '0');
INSERT INTO `yang_restaurant_flash` VALUES ('8', '快来看看啊!!', '/Uploads/Public/Uploads/2016-05-19/573db08ee0f47.jpg', 'www.baidu.com', '0', '', '1463660686', '0');

-- ----------------------------
-- Table structure for `yang_reward_conf`
-- ----------------------------
DROP TABLE IF EXISTS `yang_reward_conf`;
CREATE TABLE `yang_reward_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` int(11) NOT NULL,
  `money` float(30,4) NOT NULL,
  `day` int(5) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `sum` float(30,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_reward_conf
-- ----------------------------
INSERT INTO `yang_reward_conf` VALUES ('5', '25', '4.0000', '2', '1', '0', '16.0000');
INSERT INTO `yang_reward_conf` VALUES ('6', '26', '4.0000', '2', '1', '0', '16.0000');
INSERT INTO `yang_reward_conf` VALUES ('7', '25', '4.0000', '2', '2', '0', '16.0000');
INSERT INTO `yang_reward_conf` VALUES ('8', '26', '4.0000', '2', '2', '0', '16.0000');

-- ----------------------------
-- Table structure for `yang_reward_log`
-- ----------------------------
DROP TABLE IF EXISTS `yang_reward_log`;
CREATE TABLE `yang_reward_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reward_id` int(11) NOT NULL,
  `money` float(30,4) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `add_time` int(15) NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_reward_log
-- ----------------------------
INSERT INTO `yang_reward_log` VALUES ('65', '47', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('66', '48', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('67', '51', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('68', '52', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('69', '55', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('70', '56', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('71', '59', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('72', '60', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('73', '45', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('74', '46', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('75', '49', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('76', '50', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('77', '53', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('78', '54', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('79', '57', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('80', '58', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('81', '63', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('82', '64', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('83', '67', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('84', '68', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('85', '75', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('86', '76', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('87', '71', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('88', '72', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('89', '79', '2.0000', '25', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('90', '80', '2.0000', '26', '1484409600', '0');
INSERT INTO `yang_reward_log` VALUES ('91', '45', '2.0000', '25', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('92', '55', '2.0000', '25', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('93', '51', '2.0000', '25', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('94', '47', '2.0000', '25', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('95', '46', '2.0000', '26', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('96', '59', '2.0000', '25', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('97', '49', '2.0000', '25', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('98', '48', '2.0000', '26', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('99', '67', '2.0000', '25', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('100', '68', '2.0000', '26', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('101', '63', '2.0000', '25', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('102', '64', '2.0000', '26', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('103', '56', '2.0000', '26', '1484496000', '0');
INSERT INTO `yang_reward_log` VALUES ('104', '52', '2.0000', '26', '1484496000', '0');

-- ----------------------------
-- Table structure for `yang_reward_reg`
-- ----------------------------
DROP TABLE IF EXISTS `yang_reward_reg`;
CREATE TABLE `yang_reward_reg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `money` float(30,4) NOT NULL COMMENT '总共分发钱数',
  `down_id` int(11) DEFAULT NULL,
  `currency_id` int(11) NOT NULL COMMENT '币种',
  `surplus_day` int(5) NOT NULL COMMENT '剩余天数',
  `sum_day` int(5) NOT NULL,
  `add_time` int(15) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态  0为正常  1为已经分发完成',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_reward_reg
-- ----------------------------
INSERT INTO `yang_reward_reg` VALUES ('45', '100', '4.0000', '101', '25', '0', '2', '1484448691', '1');
INSERT INTO `yang_reward_reg` VALUES ('46', '100', '4.0000', '101', '26', '0', '2', '1484448691', '1');
INSERT INTO `yang_reward_reg` VALUES ('47', '101', '4.0000', '0', '25', '0', '2', '1484448691', '1');
INSERT INTO `yang_reward_reg` VALUES ('48', '101', '4.0000', '0', '26', '0', '2', '1484448691', '1');
INSERT INTO `yang_reward_reg` VALUES ('49', '100', '4.0000', '102', '25', '1', '2', '1484448805', '0');
INSERT INTO `yang_reward_reg` VALUES ('50', '100', '4.0000', '102', '26', '1', '2', '1484448805', '0');
INSERT INTO `yang_reward_reg` VALUES ('51', '102', '4.0000', '0', '25', '0', '2', '1484448805', '1');
INSERT INTO `yang_reward_reg` VALUES ('52', '102', '4.0000', '0', '26', '0', '2', '1484448805', '1');
INSERT INTO `yang_reward_reg` VALUES ('53', '100', '4.0000', '103', '25', '1', '2', '1484449406', '0');
INSERT INTO `yang_reward_reg` VALUES ('54', '100', '4.0000', '103', '26', '1', '2', '1484449406', '0');
INSERT INTO `yang_reward_reg` VALUES ('55', '103', '4.0000', '0', '25', '0', '2', '1484449406', '1');
INSERT INTO `yang_reward_reg` VALUES ('56', '103', '4.0000', '0', '26', '0', '2', '1484449406', '1');
INSERT INTO `yang_reward_reg` VALUES ('57', '100', '4.0000', '104', '25', '1', '2', '1484449960', '0');
INSERT INTO `yang_reward_reg` VALUES ('58', '100', '4.0000', '104', '26', '1', '2', '1484449960', '0');
INSERT INTO `yang_reward_reg` VALUES ('59', '104', '4.0000', '0', '25', '0', '2', '1484449960', '1');
INSERT INTO `yang_reward_reg` VALUES ('60', '104', '4.0000', '0', '26', '1', '2', '1484449960', '0');
INSERT INTO `yang_reward_reg` VALUES ('61', '100', '4.0000', '105', '25', '2', '2', '1484450390', '0');
INSERT INTO `yang_reward_reg` VALUES ('62', '100', '4.0000', '105', '26', '2', '2', '1484450390', '0');
INSERT INTO `yang_reward_reg` VALUES ('63', '105', '4.0000', '0', '25', '0', '2', '1484450390', '1');
INSERT INTO `yang_reward_reg` VALUES ('64', '105', '4.0000', '0', '26', '0', '2', '1484450390', '1');
INSERT INTO `yang_reward_reg` VALUES ('65', '100', '4.0000', '106', '25', '2', '2', '1484457939', '0');
INSERT INTO `yang_reward_reg` VALUES ('66', '100', '4.0000', '106', '26', '2', '2', '1484457939', '0');
INSERT INTO `yang_reward_reg` VALUES ('67', '106', '4.0000', '0', '25', '0', '2', '1484457939', '1');
INSERT INTO `yang_reward_reg` VALUES ('68', '106', '4.0000', '0', '26', '0', '2', '1484457939', '1');
INSERT INTO `yang_reward_reg` VALUES ('69', '100', '4.0000', '107', '25', '2', '2', '1484458994', '0');
INSERT INTO `yang_reward_reg` VALUES ('70', '100', '4.0000', '107', '26', '2', '2', '1484458994', '0');
INSERT INTO `yang_reward_reg` VALUES ('71', '107', '4.0000', '0', '25', '1', '2', '1484458994', '0');
INSERT INTO `yang_reward_reg` VALUES ('72', '107', '4.0000', '0', '26', '1', '2', '1484458994', '0');
INSERT INTO `yang_reward_reg` VALUES ('73', '100', '4.0000', '108', '25', '2', '2', '1484459280', '0');
INSERT INTO `yang_reward_reg` VALUES ('74', '100', '4.0000', '108', '26', '2', '2', '1484459280', '0');
INSERT INTO `yang_reward_reg` VALUES ('75', '108', '4.0000', '0', '25', '1', '2', '1484459280', '0');
INSERT INTO `yang_reward_reg` VALUES ('76', '108', '4.0000', '0', '26', '1', '2', '1484459280', '0');
INSERT INTO `yang_reward_reg` VALUES ('77', '100', '4.0000', '109', '25', '2', '2', '1484463109', '0');
INSERT INTO `yang_reward_reg` VALUES ('78', '100', '4.0000', '109', '26', '2', '2', '1484463109', '0');
INSERT INTO `yang_reward_reg` VALUES ('79', '109', '4.0000', '0', '25', '1', '2', '1484463109', '0');
INSERT INTO `yang_reward_reg` VALUES ('80', '109', '4.0000', '0', '26', '1', '2', '1484463109', '0');
INSERT INTO `yang_reward_reg` VALUES ('81', '100', '4.0000', '110', '25', '2', '2', '1484477578', '0');
INSERT INTO `yang_reward_reg` VALUES ('82', '100', '4.0000', '110', '26', '2', '2', '1484477578', '0');
INSERT INTO `yang_reward_reg` VALUES ('83', '110', '4.0000', '0', '25', '2', '2', '1484477578', '0');
INSERT INTO `yang_reward_reg` VALUES ('84', '110', '4.0000', '0', '26', '2', '2', '1484477578', '0');

-- ----------------------------
-- Table structure for `yang_tibi`
-- ----------------------------
DROP TABLE IF EXISTS `yang_tibi`;
CREATE TABLE `yang_tibi` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `url` varchar(128) NOT NULL,
  `name` varchar(32) NOT NULL,
  `add_time` int(10) NOT NULL,
  `num` decimal(20,8) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0为提币中 1为提币成功  2为充值中 3位充值成功',
  `ti_id` varchar(128) NOT NULL,
  `check_time` int(10) NOT NULL,
  `currency_id` int(10) NOT NULL,
  `fee` decimal(10,4) NOT NULL,
  `actual` decimal(10,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yang_tibi
-- ----------------------------
INSERT INTO `yang_tibi` VALUES ('75', '101', 'bJRE15GmuJSftND64JtGYYqWkhiC9w6zkY', '周成维', '1484450852', '2.00000000', '0', 'b5ee7616804c848f79b4728ddcb1ba32698aed587e88520fd212d86d59a49c29', '0', '26', '0.0000', '2.0000');
INSERT INTO `yang_tibi` VALUES ('76', '100', 'bJRE15GmuJSftND64JtGYYqWkhiC9w6zkY', 'zcw222', '1484450837', '2.00000000', '3', 'b5ee7616804c848f79b4728ddcb1ba32698aed587e88520fd212d86d59a49c29', '1484450837', '26', '0.0000', '0.0000');

-- ----------------------------
-- Table structure for `yang_trade`
-- ----------------------------
DROP TABLE IF EXISTS `yang_trade`;
CREATE TABLE `yang_trade` (
  `trade_id` int(32) NOT NULL AUTO_INCREMENT COMMENT '交易表 交易表的id',
  `trade_no` varchar(32) NOT NULL COMMENT '订单号',
  `member_id` int(10) NOT NULL COMMENT '买家uid即member_id',
  `currency_id` int(10) NOT NULL COMMENT '货币id',
  `currency_trade_id` int(10) NOT NULL,
  `price` decimal(20,4) NOT NULL COMMENT '价格',
  `num` decimal(20,4) NOT NULL COMMENT '数量',
  `money` decimal(20,4) NOT NULL,
  `fee` decimal(20,4) NOT NULL COMMENT '手续费',
  `type` char(10) NOT NULL COMMENT 'buy 或sell',
  `add_time` int(10) NOT NULL COMMENT '成交时间 （添加表的时间）',
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`trade_id`),
  KEY `type` (`type`),
  KEY `id` (`trade_id`),
  KEY `member_id` (`member_id`),
  KEY `currency_id` (`currency_id`),
  KEY `currency_trade_id` (`currency_trade_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2429 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_trade
-- ----------------------------
INSERT INTO `yang_trade` VALUES ('2400', 'T1484457405', '100', '25', '0', '2.0000', '2.0000', '4.0000', '0.0000', 'buy', '1484457405', '0');
INSERT INTO `yang_trade` VALUES ('2401', 'T1484457405', '100', '25', '0', '2.0000', '2.0000', '4.0000', '0.0000', 'sell', '1484457405', '0');
INSERT INTO `yang_trade` VALUES ('2402', 'T1484457405', '100', '25', '0', '2.0000', '2.0000', '4.0000', '0.0000', 'buy', '1484457405', '0');
INSERT INTO `yang_trade` VALUES ('2403', 'T1484457405', '103', '25', '0', '2.0000', '2.0000', '4.0000', '0.0000', 'sell', '1484457405', '0');
INSERT INTO `yang_trade` VALUES ('2404', 'T1484457405', '100', '25', '0', '2.0000', '2.0000', '4.0000', '0.0000', 'buy', '1484457405', '0');
INSERT INTO `yang_trade` VALUES ('2405', 'T1484457405', '101', '25', '0', '2.0000', '2.0000', '4.0000', '0.0000', 'sell', '1484457405', '0');
INSERT INTO `yang_trade` VALUES ('2406', 'T1484457889', '103', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484457889', '0');
INSERT INTO `yang_trade` VALUES ('2407', 'T1484457889', '101', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'sell', '1484457889', '0');
INSERT INTO `yang_trade` VALUES ('2408', 'T1484457908', '103', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484457908', '0');
INSERT INTO `yang_trade` VALUES ('2409', 'T1484457908', '101', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'sell', '1484457908', '0');
INSERT INTO `yang_trade` VALUES ('2410', 'T1484458008', '101', '26', '0', '5.0000', '4.0000', '20.0000', '0.0000', 'buy', '1484458008', '0');
INSERT INTO `yang_trade` VALUES ('2411', 'T1484458008', '103', '26', '0', '5.0000', '4.0000', '20.0000', '0.0000', 'sell', '1484458008', '0');
INSERT INTO `yang_trade` VALUES ('2412', 'T1484459231', '104', '25', '0', '0.0120', '102.0000', '1.2240', '0.0000', 'buy', '1484459231', '0');
INSERT INTO `yang_trade` VALUES ('2413', 'T1484459231', '104', '25', '0', '0.0120', '102.0000', '1.2240', '0.0000', 'sell', '1484459231', '0');
INSERT INTO `yang_trade` VALUES ('2414', 'T1484530644', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484530644', '0');
INSERT INTO `yang_trade` VALUES ('2415', 'T1484530644', '103', '25', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'sell', '1484530644', '0');
INSERT INTO `yang_trade` VALUES ('2416', 'T1484531949', '103', '26', '0', '300.0000', '10.0000', '3000.0000', '0.0000', 'buy', '1484531949', '0');
INSERT INTO `yang_trade` VALUES ('2417', 'T1484531949', '101', '26', '0', '300.0000', '10.0000', '3000.0000', '0.0000', 'sell', '1484531949', '0');
INSERT INTO `yang_trade` VALUES ('2418', 'T1484532452', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484532452', '0');
INSERT INTO `yang_trade` VALUES ('2419', 'T1484532564', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484532564', '0');
INSERT INTO `yang_trade` VALUES ('2420', 'T1484532591', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484532591', '0');
INSERT INTO `yang_trade` VALUES ('2421', 'T1484532619', '101', '25', '0', '1.0000', '2.0000', '2.0000', '0.0000', 'buy', '1484532619', '0');
INSERT INTO `yang_trade` VALUES ('2422', 'T1484532684', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0020', 'sell', '1484532684', '0');
INSERT INTO `yang_trade` VALUES ('2423', 'T1484532801', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0020', 'sell', '1484532801', '0');
INSERT INTO `yang_trade` VALUES ('2424', 'T1484532801', '101', '25', '0', '1.0000', '1.0000', '1.0000', '0.0020', 'buy', '1484532801', '0');
INSERT INTO `yang_trade` VALUES ('2425', 'T1484533004', '103', '25', '0', '100.0000', '2.0000', '200.0000', '0.0000', 'buy', '1484533004', '0');
INSERT INTO `yang_trade` VALUES ('2426', 'T1484533004', '101', '25', '0', '100.0000', '2.0000', '200.0000', '0.0000', 'sell', '1484533004', '0');
INSERT INTO `yang_trade` VALUES ('2427', 'T1484533091', '101', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'buy', '1484533091', '0');
INSERT INTO `yang_trade` VALUES ('2428', 'T1484533091', '101', '26', '0', '1.0000', '1.0000', '1.0000', '0.0000', 'sell', '1484533091', '0');

-- ----------------------------
-- Table structure for `yang_website_bank`
-- ----------------------------
DROP TABLE IF EXISTS `yang_website_bank`;
CREATE TABLE `yang_website_bank` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(125) NOT NULL,
  `bank_adddress` varchar(252) NOT NULL,
  `bank_no` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_website_bank
-- ----------------------------
INSERT INTO `yang_website_bank` VALUES ('1', '成都百年春网络科技股份有限公司', '中国民生银行成都聚龙路支行', '698966625', '1');
INSERT INTO `yang_website_bank` VALUES ('4', 'asdfa(支付宝)', 'adfasd', '3234234234234', '1');

-- ----------------------------
-- Table structure for `yang_withdraw`
-- ----------------------------
DROP TABLE IF EXISTS `yang_withdraw`;
CREATE TABLE `yang_withdraw` (
  `withdraw_id` int(32) NOT NULL AUTO_INCREMENT COMMENT '提现表',
  `uid` int(32) NOT NULL COMMENT '用户id',
  `all_money` decimal(10,2) NOT NULL COMMENT '全部价格',
  `withdraw_fee` decimal(10,2) NOT NULL COMMENT '手续费',
  `money` decimal(10,2) NOT NULL COMMENT '实际价格',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `order_num` varchar(32) NOT NULL COMMENT '订单号',
  `check_time` int(10) NOT NULL COMMENT '审核时间',
  `admin_uid` int(32) NOT NULL COMMENT '审核操作人(管理员）',
  `bank_id` int(32) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '3' COMMENT '1不通过2通过3审核中',
  PRIMARY KEY (`withdraw_id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yang_withdraw
-- ----------------------------
INSERT INTO `yang_withdraw` VALUES ('36', '100', '100.00', '0.50', '99.50', '1484459942', '100-1484459942', '1484459998', '1', '28', '2');
