<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateExistingMessagesToPolymorphicTextMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('messages')->eachById(function ($rawMessage) {
            $textMessageId = DB::table('text_messages')->insertGetId([
                'content' => $rawMessage->content,
                'created_at' => $rawMessage->created_at,
                'updated_at' => $rawMessage->updated_at,
            ]);

            DB::table('messages')->where('id', $rawMessage->id)->update([
                'content_id' => $textMessageId,
                'content_type' => (new \App\Models\Messages\TextMessage())->getMorphClass(),
            ]);
        });
    }
}
