<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable('registration_codes', function (Blueprint $table) {
    $table->id();
    $table->string('username')->index();
    $table->string('code', 191)->unique();
    $table->unsignedBigInteger('used_by')->nullable()->index();
    $table->dateTime('used_at')->nullable();
    $table->timestamps();

    $table->foreign('used_by')->references('id')->on('users')->onDelete('set null');
});
