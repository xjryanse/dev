<?php
namespace xjryanse\dev\model;

/**
 * 
 */
class DevNeedsOperate extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        //性能不佳
        [
            'field'     =>'need_id',
            'uni_name'  =>'dev_needs',
            'uni_field' =>'id',
            'del_check' => true,
        ],
    ];

}