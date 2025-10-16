<?php

use App\Models\User;
use App\Models\Wager;
use App\Models\FriendRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can invite friend to wager and friend can accept', function () {
    // 1. Create two users who are friends
    $creator = User::factory()->create();
    $friend = User::factory()->create();
    
    // Create friend relationship
    FriendRequest::create([
        'sender_id' => $creator->id,
        'receiver_id' => $friend->id,
        'status' => 'accepted',
    ]);
    
    // 2. Create a wager as the creator
    $this->actingAs($creator);
    
    $wager = Wager::factory()->create([
        'creator_id' => $creator->id,
        'status' => 'public',
        'ending_time' => now()->addDay(),
    ]);
    
    // 3. Invite friend to the wager
    $response = $this->post("/wagers/{$wager->id}/invite", [
        'user_id' => $friend->id
    ]);
    
    $response->assertStatus(200);
    
    // 4. Check that the invitation exists in the database
    $this->assertDatabaseHas('wager_invitations', [
        'wager_id' => $wager->id,
        'user_id' => $friend->id,
        'status' => 'pending'
    ]);
    
    // 5. Switch to friend's account and check notifications
    $this->actingAs($friend);
    
    // Check dashboard for notification
    $dashboardResponse = $this->get('/dashboard');
    $dashboardResponse->assertSee('You have been invited to a wager');
    
    // 6. Accept the invitation
    $acceptResponse = $this->post("/wagers/{$wager->id}/accept-invitation");
    $acceptResponse->assertRedirect();
    
    // 7. Verify the invitation was accepted
    $this->assertDatabaseHas('wager_invitations', [
        'wager_id' => $wager->id,
        'user_id' => $friend->id,
        'status' => 'accepted'
    ]);
    
    // 8. Verify friend is now a participant in the wager
    $this->assertDatabaseHas('wager_participants', [
        'wager_id' => $wager->id,
        'user_id' => $friend->id
    ]);
    
    // 9. Check that the wager appears in the friend's dashboard
    $dashboardAfterAccept = $this->get('/dashboard');
    $dashboardAfterAccept->assertSee($wager->name);
});

test('only friends can be invited to private wagers', function () {
    $creator = User::factory()->create();
    $stranger = User::factory()->create();
    
    $this->actingAs($creator);
    
    $wager = Wager::factory()->create([
        'creator_id' => $creator->id,
        'status' => 'private',
        'ending_time' => now()->addDay(),
    ]);
    
    // Try to invite someone who is not a friend
    $response = $this->post("/wagers/{$wager->id}/invite", [
        'user_id' => $stranger->id
    ]);
    
    $response->assertStatus(403);
    
    $this->assertDatabaseMissing('wager_invitations', [
        'wager_id' => $wager->id,
        'user_id' => $stranger->id
    ]);
});

test('cannot invite to non-existent wager', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->post("/wagers/999/invite", [
        'user_id' => $user->id
    ]);
    
    $response->assertStatus(404);
});
