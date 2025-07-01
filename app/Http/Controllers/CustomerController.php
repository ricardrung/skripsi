<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    //
    
public function index(Request $request)
{
    $query = User::withCount('bookingsSelesaiAsCustomer')
        ->with('currentMembership.membership')
        ->where('role', 'customer');
        

    // Filter berdasarkan pencarian
    if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('membership_id')) {
        $query->whereHas('currentMembership', function ($q) use ($request) {
            $q->where('membership_id', $request->membership_id);
        });
    }

    $customers = $query->latest()->paginate(10);
    $memberships = \App\Models\Membership::all();


    return view('Pages.Admin.manajemenpelanggan', compact('customers', 'memberships'));
}

public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'phone'    => 'nullable|string|max:20',
        'password' => 'required|string|min:6',
        'birthdate' => 'nullable|date',
        'gender' => 'required|in:male,female',
    ]);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'phone'    => $request->phone,
        'password' => Hash::make($request->password),
        'role'     => 'customer',
        'gender'    => $request->gender,
        'birthdate' => $request->birthdate,
    ]);

    return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
}

public function edit($id)
{
    $customer = User::where('role', 'customer')->findOrFail($id);
    return view('Pages.Admin.manajemenpelanggan', compact('customer'));
}

public function update(Request $request, $id)
{
    $customer = User::where('role', 'customer')->findOrFail($id);

    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $customer->id,
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|in:male,female',
        'password' => 'nullable|string|min:6',
        'birthdate' => 'nullable|date',
    ]);

    $customer->name = $request->name;
    $customer->email = $request->email;
    $customer->phone = $request->phone;
    $customer->gender = $request->gender;
    $customer->birthdate = $request->birthdate;

    if ($request->filled('password')) {
        $customer->password = Hash::make($request->password);
    }

    $customer->save();

    return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
}
public function destroy($id)
{
    $customer = User::where('role', 'customer')->findOrFail($id);
    $customer->delete();

    return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil dihapus.');
}

}
