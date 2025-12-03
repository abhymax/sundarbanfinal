<?php
require_once 'db_connect.php';

try {
    $pageKey = '2n3d_tour';

    // 1. Hero Section
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM site_sections WHERE section_key = ?");
    $stmt->execute(["{$pageKey}_hero"]);
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO site_sections (section_key, title, subtitle, cta_text, cta_link, image_url, overlay_opacity) VALUES (?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                "{$pageKey}_hero",
                'Into the Heart of Darkness',
                '3 Days of immersion. Reach the farthest watchtowers, explore historic bungalows, and experience the true life of the delta.',
                'View 3-Day Plan',
                '#itinerary',
                'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?q=80&w=2674&auto=format&fit=crop',
                '0.6'
            ]);
        echo "Hero added.<br>";
    }

    // 2. Quick Info Cards
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM page_cards WHERE page_key = ?");
    $stmt->execute([$pageKey]);
    if ($stmt->fetchColumn() == 0) {
        $cards = [
            ['bedtime', '2 Nights Stay', 'Immersive Resort Experience', 'orange', 1],
            ['map', 'Complete Tour', 'Core Area + Village Life', 'green', 2],
            ['restaurant_menu', '9 Meals Included', 'Full Board Dining', 'blue', 3],
            ['history_edu', 'History & Culture', 'Tagore Bungalow Visit', 'purple', 4]
        ];
        $insert = $pdo->prepare("INSERT INTO page_cards (page_key, section_key, icon, title, subtitle, color_theme, sort_order) VALUES (?, 'quick_info', ?, ?, ?, ?, ?)");
        foreach ($cards as $c) $insert->execute([$pageKey, $c[0], $c[1], $c[2], $c[3], $c[4]]);
        echo "Cards added.<br>";
    }

    // 3. Experience Section
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM site_sections WHERE section_key = ?");
    $stmt->execute(["{$pageKey}_exp_header"]);
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO site_sections (section_key, title, subtitle) VALUES (?, ?, ?)")
            ->execute(["{$pageKey}_exp_header", 'The Complete Jungle Experience', 'More time means deeper exploration. Witness the forest change colors from dawn to dusk.']);
        
        $h1_list = json_encode(["Bonfire & Chicken Roast", "Live Folk Music Performance", "Star Gazing away from city lights"]);
        $h2_list = json_encode(["Do-Banki Canopy Walk", "Crocodile Project"]);

        $insert = $pdo->prepare("INSERT INTO page_highlights (page_key, section_key, title, description, image_url, badge_text, list_data, sort_order) VALUES (?, 'experience', ?, ?, ?, ?, ?, ?)");
        $insert->execute([$pageKey, 'Evenings at the Resort', 'After a thrilling day of safari, unwind at our eco-resort. The evenings come alive with the rhythmic beats of Jhumur.', 'https://images.unsplash.com/photo-1543051932-6ef9fecfbc80?q=80&w=1000&auto=format&fit=crop', 'Cultural Evening', $h1_list, 1]);
        $insert->execute([$pageKey, 'Deeper Into the Core', 'With 3 days, we go further. We visit Sajnekhali, Sudhanyakhali, and Do-Banki. The extra time allows us to navigate the narrowest creeks.', 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?q=80&w=2574&auto=format&fit=crop', NULL, $h2_list, 2]);
        echo "Experience added.<br>";
    }

    // 4. Itinerary (3 Days)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM site_sections WHERE section_key = ?");
    $stmt->execute(["{$pageKey}_itin_header"]);
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO site_sections (section_key, title, subtitle) VALUES (?, ?, ?)")
            ->execute(["{$pageKey}_itin_header", '3 Days of Discovery', 'Detailed Plan']);

        $events = [
            // Day 1
            ['Pickup & Drive', '8:00 AM', 'Pickup from Kolkata. Scenic drive to Godkhali. Boarding the boat and welcome drinks.', 'directions_car', 'orange', 'https://images.unsplash.com/photo-1623164962299-0c679b329244?q=80&w=1000&auto=format&fit=crop', '', 1, 1],
            ['Sunset at Bird Island', '4:00 PM', 'Cruise to the bird sanctuary. Watch thousands of migratory birds return home at sunset.', 'wb_twilight', 'green', 'https://images.unsplash.com/photo-1596627584260-327c0303e302?q=80&w=1000&auto=format&fit=crop', '', 1, 2],
            ['Cultural Night', '7:00 PM', 'Tribal Jhumur dance performance followed by a bonfire (winter only) and dinner.', 'nightlife', 'purple', 'https://images.unsplash.com/photo-1543051932-6ef9fecfbc80?q=80&w=1000&auto=format&fit=crop', '', 1, 3],
            
            // Day 2
            ['Full Day Safari Starts', '7:00 AM', 'Start early. Cruise to Sajnekhali Watch Tower and Mangrove Interpretation Centre.', 'explore', 'orange', 'https://images.unsplash.com/photo-1604928126569-7977759a2441?q=80&w=1000&auto=format&fit=crop', '', 2, 1],
            ['The Core Zone', '11:00 AM - 3:00 PM', 'Visit Sudhanyakhali, Dobanki (Canopy Walk), Pirkhali, and Panchmukhani. Highest chance of Tiger sighting.', 'visibility', 'green', 'https://images.unsplash.com/photo-1547971718-d71680108933?q=80&w=1000&auto=format&fit=crop', 'Sajnekhali,Sudhanyakhali', 2, 2],
            
            // Day 3
            ['Village Walk', '8:00 AM', 'Walk through a local village. Interact with honey collectors and fishermen.', 'people', 'blue', 'https://images.unsplash.com/photo-1599407337675-927696660cc6?q=80&w=1000&auto=format&fit=crop', '', 3, 1],
            ['Hamilton Bungalow', '10:00 AM', 'Visit the historic Hamilton Bungalow and Rabindranath Tagore\'s Bungalow in Gosaba.', 'history_edu', 'purple', 'https://images.unsplash.com/photo-1572097662444-6e7874659223?q=80&w=1000&auto=format&fit=crop', '', 3, 2],
            ['Return Journey', '2:00 PM', 'After lunch on the boat, we return to Godkhali and transfer to the car for the drive back to Kolkata.', 'home', 'gray', '', '', 3, 3]
        ];

        $insert = $pdo->prepare("INSERT INTO page_timeline (page_key, title, time_range, description, icon, color_theme, image_url, tags, day_number, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($events as $e) $insert->execute([$pageKey, $e[0], $e[1], $e[2], $e[3], $e[4], $e[5], $e[6], $e[7], $e[8]]);
        echo "Itinerary added.<br>";
    }

    // 5. Food & Pricing
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM site_sections WHERE section_key = ?");
    $stmt->execute(["{$pageKey}_food_header"]);
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO site_sections (section_key, title, subtitle) VALUES (?, ?, ?)")
            ->execute(["{$pageKey}_food_header", '3 Days of Feasting', 'A culinary journey through Bengal\'s finest flavors.']);
        
        $menus = [
            ['Breakfasts', ["Day 1: Packed Sandwich/Sweet", "Day 2: Puri Sabzi / Kachori", "Day 3: Alu Paratha / Bread"], 0, 1],
            ['Grand Lunches', ["Rice, Dal, Bhaja (Fry)", "Prawn Malaikari", "Bhetki Fish Curry", "Crab Masala (Seasonal)"], 1, 2],
            ['Dinners', ["Fried Rice / Roti", "Chicken Kosha", "Mutton Curry (Day 2 Special)", "Salad & Rosogolla"], 0, 3],
            ['Evening Snacks', ["Chicken Pakora", "Fish Finger", "Veg Pakora", "Masala Tea / Coffee"], 0, 4]
        ];
        $insert = $pdo->prepare("INSERT INTO page_food_menu (page_key, title, items, image_url, is_highlighted, sort_order) VALUES (?, ?, ?, '', ?, ?)");
        foreach ($menus as $m) $insert->execute([$pageKey, $m[0], json_encode($m[1]), $m[2], $m[3]]);

        // Pricing
        $pdo->prepare("INSERT INTO site_sections (section_key, title, subtitle) VALUES (?, ?, ?)")
            ->execute(["{$pageKey}_price_header", 'Transparent Pricing', 'Best rates for the 2025 Season.']);
        
        $plans = [
            ['Classic Group', 'Group tour with resort stay.', '5,499', '/person', ["AC Resort Accommodation (2 Nights)", "All 9 Meals Included", "Folk Dance & Bonfire"], 'Book Classic Tour', 'light', 'Best Value', 1],
            ['Private Premium', 'Complete privacy for your group.', '25,000', '/couple', ["Private Car & Boat", "Premium Room with View", "Dedicated Guide"], 'Request Private Quote', 'dark', NULL, 2]
        ];
        $insert = $pdo->prepare("INSERT INTO page_pricing (page_key, title, subtitle, price, price_unit, features, btn_text, style_mode, badge_text, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($plans as $p) $insert->execute([$pageKey, $p[0], $p[1], $p[2], $p[3], json_encode($p[4]), $p[5], $p[6], $p[7], $p[8]]);
        
        echo "Food & Pricing added.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>