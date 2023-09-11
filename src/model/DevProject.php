<?php
namespace xjryanse\dev\model;

/**
 * 开发项目
 */
class DevProject extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        //性能不佳
        [
            'field'     =>'project_manager',
            'uni_name'  =>'user',
            'uni_field' =>'id',
            'in_list'   => false,
            'in_statics'=> false,
            'in_exist'  => true,
            'del_check' => false,
            // 20230610:管理员存在
            'exist_field' =>'isProjectManagerExist'
        ],
    ];

    public static $picFields = ['project_logo'];

    public function getProjectLogoAttr($value) {
        return self::getImgVal($value);
    }
    public function setProjectLogoAttr($value) {
        return self::setImgVal($value);
    }
}