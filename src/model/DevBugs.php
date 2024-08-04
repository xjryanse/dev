<?php
namespace xjryanse\dev\model;

/**
 * 开发项目
 */
class DevBugs extends Base
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
        [
            'field'     =>'needs_id',
            'uni_name'  =>'dev_needs',
            'uni_field' =>'id',
        ],
        [
            'field'     =>'needs_dtl_id',
            'uni_name'  =>'dev_needs_dtl',
            'uni_field' =>'id',
        ],
    ];
}