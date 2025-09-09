<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WagerController extends Controller
{
    public function index()
    {
        $wagers  = Wager::with(['creator', 'choices'])->orderBy('created_at', 'desc')->get();
        $friends = Auth::user() ? Auth::user()->friends()->get() : collect();
        return view('wagers.wagers', compact('wagers', 'friends'));
    }

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'max_players' => 'required|integer|min:2|max:100',
                // entry_fee removed
                'visibility'  => 'required|in:public,private',
                'ending_time' => 'required|date|after:now',
                'choices'     => 'nullable|array|min:1',
                'choices.*'   => 'nullable|string|max:255',
            ]);

            $wager              = new Wager();
            $wager->name        = $request->input('name');
            $wager->creator_id  = Auth::id();
            $wager->description = $request->input('description');
            $wager->max_players = $request->input('max_players');
            // entry_fee removed
            $wager->status        = $request->input('visibility') === 'public' ? 'public' : 'private';
            $wager->players       = json_encode([]);
            $wager->game_history  = json_encode([]);
            $wager->starting_time = now();
            $wager->ending_time   = date('Y-m-d H:i:s', strtotime($request->input('ending_time')));
            $wager->pot           = 0;
            $wager->save();

            // Store choices if provided
            $choices = collect($request->input('choices', []))
                ->filter(fn($c) => ! is_null($c) && trim($c) !== '')
                ->values();

            if ($choices->isNotEmpty()) {
                $wager->choices()->createMany(
                    $choices->map(fn($label) => ['label' => $label, 'total_bet' => 0])->all()
                );
            }

            return redirect()->route('wagers')->with('success', 'Wager created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating wager: ' . $e->getMessage());
        }
    }

    // public function show($id)
    // {
    //     $wager = Wager::with('creator')->findOrFail($id);
    //     return view('wager.show', compact('wager'));
    // }

    public function search(Request $request)
    {
        $query = (string) $request->query('query', '');

        if ($query === '') {
            return response()->json([]);
        }

        $wagers = Wager::with('choices')
            ->where('name', 'like', '%' . $query . '%')
            ->limit(20)
            ->get();

        return response()->json($wagers->map(function ($wager) {
            return [
                'id'          => $wager->id,
                'name'        => $wager->name,
                'description' => $wager->description,
                'status'      => $wager->status,
                'max_players' => $wager->max_players,
                'ends_human'  => optional($wager->ending_time)->diffForHumans(),
                'choices'     => $wager->choices->map(fn($c) => [
                    'id'    => $c->id,
                    'label' => $c->label,
                ]),
            ];
        }));
    }

    public function update(Request $request, Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_players' => 'required|integer|min:2|max:100',
            'visibility'  => 'required|in:public,private',
            'ending_time' => 'required|date|after:now',
            'choices'     => 'nullable|array|min:1',
            'choices.*'   => 'nullable|string|max:255',
        ]);

        $wager->name        = $request->input('name');
        $wager->description = $request->input('description');
        $wager->max_players = $request->input('max_players');
        $wager->status      = $request->input('visibility') === 'public' ? 'public' : 'private';
        $wager->ending_time = date('Y-m-d H:i:s', strtotime($request->input('ending_time')));
        $wager->save();

        // Replace choices: simple approach - delete and recreate
        $choices = collect($request->input('choices', []))
            ->filter(fn($c) => ! is_null($c) && trim($c) !== '')
            ->values();

        $wager->choices()->delete();
        if ($choices->isNotEmpty()) {
            $wager->choices()->createMany(
                $choices->map(fn($label) => ['label' => $label, 'total_bet' => 0])->all()
            );
        }

        return redirect()->route('wagers')->with('success', 'Wager updated successfully!');
    }

    public function destroy(Wager $wager)
    {
        if ($wager->creator_id !== Auth::id()) {
            abort(403);
        }

        $wager->delete();
        return redirect()->route('wagers')->with('success', 'Wager deleted.');
    }
}
