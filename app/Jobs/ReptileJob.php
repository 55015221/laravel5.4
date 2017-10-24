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

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReptileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 入库数据
     */
    protected $id;
    protected $url;

    const REQUEST_URL = 'http://www.phys.ncku.edu.tw/~astrolab/mirrors/apod/';

    /**
     * Create a new job instance.
     * OperationLog constructor.
     * @param $logger
     */
    public function __construct($id, $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    /**
     * Execute the job.
     * @param Image $image
     */
    public function handle(Image $image)
    {
        try {
            /**
             * 补充入库数据
             */
            $base64 = base64_encode(file_get_contents(ReptileJob::REQUEST_URL . $this->url));
            \DB::enableQueryLog();
            $image->where('id', $this->id)->update([
                'base64'     => $base64,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            logger(\DB::getQueryLog());

        } catch (\Exception $e) {
            logger('【ReptileJob】', [$e->getMessage(), $e->getFile(), $e->getLine()]);
        }
    }

}
