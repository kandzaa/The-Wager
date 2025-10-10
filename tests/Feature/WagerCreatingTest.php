<?php

use App\Models\User;
use App\Models\Wager;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can create a new wager with choices', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $endTime = now()->addDay()->setTime(21, 0);

    // Act
    $response = $this->post('/wagers', [
        'name'        => 'Fishing Challenge',
        'description' => 'Who will catch a fish over 2kg first?',
        'ending_time' => $endTime->format('Y-m-d\TH:i'),
        'status'      => 'public',
        'max_players' => 2,
        'choices'     => ['Choice 1', 'Choice 2'],
    ]);

    // Assert
    $this->assertDatabaseHas('wagers', [
        'name'        => 'Fishing Challenge',
        'description' => 'Who will catch a fish over 2kg first?',
        'max_players' => 2,
    ]);

    $wager = Wager::where('name', 'Fishing Challenge')->first();

    $this->assertNotNull($wager, 'Wager was not created');

    expect($wager->ending_time->diffInSeconds($endTime))->toBeLessThan(60);

    $this->assertCount(2, $wager->choices, 'Expected 2 choices to be saved');

    $choiceLabels = $wager->choices->pluck('label')->toArray();
    $this->assertContains('Choice 1', $choiceLabels, 'Expected to find "Choice 1" in choices');
    $this->assertContains('Choice 2', $choiceLabels, 'Expected to find "Choice 2" in choices');

    $response->assertRedirect('/wagers');
});

test('validates required fields when creating a wager', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $response = $this->post('/wagers', []);

    // Assert
    $response->assertSessionHasErrors([
        'name',
        'ending_time',
        'choices',
    ]);
});

test('validates end time is in the future', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $pastTime = now()->subDay();

    // Act
    $response = $this->post('/wagers', [
        'name'        => 'Invalid Time Wager',
        'description' => 'This should fail',
        'ending_time' => $pastTime->format('Y-m-d\TH:i'),
        'status'      => 'public',
        'max_players' => 2,
        'choices'     => ['Choice 1', 'Choice 2'],
    ]);

    // Assert
    $response->assertSessionHasErrors('ending_time');
    $this->assertStringContainsString(
        'must be a date after',
        session('errors')->first('ending_time')
    );
});
