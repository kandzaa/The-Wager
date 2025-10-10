<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can add and search friends', function () {
    // Create test users
    $user1 = User::factory()->create();
    $user2 = User::factory()->create(['name' => 'Test Friend']);

    // User1 adds User2 as a friend
    $this->actingAs($user1);

    // Add friend using the web endpoint
    $response = $this->post(route('friends.add'), [
        'friend_id' => $user2->id,
        '_token'    => csrf_token(),
    ]);

    $response->assertStatus(200);

    // Check if the friendship exists in the database
    $this->assertDatabaseHas('user_friends', [
        'user_id'   => $user1->id,
        'friend_id' => $user2->id,
    ]);

    // Test searching for friends
    $response = $this->get(route('friends.search', ['query' => 'Test']));

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['id', 'name', 'email', 'joined', 'initial'],
        ]);

    // Since the search excludes existing friends, we should not find the friend in search results
    $response->assertJsonMissing([
        'id'   => $user2->id,
        'name' => 'Test Friend',
    ]);

    // Test viewing friends list
    $response = $this->actingAs($user1)->get(route('friends'));
    $response->assertStatus(200);
});

test('unauthenticated user cannot add friends', function () {
    $user = User::factory()->create();

    // First, ensure we're not authenticated
    $this->assertGuest();

    // Make the request without authentication
    $response = $this->post(route('friends.add'), [
        'friend_id' => $user->id,
    ]);

    // Should redirect to login for web requests
    $response->assertRedirect(route('login'));
});

test('user cannot add themselves as friend', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('friends.add'), [
        'friend_id' => $user->id,
        '_token'    => csrf_token(),
    ]);

    $response->assertStatus(400)
        ->assertJson(['message' => 'You cannot add yourself as a friend.']);
});

test('user can search for other users', function () {
    $user1 = User::factory()->create(['name' => 'Test User']);
    $user2 = User::factory()->create(['name' => 'Searchable User']);

    $this->actingAs($user1);

    $response = $this->get(route('friends.search', ['query' => 'Searchable']));

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['id', 'name', 'email', 'joined', 'initial'],
        ]);

    // Verify the search result contains the expected user
    $found = collect($response->json())->contains('name', 'Searchable User');
    $this->assertTrue($found, 'Search should find the user');
});
