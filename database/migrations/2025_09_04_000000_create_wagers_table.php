<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // funkcija kas izveido tabulu 'wagers' no Schema kur ir definēta funkcija create kas izveido tabulu
        // tabula satur dažādus laukus kas ir nepieciešami priekš derībām
        // Izveido jaunu tabulu 'wagers' ar sekojošajiem laukiem:
        Schema::create('wagers', function (Blueprint $table) {
            // Automātiski palielināms primārā atslēga
            $table->id();
            // Derības nosaukums (virkne ar maksimālo garumu 255 simboli)
            $table->string('name');
            // Izvēles derības apraksts (var būt NULL)
            $table->text('description')->nullable();
            // Atslēga uz lietotāja tabulu, kas izveidoja derību
            // Izdzēšot lietotāju, tiks izdzēstas arī visas viņa izveidotās derības
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            // Maksimālais spēlētāju skaits derībā
            $table->integer('max_players');
            // Derības statuss - publiska vai privāta (noklusējuma vērtība 'public')
            $table->enum('status', ['public', 'private'])->default('public');

            // Laika zīmogs, kad derība sāksies
            $table->timestamp('starting_time');
            // Laika zīmogs, kad derība beigsies
            $table->timestamp('ending_time');
            // Kopējā derību summa (noklusējuma vērtība 0)
            $table->integer('pot')->default(0);
            // Automātiski aizpildāmie lauki created_at un updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wagers');
    }
};
