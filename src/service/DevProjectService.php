<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use xjryanse\system\service\SystemCompanyService;
/**
 * 
 */
class DevProjectService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;
    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevProject';
    ///从ObjectAttrTrait中来
//    // 定义对象的属性
//    protected $objAttrs = [];
//    // 定义对象是否查询过的属性
//    protected $hasObjAttrQuery = [];
//    // 定义对象属性的配置数组
    
    use \xjryanse\dev\service\project\PaginateTraits;
    use \xjryanse\dev\service\project\FieldTraits;
    use \xjryanse\dev\service\project\ListTraits;

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    self::objAttrsListBatch('devNeeds', $ids);

                    //待接单
                    $conConfirm[] = ['needStatus', '=', 'to_confirm'];
                    //开发中
                    $conDealing[] = ['needStatus', '=', 'dealing'];
                    //待验收
                    $conCheck[] = ['needStatus', '=', 'to_check'];
                    //待结算
                    $conSettle[] = ['needStatus', '=', 'to_settle'];
                    //已结算
                    $conFinish[] = ['needStatus', '=', 'finish'];

                    foreach ($lists as &$v) {
                        $v = self::detailAdd($v);
                        // 待接单
                        $v['toConfirmCount']    = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conConfirm);
                        // 开发中
                        $v['dealingCounts']     = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conDealing);
                        // 待验收
                        $v['toCheckCount']      = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conCheck);
                        // 待结算
                        $v['toSettleCount']     = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conSettle);
                        // 已结算
                        $v['finishCount']       = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conFinish);
                        // 绑定客户
                        $v['bindCustomerId']    = SystemCompanyService::getInstance($v['bind_company_id'])->fBindCustomerId();
                    }
                    return $lists;
                }, true);
    }

    /**
     * 20230523:静态无关联
     * @param type $v
     * @return type
     */
    public static function detailAdd(&$v) {
        //到期状态
        $timeDiff = strtotime($v['finish_time']) - time();
        // 1有效；2即将到期；3已过期
        $v['finishState'] = $timeDiff < 0 ? 3 : ($timeDiff > 86400 * 30 ? 1 : 2);
        // 剩余天数
        $v['remainDays'] = $timeDiff > 0 ? intval($timeDiff / 86400) : 0;
        return $v;
    }

    /**
     * 20230519:根据明细，更新到期时间
     */
    public function updateFinishTimeRam() {
        $updData['finish_time'] = DevProjectExtService::calProjectFinishTime($this->uuid);
        $res = self::getInstance($this->uuid)->updateRam($updData);
        return $res;
    }
    /*
     * 20230813
     * 计算项目对应的客户编号
     */
    public function calCustomerId(){
        $info           = $this->get();
        $bindCompanyId  = Arrays::value($info, 'bind_company_id');
        if(!$bindCompanyId){
            return null;
        }
        $companyInfo = SystemCompanyService::getInstance($bindCompanyId)->get();
        return Arrays::value($companyInfo, 'bind_customer_id');
    }
    
}
