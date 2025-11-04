@extends('layouts.app')

@section('content')
<div class="bg-white text-gray-800">

    {{-- HERO SECTION --}}
    <section class="bg-gradient-to-r from-blue-600 to-blue-400 text-white py-20">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center gap-10">
            <div class="flex-1">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Welcome to <span class="text-yellow-300">Repairo</span></h1>
                <p class="text-lg mb-6">
                    Your trusted partner for fast, reliable, and affordable gadget repair services. 
                    Experience a smooth and professional repair journey with our certified technicians.
                </p>
                <a href="{{ route('tracking') }}" 
                   class="bg-white text-blue-700 font-semibold px-6 py-3 rounded-lg shadow hover:bg-gray-100 transition">
                    Track Your Repair
                </a>
            </div>
            <div class="flex-1">
                <img src="https://images.unsplash.com/photo-1605296867304-46d5465a13f1?auto=format&fit=crop&w=800&q=80"
                     alt="Tech Repair" class="rounded-2xl shadow-lg w-full">
            </div>
        </div>
    </section>

    {{-- ABOUT SECTION --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-blue-700 mb-4">About Repairo</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Repairo is a modern repair service platform dedicated to simplifying your gadget maintenance. 
                We specialize in smartphones, laptops, and tablets, offering quick diagnostics, genuine spare parts, 
                and transparent pricing—all handled by professionals who care about quality and trust.
            </p>
        </div>
    </section>

    {{-- SERVICE FLOW --}}
    <section class="py-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-blue-700 mb-12">How Our Service Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                @php
                    $steps = [
                        ['icon' => '🛠️', 'title' => '1. Submit Request', 'desc' => 'Create a repair request online or at our service desk.'],
                        ['icon' => '📋', 'title' => '2. Quick Diagnosis', 'desc' => 'Our technician inspects and identifies the issue.'],
                        ['icon' => '💬', 'title' => '3. Transparent Quote', 'desc' => 'You receive a clear breakdown of costs before repair.'],
                        ['icon' => '⚙️', 'title' => '4. Professional Repair', 'desc' => 'Certified technicians perform the repair using genuine parts.'],
                        ['icon' => '✅', 'title' => '5. Pick Up & Enjoy', 'desc' => 'Collect your device or get it delivered in top condition.'],
                    ];
                @endphp

                @foreach ($steps as $step)
                    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="text-4xl mb-4">{{ $step['icon'] }}</div>
                        <h3 class="text-xl font-semibold text-blue-600 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MEMBER BENEFITS --}}
    <section class="py-20 bg-blue-50">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-blue-700 mb-10">Exclusive Member Benefits</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $benefits = [
                        ['title' => 'Loyalty Points', 'desc' => 'Earn points every time you repair with us and redeem them for discounts.'],
                        ['title' => 'Priority Service', 'desc' => 'Members get faster turnaround and repair queue priority.'],
                        ['title' => 'Special Discounts', 'desc' => 'Enjoy exclusive promotions and seasonal offers reserved for members.'],
                    ];
                @endphp

                @foreach ($benefits as $benefit)
                    <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
                        <h3 class="text-xl font-semibold text-blue-600 mb-3">{{ $benefit['title'] }}</h3>
                        <p class="text-gray-600">{{ $benefit['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- LOCATION SECTION --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-blue-700 mb-6">Our Location</h2>
            <p class="text-gray-600 mb-8">Visit our main service center or reach us through Google Maps below.</p>

            <div class="w-full h-96 rounded-2xl overflow-hidden shadow-lg">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.9619119449434!2d112.6167925747756!3d-7.133912071276262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e4df4dddb6c1%3A0xe0dfd50464015e8f!2sIndonesia!5e0!3m2!1sen!2sid!4v1698741523456!5m2!1sen!2sid" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </section>

</div>
@endsection
