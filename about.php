<?php include 'header.php'; ?>

<header class="relative h-[50vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1503213100123-594f8926a0d9?q=80&w=2670&auto=format&fit=crop"
            class="w-full h-full object-cover opacity-90">
        <div class="absolute inset-0 bg-gradient-to-r from-green-900/90 to-green-900/40"></div>
    </div>
    <div class="relative z-10 text-center text-white px-4">
        <span class="text-orange-300 font-bold tracking-widest uppercase text-sm">Since 2015</span>
        <h1 class="text-5xl md:text-6xl font-bold font-serif mt-2 mb-4">Guardians of the Delta</h1>
        <p class="text-xl font-light max-w-2xl mx-auto">We are locals, conservationists, and storytellers.</p>
    </div>
</header>

<section class="py-24">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-16 items-center">
            <div class="md:w-1/2 relative">
                <div class="absolute top-4 left-4 w-full h-full border-2 border-tiger-orange rounded-2xl -z-10"></div>
                <img src="https://images.unsplash.com/photo-1596895111956-bf1cf0599ce5?q=80&w=1000&auto=format&fit=crop"
                    class="rounded-2xl shadow-2xl w-full">
            </div>
            <div class="md:w-1/2 space-y-6">
                <h2 class="text-4xl font-serif font-bold text-green-900">Born from the River</h2>
                <p class="text-gray-600 leading-relaxed">
                    Sundarban Boat Safari wasn't started in a boardroom. It began on a small wooden boat in Gosaba,
                    where our founder, <strong>Rahul Mondal</strong>, grew up listening to the tigers roar across the
                    river.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    We realized that most tourists saw the Sundarbans through glass windows of large cruisers. We wanted
                    to change that. We wanted to offer an experience that smells of the wet earth, tastes of the local
                    mustard fish, and feels like an adventure, not just a sightseeing trip.
                </p>
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-tiger-orange">
                        <span class="text-3xl font-bold text-green-900">10+</span>
                        <p class="text-sm text-gray-500">Years Experience</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-green-900">
                        <span class="text-3xl font-bold text-green-900">5k+</span>
                        <p class="text-sm text-gray-500">Happy Guests</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-24 bg-green-50">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-serif font-bold text-green-900 mb-16">The People Behind the Oars</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-2 transition duration-300">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1000&auto=format&fit=crop"
                    class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-tiger-orange mb-6">
                <h3 class="text-xl font-bold text-gray-800">Rahul Mondal</h3>
                <p class="text-tiger-orange text-sm uppercase font-bold mb-4">Founder & Lead Naturalist</p>
                <p class="text-gray-600 text-sm italic">"The forest speaks to those who listen. My job is to teach you
                    its language."</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-2 transition duration-300">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=1000&auto=format&fit=crop"
                    class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-green-700 mb-6">
                <h3 class="text-xl font-bold text-gray-800">Amit Das</h3>
                <p class="text-green-700 text-sm uppercase font-bold mb-4">Senior Boat Captain</p>
                <p class="text-gray-600 text-sm italic">"I know these tides better than the back of my hand. Your safety
                    is my promise."</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-2 transition duration-300">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1000&auto=format&fit=crop"
                    class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-tiger-orange mb-6">
                <h3 class="text-xl font-bold text-gray-800">Priya Sen</h3>
                <p class="text-tiger-orange text-sm uppercase font-bold mb-4">Guest Experience Manager</p>
                <p class="text-gray-600 text-sm italic">"From your first call to your last meal, I ensure everything is
                    perfect."</p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>