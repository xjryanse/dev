<?php
namespace xjryanse\dev\model;

/**
 * 开发项目按日工时
 */
class DevProjectTime extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        //性能不佳
        [
            'field'     =>'project_id',
            'uni_name'  =>'dev_project',
            'uni_field' =>'id',
        ],
    ];
}