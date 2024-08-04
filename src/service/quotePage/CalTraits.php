<?php

namespace xjryanse\dev\service\quotePage;

use xjryanse\dev\service\DevQuoteService;
use xjryanse\dev\service\DevQuotePortService;
use xjryanse\dev\service\DevQuoteFuncService;

/**
 * 字段复用列表
 */
trait CalTraits{
    /**
     * 计算端口基础价
     */
    public static function calPrizeByQuoteId($quoteId){
        $lists = DevQuoteService::getInstance($quoteId)->objAttrsList('devQuotePage');
        return array_sum(array_column($lists, 'prize'));
    }

    /**
     * 端口计算报价
     */
    public static function calPrizeByPortId($portId){
        $lists = DevQuotePortService::getInstance($portId)->objAttrsList('devQuotePage');
        return array_sum(array_column($lists, 'prize'));
    }

    /**
     * 功能计算报价
     */
    public static function calPrizeByFuncId($funcId){
        $lists = DevQuoteFuncService::getInstance($funcId)->objAttrsList('devQuotePage');
        return array_sum(array_column($lists, 'prize'));
    }
}
