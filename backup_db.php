<?php
require_once 'db_connect.php';

$backupFile = 'backups/database_backup_' . date('Y-m-d_H-i-s') . '.sql';

// Get all tables
$tables = array();
$stmt = $pdo->query('SHOW TABLES');
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
}

$sqlScript = "";
foreach ($tables as $table) {
    // Prepare SQLscript for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $stmt = $pdo->query($query);
    $row = $stmt->fetch(PDO::FETCH_NUM);
    $sqlScript .= "\n\n" . $row[1] . ";\n\n";

    $query = "SELECT * FROM $table";
    $stmt = $pdo->query($query);
    $columnCount = $stmt->columnCount();

    for ($i = 0; $i < $columnCount; $i++) {
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j++) {
                $row[$j] = $row[$j];

                if (isset($row[$j])) {
                    $sqlScript .= '"' . addslashes($row[$j]) . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
    }
    $sqlScript .= "\n";
}

if (!empty($sqlScript)) {
    // Save the SQL script to a backup file
    $backup_file_name = '../backups/db_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $fileHandler = fopen($backup_file_name, 'w+');
    $number_of_lines = fwrite($fileHandler, $sqlScript);
    fclose($fileHandler);
    echo "Database backup created successfully: " . $backup_file_name;
}
?>