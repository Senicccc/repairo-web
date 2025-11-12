@extends('layouts.app')

@section('content')
<div class="bg-white text-gray-800">

    {{-- HERO SECTION --}}
    <section class="bg-[#0A4D9E] text-white min-h-screen flex items-center">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center gap-10">
            <div class="flex-1">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                    Welcome to <span class="text-blue-200">Repairo</span>
                </h1>
                <p class="text-lg mb-6 leading-relaxed">
                    Your trusted partner for reliable and professional gadget repair services.  
                    We combine speed, quality, and transparency to give you a stress-free experience.
                </p>
                <a href="{{ route('tracking') }}" 
                   class="bg-white text-[#0A4D9E] font-semibold px-6 py-3 rounded-lg shadow hover:bg-gray-100 transition">
                    Track Your Repair
                </a>
            </div>
            <div class="flex-1">
                <img src="{{ asset('home/home.jpg') }}"
                     alt="Phone Repair" class="rounded-2xl shadow-xl w-full object-cover h-[70vh]">
            </div>
        </div>
    </section>

    {{-- ABOUT SECTION --}}
    <section class="bg-gray-50 min-h-screen flex items-center">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-[#0A4D9E] mb-6">About Repairo</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Repairo is a modern gadget repair platform dedicated to delivering professional service and
                reliable results. Our team specializes in smartphones, tablets, and laptops — providing fast
                diagnostics, genuine spare parts, and upfront pricing. We believe in earning your trust with
                every repair we complete.
            </p>
        </div>
    </section>

    {{-- SERVICE FLOW --}}
    <section class="min-h-screen flex items-center">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-[#0A4D9E] mb-12">How Our Service Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                @php
                    $steps = [
                        ['icon' => '📱', 'title' => '1. Submit Request', 'desc' => 'Create a repair request online or at our front desk.'],
                        ['icon' => '🔍', 'title' => '2. Quick Diagnosis', 'desc' => 'Our technician inspects and identifies the issue.'],
                        ['icon' => '💬', 'title' => '3. Transparent Quote', 'desc' => 'We provide a clear and honest cost estimate.'],
                        ['icon' => '⚙️', 'title' => '4. Professional Repair', 'desc' => 'Certified experts repair your device with care.'],
                        ['icon' => '🚀', 'title' => '5. Pickup or Delivery', 'desc' => 'Get your gadget back in perfect condition.'],
                    ];
                @endphp

                @foreach ($steps as $step)
                    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="text-4xl mb-4">{{ $step['icon'] }}</div>
                        <h3 class="text-xl font-semibold text-[#0A4D9E] mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MEMBER BENEFITS --}}
    <section class="bg-blue-50 min-h-screen flex items-center">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-[#0A4D9E] mb-10">Exclusive Member Benefits</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $benefits = [
                        ['title' => 'Loyalty Points', 'desc' => 'Earn points with every repair and redeem them for discounts.'],
                        ['title' => 'Priority Service', 'desc' => 'Members enjoy faster repair queues and exclusive access.'],
                        ['title' => 'Special Discounts', 'desc' => 'Enjoy member-only promotions and seasonal offers.'],
                    ];
                @endphp

                @foreach ($benefits as $benefit)
                    <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
                        <h3 class="text-xl font-semibold text-[#0A4D9E] mb-3">{{ $benefit['title'] }}</h3>
                        <p class="text-gray-600">{{ $benefit['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- LOCATION SECTION --}}
    <section class="bg-gray-50 min-h-screen flex flex-col justify-center">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-[#0A4D9E] mb-6">Our Location</h2>
            <p class="text-gray-600 mb-8">
                Visit our main service center in Jakarta, or find us easily through Google Maps below.
            </p>

            <div class="w-full h-[70vh] rounded-2xl overflow-hidden shadow-lg">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.564317342181!2d106.82715387512416!3d-6.191263993798715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e95d2f47c3%3A0x57b67e4b2e2a01c9!2sJakarta%20Pusat!5e0!3m2!1sen!2sid!4v1730657433214!5m2!1sen!2sid" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </section>

</div>
@endsection
