<?php

namespace xjryanse\dev\service\quotePort;

use xjryanse\dev\service\DevQuoteService;
/**
 * 字段复用列表
 */
trait CalTraits{
    /**
     * 计算端口基础价
     */
    public static function calBasePrizeByQuoteId($quoteId){
        $lists = DevQuoteService::getInstance($quoteId)->objAttrsList('devQuotePort');
        return array_sum(array_column($lists, 'base_prize'));
    }

}
