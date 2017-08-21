<?php

/**
 * 操作日志表
 * @author Bily
 * @date 2017-8-7 11:40:20
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_records', function (Blueprint $table) {
            $table->increments('id')->comment('主键ID');
            $table->unsignedInteger('uid')->default(0)->comment('操作人ID');
            $table->string('url', 255)->default('')->comment('访问地址');
            $table->string('method', 20)->default('')->comment('请求方法');
            $table->unsignedInteger('status')->default(0)->comment('响应状态码');
            $table->unsignedInteger('code')->default(0)->comment('业务状态码');
            $table->string('version', 10)->default('')->comment('接口版本');
            $table->string('ip', 20)->default('')->comment('请求IP');
            $table->string('browser', 255)->default('')->comment('客户端浏览器');
            $table->text('request')->comment('请求数据');
            $table->text('response')->comment('响应数据');
            $table->timestamps();
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_records');
    }
}
