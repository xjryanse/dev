<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 
 */
class DevQuoteService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\ObjectAttrTrait;
    
    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevQuote';

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
            return $lists;
        },true);
    }
}
