<?php include 'header.php'; ?>

<header class="relative h-screen flex items-center justify-center overflow-hidden clip-path-wave mb-12">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1615656783693-793dbd239c04?q=80&w=2670&auto=format&fit=crop"
            alt="Sundarban Sunrise" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/60"></div>
    </div>

    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-16">
        <span
            class="inline-block py-1 px-3 rounded-full bg-orange-500/20 border border-orange-400 text-orange-100 text-sm font-bold tracking-widest uppercase mb-6 backdrop-blur-sm animate-pulse">
            1 Day Premium Adventure
        </span>
        <h1 class="text-5xl md:text-7xl text-white font-bold mb-6 hero-text leading-tight drop-shadow-lg">
            Into the Wild <br><span
                class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-300">Delta</span>
        </h1>
        <p class="text-xl text-gray-100 mb-10 max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
            Escape the city chaos. Experience the mystic mangroves, spot the Royal Bengal Tiger, and savor authentic
            local cuisine—all in a single, unforgettable day.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#itinerary"
                class="bg-white text-green-900 px-8 py-4 rounded-full font-bold hover:bg-gray-100 transition transform hover:-translate-y-1 shadow-xl flex items-center justify-center gap-2">
                View Itinerary <span class="material-symbols-outlined">arrow_downward</span>
            </a>
            <a href="#map-section"
                class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-bold hover:bg-white/10 transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                See Route Map <span class="material-symbols-outlined">map</span>
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
                <span class="material-symbols-outlined text-orange-600 text-3xl">schedule</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">Full Day Trip</h3>
            <p class="text-sm text-gray-500 mt-1">5 AM - 9 PM</p>
        </div>
        <div
            class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-green-600 flex flex-col items-center text-center transform hover:-translate-y-2 transition duration-300">
            <div class="bg-green-100 p-3 rounded-full mb-4">
                <span class="material-symbols-outlined text-green-600 text-3xl">directions_boat</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">Private/Group Boat</h3>
            <p class="text-sm text-gray-500 mt-1">6-Cylinder Safety Boat</p>
        </div>
        <div
            class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-blue-500 flex flex-col items-center text-center transform hover:-translate-y-2 transition duration-300">
            <div class="bg-blue-100 p-3 rounded-full mb-4">
                <span class="material-symbols-outlined text-blue-600 text-3xl">restaurant_menu</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">All Meals Included</h3>
            <p class="text-sm text-gray-500 mt-1">Breakfast, Lunch, Snacks</p>
        </div>
        <div
            class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-purple-500 flex flex-col items-center text-center transform hover:-translate-y-2 transition duration-300">
            <div class="bg-purple-100 p-3 rounded-full mb-4">
                <span class="material-symbols-outlined text-purple-600 text-3xl">explore</span>
            </div>
            <h3 class="font-bold text-lg text-gray-800">Expert Guided</h3>
            <p class="text-sm text-gray-500 mt-1">Govt. Certified Guide</p>
        </div>
    </div>
</section>

<section id="experience" class="py-16 leaf-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-green-900 mb-4">Why Choose This 1-Day Escape?</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Unlike standard tours, we maximize your time in the core
                jungle area. Experience the raw beauty of the delta without the hassle of overnight packing.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
            <div class="order-2 md:order-1 relative group">
                <div
                    class="absolute -inset-2 bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200">
                </div>
                <img src="https://images.unsplash.com/photo-1547971718-d71680108933?q=80&w=1000&auto=format&fit=crop"
                    alt="Royal Bengal Tiger"
                    class="relative rounded-2xl shadow-2xl w-full object-cover h-96 transform transition duration-500 hover:scale-[1.01]">
                <div
                    class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded text-xs backdrop-blur-md">
                    Possibility: 20% Sighting Chance
                </div>
            </div>
            <div class="order-1 md:order-2 space-y-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span
                        class="bg-orange-100 text-orange-600 p-2 rounded-lg material-symbols-outlined">visibility</span>
                    Wildlife Encounters
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    Navigate through the narrow creeks of <strong>Pirkhali</strong> and <strong>Gazikhali</strong>.
                    These silent channels are the best spots to sight the elusive Royal Bengal Tiger, Estuarine
                    Crocodiles, and Spotted Deer drinking at the river banks.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700">
                        <span class="material-symbols-outlined text-green-500 mr-2">check_circle</span>
                        Estuarine Crocodiles & Water Monitors
                    </li>
                    <li class="flex items-center text-gray-700">
                        <span class="material-symbols-outlined text-green-500 mr-2">check_circle</span>
                        Spotted Deer & Wild Boars
                    </li>
                    <li class="flex items-center text-gray-700">
                        <span class="material-symbols-outlined text-green-500 mr-2">check_circle</span>
                        Rare Kingfishers & Migratory Birds
                    </li>
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="bg-blue-100 text-blue-600 p-2 rounded-lg material-symbols-outlined">deck</span>
                    Premium Boat Safari
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    Your comfort is our priority. Our boats are not just transport; they are floating lounges. Equipped
                    with clean western toilets, open decks for 360° views, and a dedicated kitchen crew cooking fresh
                    meals on board.
                </p>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <span class="material-symbols-outlined text-blue-500 mb-2">wc</span>
                        <p class="font-bold text-sm">Hygienic Washrooms</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <span class="material-symbols-outlined text-blue-500 mb-2">bed</span>
                        <p class="font-bold text-sm">Rest Area for Elderly</p>
                    </div>
                </div>
            </div>
            <div class="relative group">
                <div
                    class="absolute -inset-2 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200">
                </div>
                <img src="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?q=80&w=2574&auto=format&fit=crop"
                    alt="Premium Boat Safari"
                    class="relative rounded-2xl shadow-2xl w-full object-cover h-96 transform transition duration-500 hover:scale-[1.01]">
            </div>
        </div>
    </div>
</section>

<section id="itinerary" class="py-20 bg-white relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="text-green-600 font-bold tracking-widest uppercase text-sm">Hour by Hour</span>
            <h2 class="text-4xl font-bold text-gray-900 mt-2">Your Day in the Delta</h2>
        </div>

        <div class="relative">
            <div class="timeline-line"></div>

            <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-16 items-center timeline-item">
                <div class="w-full md:w-1/2 flex justify-end md:pr-8 text-right">
                    <div
                        class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-orange-500 w-full md:w-4/5 transform hover:-translate-x-2 transition">
                        <div class="text-2xl font-bold text-orange-600 mb-1 font-heading">5:00 AM - 6:00 AM</div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">The Journey Begins</h4>
                        <p class="text-gray-600 text-sm">Pickup from designated points in Kolkata (Esplanade / Science
                            City). Enjoy a smooth drive through the Bengal countryside.</p>
                        <div
                            class="mt-3 inline-flex items-center text-xs font-bold text-orange-600 bg-orange-100 px-2 py-1 rounded">
                            <span class="material-symbols-outlined text-sm mr-1">local_taxi</span> AC Transfer
                        </div>
                    </div>
                </div>
                <div
                    class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-orange-500 text-white shadow-lg z-20">
                    <span class="material-symbols-outlined">departure_board</span>
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
                    class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-green-600 text-white shadow-lg z-20">
                    <span class="material-symbols-outlined">coffee</span>
                </div>
                <div class="w-full md:w-1/2 flex justify-start md:pl-8">
                    <div
                        class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-green-600 w-full md:w-4/5 transform hover:translate-x-2 transition">
                        <div class="text-2xl font-bold text-green-600 mb-1 font-heading">8:30 AM</div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Arrival at Godkhali</h4>
                        <p class="text-gray-600 text-sm">Reach the gateway of Sundarbans. Board our motorized safari
                            boat. Welcome drinks and breakfast served immediately on board.</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-16 items-center timeline-item">
                <div class="w-full md:w-1/2 flex justify-end md:pr-8 text-right">
                    <div
                        class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-blue-600 w-full md:w-4/5 transform hover:-translate-x-2 transition">
                        <div class="text-2xl font-bold text-blue-600 mb-1 font-heading">9:30 AM - 1:00 PM</div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Deep Jungle Safari</h4>
                        <p class="text-gray-600 text-sm">Cruise through Sajnekhali and Sudhanyakhali Watch Towers. Visit
                            the Mangrove Interpretation Centre. Navigate narrow creeks for wildlife spotting.</p>
                        <div class="mt-3 flex gap-2 justify-end flex-wrap">
                            <span
                                class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded font-bold">Sajnekhali</span>
                            <span
                                class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded font-bold">Sudhanyakhali</span>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white shadow-lg z-20">
                    <span class="material-symbols-outlined">pets</span>
                </div>
                <div class="w-full md:w-1/2 md:pl-8 hidden md:block">
                    <img src="https://images.unsplash.com/photo-1604928126569-7977759a2441?q=80&w=1000&auto=format&fit=crop"
                        class="rounded-xl shadow-lg w-3/4 opacity-80 grayscale hover:grayscale-0 transition">
                </div>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-16 items-center timeline-item">
                <div class="w-full md:w-1/2 md:pr-8 hidden md:block text-right">
                    <img src="https://images.unsplash.com/photo-1596627584260-327c0303e302?q=80&w=1000&auto=format&fit=crop"
                        class="rounded-xl shadow-lg w-3/4 ml-auto opacity-80 grayscale hover:grayscale-0 transition">
                </div>
                <div
                    class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-yellow-500 text-white shadow-lg z-20">
                    <span class="material-symbols-outlined">restaurant</span>
                </div>
                <div class="w-full md:w-1/2 flex justify-start md:pl-8">
                    <div
                        class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-yellow-500 w-full md:w-4/5 transform hover:translate-x-2 transition">
                        <div class="text-2xl font-bold text-yellow-600 mb-1 font-heading">1:30 PM</div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Lunch on the River</h4>
                        <p class="text-gray-600 text-sm">Enjoy a freshly cooked, hot Bengali buffet lunch while the boat
                            anchors in the middle of the river, surrounded by silence and nature.</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row gap-8 mb-8 items-center timeline-item">
                <div class="w-full md:w-1/2 flex justify-end md:pr-8 text-right">
                    <div
                        class="bg-gray-50 p-6 rounded-xl shadow-md border-l-4 border-gray-600 w-full md:w-4/5 transform hover:-translate-x-2 transition">
                        <div class="text-2xl font-bold text-gray-600 mb-1 font-heading">5:00 PM - 8:30 PM</div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Sunset Return</h4>
                        <p class="text-gray-600 text-sm">Witness a magical sunset over the delta as we return to
                            Godkhali. Transfer back to your vehicle and drop-off at Kolkata.</p>
                    </div>
                </div>
                <div
                    class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-gray-700 text-white shadow-lg z-20">
                    <span class="material-symbols-outlined">home</span>
                </div>
                <div class="w-full md:w-1/2 md:pl-8 hidden md:block">
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
            <span class="material-symbols-outlined text-green-600 text-sm">visibility</span>
            <span class="text-xs text-gray-600">Watch Towers</span>
        </div>
    </div>
</section>

<section id="food" class="py-20 bg-stone-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="material-symbols-outlined text-4xl text-orange-500 mb-2">ramen_dining</span>
            <h2 class="text-4xl font-bold text-gray-900">A Taste of Bengal</h2>
            <p class="text-gray-600 mt-2">Freshly cooked on the boat. Hygienic, hot, and delicious.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?q=80&w=1000&auto=format&fit=crop"
                        alt="Breakfast"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Breakfast</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li class="border-b border-dashed pb-1">Radhaballavi / Kachori</li>
                        <li class="border-b border-dashed pb-1">Alur Dom (Spicy Potato Curry)</li>
                        <li class="border-b border-dashed pb-1">Boiled Eggs</li>
                        <li>Bengali Sweets & Tea/Coffee</li>
                    </ul>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group border-t-4 border-orange-500">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1631452180519-c014fe946bc7?q=80&w=1000&auto=format&fit=crop"
                        alt="Lunch"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Grand Lunch</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li class="border-b border-dashed pb-1">Basmati Rice & Moong Dal</li>
                        <li class="border-b border-dashed pb-1">Bhaja (Fried Veggies)</li>
                        <li class="border-b border-dashed pb-1 font-bold text-orange-600">Bhetki Fish Curry / Prawn
                            Malaikari</li>
                        <li>Chicken Curry, Chutney, Papad</li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1601050690117-94f5f6fa8bd7?q=80&w=1000&auto=format&fit=crop"
                        alt="Snacks"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Evening Bites</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li class="border-b border-dashed pb-1">Chicken Pakora (Veg option available)</li>
                        <li class="border-b border-dashed pb-1">Masala Tea / Coffee</li>
                        <li>Biscuits</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="pricing" class="py-20 leaf-pattern text-white relative">
    <div class="absolute inset-0 bg-green-900/90 z-0"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Simple, Transparent Pricing</h2>
            <p class="text-green-100">No hidden charges. Book your seat today.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div
                class="bg-white rounded-2xl p-8 text-gray-800 shadow-2xl transform transition hover:scale-105 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-green-600 text-white px-4 py-1 rounded-bl-lg font-bold text-sm">
                    Most Popular</div>
                <h3 class="text-2xl font-bold mb-2">Group Departure</h3>
                <p class="text-gray-500 text-sm mb-6">Join other nature lovers. Perfect for couples and solo travelers.
                </p>
                <div class="text-5xl font-bold text-green-700 mb-6">₹2,800<span
                        class="text-lg text-gray-400 font-normal">/person</span></div>

                <ul class="space-y-3 mb-8">
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-green-500 mr-2">check</span> Pickup: Science City /
                        Esplanade</li>
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-green-500 mr-2">check</span> All Meals Included</li>
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-green-500 mr-2">check</span> Boat Permit & Guide Fees
                    </li>
                </ul>
                <button
                    class="w-full py-4 bg-green-700 hover:bg-green-800 text-white rounded-xl font-bold transition shadow-lg">Book
                    Group Tour</button>
            </div>

            <div
                class="bg-gray-900 rounded-2xl p-8 text-white shadow-2xl transform transition hover:scale-105 border border-gray-700">
                <h3 class="text-2xl font-bold mb-2">Private Boat</h3>
                <p class="text-gray-400 text-sm mb-6">Exclusive experience for your family or friends group.</p>
                <div class="text-5xl font-bold text-orange-500 mb-6">₹16,000<span
                        class="text-lg text-gray-400 font-normal">/group (1-4 pax)</span></div>
                <p class="text-xs text-gray-400 -mt-4 mb-6">+ ₹1500 per extra person</p>

                <ul class="space-y-3 mb-8">
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-orange-500 mr-2">check</span> Private Car Pickup</li>
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-orange-500 mr-2">check</span> Exclusive Boat (No
                        strangers)</li>
                    <li class="flex items-center"><span
                            class="material-symbols-outlined text-orange-500 mr-2">check</span> Customized Food Menu
                    </li>
                </ul>
                <button
                    class="w-full py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-bold transition shadow-lg">Request
                    Private Quote</button>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h3 class="text-xl font-bold text-gray-700 mb-8">Wildlife Spotting Probability (This Season)</h3>
        <div class="flex justify-center gap-8 flex-wrap">
            <div class="flex flex-col items-center">
                <div
                    class="w-20 h-20 rounded-full border-4 border-orange-500 flex items-center justify-center text-xl font-bold text-orange-600 mb-2">
                    25%</div>
                <span class="text-sm font-bold">Royal Bengal Tiger</span>
            </div>
            <div class="flex flex-col items-center">
                <div
                    class="w-20 h-20 rounded-full border-4 border-green-600 flex items-center justify-center text-xl font-bold text-green-700 mb-2">
                    90%</div>
                <span class="text-sm font-bold">Spotted Deer</span>
            </div>
            <div class="flex flex-col items-center">
                <div
                    class="w-20 h-20 rounded-full border-4 border-blue-600 flex items-center justify-center text-xl font-bold text-blue-700 mb-2">
                    85%</div>
                <span class="text-sm font-bold">Estuarine Croc</span>
            </div>
            <div class="flex flex-col items-center">
                <div
                    class="w-20 h-20 rounded-full border-4 border-yellow-500 flex items-center justify-center text-xl font-bold text-yellow-600 mb-2">
                    70%</div>
                <span class="text-sm font-bold">Monitor Lizard</span>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>