<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WagerController extends Controller
{
    public function index()
    {
        $wagers = Wager::with('creator')->orderBy('created_at', 'desc')->get();
        return view('wagers', compact('wagers'));
    }

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'max_players' => 'required|integer|min:2|max:100',
                'entry_fee'   => 'required|integer|min:0|max:10000',
                'visibility'  => 'required|in:public,private',
                'ending_time' => 'required|date|after:now',
            ]);

            $wager                = new Wager();
            $wager->name          = $request->input('name');
            $wager->creator_id    = Auth::id();
            $wager->description   = $request->input('description');
            $wager->max_players   = $request->input('max_players');
            $wager->entry_fee     = $request->input('entry_fee');
            $wager->status        = $request->input('visibility') === 'public' ? 'public' : 'private';
            $wager->players       = json_encode([]);
            $wager->game_history  = json_encode([]);
            $wager->starting_time = now();
            $wager->ending_time   = date('Y-m-d H:i:s', strtotime($request->input('ending_time')));
            $wager->pot           = 0;
            $wager->save();

            return redirect()->route('wagers')->with('success', 'Wager created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating wager: ' . $e->getMessage());
        }
    }
}
