<?php

namespace xjryanse\dev\service\needs;

use xjryanse\logic\DataCheck;
use xjryanse\logic\Debug;
use xjryanse\logic\Arrays;
use Exception;
/**
 * 
 */
trait TriggerTraits{

    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
//        $info = self::getInstance($uuid)->get();
//        if (!Arrays::value($info, 'dev_finish') && Arrays::value($data, 'dev_finish')) {
//            $data['dev_finish_time'] = date('Y-m-d H:i:s');
//        }
//        return $data;
    }

    /**
     * 前序保存
     * @param type $data
     * @param type $uuid
     * @return type
     * @throws Exception
     */
    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
//        if (!Arrays::value($data, 'need_title')) {
//            throw new Exception('需求标题必须');
//        }
//        return $data;
    }

    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }

    public static function ramPreSave(&$data, $uuid) {
        $keys = ['project_id','need_title'];
        DataCheck::must($data, $keys);
        // 20240319:TODO兼容前端bug
        if(!Arrays::value($data,'need_desc')){
            $data['need_desc'] = '';
        }
        // 已有同名需求
        $con    = [];
        $con[]  = ['project_id','=',Arrays::value($data, 'project_id')];
        $con[]  = ['need_title','=',Arrays::value($data, 'need_title')];
        $has = self::where($con)->count();
        if($has){
            throw new Exception('已有同名需求，请调整');
        }
        // Debug::dump($data);
    }

    /**
     * 20220810：增加判断
     * @throws Exception
     */
    public function ramPreDelete() {
        $info = $this->get();
        Debug::debug('info', $info);
        if ($info['has_settle']) {
            throw new Exception('已结算不可删');
        }
        if ($info['dev_finish']) {
            throw new Exception('已完工不可删');
        }
        if ($info['has_confirm']) {
            throw new Exception('已接单不可删');
        }
    }
}
