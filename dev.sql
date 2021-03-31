
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for w_dev_needs
-- ----------------------------
CREATE TABLE `w_dev_needs`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `need_title` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '需求文档名',
  `need_type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '需求类型：合同、补充、口头',
  `need_desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '需求描述',
  `need_user` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '需求人姓名',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `app_id`(`app_id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '需求文档' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_dev_needs_dtl
-- ----------------------------
CREATE TABLE `w_dev_needs_dtl`  (
  `id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `app_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `company_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `need_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '需求的id',
  `pid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '父级需求详情',
  `dtl_type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '需求类型:页面(前端)，逻辑(后端)，功能模块',
  `dtl_title` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '需求标题',
  `dtl_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '需求内容',
  `dtl_user` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '需求人姓名',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `app_id`(`app_id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '开发需求' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
