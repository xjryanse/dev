<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 
 */
class DevProjectUserService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevProjectUser';

    use \xjryanse\dev\service\projectUser\FieldTraits;
    use \xjryanse\dev\service\projectUser\CustomerTraits;
    
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


}
