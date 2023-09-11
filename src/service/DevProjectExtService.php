<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\order\service\OrderService;
use xjryanse\goods\service\GoodsService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use xjryanse\logic\Datetime;
use xjryanse\logic\DbOperate;
use app\order\service\OrderEProjectExtService;

/**
 * 项目续费记录
  CREATE TABLE `w_dev_project_ext` (
  `id` char(19) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `project_id` char(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '项目id',
  `old_finish_time` datetime DEFAULT NULL COMMENT '原到期日',
  `new_finish_time` datetime DEFAULT NULL COMMENT '新到期日',
  `ext_start_time` datetime DEFAULT NULL COMMENT '续费期开始',
  `ext_days` int(11) DEFAULT NULL COMMENT '续费天数',
  `from_table` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '续费来源表，订单，优惠券使用记录表等',
  `from_table_id` char(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '续费来源表_id，不一定是订单，也有可能是优惠券兑换',
  `sort` int(11) DEFAULT '1000' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) DEFAULT '0' COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) DEFAULT '0' COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目续期';
 * 
 */
class DevProjectExtService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevProjectExt';

    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }

    protected static function dataGenerate($projectId, $extDays, $data = []) {
        $projInfo = DevProjectService::getInstance($projectId)->get();
        $data['old_finish_time'] = $projInfo['finish_time'] ?: null;
        $finishTimeStamp = strtotime($projInfo['finish_time']);
        // TODO : 延期开始时间：还没过期，取没过期时间
        $data['ext_start_time'] = $projInfo['finish_time'] && $finishTimeStamp > time() ? $projInfo['finish_time'] : date('Y-m-d H:i:s');

        $data['new_finish_time'] = Datetime::datetimeDayExt($extDays, $data['ext_start_time']);
        return $data;
    }

    public static function ramPreSave(&$data, $uuid) {
        $projectId = $data['project_id'];
        $extDays = $data['ext_days'];
        $data = self::dataGenerate($projectId, $extDays, $data);

        return $data;
    }

    public static function ramAfterSave(&$data, $uuid) {
        //20230519:更新项目的到期时间
        $projectId = $data['project_id'];
        DevProjectService::getInstance($projectId)->updateFinishTimeRam();
    }

    public static function ramAfterUpdate(&$data, $uuid) {
        //20230519:更新项目的到期时间
        $info = self::getInstance($uuid)->get();
        $projectId = $info['project_id'];
        DevProjectService::getInstance($projectId)->updateFinishTimeRam();
    }

    public function ramAfterDelete($info) {
        $projectId = $info['project_id'];
        DevProjectService::getInstance($projectId)->updateFinishTimeRam();
    }

    public static function hasExtLog($fromTable, $fromTableId) {
        $con[] = ['from_table', '=', $fromTable];
        $con[] = ['from_table_id', '=', $fromTableId];
        return self::count($con);
    }

    /**
     * 计算项目的到期时间
     * @param type $projectId
     */
    public static function calProjectFinishTime($projectId) {
        $lists = DevProjectService::getInstance($projectId)->objAttrsList('devProjectExt');
        return $lists ? max(array_column($lists, 'new_finish_time')) : null;
    }

    /*
     * 20230530:处理订单续费的逻辑
     */

    public static function doOrderExtBatch() {
        // 20230530：提取未处理的订单
        $orderIds = self::noDealOrderIds();
        foreach ($orderIds as $orderId) {
            self::dealOrderExtRam($orderId);
        }
        //TODO 批量处理订单续费
        DbOperate::dealGlobal();
    }

    /**
     * 20230530:提取未续费的订单编号
     */
    public static function noDealOrderIds() {
        // 20230530：提取未处理的订单
        $thisTable = self::getTable();

        $con[] = ['a.isFullPay', '=', 1];
        $con[] = ['a.order_prize', '>', 0];
        $con[] = ['a.is_cancel', '=', 0];
        $con[] = ['a.company_id', '=', session(SESSION_COMPANY_ID)];
        $orderIds = OrderService::mainModel()->alias('a')->leftJoin($thisTable . ' b', 'b.from_table_id = a.id')
                ->where('a.order_type', 'projectExt')
                ->whereNull('b.id')
                ->where($con)
                ->column('a.id');
        Debug::debug('noDealOrderIds提取未处理订单编号的sql', self::mainModel()->getLastSql());
        return $orderIds;
    }

    /**
     * 20230530：处理订单续期
     */
    public static function dealOrderExtRam($orderId) {
        $orderTable = OrderService::getTable();
        $orderInst = OrderService::getInstance($orderId);
        // 未支付
        if (!$orderInst->hasPay()) {
            return false;
        }
        // 已处理
        if (self::hasExtLog($orderTable, $orderId)) {
            return false;
        }
        // 处理逻辑
        $orderInfo = $orderInst->get();
        $goodsInfo = GoodsService::getInstance($orderInfo['goods_id'])->get(0);
        $eOrderInfo = OrderEProjectExtService::getInstance($orderId)->get();
        // 下单时未捆绑项目
        if (!$eOrderInfo['dev_project_id']) {
            return false;
        }

        $upData['project_id'] = $eOrderInfo['dev_project_id'];
        $upData['ext_days'] = $goodsInfo['goods_value'];
        $upData['from_table'] = $orderTable;
        $upData['from_table_id'] = $orderId;
        self::saveRam($upData);
    }

}
