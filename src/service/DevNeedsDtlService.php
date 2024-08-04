<?php

namespace xjryanse\dev\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\dev\service\DevBugsService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Number;

/**
 * 
 */
class DevNeedsDtlService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;
    
    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\dev\\model\\DevNeedsDtl';

    use \xjryanse\dev\service\needsDtl\FieldTraits;
    use \xjryanse\dev\service\needsDtl\CustomerTraits;
    use \xjryanse\dev\service\needsDtl\TriggerTraits;
    use \xjryanse\dev\service\needsDtl\DoTraits;
    use \xjryanse\dev\service\needsDtl\NoticeTraits;
    
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    // 提取待处理列表
                    $con    = [];
                    $con[]  = ['needs_dtl_id','in',$ids];
                    $bugs   = DevBugsService::where($con)->select();
                    $bugsArr = $bugs ? $bugs->toArray() : [];
            
                    foreach($lists as &$v){
                        // 是否有价格
                        $v['hasPrize'] = intval(Arrays::value($v, 'prize')) ? 1:0;
                        // -1无价格；0待确认；1同意；2拒绝；
                        $v['prizeAcceptWithHas'] = intval(Arrays::value($v, 'prize')) ? $v['prize_accept'] : -1;
                        // 已处理功能点
                        $conDeal = [['needs_dtl_id','=',$v['id']],['has_deal','=',1]];
                        $v['dealBugsCount'] = count(Arrays2d::listFilter($bugsArr, $conDeal));
                        // 待处理功能点
                        $conToDo = [['needs_dtl_id','=',$v['id']],['has_deal','=',0]];
                        $v['todoBugsCount'] = count(Arrays2d::listFilter($bugsArr, $conToDo));
                        // 进度
                        $v['dealRate']      = Number::Rate($v['dealBugsCount'] , ($v['todoBugsCount'] + $v['dealBugsCount'])) ;
                        // 0:待处理；1:处理中；2:已处理
                        $v['dealState']     = (!$v['todoBugsCount'] && $v['is_accept']) 
                                ? 2 
                                : ($v['dealBugsCount'] ? 1 : 0);
                    }
                    
                    return $lists;
                }, true);
    }

    
    

}
