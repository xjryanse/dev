<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 
 */
class DevQuotePageService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;
    
    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevQuotePage';

    use \xjryanse\dev\service\quotePage\CalTraits;
    use \xjryanse\dev\service\quotePage\TriggerTraits;
    
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
            return $lists;
        },true);
    }
}
