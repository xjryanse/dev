<?php

namespace xjryanse\dev\service\projectExt;

use xjryanse\logic\Arrays;
/**
 * 字段复用列表
 */
trait DoTraits{
    /**
     * 触发订单续费动作
     * @param type $param
     * @return type
     */
    public static function doDealOrderExtRam($param){
        $orderId = Arrays::value($param, 'order_id');
        return self::dealOrderExtRam($orderId);
    }
}
