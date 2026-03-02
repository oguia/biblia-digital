<?php
// Mock setup
require_once __DIR__ . '/../ogm/app/Core/Database.php';
require_once __DIR__ . '/../ogm/app/Core/Model.php';
require_once __DIR__ . '/../ogm/app/Models/Company.php';

// Setup Env for SQLite
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . __DIR__ . '/../ogm/tests/ogm_verification.sqlite');

try {
    $companyModel = new Company();

    // Test Case 1: Empty Query
    echo "Testing Empty Query...\n";
    $companyModel->search('');
    echo "Empty Query OK.\n";

    // Test Case 2: Text Query
    echo "Testing Text Query 'Restaurante'...\n";
    $companyModel->search('Restaurante');
    echo "Text Query OK.\n";

    // Test Case 3: Category Filter
    echo "Testing Category Filter...\n";
    $companyModel->search('', 'restaurante');
    echo "Category Filter OK.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
