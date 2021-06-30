<?php
namespace xjryanse\dev\model;

/**
 * 
 */
class DevNeeds extends Base
{
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