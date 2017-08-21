<?php
/**
 * 系统操作日志 列队
 * 记录系统的操作日志
 * php artisan queue:work beanstalkd  --queue=operationLog
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Jobs;

use App\Models\AccessRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AccessRecordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 入库数据
     */
    protected $records;

    /**
     * Create a new job instance.
     * OperationLog constructor.
     * @param $logger
     */
    public function __construct($records)
    {
        $this->records = $records;
    }

    /**
     * Execute the job.
     * @param AccessRecord $accessRecord
     */
    public function handle(AccessRecord $accessRecord)
    {
        try {
            /**
             * 补充入库数据
             */
            $ret = $accessRecord->create($this->records);

        } catch (\Exception $e) {
            logger('【AccessRecordJob】', [$e->getMessage(), $e->getFile(), $e->getLine()]);
        }
    }

}
