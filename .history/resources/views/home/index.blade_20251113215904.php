@extends('layouts.app')

@section('content')
<div class="bg-white text-gray-800">

    {{ -- HERO SECTION --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/home/home.png') }}" 
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
    <section class="relative py-24 bg-gradient-to-br from-[#1800AD]/5 via-white to-[#1800AD]/10 overflow-hidden">
        {{-- Soft background image --}}
        <div class="absolute inset-0">
            <img src="{{ asset('images/home/about.png') }}" 
                alt="Background About Repairo" 
                class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-white/80 backdrop-blur-sm"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 items-center gap-12">

            {{-- Left side: About text --}}
            <div class="text-center lg:text-left">
                <h2 class="text-4xl lg:text-5xl font-extrabold text-[#1800AD] mb-6">
                    About <span class="text-gray-900">Repairo</span>
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-8">
                    <span class="font-semibold text-[#1800AD]">Repairo</span> is your trusted partner in gadget repair excellence.
                    We blend technology, precision, and transparency to bring your devices back to life.
                    Our certified technicians use only genuine parts and cutting-edge tools, ensuring
                    every repair is performed with care, integrity, and lasting quality.
                </p>
                <a href="{{ route('tracking') }}" 
                class="inline-block bg-[#1800AD] text-white font-semibold px-8 py-3 rounded-full shadow hover:bg-[#15008f] hover:scale-105 transition-transform duration-300">
                    Learn More
                </a>
            </div>

            {{-- Right side: Layered image (no cut-off) --}}
            <div class="flex justify-center relative overflow-visible">
                {{-- Decorative background layer --}}
                <div class="absolute -top-8 -left-8 w-full max-w-md h-full bg-[#1800AD]/10 rounded-3xl rotate-3"></div>
                
                {{-- Main image --}}
                <img src="{{ asset('images/home/about.png') }}" 
                    alt="Device Repair" 
                    class="relative rounded-3xl shadow-2xl w-full max-w-md object-cover z-10">

                {{-- Reflection / soft shadow --}}
                <img src="{{ asset('images/home/about.png') }}" 
                    alt="Device Repair Reflection" 
                    class="absolute right-0 top-8 w-[90%] opacity-40 blur-sm translate-x-6 translate-y-8 rounded-3xl scale-105">
            </div>
        </div>
    </section>

    {{-- SERVICE FLOW --}}
    <section class="py-24 bg-gradient-to-b from-white to-[#F3F5FF] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('{{ asset('images/home/pattern.svg') }}')] bg-cover bg-center"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold text-[#1800AD] mb-4">How Our Service Works</h2>
            <p class="text-gray-600 mb-16 text-lg max-w-2xl mx-auto">
                Experience a smooth and transparent repair journey from start to finish.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
                @php
                    $steps = [
                        ['icon' => '📱', 'title' => 'Submit Request', 'desc' => 'Create a repair request online or at our front desk.'],
                        ['icon' => '🔍', 'title' => 'Quick Diagnosis', 'desc' => 'Our experts identify your device’s issue accurately.'],
                        ['icon' => '💬', 'title' => 'Transparent Quote', 'desc' => 'We’ll send you an upfront quote before starting any repair.'],
                        ['icon' => '⚙️', 'title' => 'Professional Repair', 'desc' => 'Certified technicians handle every fix with precision.'],
                        ['icon' => '🚀', 'title' => 'Pickup or Delivery', 'desc' => 'Get your device back quickly — just like new!'],
                    ];
                @endphp

                @foreach ($steps as $index => $step)
                    <div class="relative group bg-white rounded-3xl p-8 shadow-md hover:shadow-xl transition transform hover:-translate-y-2">
                        <div class="absolute -top-4 -right-4 bg-[#1800AD]/10 text-[#1800AD] font-bold w-10 h-10 flex items-center justify-center rounded-full">
                            {{ $index + 1 }}
                        </div>
                        <div class="text-5xl mb-4 group-hover:scale-110 transition">{{ $step['icon'] }}</div>
                        <h3 class="text-lg font-semibold text-[#1800AD] mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MEMBER BENEFITS --}}
    <section class="py-24 bg-gradient-to-br from-[#EAF0FF] to-[#FFFFFF] relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('{{ asset('images/home/waves.svg') }}')] bg-cover bg-center opacity-5"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold text-[#1800AD] mb-4">Exclusive Member Benefits</h2>
            <p class="text-gray-600 mb-16 text-lg max-w-2xl mx-auto">
                Join Repairo membership and enjoy priority service, rewards, and savings made for loyal customers.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @php
                    $benefits = [
                        ['icon' => '🏆', 'title' => 'Loyalty Points', 'desc' => 'Earn rewards every time you repair and redeem them for discounts.'],
                        ['icon' => '⚡', 'title' => 'Priority Service', 'desc' => 'Members receive faster queueing and dedicated support.'],
                        ['icon' => '💎', 'title' => 'Exclusive Discounts', 'desc' => 'Get special member-only offers throughout the year.'],
                    ];
                @endphp

                @foreach ($benefits as $benefit)
                    <div class="relative bg-white rounded-3xl p-10 shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 hover:scale-105">
                        <div class="text-6xl mb-6 text-[#1800AD]">{{ $benefit['icon'] }}</div>
                        <h3 class="text-2xl font-bold text-[#1800AD] mb-4">{{ $benefit['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $benefit['desc'] }}</p>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-3/4 h-[2px] bg-[#1800AD]/20 rounded-full"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- LOCATION SECTION --}}
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-[#1800AD]/5 to-white"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold text-[#1800AD] mb-4">Our Location</h2>
            <p class="text-gray-600 mb-12 text-lg max-w-2xl mx-auto">
                Find us easily at our central service hub below or open directly in Google Maps.
            </p>

            <div class="relative w-full h-[70vh] rounded-3xl overflow-hidden shadow-2xl border border-gray-200 group">
                <a href="https://www.google.com/maps/place/Jakarta+Pusat/" 
                   target="_blank" 
                   class="absolute inset-0 bg-[#1800AD]/10 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                    <p class="text-white bg-[#1800AD] px-8 py-3 rounded-full font-semibold shadow-lg">
                        View on Google Maps
                    </p>
                </a>

                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.564317342181!2d106.82715387512416!3d-6.191263993798715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e95d2f47c3%3A0x57b67e4b2e2a01c9!2sJakarta%20Pusat!5e0!3m2!1sen!2sid!4v1730657433214!5m2!1sen!2sid" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </section>
</div>
@endsection
