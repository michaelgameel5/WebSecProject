<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\User;
use App\Notifications\VoucherNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        $customers = User::where('is_employee', false)->get();
        return view('vouchers.index', compact('vouchers', 'customers'));
    }

    public function create()
    {
        $customers = User::where('is_employee', false)->get();
        $voucherCode = Str::random(8); // Generate a unique voucher code
        return view('vouchers.create', compact('customers', 'voucherCode'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|unique:vouchers',
                'discount_percentage' => 'required|numeric|min:1|max:100',
                'expires_at' => 'required|date|after:today',
                'customer_ids' => 'required|array',
                'customer_ids.*' => 'exists:users,id'
            ]);

            DB::beginTransaction();

            // Create voucher
            $voucher = Voucher::create([
                'code' => $request->code,
                'discount_percentage' => $request->discount_percentage,
                'expires_at' => $request->expires_at,
                'is_used' => false,
                'created_by' => auth()->id()
            ]);

            // Send notifications synchronously
            $customers = User::whereIn('id', $request->customer_ids)->get();
            foreach ($customers as $customer) {
                try {
                    $customer->notify(new VoucherNotification($voucher));
                    Log::info('Notification sent to customer: ' . $customer->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send notification to customer ' . $customer->email . ': ' . $e->getMessage());
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Voucher created and sent to selected customers successfully.'
                ]);
            }

            return redirect()->route('vouchers.index')
                ->with('success', 'Voucher created and sent to selected customers successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Voucher creation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create voucher: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to create voucher: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Voucher $voucher)
    {
        // If user is not an employee, check if they are the intended recipient
        if (!auth()->user()->isEmployee()) {
            // Check if the user has received this voucher
            $hasReceivedVoucher = auth()->user()->notifications()
                ->where('type', 'App\Notifications\VoucherNotification')
                ->where('data->voucher_id', $voucher->id)
                ->exists();

            if (!$hasReceivedVoucher) {
                return redirect()->route('vouchers.index')
                    ->with('error', 'You do not have access to this voucher.');
            }
        }

        return view('vouchers.show', compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        $customers = User::where('is_employee', false)->get();
        return view('vouchers.edit', compact('voucher', 'customers'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        try {
            $request->validate([
                'code' => 'required|unique:vouchers,code,' . $voucher->id,
                'discount_percentage' => 'required|numeric|min:1|max:100',
                'expires_at' => 'required|date|after:today',
                'customer_ids' => 'required|array',
                'customer_ids.*' => 'exists:users,id'
            ]);

            DB::beginTransaction();

            // Update voucher
            $voucher->update([
                'code' => $request->code,
                'discount_percentage' => $request->discount_percentage,
                'expires_at' => $request->expires_at,
            ]);

            // Send notifications synchronously
            $customers = User::whereIn('id', $request->customer_ids)->get();
            foreach ($customers as $customer) {
                try {
                    $customer->notify(new VoucherNotification($voucher));
                    Log::info('Notification sent to customer: ' . $customer->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send notification to customer ' . $customer->email . ': ' . $e->getMessage());
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Voucher updated and sent to selected customers successfully.'
                ]);
            }

            return redirect()->route('vouchers.index')
                ->with('success', 'Voucher updated and sent to selected customers successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Voucher update failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update voucher: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update voucher: ' . $e->getMessage());
        }
    }

    public function send(Request $request, Voucher $voucher)
    {
        try {
            $request->validate([
                'customer_ids' => 'required|array',
                'customer_ids.*' => 'exists:users,id'
            ]);

            DB::beginTransaction();

            // Send notifications synchronously
            $customers = User::whereIn('id', $request->customer_ids)->get();
            foreach ($customers as $customer) {
                try {
                    $customer->notify(new VoucherNotification($voucher));
                    Log::info('Notification sent to customer: ' . $customer->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send notification to customer ' . $customer->email . ': ' . $e->getMessage());
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Voucher sent to selected customers successfully.'
                ]);
            }

            return redirect()->route('vouchers.index')
                ->with('success', 'Voucher sent to selected customers successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Voucher send failed: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send voucher: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to send voucher: ' . $e->getMessage());
        }
    }

    public function destroy(Voucher $voucher)
    {
        try {
            $voucher->delete();
            return redirect()->route('vouchers.index')
                ->with('success', 'Voucher deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Voucher deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete voucher: ' . $e->getMessage());
        }
    }
} 