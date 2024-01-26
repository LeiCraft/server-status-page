<?php

    class DatabaseConnection {
        private static $instance; // The single instance
        private $connection;

        private function __construct() {
            $config = require_once $_SERVER['DOCUMENT_ROOT'] . '/util/config/config.php';

            $host = $config['mysql']['host'] . ":" . $config['mysql']['port'];
            $username = $config['mysql']['user'];
            $password = $config['mysql']['password'];
            $dbname = $config['mysql']['database'];

            $this->connection = mysqli_connect($host, $username, $password, $dbname);

            $connection = $this->connection;

            $this->createHostOutageTable();
            $this->createServiceOutageTable();

            if (!$this->connection) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }
        }

        private function createHostOutageTable() {
            $stmt = mysqli_stmt_init($this->connection);
        
            $query = "CREATE TABLE IF NOT EXISTS host_outages (
                id INT AUTO_INCREMENT,
                host_id VARCHAR(255),
                created_at DATETIME,
                code INT(1) DEFAULT 1,
                message VARCHAR(255) DEFAULT NULL,
                fixed_at DATETIME DEFAULT NULL,
                primary key (id)
            );";

            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_execute($stmt);
            }
        }

        private function createServiceOutageTable() {
            $stmt = mysqli_stmt_init($this->connection);
        
            $query = "CREATE TABLE IF NOT EXISTS service_outages (
                id INT AUTO_INCREMENT,
                host_id VARCHAR(255),
                created_at DATETIME,
                service_name VARCHAR(255),
                code INT(1) DEFAULT 1,
                message VARCHAR(255) DEFAULT NULL,
                fixed_at DATETIME DEFAULT NULL,
                primary key (id)
            );";

            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_execute($stmt);
            }
        }

        // Get the single instance of the database connection
        public static function getInstance() {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        // Get the database connection
        public function getConnection() {
            return $this->connection;
        }
        
        // Prevent cloning of the Singleton instance
        private function __clone() { }

        // Prevent unserialization of the Singleton instance
        private function __wakeup() { }
        
    }

    function getHostOutages($hosts) {
        try {
            $stmt = mysqli_stmt_init(DatabaseConnection::getInstance()->getConnection());
    
            // Query to fetch data for the last 30 days, sorted by time in descending order
            $query = "SELECT * FROM host_outages WHERE created_at >= UTC_DATE() - INTERVAL 89 DAY ORDER BY created_at DESC";
    
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
    
                $outages = [];

                foreach ($hosts as $host_id) {
                    $outages[$host_id] = [];
                }
    
                while ($row = mysqli_fetch_assoc($result)) {
                    $outages[$row['host_id']][] = $row;
                }
    
                mysqli_stmt_close($stmt);
    
                return $outages;
            } else {
                throw new Exception("Statement prepare failed");
                return "error";
            }
        } catch (Exception $e) {
            return "error: " . $e->getMessage();
        }
    }     

    function addHostOutage() {

    }

?>
