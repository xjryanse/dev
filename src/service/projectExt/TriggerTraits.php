<?php

namespace xjryanse\dev\service\projectExt;

use xjryanse\dev\service\DevProjectService;
use xjryanse\logic\Datetime;
/**
 * 字段复用列表
 */
trait TriggerTraits{
    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public function extraPreDelete() {
        self::stopUse(__METHOD__);
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
    
    protected static function dataGenerate($projectId, $extDays, $data = []) {
        $projInfo                   = DevProjectService::getInstance($projectId)->get();
        $data['old_finish_time']    = $projInfo['finish_time'] ?: null;
        // $finishTimeStamp            = strtotime($projInfo['finish_time']);
        // TODO : 延期开始时间：还没过期，取没过期时间
        $data['ext_start_time']     = self::calExtStartTime($projectId);

        $data['new_finish_time']    = Datetime::datetimeDayExt($extDays, $data['ext_start_time']);
        return $data;
    }
}
