<?php
/**
 * 多线程爬虫实例
 * 运行：php artisan test:multi-thread-request
 * @author Bily
 * @date 2017年10月02日16:17:47
 */

namespace App\Console\Commands;

use App\Jobs\ReptileJob;
use App\Models\Image;
use Illuminate\Console\Command;

class ReptileRequest extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ReptileRequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    const REQUEST_URL = 'http://www.phys.ncku.edu.tw/~astrolab/mirrors/apod/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Image $image)
    {
        try {

            $imageObj = $image->whereNull('base64')->get(['id', 'url'])->all();
            foreach ($imageObj as $obj) {
                /**
                 * 放入列队
                 * 目前是同步执行 可以设置 每天 0点之后处理，减少对业务请求的影响
                 */
                dispatch(new ReptileJob($obj->id, $obj->url));
            }

        } catch (\Exception $e) {
            logger('【ReptileJob】', [$e->getMessage(), $e->getFile(), $e->getLine()]);
        }
    }
}
