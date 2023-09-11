<?php
namespace xjryanse\dev\model;

/**
 * 需求组测试方案
 */
class DevNeedsGroupTester extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'group_id',
            'uni_name'  =>'dev_needs_group',
            'uni_field' =>'id',
            'del_check' => true,
            'del_msg'   => '请先删除{$count}条测试方案'
        ]
    ];
}
