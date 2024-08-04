<?php

namespace xjryanse\dev\service\bill;


use xjryanse\dev\service\DevProjectUserService;

/**
 * 
 */
trait CustomerTraits{

    /**
     * 20240316:给客户看
     */
    public static function paginateForCustomer($con = [], $order = '', $perPage = 10, $having = '', $field = "*", $withSum = false){
        // 提取我的项目列表
        $projectIds = DevProjectUserService::myProjectIds();
        $con[]      = ['project_id','in',$projectIds];

        $withSum    = true;
        return self::paginate($con, $order, $perPage, $having, $field, $withSum);
    }
    
    
}
