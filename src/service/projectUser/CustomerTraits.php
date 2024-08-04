<?php

namespace xjryanse\dev\service\projectUser;

/**
 * 字段复用列表
 */
trait CustomerTraits{
    
    /**
     * 20240710:提取我的项目列表，一般用于客户端过滤数据
     */
    public static function myProjectIds(){
        $userId     = session(SESSION_USER_ID);
        $cond[]     = ['user_id', '=', $userId];
        $projectIds = self::mainModel()->where($cond)->column('project_id');
        return $projectIds;
    }

}
