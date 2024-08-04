<?php

namespace xjryanse\dev\service\bugs;

use xjryanse\dev\service\DevNeedsDtlService;
use xjryanse\dev\service\DevNeedsService;
use xjryanse\logic\Arrays;
use Exception;
/**
 * 
 */
trait TriggerTraits{

    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public function extraPreDelete() {
        self::stopUse(__METHOD__);
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
    
    protected static function redunFields(&$data, $uuid){
        if(Arrays::value($data, 'needs_dtl_id')){
            $data['needs_id'] = DevNeedsDtlService::getInstance($data['needs_dtl_id'])->fNeedId();
        }
        if(Arrays::value($data, 'needs_id')){
            $data['project_id'] = DevNeedsService::getInstance($data['needs_id'])->fProjectId();
        }

        return $data;
    }
}
