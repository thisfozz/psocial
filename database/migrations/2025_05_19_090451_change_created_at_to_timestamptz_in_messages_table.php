<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE messages ALTER COLUMN created_at TYPE timestamptz USING created_at::timestamptz");
        DB::statement("ALTER TABLE messages ALTER COLUMN updated_at TYPE timestamptz USING updated_at::timestamptz");
    }

    public function down()
    {
        DB::statement("ALTER TABLE messages ALTER COLUMN created_at TYPE timestamp USING created_at::timestamp");
        DB::statement("ALTER TABLE messages ALTER COLUMN updated_at TYPE timestamp USING updated_at::timestamp");
    }
};
