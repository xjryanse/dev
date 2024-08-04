<?php

namespace xjryanse\dev\service\needsDtl;

use xjryanse\wechat\service\WechatWePubTemplateMsgService;
use xjryanse\dev\service\DevProjectUserService;
use xjryanse\dev\service\DevProjectService;
use xjryanse\dev\service\DevNeedsService;
use xjryanse\user\service\UserService;
use xjryanse\logic\Arrays;
use Exception;
/**
 * 通知逻辑
 */
trait NoticeTraits{
    /**
     * 加价通知客户
     */
    public function wePubTplPrizeAddNoticeCustomer($data){
        $templateKey = Arrays::value($data, 'templateKey') ? : 'devNeedsDtlPrizeAddNoticeCustomer';
        
        $info = $this->get();
        // 提取客户
        $needId     = Arrays::value($info, 'need_id');
        $projectId  = DevNeedsService::getInstance($needId)->fProjectId();
        if(!$projectId){
            throw new Exception('需求'.$needId.'没有项目id');
        }
        // 项目负责人
        $projectManagerId = DevProjectService::getInstance($projectId)->fProjectManager();
        if(!Arrays::value($info, 'dtl_title')){
            throw new Exception('该调整点无标题无法推送'.$this->uuid);
        }
        // {"thing15":{"value":"need_title","color":"#1759BE"},"thing12":{"value":"cate_name","color":"#1759BE"},"time13":{"value":"create_time","color":"#1759BE"},"phrase10":{"value":"realname","color":"#1759BE"}}
        $info['addReason']  = '增加内容加价，等待客户确认';
        $info['addUser']    = UserService::getInstance($projectManagerId)->fRealname();
        // 发送必有
        $info['from_table'] = self::getTable();
        $info['from_table_id'] = $this->uuid;
        
        $userIds    = DevProjectUserService::projectRoleUsers($projectId, 'customer');
        return WechatWePubTemplateMsgService::doSendByDataAndUser($templateKey, $info, $userIds);
    }
}
