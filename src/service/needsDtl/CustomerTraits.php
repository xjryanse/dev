<?php

namespace xjryanse\dev\service\needsDtl;

use xjryanse\dev\service\DevProjectUserService;
use xjryanse\sql\service\SqlService;
use think\facade\Request;

/**
 * 客户端专用逻辑
 */
trait CustomerTraits{
    
    public static function paginateForCustomer(){
        // 20240710：提取当前用户的项目id
        $projectIds = DevProjectUserService::myProjectIds();
        
        $sqlKey     = 'devNeedsDtlWithState';
        $sqlId      = SqlService::keyToId($sqlKey);

        $uparam     = Request::param('table_data') ? : Request::param();

        $where      = SqlService::getInstance($sqlId)->whereFields($uparam);
        // 特殊权限控制
        $where[]    = ['project_id', 'in', $projectIds];
        
        $staticsGroupField = Request::param('staticsGroupField') ? : 'custConfirmState';
        // 20240120:封装
        $pgLists            = SqlService::sqlPaginateData($sqlKey, $where, '', 50, '', '', 1, $staticsGroupField);
        $pgLists['$con']    = $where;
        // $pgLists['statics'] = ['BPrizeFinish'=>11];
        // 空车描述
        return $pgLists;
    }
    
}
