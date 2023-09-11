<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 
 */
class DevQuotePageService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevQuotePage';


}
