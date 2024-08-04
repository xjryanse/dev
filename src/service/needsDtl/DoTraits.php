<?php

namespace xjryanse\dev\service\needsDtl;

use xjryanse\logic\Arrays;
/**
 * 
 */
trait DoTraits{
    /**
     * 费用确认
     * prize_accept ：同意传1； 不同意传2：
     */
    public static function doPrizeConfirm($paramRaw){
        $param = Arrays::value($paramRaw, 'table_data') ? : $paramRaw;
        
        $id             = Arrays::value($param, 'id');
        $prizeAccept    = Arrays::value($param, 'prize_accept');
        // 是否接受报价
        $data['prize_accept']       = $prizeAccept;
        $data['prize_accept_user']  = $prizeAccept ? session(SESSION_USER_ID) : '';

        $res = self::getInstance($id)->doUpdateRam($data);

        return $res;
    }

}
