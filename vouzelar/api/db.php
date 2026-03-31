<?php
/**
 * Simple SQLite wrapper for VouZelar
 */

class DB {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Use a relative path to the database file in the same directory
        $dbFile = __DIR__ . '/vouzelar.sqlite';
        $dsn = "sqlite:" . $dbFile;

        try {
            // Enable WAL mode for better concurrency on SQLite
            $this->pdo = new PDO($dsn);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->exec("PRAGMA journal_mode = WAL;");
            $this->pdo->exec("PRAGMA foreign_keys = ON;");

            $this->initDb();
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die(json_encode(['error' => 'Database connection failed.']));
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    private function initDb() {
        $queries = [
            "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                family_group_id INTEGER, -- Used to link family members
                name TEXT NOT NULL,
                email TEXT UNIQUE, -- Optional for patients
                password_hash TEXT, -- Optional for patients
                role TEXT CHECK(role IN ('admin', 'caregiver', 'patient')) NOT NULL DEFAULT 'admin',
                plan TEXT CHECK(plan IN ('free', 'individual', 'family')) NOT NULL DEFAULT 'free',
                trial_ends_at DATETIME,
                subscription_status TEXT DEFAULT 'active', -- active, past_due, canceled
                mercado_pago_id TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (family_group_id) REFERENCES users(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS medications (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                patient_id INTEGER NOT NULL,
                name TEXT NOT NULL,
                dosage TEXT, -- e.g. 50mg
                frequency_hours INTEGER, -- e.g. 24 (once a day), 12 (twice a day)
                times_per_day INTEGER DEFAULT 1,
                specific_times TEXT, -- JSON array of times e.g. ['08:00', '20:00']
                stock_current INTEGER DEFAULT 0,
                stock_minimum INTEGER DEFAULT 5, -- Alert threshold
                photo_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS dose_history (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                medication_id INTEGER NOT NULL,
                patient_id INTEGER NOT NULL,
                scheduled_time DATETIME NOT NULL,
                taken_at DATETIME,
                status TEXT CHECK(status IN ('taken', 'missed', 'pending', 'postponed')) DEFAULT 'pending',
                taken_by_user_id INTEGER, -- If a caregiver gave the dose
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (medication_id) REFERENCES medications(id) ON DELETE CASCADE,
                FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS push_subscriptions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                endpoint TEXT NOT NULL UNIQUE,
                p256dh TEXT NOT NULL,
                auth TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            );",

             "CREATE TABLE IF NOT EXISTS medication_prices (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                medication_name TEXT NOT NULL UNIQUE, -- Normalized generic name
                lowest_price REAL,
                store_name TEXT,
                store_link TEXT,
                last_updated DATETIME DEFAULT CURRENT_TIMESTAMP
            );"
        ];

        foreach ($queries as $query) {
            $this->pdo->exec($query);
        }
    }
}
