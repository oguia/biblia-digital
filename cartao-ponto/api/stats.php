<?php
// api/stats.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require 'db.php';

$user = getAuthUser($pdo);
if (!$user) {
    sendJson(['error' => 'Unauthorized'], 401);
}

// Get all completed entries for calculations
$stmt = $pdo->prepare("
    SELECT t.*, p.rate_type, p.rate_amount, p.expected_hours
    FROM time_entries t
    LEFT JOIN projects p ON t.project_id = p.id
    WHERE t.user_id = ? AND t.status = 'completed'
    ORDER BY t.start_time DESC
");
$stmt->execute([$user['id']]);
$entries = $stmt->fetchAll();

$totalEarnings = 0;
$totalSecondsWorked = 0;
$todaySecondsWorked = 0;
$todayEarnings = 0;

$today = date('Y-m-d');

foreach ($entries as $entry) {
    $start = strtotime($entry['start_time']);
    $end = strtotime($entry['end_time']);

    // Safety check if dates are malformed
    if (!$start || !$end) continue;

    $pause = intval($entry['total_pause_seconds']);
    $durationSeconds = ($end - $start) - $pause;

    if ($durationSeconds < 0) $durationSeconds = 0; // Prevent negative time

    $totalSecondsWorked += $durationSeconds;

    $entryDate = date('Y-m-d', $start);
    $isToday = ($entryDate === $today);

    if ($isToday) {
        $todaySecondsWorked += $durationSeconds;
    }

    // Earnings Calculation
    $earnings = 0;
    if (isset($entry['rate_type']) && $entry['rate_amount'] > 0) {
        $hoursWorked = $durationSeconds / 3600;

        switch ($entry['rate_type']) {
            case 'hourly':
                $earnings = $hoursWorked * floatval($entry['rate_amount']);
                break;
            case 'daily':
                // Daily rate / expected daily hours = derived hourly rate
                $expected = floatval($entry['expected_hours']) > 0 ? floatval($entry['expected_hours']) : 8;
                $derivedHourly = floatval($entry['rate_amount']) / $expected;
                $earnings = $hoursWorked * $derivedHourly;
                break;
            case 'monthly':
                // Monthly rate / expected monthly hours = derived hourly rate
                $expected = floatval($entry['expected_hours']) > 0 ? floatval($entry['expected_hours']) : 220;
                $derivedHourly = floatval($entry['rate_amount']) / $expected;
                $earnings = $hoursWorked * $derivedHourly;
                break;
        }
    }

    $totalEarnings += $earnings;
    if ($isToday) {
        $todayEarnings += $earnings;
    }
}

sendJson([
    'total_seconds' => $totalSecondsWorked,
    'total_earnings' => round($totalEarnings, 2),
    'today_seconds' => $todaySecondsWorked,
    'today_earnings' => round($todayEarnings, 2),
]);
