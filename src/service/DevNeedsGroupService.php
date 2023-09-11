<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use app\generate\service\GenerateTemplateService;
use app\generate\service\GenerateTemplateLogService;

/**
 * 
 */
class DevNeedsGroupService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevNeedsGroup';

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $needsCounts = DevNeedsService::groupBatchCount('group_id', $ids);
                    $budgePrize = DevNeedsService::groupBatchSum('group_id', $ids, 'order_amount');
                    // 已完工金额
                    $conFinish[] = ['dev_finish', '=', 1];
                    $finishPrize = DevNeedsService::groupBatchSum('group_id', $ids, 'order_amount', $conFinish);
                    // 已结算金额
                    $conSettle[] = ['has_settle', '=', 1];
                    $settlePrize = DevNeedsService::groupBatchSum('group_id', $ids, 'order_amount', $conSettle);

                    foreach ($lists as &$v) {
                        // 需求数
                        $v['needsCount'] = Arrays::value($needsCounts, $v['id'], 0);
                        // 预算金额
                        $v['budgePrize'] = Arrays::value($budgePrize, $v['id'], 0);
                        // 已完工金额
                        $v['finishPrize'] = Arrays::value($finishPrize, $v['id'], 0);
                        // 已结算金额
                        $v['settlePrize'] = Arrays::value($settlePrize, $v['id'], 0);
                    }
                    return $lists;
                }, true);
    }

    /*
     * 2023-02-22:模板导出word
     */

    public function infoGenerate($templateKey) {
        $data = $this->info();
        $templateId = GenerateTemplateService::keyToId($templateKey);
        $res = GenerateTemplateService::getInstance($templateId)->generate($data);
        return $res['file_path'];
    }

    /**
     *
     */
    public function fId() {
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
     * 需求文档名
     */
    public function fNeedTitle() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 需求类型：合同、补充、口头
     */
    public function fNeedType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 需求描述
     */
    public function fNeedDesc() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 需求人姓名
     */
    public function fNeedUser() {
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
