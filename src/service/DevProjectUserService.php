<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 
 */
class DevProjectUserService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevProjectUser';

    /**
     * 获取用户的角色属性
     * @param type $projectId
     * @param type $userId
     * @return type
     */
    public static function getUserRoles($projectId, $userId) {
        $con[] = ['project_id', '=', $projectId];
        $con[] = ['user_id', 'in', $userId];
        $con[] = ['status', '=', 1];
        return self::mainModel()->where($con)->column('distinct role');
    }

    /**
     * 用户是否有访问某项目的权限
     */
    public static function hasAuth($projectId, $userId) {
        // 用户是否是项目成员
        $con[] = ['project_id', '=', $projectId];
        $con[] = ['user_id', 'in', $userId];
        $con[] = ['status', '=', 1];
        $count = self::count($con);
        // 判断2，用户是否是需求人
        $isNeedUser = DevNeedsService::isNeedUser($projectId, $userId);
        return $count || $isNeedUser;
    }

    /**
     * 获取项目的角色用户
     */
    public static function projectRoleUsers($projectId, $role) {
        $con[] = ['project_id', '=', $projectId];
        $con[] = ['role', 'in', $role];
        $con[] = ['status', '=', 1];
        return self::mainModel()->where($con)->column('distinct user_id');
    }

    /**
     * 20220922:用户是否客户:客户可以验收
     */
    public static function isCustomer($projectId, $userId = '') {
        if (!$userId) {
            $userId = session(SESSION_USER_ID);
        }
        $con[] = ['user_id', '=', $userId];
        $con[] = ['project_id', '=', $projectId];
        $con[] = ['role', '=', 'customer'];
        $con[] = ['status', '=', 1];
        return self::staticConCount($con) ? true : false;
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
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fProjectId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fUserId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fRole() {
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
