<?php
namespace xjryanse\dev\model;

/**
 * 开发项目
 */
class DevProject extends Base
{
    public function getProjectLogoAttr($value) {
        return self::getImgVal($value);
    }
    public function setProjectLogoAttr($value) {
        return self::setImgVal($value);
    }
}