<?php
/**
 * 多线程爬虫实例
 * 运行：php artisan command:MultiThreadRequest
 * @author Bily
 * @date 2017年10月02日16:17:47
 */

namespace App\Console\Commands;

use App\Jobs\ReptileJob;
use App\Models\Image;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use function GuzzleHttp\Psr7\str;
use Illuminate\Console\Command;
use Storage;

class MultiThreadRequest extends Command
{


    public $counter = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:MultiThreadRequest';

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
    public function handle()
    {
        $client = new Client();
        $indexPage = MultiThreadRequest::REQUEST_URL . 'archivepix.html';

        $response = $client->get($indexPage);
        $content = $response->getBody()->getContents();

        preg_match_all('/<a href=\"(ap\d+\.html)\">/', $content, $matches);


        //<a href="ap960324.html">Comet Hyakutake's Closest Approach</a><br>

        $data = $matches[1];
        $total = count($data);

        $requests = function ($total) use ($client, $data) {
            foreach ($data as $uri) {
                $url = MultiThreadRequest::REQUEST_URL . $uri;
                yield function () use ($client, $url) {
                    return $client->getAsync($url);
                };
            }
        };

        $pool = new Pool($client, $requests(100), [
            'concurrency' => 10, //并发请求
            'fulfilled'   => function ($response, $index) {
                try {
                    $content = $response->getBody()->getContents();
                    preg_match('/<IMG SRC=\"(image\/[\s\S]+?)\"[\s\S]+?alt=\"[\s\S]+?\"[\s\S]+?<b>([\s\S]+?)<\/b>/', $content, $matches);
                    preg_match('/<b>說明: <\/b>([\s\S]+?)<p>/', $content, $description);
                    if (!empty($matches)) {
                        $url = MultiThreadRequest::REQUEST_URL . $matches[1];
                        $title = $matches[2];
                        $description = isset($description[1]) ? $description[1] : '';
                        $filename = basename($url);
                        Storage::put($filename, file_get_contents($url));
                        $id = Image::create([
                            'url'         => $url,
                            'title'       => strval($title),
                            'description' => strval($description),
                            'path'        => $filename,
                        ]);
                        /**
                         * 放入列队
                         * 目前是同步执行 可以设置 每天 0点之后处理，减少对业务请求的影响
                         */
//                        dispatch(new ReptileJob($id));
                        $this->info("请求第 $index 个请求");
                    }
                } catch (\Exception $e) {

                }
            },
            'rejected'    => function ($reason, $index) {
                $this->error("rejected");
                $this->error("rejected reason: " . $reason);
            },
        ]);

        // 开始发送请求
        $promise = $pool->promise();
        $promise->wait();
    }
}
