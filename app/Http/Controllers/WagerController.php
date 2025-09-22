<?php
namespace App\Http\Controllers;

use App\Models\Wager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WagerController extends Controller
{
    public function index()
    {
        $wagers  = Wager::with(['creator', 'choices'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $friends = Auth::user() ? Auth::user()->friends()->get() : collect();
        return view('wagers.lobby', compact('wagers', 'friends'));
    }

    public function stats(Wager $wager)
    {
        $wager->load('choices');
        return response()->json([
            'labels' => $wager->choices->pluck('label')->values(),
            'data'   => $wager->choices->pluck('total_bet')->map(fn($v) => (int) $v)->values(),
            'pot'    => (int) $wager->pot,
        ]);
    }

    public function bet(Request $request, Wager $wager)
    {
        $validated = $request->validate([
            'choice_id' => 'required|integer',
            'amount'    => 'required|integer|min:1',
        ]);

        $players  = collect($wager->players ?? []);
        $isJoined = $players->contains(function ($p) {
            return is_array($p) && ($p['user_id'] ?? null) === Auth::id();
        });
        if (! $isJoined) {
            return redirect()->back()->with('error', 'Please join this wager before placing a bet.');
        }

        $choice = $wager->choices()->where('id', $validated['choice_id'])->first();
        if (! $choice) {
            return redirect()->back()->with('error', 'Invalid choice selected for this wager.');
        }

        try {
            DB::transaction(function () use ($wager, $choice, $validated) {
                $amount = (int) $validated['amount'];

                $user = Auth::user();
                $user->refresh();
                if ((int) ($user->balance ?? 0) < $amount) {
                    throw new \RuntimeException('Insufficient balance to place this bet.');
                }

                $user->balance = (int) $user->balance - $amount;
                $user->save();

                $wager->pot = (int) $wager->pot + $amount;

                $players = collect($wager->players ?? []);
                $players->push([
                    'user_id' => $user->id,
                    'name'    => $user->name,
                    'choice'  => $choice->label,
                    'amount'  => $amount,
                    'time'    => now()->toDateTimeString(),
                ]);
                $wager->players = $players->toArray();
                $wager->save();

                $choice->total_bet = (int) $choice->total_bet + $amount;
                $choice->save();
            });

            return redirect()->route('wager.show', ['id' => $wager->id])->with('success', 'Bet placed successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to place bet: ' . $e->getMessage());
        }
    }

    public function join(Wager $wager)
    {
        $players = collect($wager->players ?? []);
        $already = $players->contains(function ($p) {
            return is_array($p) && ($p['user_id'] ?? null) === Auth::id();
        });

        if (! $already) {
            $players->push([
                'user_id' => Auth::id(),
                'name'    => optional(Auth::user())->name,
                'choice'  => null,
                'amount'  => 0,
                'joined'  => true,
                'time'    => now()->toDateTimeString(),
            ]);
            $wager->players = $players->toArray();
            $wager->save();
        }

        return redirect()->route('wager.show', ['id' => $wager->id])->with('success', 'You joined this wager.');
    }

    /**
     * Izveido jaunu derību, pamatojoties uz lietotāja ievadītajiem datiem.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            // Validē ievadītos datus
            $validated = $request->validate([
                'name'        => 'required|string|max:255',          // Obligāts derības nosaukums (max 255 simboli)
                'description' => 'nullable|string|max:1000',         // Neobligāts apraksts (max 1000 simboli)
                'max_players' => 'required|integer|min:2|max:100',   // Spēlētāju skaits (starp 2 un 100)
                'visibility'  => 'required|in:public,private',       // Vai derība ir publiska vai privāta
                'ending_time' => 'required|date|after:now',          // Beigu laikam jābūt nākotnē
                'choices'     => 'nullable|array|min:1',             // Izvēlnes (masīvs ar vismaz 1 elementu)
                'choices.*'   => 'nullable|string|max:255',          // Katra izvēlne (max 255 simboli)
            ]);

            // Izveido jaunu Wager modeli un aizpilda ar datiem no pieprasījuma
            $wager = new Wager();
            $wager->name = $request->input('name');
            $wager->creator_id = Auth::id();  // Iestata pašreizējo lietotāju kā derības veidotāju
            $wager->description = $request->input('description');
            $wager->max_players = $request->input('max_players');
            $wager->status = $request->input('visibility') === 'public' ? 'public' : 'private';
            $wager->players = json_encode([]);  // Inicializē tukšu spēlētāju masīvu
            $wager->game_history = json_encode([]);  // Inicializē tukšu vēstures masīvu
            $wager->starting_time = now();  // Iestata pašreizējo laiku kā sākuma laiku
            $wager->ending_time = date('Y-m-d H:i:s', strtotime($request->input('ending_time')));
            $wager->pot = 0;  // Sākotnējā derību summa ir 0
            $wager->save();  // Saglabā derību datubāzē

            // Apstrādā iespējas (izvēlnes), ja tādas ir norādītas
            $choices = collect($request->input('choices', []))
                ->filter(fn($c) => ! is_null($c) && trim($c) !== '')  // Noņem tukšus vai null vērtības
                ->values();  // Pārindeksē masīvu

            // Ja ir norādītas derību iespējas, tās saglabā ar saistību izmantošanu
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

    public function showWager($id)
    {
        $wager   = Wager::with(['creator', 'choices'])->findOrFail($id);
        $friends = Auth::user() ? Auth::user()->friends()->get() : collect();
        return view('wagers.wager_detail', compact('wager', 'friends'));
    }

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
