<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\logic\Arrays;
use Exception;

/**
 * 
 */
class DevBillService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevBill';

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $needsCounts = DevNeedsService::groupBatchCount('bill_id', $ids);
                    foreach ($lists as &$v) {
                        // 需求数
                        $v['needsCount'] = Arrays::value($needsCounts, $v['id'], 0);
                    }
                    return $lists;
                });
    }

    /**
     * 2023-02-28:前序保存账单
     * @param type $data
     * @param type $uuid
     * @return type
     * @throws Exception
     */
    public static function extraPreSave(&$data, $uuid) {
        if (isset($data['needIds'])) {
            self::checkTransaction();
            $con[] = ['id', 'in', $data['needIds']];
            $count = DevNeedsService::where($con)->whereNotNull('bill_id')->count();
            if ($count) {
                throw new Exception('存在已生成账单的明细，请核查');
            }
            //更新明细的账单号
            DevNeedsService::where($con)->update(['bill_id' => $uuid]);
        }
        return $data;
    }

    /**
     * 2023-02-28：删除订单
     */
    public function extraPreDelete() {
        // 查询账单
        $con[] = ['bill_id', '=', $this->uuid];
        DevNeedsService::where($con)->update(['bill_id' => null]);
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
