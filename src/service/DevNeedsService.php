<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\user\service\UserService;
use xjryanse\wechat\service\WechatWePubFansService;
use xjryanse\order\service\OrderService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Debug;
use Exception;

/**
 * 
 */
class DevNeedsService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevNeeds';

    public static function extraDataAuthCond() {
        $sessionUserInfo = session(SESSION_USER_INFO);
        if ($sessionUserInfo['admin_type'] == 'super') {
            return [];
        }

        //过滤用户可查看的项目权限
        $userId = session(SESSION_USER_ID);
        $cond[] = ['user_id', '=', $userId];
        $projectIds = DevProjectUserService::mainModel()->where($cond)->column('project_id');
        $authCond[] = ['project_id', 'in', $projectIds];
        //TODO如果不是项目成员，只能查看自己提的需求
        return $authCond;
    }

    public static function extraPreUpdate(&$data, $uuid) {
        $info = self::getInstance($uuid)->get();
        if (!Arrays::value($info, 'dev_finish') && Arrays::value($data, 'dev_finish')) {
            $data['dev_finish_time'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * 前序保存
     * @param type $data
     * @param type $uuid
     * @return type
     * @throws Exception
     */
    public static function extraPreSave(&$data, $uuid) {
        if (!Arrays::value($data, 'need_title')) {
            throw new Exception('需求标题必须');
        }
        return $data;
    }

    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    //剩余未收
                    foreach ($lists as &$v) {
                        $v['hasDevDesc'] = $v['dev_desc'] ? 1 : 0;
                        // 有账单
                        $v['hasBill'] = $v['bill_id'] ? 1 : 0;
                        // 有群组
                        $v['hasGroup'] = $v['group_id'] ? 1 : 0;
                    }
                    return $lists;
                });
    }

    /**
     * 20220810：增加判断
     * @throws Exception
     */
    public function ramPreDelete() {
        $info = $this->get();
        Debug::debug('info', $info);
        if ($info['has_settle']) {
            throw new Exception('已结算不可删');
        }
        if ($info['dev_finish']) {
            throw new Exception('已完工不可删');
        }
        if ($info['has_confirm']) {
            throw new Exception('已接单不可删');
        }
    }

    /**
     * 20220821
     * @param type $con
     * @param type $order
     * @param type $perPage
     * @param type $having
     * @param type $field
     * @param type $withSum
     * @return type
     */
    public static function paginate($con = [], $order = '', $perPage = 10, $having = '', $field = "*", $withSum = false) {
        $res = self::paginateX($con, $order, $perPage, $having, $field, $withSum);
        /** TODO 标准化？？ **** */
        foreach ($con as $k => $v) {
            if ($v[0] == 'needStatus') {
                unset($con[$k]);
            }
        }
        $conAll = array_merge($con, self::commCondition());
        if (method_exists(__CLASS__, 'extraDataAuthCond')) {
            $conAll = array_merge($conAll, self::extraDataAuthCond());
        }
        $statics = self::mainModel()->where($conAll)->field('count(1) as number,needStatus')->group('needStatus')->select();
        $staticsArr = $statics ? $statics->toArray() : [];
        $res['statics'] = Arrays2d::toKeyValue($staticsArr, 'needStatus', 'number');

        return $res;
    }

    /**
     * 需求设定订单
     * @param type $orderId
     * @throws Exception
     */
    public function setOrderInfo($orderId) {
        $orderInfo = OrderService::getInstance($orderId)->get();
        if (!$orderInfo) {
            throw new Exception('订单"' . $orderId . '"不存在,请联系开发');
        }
        $info = $this->get(0);
        if ($info['order_id'] && $info['order_id'] != $orderId) {
            throw new Exception('需求已绑定订单' . $info['order_id'] . ',请联系开发');
        }
        if ($orderInfo['goods_table_id'] != $this->uuid) {
            throw new Exception('订单的goods_table_id"' . $orderInfo['goods_table_id'] . '"与当前需求id"' . $this->uuid . '"不匹配，请联系开发');
        }
        $upData['order_id'] = $orderId;
        // 订单绑定
        $this->update($upData);
    }

    /**
     * 需求验收
     */
    public function verify($userId) {
        $info = $this->get(0);
        if (!$info) {
            throw new Exception('需求"' . $this->uuid . '"不存在,请联系开发');
        }
        if ($info['need_verify_user'] || $info['need_verify_wepub_openid']) {
            throw new Exception('该需求已验收' . $this->uuid);
        }
        $data['need_verify_wepub_openid'] = $this->openid;
        $data['need_verify_user'] = $userId;
        $data['need_verify_time'] = date('Y-m-d H:i:s');

        $res = self::mainModel()->where('id', $this->uuid)->update($data);
        if (!$res) {
            throw new Exception('验收失败，请联系开发');
        }
        return $this->get(0);
    }

    public function info() {
        $info = $this->get(0);
        if ($info) {
            $info['needVerifyUserInfo'] = UserService::getInstance($info['need_verify_user'])->get();
            $info['needVerifyWepubOpenIdInfo'] = WechatWePubFansService::findByOpenid($info['need_verify_wepub_openid']);
            $info['needDealUserInfo'] = UserService::getInstance($info['need_deal_user'])->get();
            //是否直接在线支付
            $info['directPay'] = DevProjectService::getInstance($info['project_id'])->fDirectPay();
        }
        return $info;
    }

    /**
     * 查询用户是否是需求人
     * @param type $projectId
     * @param type $userId
     */
    public static function isNeedUser($projectId, $userId) {
        $con[] = ['project_id', '=', $projectId];
        $con[] = ['need_user_id', '=', $userId];
        return self::count($con);
    }

    /**
     * 2023-02-28: 批量登记前序数据处理
     */
    public static function batchPreData($ids, $forMethod = '') {
        $con[] = ['id', 'in', $ids];
        $data['needIds'] = $ids;
        $data['project_id'] = self::mainModel()->where($con)->value('project_id');
        $data['bill_name'] = date('Y-m-d') . '结算账单';
        $data['bill_prize'] = self::mainModel()->where($con)->sum('order_amount');

        return $data;
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
