<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('post_likes', function (Blueprint $table) {
        $table->id();
        $table->string('username');
        $table->integer('post_id');
        $table->integer('likes')->default(0);
        $table->timestamps();

        $table->unique(['username', 'post_id']);
    });
}
};
