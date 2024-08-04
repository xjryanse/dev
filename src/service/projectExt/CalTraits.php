<?php

namespace xjryanse\dev\service\projectExt;

use xjryanse\dev\service\DevProjectService;
use xjryanse\goods\service\GoodsService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Datetime;
/**
 * 字段复用列表
 */
trait CalTraits{

    /**
     * 功能计算报价
     */
    public static function calExtStartTime($projectId){
        $info = DevProjectService::getInstance($projectId)->get();
        // 连续计期
        $extContinue = Arrays::value($info, 'ext_continue') ? 1:0;
        if($extContinue){
            // 连续计期
            $extStartTime = $info['finish_time'];
        } else {
            $finishTimeStamp        = strtotime($info['finish_time']);
            $extStartTime = $info['finish_time'] && $finishTimeStamp > time() 
                    ? $info['finish_time'] : date('Y-m-d H:i:s');
        }
        
        return $extStartTime;
    }
    /**
     * 按月续费(年份转为12个月)
     * @param type $projectId
     * @param type $monthCount
     */
    public static function calExtDaysByMonth($projectId, $monthCount){
        $extStartTime = self::calExtStartTime($projectId);
        // 计算结束时间,intval处理1.00
        $extEndTime   = date('Y-m-d H:i:s',strtotime($extStartTime ." +".intval($monthCount)." month"));
        // 计算天数
        return Datetime::dayDiff($extEndTime, $extStartTime);
    }
    /**
     * 计算续期天数
     * @param type $projectId
     * @param type $goodsId
     */
    public static function calExtDaysByGoodsId($projectId,$goodsId){
        $goodsInfo  = GoodsService::getInstance($goodsId)->get();
        $value      = Arrays::value($goodsInfo, 'goods_value');
        $valueUnit  = Arrays::value($goodsInfo, 'value_unit');
        if($valueUnit == 'month'){
            return self::calExtDaysByMonth($projectId, $value);
        }
        return $value;
    }
    /**
     * 计算项目的到期时间
     * @param type $projectId
     */
    public static function calProjectFinishTime($projectId) {
        $lists = DevProjectService::getInstance($projectId)->objAttrsList('devProjectExt');
        return $lists ? max(array_column($lists, 'new_finish_time')) : null;
    }
}
