<?php
namespace App\Http\Controllers;

use App\Models\MoneyTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    // Nosūta monētas draugam kā neapstiprinātu pārskaitījumu
    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id', 'not_in:' . Auth::id()],
            'amount'       => 'required|integer|min:1|max:2147483647',
            'message'      => 'nullable|string|max:255',
        ]);

        $sender = Auth::user();

        if (!$sender->friends()->where('friend_id', $validated['recipient_id'])->exists()) {
            return back()->with('transfer_error', 'You can only send coins to friends.');
        }

        if ($sender->balance < $validated['amount']) {
            return back()->with('transfer_error', 'Insufficient balance.');
        }

        DB::transaction(function () use ($sender, $validated) {
            DB::table('users')->where('id', $sender->id)->decrement('balance', $validated['amount']);
            MoneyTransfer::create([
                'sender_id'    => $sender->id,
                'recipient_id' => $validated['recipient_id'],
                'amount'       => $validated['amount'],
                'message'      => $validated['message'] ?? null,
                'status'       => 'pending',
            ]);
        });

        return back()->with('transfer_success', 'Transfer sent! Waiting for the recipient to accept.');
    }

    // Apstiprina saņemto pārskaitījumu un pievieno monētas saņēmējam
    public function accept(MoneyTransfer $transfer)
    {
        if ($transfer->recipient_id !== Auth::id() || $transfer->status !== 'pending') {
            return back()->with('transfer_error', 'Invalid transfer.');
        }

        DB::transaction(function () use ($transfer) {
            DB::table('users')->where('id', $transfer->recipient_id)->increment('balance', $transfer->amount);
            $transfer->update(['status' => 'accepted']);
        });

        return back()->with('transfer_success', 'You received ' . number_format($transfer->amount) . ' coins!');
    }

    // Noraida pārskaitījumu un atgriež monētas sūtītājam
    public function decline(MoneyTransfer $transfer)
    {
        if ($transfer->recipient_id !== Auth::id() || $transfer->status !== 'pending') {
            return back()->with('transfer_error', 'Invalid transfer.');
        }

        DB::transaction(function () use ($transfer) {
            DB::table('users')->where('id', $transfer->sender_id)->increment('balance', $transfer->amount);
            $transfer->update(['status' => 'declined']);
        });

        return back()->with('transfer_success', 'Transfer declined. Coins returned to sender.');
    }
}
