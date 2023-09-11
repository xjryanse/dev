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
    use \xjryanse\traits\MainModelQueryTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;
    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevProject';
    ///从ObjectAttrTrait中来
    // 定义对象的属性
    protected $objAttrs = [];
    // 定义对象是否查询过的属性
    protected $hasObjAttrQuery = [];
    // 定义对象属性的配置数组
    protected static $objAttrConf = [
        'devNeeds' => [
            'class' => '\\xjryanse\\dev\\service\\DevNeedsService',
            'keyField' => 'project_id',
            'master' => true
        ],
        'devNeedsGroup' => [
            'class' => '\\xjryanse\\dev\\service\\DevNeedsGroupService',
            'keyField' => 'project_id',
            'master' => true
        ],
        'devProjectTime' => [
            'class' => '\\xjryanse\\dev\\service\\DevProjectTimeService',
            'keyField' => 'project_id',
            'master' => true
        ],
        'devProjectExt' => [
            'class' => '\\xjryanse\\dev\\service\\DevProjectExtService',
            'keyField' => 'project_id',
            'master' => true
        ],
        'devProjectUser' => [
            'class' => '\\xjryanse\\dev\\service\\DevProjectUserService',
            'keyField' => 'project_id',
            'master' => false
        ],
        'devBugs' => [
            'class' => '\\xjryanse\\dev\\service\\DevBugsService',
            'keyField' => 'project_id',
            'master' => true
        ],
        'devBill' => [
            'class' => '\\xjryanse\\dev\\service\\DevBillService',
            'keyField' => 'project_id',
            'master' => true
        ],
    ];

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    self::objAttrsListBatch('devNeeds', $ids);
                    self::objAttrsListBatch('devNeedsGroup', $ids);
                    self::objAttrsListBatch('devProjectExt', $ids);
                    self::objAttrsListBatch('devProjectTime', $ids);
                    self::objAttrsListBatch('devProjectUser', $ids);
                    self::objAttrsListBatch('devBugs', $ids);
                    self::objAttrsListBatch('devBill', $ids);

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
                        // 需求数
                        $v['needsCount'] = self::getInstance($v['id'])->objAttrsCount('devNeeds');
                        // 续期次数
                        $v['extCount'] = self::getInstance($v['id'])->objAttrsCount('devProjectExt');
                        // 需求组数
                        // $v['needsGroupCount']   = Arrays::value($needsGroupCount, $v['id'],0);
                        $v['needsGroupCount'] = self::getInstance($v['id'])->objAttrsCount('devNeedsGroup');
                        // 待接单
                        $v['toConfirmCount'] = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conConfirm);
                        // 开发中
                        $v['dealingCounts'] = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conDealing);
                        // 待验收
                        $v['toCheckCount'] = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conCheck);
                        // 待结算
                        $v['toSettleCount'] = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conSettle);
                        // 已结算
                        $v['finishCount'] = self::getInstance($v['id'])->objAttrsCount('devNeeds', $conFinish);
                        // 工时记录数
                        $v['timeCount'] = self::getInstance($v['id'])->objAttrsCount('devProjectTime');
                        // 成员数
                        $v['userCount'] = self::getInstance($v['id'])->objAttrsCount('devProjectUser');
                        // bug数
                        $v['bugCount'] = self::getInstance($v['id'])->objAttrsCount('devBugs');
                        // 账单数
                        $v['billCount'] = self::getInstance($v['id'])->objAttrsCount('devBill');
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
    
    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 直付款
     * @return type
     */
    public function fDirectPay() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fProjectTitle() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fProjectLogo() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 排序
     */
    public function fSort() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 状态(0禁用,1启用)
     */
    public function fStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 有使用(0否,1是)
     */
    public function fHasUsed() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未锁，1：已锁）
     */
    public function fIsLock() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未删，1：已删）
     */
    public function fIsDelete() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 备注
     */
    public function fRemark() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建者，user表
     */
    public function fCreater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新者，user表
     */
    public function fUpdater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建时间
     */
    public function fCreateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新时间
     */
    public function fUpdateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

}
