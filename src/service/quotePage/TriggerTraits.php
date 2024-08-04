<?php

namespace xjryanse\dev\service\quotePage;

use xjryanse\dev\service\DevQuoteService;
use xjryanse\logic\Arrays;
/**
 * 字段复用列表
 */
trait TriggerTraits{
    /**
     * 钩子-保存前
     */
    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }
    
    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }

    /**
     * 20230923:批量保存前处理
     * @param type $data
     * @param type $uuid
     */
    public static function ramPreSaveAll(&$data) {
//        dump($data);
//        exit;
    }

    /**
     * 钩子-保存前
     */
    public static function ramPreSave(&$data, $uuid) {


    }

    /**
     * 钩子-保存后
     */
    public static function ramAfterSave(&$data, $uuid) {
        $info       = self::getInstance($uuid)->get();
        $quoteId    = Arrays::value($info,'quote_id');
        // 父级数据更新
        DevQuoteService::getInstance($quoteId)->dataSync();        
    }

    /**
     * 钩子-更新前
     */
    public static function ramPreUpdate(&$data, $uuid) {

    }

    /**
     * 钩子-更新后
     */
    public static function ramAfterUpdate(&$data, $uuid) {
        $info       = self::getInstance($uuid)->get();
        $quoteId    = Arrays::value($info,'quote_id');
        // 父级数据更新
        DevQuoteService::getInstance($quoteId)->dataSync();        

    }

    /**
     * 钩子-删除前
     */
    public function ramPreDelete() {

    }

    /**
     * 钩子-删除后
     */
    public function ramAfterDelete($rawData) {
        $quoteId = Arrays::value($rawData, 'quote_id');
        // 父级数据更新
        DevQuoteService::getInstance($quoteId)->dataSync();
    }

}
