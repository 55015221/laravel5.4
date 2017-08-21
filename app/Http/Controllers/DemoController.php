<?php


namespace App\Http\Controllers;

use App\Http\Services\BaseService;
use App\Http\Services\ESignService;
use App\Libs\tech\tech;

class DemoController extends Controller
{


    public function index(ESignService $ESignService)
    {

        set_time_limit(0);

        $data = [
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '卡卡990',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '基本定义',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '在任何情形',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '按照该等合',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '至最终到期',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '结息日。',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '款人需按本协',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '最后一期利息',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '付应付款项',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '须优先偿还其所欠',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '分之五计收逾期',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '让交易通知担保',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '且出借人和债',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '承担连带保证责任',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '司的担保范围为',
                ],
            ], [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '的借款提供物的担保',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '保证人对物的',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '担保公司支付担',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '记录及还款流水',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '销地授权理想宝',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '证明及向借款人',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '出借人与借款人均确认由',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '期限届满前',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '付的代偿资金后',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '足额支付应付款',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '证责任后有权向借款',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '追偿以下款项',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '公司代偿的全部款',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '述款项所产生的其',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '单期还款',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '付顺序依次',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '借款人转让本协议',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '下列情形之一者',
                ],
            ],

            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '人银行还款账户中的资',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '按每日1‰的标准向',
                ],
            ],

            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '代偿款项支付至出借',
                ],
            ],

            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '多期还款',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '得担保人同意的情况',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '列任一违约情',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '有转移财产',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '除继续计算利',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '协议项下的提供借',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '正确或具有误导',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '提前30日通知出借',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '之五每日向理想',
                ],
            ],
            [
                'accountId' => '7ABB83BB0FE8488FA2119343726E49AA',
                'seal'      => 'Public/Uploads/eSign/dfb8e66488822c01f5d4c1d950942ebc.png',
                'signPos'   => [
                    'key' => '协议项下的提供借',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '王大业',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '人收取催收服',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '另有规定的除',
                ],
            ],
            [
                'sealId'  => 0,
                'signPos' => [
                    'key' => '担保人（盖章）',
                ],
            ],
        ];


        $data = array_slice($data, 0, 1);
//

        foreach ($data as &$val) {
            if(isset($val['seal'])){
                $val['sealData'] = base64_encode(file_get_contents('http://www.id68.cn/' . $val['seal']));
            }
        }


        $url = 'http://192.168.1.163:10006/contracts/201707/31/jiedaihetong_8031.pdf';

        $contractFile = '/tmp/bb.pdf';

        file_put_contents($contractFile, file_get_contents($url));

        $ESignService->signType = 'Key';

        $time_start = $this->microtime_float();

        $signServiceId = $ESignService->stamp($contractFile, $data);

        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;

        logger("共用时间：" . $time);
        header('Content-type: application/pdf');
        echo file_get_contents($contractFile);
        exit;


//        $tech = tech::getInstance();
    }

    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}