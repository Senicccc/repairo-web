@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">

    <div class="bg-white rounded-2xl shadow-xl p-10 border border-gray-100">

        {{-- HEADER --}}
        <h2 class="text-3xl font-bold text-center text-[#1800AD] tracking-tight mb-8">
            Edit Profile
        </h2>

        {{-- FORM --}}
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf

            {{-- NAME --}}
            <div>
                <label class="block mb-1 text-gray-700 font-medium">Full Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#1800AD] focus:ring-[#1800AD] focus:ring-2 transition"
                    placeholder="Enter your full name"
                >
            </div>

            {{-- PHONE --}}
            <div>
                <label class="block mb-1 text-gray-700 font-medium">Phone Number</label>
                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#1800AD] focus:ring-[#1800AD] focus:ring-2 transition"
                    placeholder="08xxxxxxxxxx"
                >
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block mb-1 text-gray-700 font-medium">Email Address</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#1800AD] focus:ring-[#1800AD] focus:ring-2 transition"
                    placeholder="yourmail@example.com"
                >
            </div>

            {{-- BUTTONS --}}
            <div class="flex items-center justify-between pt-4">

                {{-- SAVE BUTTON --}}
                <button
                    type="submit"
                    class="bg-[#1800AD] hover:bg-[#13008a] text-white font-semibold px-6 py-3 rounded-xl shadow-md transition transform hover:scale-[1.02] active:scale-95 inline-flex items-center gap-2"
                >
                    Save Changes
                </button>

                {{-- CANCEL BUTTON --}}
                <a
                    href="{{ route('profile.show') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-xl transition transform hover:scale-[1.02] active:scale-95 inline-flex items-center gap-2"
                >
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
