<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    public function up(): void
    {
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->id('friend_request_id');
            $table->integer('requester_id');
            $table->integer('recipient_id');
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamps();

            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['requester_id', 'recipient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friend_requests');
    }
};
