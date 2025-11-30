<?php include 'header.php'; ?>

<header class="relative h-screen flex items-center justify-center overflow-hidden clip-path-wave mb-12">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1519451241324-20b4ea2c4220?q=80&w=2670&auto=format&fit=crop"
            alt="Sundarban Resort Evening" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/20 to-black/70"></div>
    </div>

    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-16">
        <span
            class="inline-block py-1 px-3 rounded-full bg-orange-500/20 border border-orange-400 text-orange-100 text-sm font-bold tracking-widest uppercase mb-6 backdrop-blur-sm animate-pulse">
            Most Popular Choice
        </span>
        <h1 class="text-5xl md:text-7xl text-white font-bold mb-6 hero-text leading-tight drop-shadow-lg">
            Jungle Nights & <br><span
                class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-300">River Tales</span>
        </h1>
        <p class="text-xl text-gray-100 mb-10 max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
            Experience the silence of the forest at night. Stay in an eco-resort, enjoy tribal folk dance, and explore
            the deep core zones at sunrise.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#itinerary"
                class="bg-white text-green-900 px-8 py-4 rounded-full font-bold hover:bg-gray-100 transition transform hover:-translate-y-1 shadow-xl flex items-center justify-center gap-2">
                View 2-Day Plan <span class="material-symbols-outlined">arrow_downward</span>
            </a>
            <a href="#pricing"
                class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-bold hover:bg-white/10 transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                Get Quote <span class="material-symbols-outlined">currency_rupee</span>
            </a>
        </div>
    </div>

    <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 animate-bounce text-white/80">
        <span class="material-symbols-outlined text-4xl">keyboard_double_arrow_down</span>
    </div>
</header>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-32 relative z-20 mb-20">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div
            class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-orange-500 flex flex-col items-center text-center transform hover:-translate-y-2 transition duration-300">
            <div class="bg-orange-100 p-3 rounded-full mb-4">
                <span class="material-symbols-outlined text-orange-600 text-3xl">nights_stay</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">1 Night Stay</h3>
            <p class="text-sm text-gray-500 mt-1">AC Eco-Resort / Cottage</p>
        </div>

        <div
            class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-green-600 flex flex-col items-center text-center transform hover:-translate-y-2 transition duration-300">
            <div class="bg-green-100 p-3 rounded-full mb-4">
                <span class="material-symbols-outlined text-green-600 text-3xl">sailing</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">2 Days Safari</h3>
            <p class="text-sm text-gray-500 mt-1">Deep Core Area Access</p>
        </div>

        <div
            class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-blue-500 flex flex-col items-center text-center transform hover:-translate-y-2 transition duration-300">
            <div class="bg-blue-100 p-3 rounded-full mb-4">
                <span class="material-symbols-outlined text-blue-600 text-3xl">restaurant</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">6 Meals Included</h3>
            <p class="text-sm text-gray-500 mt-1">2 Lunch, 1 Dinner, 2 Brkfast</p>
        </div>

        <div
            class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-purple-500 flex flex-col items-center text-center transform hover:-translate-y-2 transition duration-300">
            <div class="bg-purple-100 p-3 rounded-full mb-4">
                <span class="material-symbols-outlined text-purple-600 text-3xl">groups</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">Cultural Show</h3>
            <p class="text-sm text-gray-500 mt-1">Tribal Folk Dance (Jhumur)</p>
        </div>
    </div>
</section>

<section id="experience" class="py-16 leaf-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-green-900 mb-4">The Complete Jungle Experience</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">More time means deeper exploration. Witness the forest
                change colors from dawn to dusk, and experience the thrill of a night in the wild.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
            <div class="order-2 md:order-1 relative group">
                <div
                    class="absolute -inset-2 bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200">
                </div>
                <img src="https://images.unsplash.com/photo-1543051932-6ef9fecfbc80?q=80&w=1000&auto=format&fit=crop"
                    alt="Bonfire Night"
                    class="relative rounded-2xl shadow-2xl w-full object-cover h-96 transform transition duration-500 hover:scale-[1.01]">
                <div
                    class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded text-xs backdrop-blur-md">
                    Cultural Evening
                </div>
            </div>
            <div class="order-1 md:order-2 space-y-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="bg-orange-100 text-orange-600 p-2 rounded-lg material-symbols-outlined">campfire</span>
                    Evenings at the Resort
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    After a thrilling day of safari, unwind at our eco-resort. The evenings come alive with the rhythmic
                    beats of <strong>Jhumur (Tribal Dance)</strong> performed by local artists.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700">
                        <span class="material-symbols-outlined text-green-500 mr-2">check_circle</span>
                        Bonfire & Chicken Roast
                    </li>
                    <li class="flex items-center text-gray-700">
                        <span class="material-symbols-outlined text-green-500 mr-2">check_circle</span>
                        Live Folk Music Performance
                    </li>
                    <li class="flex items-center text-gray-700">
                        <span class="material-symbols-outlined text-green-500 mr-2">check_circle</span>
                        Star Gazing away from city lights
                    </li>
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span
                        class="bg-blue-100 text-blue-600 p-2 rounded-lg material-symbols-outlined">travel_explore</span>
                    Deeper Into the Core
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    With 2 days, we go further. We visit <strong>Sajnekhali, Sudhanyakhali, and Do-Banki</strong>. The
                    extra time allows us to navigate the narrowest creeks where big boats can't go, increasing your
                    chances of spotting the Tiger.
                </p>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <span class="material-symbols-outlined text-blue-500 mb-2">landscape</span>
                        <p class="font-bold text-sm">Do-Banki Canopy Walk</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <span class="material-symbols-outlined text-blue-500 mb-2">pets</span>
                        <p class="font-bold text-sm">Crocodile Project</p>
                    </div>
                </div>
            </div>
            <div class="relative group">
                <div
                    class="absolute -inset-2 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200">
                </div>
                <img src="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?q=80&w=2574&auto=format&fit=crop"
                    alt="Narrow Creek Safari"
                    class="relative rounded-2xl shadow-2xl w-full object-cover h-96 transform transition duration-500 hover:scale-[1.01]">
            </div>
        </div>
    </div>
</section>

<section id="itinerary" class="py-20 bg-white relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="text-green-600 font-bold tracking-widest uppercase text-sm">Detailed Plan</span>
            <h2 class="text-4xl font-bold text-gray-900 mt-2">2 Days of Adventure</h2>
        </div>

        <div class="flex justify-center mb-12">
            <div class="bg-white p-1 rounded-full shadow-md inline-flex">
                <button onclick="switchDay(1)" id="tab-day1"
                    class="px-8 py-3 rounded-full font-bold transition-all duration-300 bg-green-900 text-white">Day 1:
                    Arrival & Culture</button>
                <button onclick="switchDay(2)" id="tab-day2"
                    class="px-8 py-3 rounded-full font-bold transition-all duration-300 text-gray-600 hover:bg-gray-100">Day
                    2: Deep Safari</button>
            </div>
        </div>

        <div class="relative">
            <div class="timeline-line"></div>

            <div id="day1-content" class="transition-opacity duration-500 opacity-100">
                <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-16 items-center timeline-item">
                    <div class="w-full md:w-1/2 flex justify-end md:pr-8 text-right">
                        <div
                            class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-orange-500 w-full md:w-4/5 transform hover:-translate-x-2 transition">
                            <div class="text-2xl font-bold text-orange-600 mb-1 font-heading">8:00 AM</div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Pickup from Kolkata</h4>
                            <p class="text-gray-600 text-sm">Pickup from Esplanade or Science City. Enjoy a 3-hour
                                scenic drive through the rural landscapes of Bengal to Godkhali.</p>
                        </div>
                    </div>
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-orange-500 text-white shadow-lg z-20">
                        <span class="material-symbols-outlined">directions_car</span>
                    </div>
                    <div class="w-full md:w-1/2 md:pl-8 hidden md:block">
                        <img src="https://images.unsplash.com/photo-1558280417-695022067571?q=80&w=1000&auto=format&fit=crop"
                            class="rounded-xl shadow-lg w-3/4 opacity-80 grayscale hover:grayscale-0 transition">
                    </div>
                </div>

                <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-16 items-center timeline-item">
                    <div class="w-full md:w-1/2 md:pr-8 hidden md:block text-right">
                        <img src="https://images.unsplash.com/photo-1623164962299-0c679b329244?q=80&w=1000&auto=format&fit=crop"
                            class="rounded-xl shadow-lg w-3/4 ml-auto opacity-80 grayscale hover:grayscale-0 transition">
                    </div>
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white shadow-lg z-20">
                        <span class="material-symbols-outlined">sailing</span>
                    </div>
                    <div class="w-full md:w-1/2 flex justify-start md:pl-8">
                        <div
                            class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-blue-600 w-full md:w-4/5 transform hover:translate-x-2 transition">
                            <div class="text-2xl font-bold text-blue-600 mb-1 font-heading">12:00 PM</div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Boat Boarding & Lunch</h4>
                            <p class="text-gray-600 text-sm">Board our motorized boat at Godkhali. Welcome drink
                                followed by a hot lunch on board as we cruise towards the resort.</p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-16 items-center timeline-item">
                    <div class="w-full md:w-1/2 flex justify-end md:pr-8 text-right">
                        <div
                            class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-green-600 w-full md:w-4/5 transform hover:-translate-x-2 transition">
                            <div class="text-2xl font-bold text-green-600 mb-1 font-heading">4:00 PM</div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Bird Watching & Sunset</h4>
                            <p class="text-gray-600 text-sm">Visit the 'Pakhiralaya' (Bird Sanctuary). Watch the sun set
                                over the river confluence, painting the sky in orange hues.</p>
                        </div>
                    </div>
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-green-600 text-white shadow-lg z-20">
                        <span class="material-symbols-outlined">photo_camera</span>
                    </div>
                    <div class="w-full md:w-1/2 md:pl-8 hidden md:block">
                        <img src="https://images.unsplash.com/photo-1596627584260-327c0303e302?q=80&w=1000&auto=format&fit=crop"
                            class="rounded-xl shadow-lg w-3/4 opacity-80 grayscale hover:grayscale-0 transition">
                    </div>
                </div>

                <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-16 items-center timeline-item">
                    <div class="w-full md:w-1/2 md:pr-8 hidden md:block text-right">
                        <img src="https://images.unsplash.com/photo-1543051932-6ef9fecfbc80?q=80&w=1000&auto=format&fit=crop"
                            class="rounded-xl shadow-lg w-3/4 ml-auto opacity-80 grayscale hover:grayscale-0 transition">
                    </div>
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-purple-600 text-white shadow-lg z-20">
                        <span class="material-symbols-outlined">nightlife</span>
                    </div>
                    <div class="w-full md:w-1/2 flex justify-start md:pl-8">
                        <div
                            class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-purple-600 w-full md:w-4/5 transform hover:translate-x-2 transition">
                            <div class="text-2xl font-bold text-purple-600 mb-1 font-heading">7:00 PM</div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Tribal Dance & Dinner</h4>
                            <p class="text-gray-600 text-sm">Enjoy the 'Jhumur' folk dance by local tribals. End the day
                                with a delicious dinner at the resort.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="day2-content" class="hidden opacity-0 transition-opacity duration-500">
                <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-16 items-center timeline-item">
                    <div class="w-full md:w-1/2 flex justify-end md:pr-8 text-right">
                        <div
                            class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-orange-500 w-full md:w-4/5 transform hover:-translate-x-2 transition">
                            <div class="text-2xl font-bold text-orange-600 mb-1 font-heading">7:00 AM</div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Morning Safari</h4>
                            <p class="text-gray-600 text-sm">Start early. Cruise to Sajnekhali Watch Tower and Mangrove
                                Interpretation Centre to get permits.</p>
                        </div>
                    </div>
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-orange-500 text-white shadow-lg z-20">
                        <span class="material-symbols-outlined">wb_sunny</span>
                    </div>
                    <div class="w-full md:w-1/2 md:pl-8 hidden md:block">
                        <img src="https://images.unsplash.com/photo-1547971718-d71680108933?q=80&w=1000&auto=format&fit=crop"
                            class="rounded-xl shadow-lg w-3/4 opacity-80 grayscale hover:grayscale-0 transition">
                    </div>
                </div>

                <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-8 items-center timeline-item">
                    <div class="w-full md:w-1/2 md:pr-8 hidden md:block text-right">
                        <img src="https://images.unsplash.com/photo-1604928126569-7977759a2441?q=80&w=1000&auto=format&fit=crop"
                            class="rounded-xl shadow-lg w-3/4 ml-auto opacity-80 grayscale hover:grayscale-0 transition">
                    </div>
                    <div
                        class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-green-700 text-white shadow-lg z-20">
                        <span class="material-symbols-outlined">forest</span>
                    </div>
                    <div class="w-full md:w-1/2 flex justify-start md:pl-8">
                        <div
                            class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-green-700 w-full md:w-4/5 transform hover:translate-x-2 transition">
                            <div class="text-2xl font-bold text-green-700 mb-1 font-heading">9:00 AM - 3:00 PM</div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Core Area Exploration</h4>
                            <p class="text-gray-600 text-sm">Visit Sudhanyakhali Watch Tower. Cruise through Pirkhali
                                and Gazikhali creeks. Lunch on deck. Return to Godkhali by 4 PM.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section id="map-section" class="h-[500px] w-full relative">
    <div id="map" class="w-full h-full bg-gray-200 flex items-center justify-center">
        <p class="text-gray-500">Loading Map...</p>
    </div>
    <div class="absolute bottom-4 left-4 z-10 bg-white p-4 rounded-lg shadow-lg max-w-xs">
        <h4 class="font-bold text-gray-800 mb-2">Route Map</h4>
        <div class="flex items-center gap-2 mb-1">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <span class="text-xs text-gray-600">Pickup Points</span>
        </div>
        <div class="flex items-center gap-2 mb-1">
            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
            <span class="text-xs text-gray-600">Boat Journey</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-600 text-sm">night_shelter</span>
            <span class="text-xs text-gray-600">Night Stay (Resort)</span>
        </div>
    </div>
</section>

<section id="food" class="py-20 bg-stone-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="material-symbols-outlined text-4xl text-orange-500 mb-2">restaurant_menu</span>
            <h2 class="text-4xl font-bold text-gray-900">All-Inclusive Dining</h2>
            <p class="text-gray-600 mt-2">From morning tea to dinner, we have you covered with fresh, local delicacies.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition group">
                <h3 class="font-bold text-gray-800 mb-2 border-b pb-2">Breakfast (Day 1 & 2)</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Puri Sabzi / Kachori</li>
                    <li>• Boiled Eggs</li>
                    <li>• Sweets</li>
                    <li>• Tea / Coffee</li>
                </ul>
            </div>

            <div
                class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition group border-t-4 border-orange-500">
                <h3 class="font-bold text-gray-800 mb-2 border-b pb-2">Lunch (Day 1 & 2)</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Rice, Dal, Bhaja</li>
                    <li>• <strong>Fish Curry</strong> (Bhetki/Rui)</li>
                    <li>• <strong>Prawn Malaikari</strong></li>
                    <li>• Chutney, Papad</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition group">
                <h3 class="font-bold text-gray-800 mb-2 border-b pb-2">Evening Snacks</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Chicken Pakora</li>
                    <li>• Veg Pakora</li>
                    <li>• Masala Tea</li>
                    <li>• Biscuits</li>
                </ul>
            </div>

            <div class="bg-gray-800 text-white rounded-xl shadow-md p-6 hover:shadow-xl transition group">
                <h3 class="font-bold text-orange-400 mb-2 border-b border-gray-600 pb-2">Dinner</h3>
                <ul class="text-sm text-gray-300 space-y-1">
                    <li>• Fried Rice / Roti</li>
                    <li>• <strong>Chicken Kosha</strong></li>
                    <li>• Salad</li>
                    <li>• Dessert</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="pricing" class="py-20 leaf-pattern text-white relative">
    <div class="absolute inset-0 bg-green-900/90 z-0"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Transparent Pricing</h2>
            <p class="text-green-100">Best rates for the 2025 Season.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div
                class="bg-white rounded-2xl p-8 text-gray-800 shadow-2xl transform transition hover:scale-105 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-orange-600 text-white px-4 py-1 rounded-bl-lg font-bold text-sm">
                    Best Seller</div>
                <h3 class="text-2xl font-bold mb-2">Classic Package</h3>
                <p class="text-gray-500 text-sm mb-6">Group tour with resort stay.</p>
                <div class="text-5xl font-bold text-green-700 mb-6">₹3,999<span
                        class="text-lg text-gray-400 font-normal">/person</span></div>

                <ul class="space-y-3 mb-8">
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-green-500 mr-2">check</span> AC Resort Accommodation
                    </li>
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-green-500 mr-2">check</span> All 6 Meals Included</li>
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-green-500 mr-2">check</span> Evening Cultural Show
                    </li>
                </ul>
                <button
                    class="w-full py-4 bg-green-700 hover:bg-green-800 text-white rounded-xl font-bold transition shadow-lg">Book
                    Classic Tour</button>
            </div>

            <div
                class="bg-gray-900 rounded-2xl p-8 text-white shadow-2xl transform transition hover:scale-105 border border-gray-700">
                <h3 class="text-2xl font-bold mb-2">Private Premium</h3>
                <p class="text-gray-400 text-sm mb-6">Private boat & car for your family.</p>
                <div class="text-5xl font-bold text-orange-500 mb-6">₹22,000<span
                        class="text-lg text-gray-400 font-normal">/couple</span></div>
                <p class="text-xs text-gray-400 -mt-4 mb-6">Customizable Menu & Timing</p>

                <ul class="space-y-3 mb-8">
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-orange-500 mr-2">check</span> Private Car Pickup</li>
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-orange-500 mr-2">check</span> Exclusive Boat & Guide
                    </li>
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-orange-500 mr-2">check</span> Premium Resort Room</li>
                </ul>
                <button
                    class="w-full py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-bold transition shadow-lg">Request
                    Private Quote</button>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>