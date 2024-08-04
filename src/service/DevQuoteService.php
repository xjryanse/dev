<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\dev\service\DevQuotePortService;
use xjryanse\dev\service\DevQuotePageService;

/**
 * 
 */
class DevQuoteService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;
    
    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevQuote';

    use \xjryanse\dev\service\quote\CalTraits;
    use \xjryanse\dev\service\quote\TriggerTraits;
    
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
            return $lists;
        },true);
    }
    
    /**
     * 20230929:明细数据同步逻辑
     */
    public function dataSync(){
        $data['port_base_prize']    = DevQuotePortService::calBasePrizeByQuoteId($this->uuid);
        $data['page_prize']         = DevQuotePageService::calPrizeByQuoteId($this->uuid);
        $data['all_prize']          = $data['port_base_prize'] + $data['page_prize'];

        return $this->updateRam($data);
    }
}
