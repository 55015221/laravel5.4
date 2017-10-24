<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id')->comment('主键ID');
            $table->string('title', 255)->default('')->comment('标题');
            $table->string('description', 255)->default('')->comment('描述');
            $table->string('path', 255)->default('')->comment('文件路径');
            $table->string('url', 255)->default('')->comment('地址url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
