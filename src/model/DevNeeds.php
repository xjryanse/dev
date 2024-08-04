<?php
namespace xjryanse\dev\model;

/**
 * 
 */
class DevNeeds extends Base
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
    
    /**
     * 验收时间
     * @param type $value
     * @return type
     */
    public function setNeedVerifyTimeAttr($value)
    {
        return $this->setTimeVal($value);
    }
    
    public function setDevFinishTimeAttr($value)
    {
        return $this->setTimeVal($value);
    }
}