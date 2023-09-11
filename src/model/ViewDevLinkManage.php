<?php
namespace xjryanse\dev\model;

/**
 * 开发项目管理视图
 * 
    SELECT
	`a`.`id` AS `id`,
	`a`.`company_id` AS `company_id`,
	`a`.`customer_id` AS `customer_id`,
	max( ( CASE `a`.`cate` WHEN 'admLogin' THEN `a`.`url` ELSE NULL END ) ) AS `admLogin`,
	max( ( CASE `a`.`cate` WHEN 'admConf' THEN `a`.`url` ELSE NULL END ) ) AS `admConf`,
	max( ( CASE `a`.`cate` WHEN 'admTable' THEN `a`.`url` ELSE NULL END ) ) AS `admTable`,
	max( ( CASE `a`.`cate` WHEN 'admPage' THEN `a`.`url` ELSE NULL END ) ) AS `admPage`,
	max( ( CASE `a`.`cate` WHEN 'userList' THEN `a`.`url` ELSE NULL END ) ) AS `userList`,
	max( ( CASE `a`.`cate` WHEN 'compBus' THEN `a`.`url` ELSE NULL END ) ) AS `compBus`,
	max( ( CASE `a`.`cate` WHEN 'goods' THEN `a`.`url` ELSE NULL END ) ) AS `goods`,
	max( ( CASE `a`.`cate` WHEN 'orderList' THEN `a`.`url` ELSE NULL END ) ) AS `orderList`,
	max( ( CASE `a`.`cate` WHEN 'orderPin' THEN `a`.`url` ELSE NULL END ) ) AS `orderPin`,
	max( ( CASE `a`.`cate` WHEN 'orderBao' THEN `a`.`url` ELSE NULL END ) ) AS `orderBao`,
	max( ( CASE `a`.`cate` WHEN 'tourTab' THEN `a`.`url` ELSE NULL END ) ) AS `tourTab`,
	max( ( CASE `a`.`cate` WHEN 'wxManage' THEN `a`.`url` ELSE NULL END ) ) AS `wxManage`,
	max( ( CASE `a`.`cate` WHEN 'baoTa' THEN `a`.`url` ELSE NULL END ) ) AS `baoTa`,
	max( ( CASE `a`.`cate` WHEN 'needs' THEN `a`.`url` ELSE NULL END ) ) AS `needs`,
	max( ( CASE `a`.`cate` WHEN 'cacheClear' THEN `a`.`url` ELSE NULL END ) ) AS `cacheClear`,
	max( ( CASE `a`.`cate` WHEN 'generateFile' THEN `a`.`url` ELSE NULL END ) ) AS `generateFile`,
	max( ( CASE `a`.`cate` WHEN 'busGps' THEN `a`.`url` ELSE NULL END ) ) AS `busGps`,
	max( ( CASE `a`.`cate` WHEN 'prizeRule' THEN `a`.`url` ELSE NULL END ) ) AS `prizeRule`,
	max( ( CASE `a`.`cate` WHEN 'wxTplMsgLog' THEN `a`.`url` ELSE NULL END ) ) AS `wxTplMsgLog`,
	max( ( CASE `a`.`cate` WHEN 'sysErr' THEN `a`.`url` ELSE NULL END ) ) AS `sysErr`,
	max( ( CASE `a`.`cate` WHEN 'uniForm' THEN `a`.`url` ELSE NULL END ) ) AS `uniForm`,
	max( ( CASE `a`.`cate` WHEN 'devIp' THEN `a`.`url` ELSE NULL END ) ) AS `devIp`,
	max( ( CASE `a`.`cate` WHEN 'queryLog' THEN `a`.`url` ELSE NULL END ) ) AS `queryLog`,
	max( ( CASE `a`.`cate` WHEN 'timing' THEN `a`.`url` ELSE NULL END ) ) AS `timing`,
	max( ( CASE `a`.`cate` WHEN 'manual' THEN `a`.`url` ELSE NULL END ) ) AS `manual`,
	max( ( CASE `a`.`cate` WHEN 'approval' THEN `a`.`url` ELSE NULL END ) ) AS `approval` 
    FROM
        `w_knowledge_site` `a` 
    GROUP BY
        `a`.`company_id`,
        `a`.`customer_id`
 */
class ViewDevLinkManage extends Base
{

}