<?php include 'header.php'; ?>

<main class="pt-20 min-h-screen relative flex flex-col md:flex-row">
    <div
        <div>
            <p class="text-xs text-gray-400 uppercase">Office</p>
            <p class="font-bold text-xl">Canning Road, Kolkata</p>
        </div>
    </div>
    </div>

    <div class="mt-12 flex gap-4">
        <a href="#" class="p-3 bg-green-700 rounded-full hover:bg-green-600 transition">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="w-6 h-6">
        </a>
        <a href="#"
            class="p-3 bg-blue-600 rounded-full hover:bg-blue-500 transition text-white flex items-center justify-center">
            <span class="material-symbols-outlined text-xl">facebook</span>
        </a>
    </div>
    </div>
    </div>

    <div class="md:w-1/2 bg-white flex items-center justify-center p-8 md:p-12">
        <div class="w-full max-w-md">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Send us a Message</h2>
            <form class="space-y-6" onsubmit="event.preventDefault(); alert('Message Sent!');">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">First Name</label>
                        <input type="text"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Last Name</label>
                        <input type="text"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input type="email"
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subject</label>
                    <select
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition">
                        <option>General Inquiry</option>
                        <option>Booking Request</option>
                        <option>Custom Package</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Message</label>
                    <textarea
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 h-32 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition"></textarea>
                </div>
                <button
                    class="w-full bg-tiger-orange text-white font-bold py-4 rounded-xl hover:bg-orange-700 transition shadow-lg transform hover:-translate-y-1">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>