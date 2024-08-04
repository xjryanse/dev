<?php

namespace xjryanse\dev\service\needs;


use xjryanse\dev\service\DevProjectUserService;
use xjryanse\sql\service\SqlService;
use think\facade\Request;

/**
 * 
 */
trait CustomerTraits{

    /**
     * 20240316:给客户看
     */
    public static function paginateForCustomer(){
        // 提取我的项目列表
        $projectIds = DevProjectUserService::myProjectIds();
        
        $sqlKey     = 'devNeedsWithOperate';
        $sqlId      = SqlService::keyToId($sqlKey);

        $perPage    = Request::param('per_page', 50);
        // 20231027
        // $withSum    = Request::param('withSum') ? 1 : 0;
        $withSum    = 1;
        $orderBy    = Request::param('orderBy', 'dealRateNum desc,create_time desc');

        $uparam     = Request::param('table_data') ? : Request::param();
        // 放在url中的参数：区分推进和中止的项目
        if(Request::param('currentDirection')){
            $uparam['currentDirection'] = Request::param('currentDirection');
        }

        $where      = SqlService::getInstance($sqlId)->whereFields($uparam);
        // 特殊权限控制
        $where[]    = ['project_id', 'in', $projectIds];
        
        $staticsGroupField = Request::param('staticsGroupField');
        // 20240120:封装
        $pgLists    = SqlService::sqlPaginateData($sqlKey, $where, $orderBy, $perPage, '', '', $withSum, $staticsGroupField);
        $pgLists['$con'] = $where;
        // $pgLists['statics'] = ['BPrizeFinish'=>11];
        // 空车描述
        return $pgLists;
    }
    
    
}
