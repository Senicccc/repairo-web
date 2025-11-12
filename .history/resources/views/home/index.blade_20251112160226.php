@extends('layouts.app')

@section('content')
<div class="bg-white text-gray-800">

    {{-- HERO SECTION --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <img src="{{ asset('home/home.jpg') }}" 
             alt="Phone Repair" 
             class="absolute inset-0 w-full h-full object-cover brightness-75">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0A4D9E]/70 via-[#0A4D9E]/60 to-[#0A4D9E]/80"></div>

        <div class="relative z-10 max-w-5xl text-center px-6 text-white">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 drop-shadow-lg">
                Welcome to <span class="text-blue-200">Repairo</span>
            </h1>
            <p class="text-lg md:text-xl mb-8 leading-relaxed text-gray-100">
                Fast, professional, and transparent gadget repair services.<br>
                We bring your devices back to life — with quality you can trust.
            </p>
            <a href="{{ route('tracking') }}" 
               class="bg-white text-[#0A4D9E] font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-gray-100 hover:scale-105 transition transform duration-300">
                Track Your Repair
            </a>
        </div>
    </section>

    {{-- ABOUT SECTION --}}
    <section class="min-h-screen flex items-center bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-[#0A4D9E] mb-6">About Repairo</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                At <span class="font-semibold text-[#0A4D9E]">Repairo</span>, we combine innovation and integrity 
                to deliver top-tier gadget repair services. Our certified experts handle smartphones, tablets, 
                and laptops with precision and care — using only genuine parts, transparent pricing, and modern tools.  
            </p>
            <div class="mt-10 flex justify-center">
                <div class="w-32 h-1 bg-[#0A4D9E] rounded-full"></div>
            </div>
        </div>
    </section>

    {{-- SERVICE FLOW --}}
    <section class="min-h-screen flex items-center bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-[#0A4D9E] mb-12">How Our Service Works</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
                @php
                    $steps = [
                        ['icon' => '📱', 'title' => '1. Submit Request', 'desc' => 'Create a repair request online or at our front desk.'],
                        ['icon' => '🔍', 'title' => '2. Quick Diagnosis', 'desc' => 'We identify the issue swiftly with professional tools.'],
                        ['icon' => '💬', 'title' => '3. Transparent Quote', 'desc' => 'You’ll get a clear, upfront price before we start.'],
                        ['icon' => '⚙️', 'title' => '4. Professional Repair', 'desc' => 'Certified experts repair your device with precision.'],
                        ['icon' => '🚀', 'title' => '5. Pickup or Delivery', 'desc' => 'Get your device back fast — good as new!'],
                    ];
                @endphp

                @foreach ($steps as $step)
                    <div class="bg-gray-50 rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-1 p-6 flex flex-col items-center">
                        <div class="text-5xl mb-4">{{ $step['icon'] }}</div>
                        <h3 class="text-lg font-semibold text-[#0A4D9E] mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MEMBER BENEFITS --}}
    <section class="min-h-screen flex items-center bg-gradient-to-br from-[#EAF1FF] to-[#F6FAFF]">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-[#0A4D9E] mb-10">Exclusive Member Benefits</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $benefits = [
                        ['title' => 'Loyalty Points', 'desc' => 'Earn rewards for every service — redeem for discounts later.'],
                        ['title' => 'Priority Service', 'desc' => 'Members get fast-track repairs and priority assistance.'],
                        ['title' => 'Exclusive Discounts', 'desc' => 'Enjoy special member-only promotions all year round.'],
                    ];
                @endphp

                @foreach ($benefits as $benefit)
                    <div class="bg-white rounded-2xl p-10 shadow hover:shadow-2xl hover:scale-105 transition duration-300">
                        <h3 class="text-2xl font-semibold text-[#0A4D9E] mb-4">{{ $benefit['title'] }}</h3>
                        <p class="text-gray-600">{{ $benefit['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- LOCATION SECTION --}}
    <section class="min-h-screen flex flex-col justify-center bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-[#0A4D9E] mb-6">Our Location</h2>
            <p class="text-gray-600 mb-8">
                Visit our main service center in Jakarta, or find us easily through Google Maps below.
            </p>

            <div class="w-full h-[70vh] rounded-2xl overflow-hidden shadow-2xl border border-gray-200">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.564317342181!2d106.82715387512416!3d-6.191263993798715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e95d2f47c3%3A0x57b67e4b2e2a01c9!2sJakarta%20Pusat!5e0!3m2!1sen!2sid!4v1730657433214!5m2!1sen!2sid" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </section>

</div>
@endsection
