<?php

namespace xjryanse\dev\service\project;

/**
 * 字段复用列表
 */
trait PaginateTraits{
    
    /**
     * 手机管理端口分页
     * 只查状态开
     */
    public static function paginateForWebManage($con = []){
        $con[] = ['status','=','1'];
        
        return self::paginateRaw($con, 'finish_time desc');
    }
}
