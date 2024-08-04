<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
/**
 * 
 */
class DevNeedsOperateService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevNeedsOperate';

    use \xjryanse\dev\service\needsOperate\TriggerTraits;
    
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    foreach ($lists as &$v) {
                        // 是不是我的操作单
                        $v['operateUserIsMe'] = $v['operate_user'] == session(SESSION_USER_ID) ? 1 : 0;
                    }

                    return $lists;
                }, true);
    }
    
    /**
     * 
     * operate_type:甲方A；乙方B
     * AToPrize 甲方提交需求给乙方
     * BPrizeFinish 乙方报价完成
     * APrizeCheck 甲方确认报价
     * BAcceptOrder 乙方接单
     * BFinishOrder 乙方完工
     * AVerify 甲方验收
     * ASettle 甲方给乙方结算
     * @param type $param
     * @return type
     */
    public static function doOperate($paramRaw){
        $param = Arrays::value($paramRaw, 'table_data') ? : $paramRaw;

        $keys = ['need_id','operate_type','opinion'];
        $data = Arrays::getByKeys($param, $keys);

        $data['direction']              = Arrays::value($param, 'direction') ? : 1;
        $data['operate_user']           = session(SESSION_USER_ID);
        $data['operate_wepub_openid']   = session(SESSION_OPENID);
        $data['operate_time']           = date('Y-m-d H:i:s');

        return self::saveRam($data);
    }
    
}
