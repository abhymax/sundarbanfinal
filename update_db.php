<?php
require_once 'db_connect.php';

try {
    // Add columns to settings if they don't exist
    $columns = $pdo->query("SHOW COLUMNS FROM settings")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('instagram_url', $columns)) {
        $pdo->exec("ALTER TABLE settings ADD COLUMN instagram_url varchar(255) DEFAULT NULL AFTER facebook_url");
        echo "Added instagram_url column.<br>";
    }

    if (!in_array('youtube_url', $columns)) {
        $pdo->exec("ALTER TABLE settings ADD COLUMN youtube_url varchar(255) DEFAULT NULL AFTER instagram_url");
        echo "Added youtube_url column.<br>";
    }

    // Create menus table
    $pdo->exec("CREATE TABLE IF NOT EXISTS menus (
      id int(11) NOT NULL AUTO_INCREMENT,
      label varchar(100) NOT NULL,
      link varchar(255) NOT NULL,
      parent_id int(11) DEFAULT NULL,
      sort_order int(11) DEFAULT 0,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "Created menus table.<br>";

    // Check if menus table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM menus")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO menus (id, label, link, parent_id, sort_order) VALUES
        (1, 'Home', 'index.php', NULL, 1),
        (2, 'About Us', 'about.php', NULL, 2),
        (3, 'Packages', '#', NULL, 3),
        (4, 'Gallery', 'gallery.php', NULL, 4),
        (5, 'Contact Us', 'contact.php', NULL, 5),
        (6, '1 Day Sundarban Tour', '1-day-tour.php', 3, 1),
        (7, '1 Night 2 Days Package', '1-night-2-days-tour.php', 3, 2),
        (8, '2 Night 3 Days Package', '2-nights-3-days-tour.php', 3, 3)");
        echo "Seeded menus table.<br>";
    }

    echo "Database update completed successfully.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>