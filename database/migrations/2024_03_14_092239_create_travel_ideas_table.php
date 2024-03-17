<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelIdeasTable extends Migration
{
    public function up()
    {
        Schema::create('travel_ideas', function (Blueprint $table) {
            $table->id('idea_id'); // 使用 id 方法自动创建一个自增ID，并重命名为 idea_id
            $table->string('title');
            $table->string('destination');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('tags'); // 标签可以存为文本，使用逗号分隔；也可以考虑创建一个单独的表来实现多对多关系
            $table->string('user_name'); // 假设每个旅游想法都由一个用户名标识；在实际应用中可能会使用用户ID外键
            $table->timestamps(); // 创建 created_at 和 updated_at 时间戳
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_ideas');
    }
}
