@extends('Components.Layout.layout')

@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#2c1a0f] text-center">Memberships</h1>
            <div class="container mx-auto px-4 py-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($memberships as $membership)
                        <div
                            class="bg-white rounded-xl shadow-lg p-6 border
                            {{ $activeMembership && $activeMembership->id === $membership->id ? 'border-blue-500 ring-2 ring-blue-300' : 'border-gray-200' }}">

                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-2xl font-bold text-[#2c1a0f]">{{ $membership->name }}</h2>

                                @if ($activeMembership && $activeMembership->id === $membership->id)
                                    <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-3 py-1 rounded-full">
                                        Membership Aktif
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600 mb-1">
                                Syarat Belanja: <span
                                    class="font-medium">Rp{{ number_format($membership->min_annual_spending) }}</span>
                            </p>

                            <p class="text-sm text-gray-600 mb-1">
                                Diskon: <span class="font-medium">{{ $membership->discount_percent }}%</span>
                            </p>

                            <p class="text-sm text-gray-600 mb-1">
                                Berlaku Untuk:
                                <span
                                    class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">
                                    {{ ucfirst($membership->applies_to) }}
                                </span>
                            </p>

                            @if ($membership->benefit_note)
                                <p class="text-sm text-gray-500 italic mt-2">“{{ $membership->benefit_note }}”</p>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </header>
@endsection
