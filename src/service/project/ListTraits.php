<?php

namespace xjryanse\dev\service\project;

use xjryanse\logic\Arrays;
use xjryanse\goods\service\GoodsService;
use Exception;
/**
 * 字段复用列表
 */
trait ListTraits{
    
    /**
     * 获取项目续费商品
     */
    public static function listProjectExtGoods($param){
        $projectId  = Arrays::value($param, 'dev_project_id');
        $extSpuId   = self::getInstance($projectId)->fExtSpuId();
        if(!$extSpuId){
            throw new Exception('未配置续费信息，请联系客服续费'.$projectId);
        }
        $saleType = 'projectExt';

        $con    = [];
        $con[]  = ['spu_id','in',$extSpuId];
        return GoodsService::dimListBySaleTypeEffect($saleType, $con);
    }

}
