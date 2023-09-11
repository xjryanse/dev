<?php
namespace xjryanse\dev\logic;

use xjryanse\logic\Cachex;
use xjryanse\system\service\SystemCompanyService;
use xjryanse\logic\Url;
use xjryanse\curl\Query;
use xjryanse\logic\Arrays;
/**
 * 20230523：项目的执行逻辑
 */
class ProjectLogic
{
    use \xjryanse\traits\InstTrait;

    public function info(){
        $cacheKey = __METHOD__.$this->uuid;
        return Cachex::funcGet($cacheKey, function(){
            $baseHost = 'https://axsl.xiesemi.cn/QsP6cXAp/';
            $url        = $baseHost.'/dev/project/info';
            $param['id'] = $this->uuid;
            $finalUrl   = Url::addParam($url, $param);
            $res        = Query::get($finalUrl);
            return $res['code'] == 0 ? $res['data'] : [];
        });
    }
    /**
     * 20230531：端口是否可操作；
     * 1，购买端口，过期了仍然可以操作
     * 2，租用端口，过期了则不能操作
     */
    public function canOperate(){
        $info = $this->info();
        // 租用端口已过期，则不可操作
        if (Arrays::value($info, 'project_cate') == 2 && Arrays::value($info, 'finishState') == 3 ){
            return false;
        }
        return true;
    }
    
}
