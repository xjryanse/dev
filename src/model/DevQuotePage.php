<?php
namespace xjryanse\dev\model;

/**
 * 报价页面
 */
class DevQuotePage extends Base
{
    use \xjryanse\traits\ModelUniTrait;

    public static $uniFields = [
        [
            'field'     =>'quote_id',
            'uni_name'  =>'dev_quote',
            'uni_field' =>'id',
            'del_check' => true
        ],
        [
            'field'     =>'func_id',
            'uni_name'  =>'dev_quote_func',
            'uni_field' =>'id',
            'del_check' => true
        ],
        [
            'field'     =>'port_id',
            'uni_name'  =>'dev_quote_port',
            'uni_field' =>'id',
            'del_check' => true
        ]
    ];
    
    public static $picFields = ['demo_pic'];

    public function getDemoPicAttr($value) {
        return self::getImgVal($value);
    }
    public function setDemoPicAttr($value) {
        return self::setImgVal($value);
    }
}