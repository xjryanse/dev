<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * api接口文档说明
 */
class DevApiDescService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevApiDesc';

}
