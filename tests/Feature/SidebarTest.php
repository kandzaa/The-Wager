<?php

use App\Models\User;

// kods kas pārbauda 3 mājaslapas maršrutus
//   / maršrutā lietotājam vajadzētu redzēt "The Wager" tekstu
it('should be that the user sees The Wager', function () {
    //Aiziet uz / maršrutu
    $page = visit('/');

    $page->assertSee('The Wager');
});

// maršrutā /dashboard lietotājam vajadzētu redzēt "Hello" tekstu
it('should be that the user sees Hello', function () {
    // izveido lietotāju un pārbauda, ka lietotājs ir autentificēts
    $user = User::factory()->create();
    $this->actingAs($user);
    //Aiziet uz /dashboard maršrutu
    $page = visit('/dashboard');

    $page->assertSee('Hello');
});

// maršrutā /wagers lietotājam vajadzētu redzēt "Wagers" tekstu
it('should be that the user sees Wagers', function () {
    // izveido lietotāju un pārbauda, ka lietotājs ir autentificēts
    $user = User::factory()->create();
    $this->actingAs($user);
    //Aiziet uz /wagers maršrutu
    $page = visit('/wagers');

    $page->assertSee('Wagers');
});
