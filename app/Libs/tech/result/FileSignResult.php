<?php
/**
 * User: Administrator
 * Date: 2017/1/11
 */

namespace tech\result;


class FileSignResult extends AbstractResult
{
    public function parseData()
    {
        $resp = $this->rawResponse;
        $result = array();
        if (isset($resp['signServiceId'])) {
            $result['signServiceId'] = $resp['signServiceId'];
        }
        if (isset($resp['stream'])) {
            $result['stream'] = $resp['stream'];
        }
        return array_merge($this->errInfo, $result);
    }

}