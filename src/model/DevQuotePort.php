<?php
namespace xjryanse\dev\model;

/**
 * 报价页面
 */
class DevQuotePort extends Base
{
    use \xjryanse\traits\ModelUniTrait;

    public static $uniFields = [
        [
            'field'     =>'quote_id',
            'uni_name'  =>'dev_quote',
            'uni_field' =>'id',
            'del_check' => true
        ]
    ];
    
    public static $picFields = [];

}