<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $customers = Customer::latest()->get();
            return response()->json([
                'customers' => $customers
            ]);
        }

        $trashedCustomers = Customer::onlyTrashed()->latest()->get();
        return view('customers.index', compact('trashedCustomers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20'
        ]);

        $customer = Customer::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully.',
                'customer' => $customer
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        if (request()->ajax()) {
            return response()->json($customer);
        }

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20'
        ]);

        $customer->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully.',
                'customer' => $customer
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer moved to trash successfully.'
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer moved to trash successfully.');
    }

    public function restore($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer restored successfully.',
                'customer' => $customer
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer restored successfully.');
    }

    public function forceDelete($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->forceDelete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer permanently deleted.'
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer permanently deleted.');
    }
} 