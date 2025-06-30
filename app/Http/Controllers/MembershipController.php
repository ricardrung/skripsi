<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;


class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin melihat manajemen membership
            $memberships = Membership::orderBy('min_annual_spending')->get();
            return view('pages.admin.memberships', [ 'memberships' => $memberships, 'mode' => 'index']);
        } else {
            // Customer melihat daftar tingkatan membership
            $memberships = Membership::orderBy('min_annual_spending')->get();
            $activeMembership = $user->currentMembership?->membership;

            $yearlySpending = $user->currentMembership?->yearly_spending ?? 0;

            return view('pages.membership.membership', compact('memberships', 'activeMembership', 'yearlySpending'));
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.admin.memberships', ['mode' => 'create','membership' => new Membership()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'min_annual_spending' => 'required|integer|min:0',
            'discount_percent' => 'required|integer|min:0|max:100',
            'applies_to' => 'required|in:all,Body Treatment,Face Treatment,Reflexology,Hair Treatment,Treatment Packages,A La Carte',
            'benefit_note' => 'nullable|string|max:255',
        ]);

        Membership::create($request->all());

        return redirect()->route('memberships.index')->with('success', 'Membership berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membership $membership)
    {
        //
        return view('pages.admin.memberships', ['mode' => 'edit','membership' => $membership]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membership $membership)
    {
        //
        $request->validate([
            'name' => 'required',
            'min_annual_spending' => 'required|integer|min:0',
            'discount_percent' => 'required|integer|min:0|max:100',
            'applies_to' => 'required|in:all,Body Treatment,Face Treatment,Reflexology,Hair Treatment,Treatment Packages,A La Carte',
            'benefit_note' => 'nullable|string|max:255',
        ]);

        $membership->update($request->all());

        return redirect()->route('memberships.index')->with('success', 'Membership berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership)
    {
        //
        $membership->delete();
        return redirect()->route('memberships.index')->with('success', 'Membership berhasil dihapus.');
    }
}
