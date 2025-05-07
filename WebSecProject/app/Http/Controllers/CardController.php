<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CreditRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\CreditRequestNotification;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function index()
    {
        // For employees, show credit requests instead of cards
        if (Auth::user()->isEmployee()) {
            return $this->creditRequests();
        }

        // For regular users, show their cards
        $cards = Card::where('user_id', Auth::id())
                    ->where('is_active', true)
                    ->with('billingAddress')
                    ->get();
        
        return view('cards.index', compact('cards'));
    }

    public function store(Request $request)
    {
        // Prevent employees from creating cards
        if (Auth::user()->isEmployee()) {
            return redirect()->back()->with('error', 'Employees are not allowed to have cards.');
        }

        $request->validate([
            'card_number' => 'required|string|unique:cards',
            'expiry_date' => 'required|date',
            'cvv' => 'required|string',
        ]);

        $card = new Card();
        $card->user_id = Auth::id();
        $card->card_number = $request->card_number;
        $card->expiry_date = $request->expiry_date;
        $card->cvv = $request->cvv;
        $card->credit_balance = 0;
        $card->is_active = true;
        $card->save();

        return redirect()->route('cards.index')->with('success', 'Card added successfully.');
    }

    public function deactivate(Card $card)
    {
        if ($card->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action');
        }

        $card->update(['is_active' => false]);
        return back()->with('success', 'Card deactivated successfully');
    }

    public function creditRequests()
    {
        // Only employees can view credit requests
        if (!Auth::user()->isEmployee()) {
            return redirect()->route('cards.index')
                ->with('error', 'You are not authorized to view credit requests.');
        }

        $creditRequests = CreditRequest::with(['user', 'card'])->orderBy('created_at', 'desc')->get();
        return view('cards.credit-requests', compact('creditRequests'));
    }

    public function requestCredit(Request $request, Card $card)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Create credit request
            $creditRequest = CreditRequest::create([
                'user_id' => Auth::id(),
                'card_id' => $card->id,
                'amount' => $request->amount,
                'status' => 'pending'
            ]);

            Log::info('Credit request created successfully', [
                'request_id' => $creditRequest->id,
                'user_id' => Auth::id(),
                'card_id' => $card->id,
                'amount' => $request->amount
            ]);

            // Notify all employees
            $employees = User::where('is_employee', true)->get();
            Log::info('Found employees to notify', ['count' => $employees->count()]);

            foreach ($employees as $employee) {
                try {
                    Log::info('Attempting to send notification to employee', [
                        'employee_id' => $employee->id,
                        'employee_email' => $employee->email,
                        'is_employee' => $employee->is_employee
                    ]);

                    $employee->notify(new CreditRequestNotification($card, $request->amount));
                    
                    Log::info('Notification sent successfully to employee', [
                        'employee_id' => $employee->id,
                        'employee_email' => $employee->email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send notification to employee', [
                        'employee_id' => $employee->id,
                        'employee_email' => $employee->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('cards.index')
                ->with('success', 'Credit request submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Credit request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to submit credit request: ' . $e->getMessage());
        }
    }

    public function approveCredit(CreditRequest $creditRequest)
    {
        // Only employees can approve credit requests
        if (!Auth::user()->isEmployee()) {
            return redirect()->back()
                ->with('error', 'You are not authorized to approve credit requests.');
        }

        if ($creditRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This credit request has already been processed.');
        }

        DB::transaction(function () use ($creditRequest) {
            $card = $creditRequest->card;
            $card->credit_balance += $creditRequest->amount;
            $card->save();
            $card->refresh(); // Refresh the card model to get the updated balance

            $creditRequest->status = 'approved';
            $creditRequest->processed_by = Auth::id();
            $creditRequest->processed_at = now();
            $creditRequest->save();
        });

        return redirect()->route('cards.credit-requests')
            ->with('success', 'Credit request approved successfully.');
    }

    public function rejectCredit(CreditRequest $creditRequest)
    {
        // Only employees can reject credit requests
        if (!Auth::user()->isEmployee()) {
            return redirect()->back()
                ->with('error', 'You are not authorized to reject credit requests.');
        }

        if ($creditRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This credit request has already been processed.');
        }

        $creditRequest->status = 'rejected';
        $creditRequest->processed_by = Auth::id();
        $creditRequest->processed_at = now();
        $creditRequest->save();

        return redirect()->route('cards.credit-requests')
            ->with('success', 'Credit request rejected successfully.');
    }
} 