<?php

namespace xjryanse\dev\service\needsDtl;

use xjryanse\logic\DataCheck;
use xjryanse\logic\Debug;
use xjryanse\logic\Arrays;
use xjryanse\dev\service\DevNeedsService;
use Exception;
/**
 * 
 */
trait TriggerTraits{

    public static function extraPreUpdate(&$data, $uuid) {
        // self::stopUse(__METHOD__);
    }

    /**
     * 前序保存
     * @param type $data
     * @param type $uuid
     * @return type
     * @throws Exception
     */
    public static function extraPreSave(&$data, $uuid) {
        // self::stopUse(__METHOD__);
    }

    public function extraPreDelete() {
        // self::stopUse(__METHOD__);
    }
    
    public static function ramPreSave(&$data, $uuid) {
        self::redunFields($data, $uuid);
    }

    public static function ramPreUpdate(&$data, $uuid) {
        
        self::redunFields($data, $uuid);
    }
    
    /**
     * 20220810：增加判断
     * @throws Exception
     */
    public function ramPreDelete() {

    }

    public static function ramAfterSave(&$data, $uuid) {
        $info = self::getInstance($uuid)->get();
        if ($info['need_id']) {
            DevNeedsService::getInstance($info['need_id'])->appendPrizeUpdateRam();
        }
    }

    public static function ramAfterUpdate(&$data, $uuid) {
        $info = self::getInstance($uuid)->get();
        if ($info['need_id']) {
            DevNeedsService::getInstance($info['need_id'])->appendPrizeUpdateRam();
        }
    }
    
    protected static function redunFields(&$data, $uuid){

        return $data;
    }
    
}
