<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable('registration_codes', function (Blueprint $table) {
    $table->increments('id');
    $table->string('username');
    $table->string('code', 191)->unique();
    $table->unsignedInteger('used_by')->nullable()->index();
    $table->dateTime('used_at')->nullable();
    $table->timestamps();
});
