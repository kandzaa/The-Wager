<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WagerController extends Controller
{
    public function index()
    {
        $wagers = Wager::with('choices')->latest()->get();
        return view('wagers.lobby', compact('wagers'));
    }

    public function create()
    {
        return view('wagers.create_wager');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'max_players' => 'required|integer|min:2|max:100',
            'ending_time' => 'required|date|after:' . now()->addHour()->format('Y-m-d H:i'),
            'choices'     => 'required|array|min:2|max:10',
            'choices.*'   => 'required|string|max:255',
        ]);

        $wagerData = [
            'name'          => $validated['name'],
            'description'   => $request->description ?? null,
            'creator_id'    => Auth::id(),
            'max_players'   => $validated['max_players'],
            'starting_time' => now(),
            'ending_time'   => $validated['ending_time'],
        ];

        $wager = Wager::create($wagerData);

        foreach ($validated['choices'] as $index => $choice) {
            $wager->choices()->create([
                'label'      => trim($choice),
                'sort_order' => $index,
                'total_bet'  => 0,
            ]);
        }

        return redirect()->route('wagers', $wager);
    }

    public function show(Wager $wager)
    {
        $wager->load('choices');
        return view('wagers.wager-show', compact('wager'));
    }

    public function join(Wager $wager)
    {
        if ($wager->isFull()) {
            return back()->with('error', 'Wager is full');
        }

        $players   = $wager->players ?? [];
        $players[] = [
            'user_id'   => Auth::id(),
            'name'      => Auth::user()->name,
            'joined_at' => now(),
        ];

        $wager->update(['players' => $players]);
        return back();
    }

    public function destroy(Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            return back()->with('error', 'Not authorized');
        }

        $wager->choices()->delete();
        $wager->delete();
        return redirect()->route('wagers');
    }
}
