<?php error_reporting(E_ALL); ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');
$pacth_url	= 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '';

// -------------- MongoDB Atlas Connection ------------
$MONGO_URI = 'mongodb+srv://bejir:appsiqarnich@siqar.f2zyp1r.mongodb.net/?retryWrites=true&w=majority&appName=SIQAR';
$MONGO_DB = 'siqar_db'; // Nama database di MongoDB Atlas

// Define constants for compatibility
@define("MONGO_URI", $MONGO_URI);
@define("MONGO_DB", $MONGO_DB);

// Legacy MariaDB constants (kept for compatibility)
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASSWD = '';
$DB_NAME = 'siqar_db';
@define("DB_HOST", $DB_HOST);
@define("DB_USER", $DB_USER);
@define("DB_PASSWD", $DB_PASSWD);
@define("DB_NAME", $DB_NAME);

// Create MongoDB connection
try {
    // Create MongoDB client
    $mongo_client = new MongoDB\Client($MONGO_URI);
    $mongo_db = $mongo_client->selectDatabase($MONGO_DB);
    
    // Create a wrapper class for backward compatibility
    class MongoDBWrapper {
        private $mongo_client;
        private $mongo_db;
        public $connect_error = false;
        
        public function __construct($client, $db) {
            $this->mongo_client = $client;
            $this->mongo_db = $db;
        }
        
        public function query($query) {
            // Parse the SQL query to determine what MongoDB operation to perform
            if (stripos($query, "SELECT") !== false) {
                return $this->handleSelect($query);
            } else if (stripos($query, "INSERT") !== false) {
                return $this->handleInsert($query);
            } else if (stripos($query, "UPDATE") !== false) {
                return $this->handleUpdate($query);
            } else if (stripos($query, "DELETE") !== false) {
                return $this->handleDelete($query);
            }
            return null;
        }
        
        private function handleSelect($query) {
            // Simple SQL parser for SELECT queries
            preg_match('/SELECT\s+(.*?)\s+FROM\s+(\w+)(?:\s+WHERE\s+(.*?))?(?:\s+LIMIT\s+(\d+))?/is', $query, $matches);
            
            $fields = $matches[1] ?? '*';
            $table = $matches[2] ?? '';
            $where = $matches[3] ?? '';
            $limit = isset($matches[4]) ? (int)$matches[4] : 0;
            
            // Convert SQL WHERE to MongoDB filter
            $filter = [];
            if ($where) {
                // Simple WHERE clause parser for basic conditions
                // This is a simplified implementation that handles common cases
                if (strpos($where, '=') !== false) {
                    $parts = explode('=', $where);
                    $field = trim($parts[0]);
                    $value = trim($parts[1], " '\"");
                    $filter[$field] = $value;
                }
            }
            
            // Create result wrapper
            $options = [];
            if ($limit > 0) {
                $options['limit'] = $limit;
            }
            
            $collection = $this->mongo_db->$table;
            $cursor = $collection->find($filter, $options);
            
            return new MongoDBResult($cursor);
        }
        
        private function handleInsert($query) {
            // Basic parser for INSERT queries
            preg_match('/INSERT\s+INTO\s+(\w+)\s+\((.*?)\)\s+VALUES\s+\((.*?)\)/is', $query, $matches);
            
            $table = $matches[1] ?? '';
            $fields = explode(',', $matches[2] ?? '');
            $values = explode(',', $matches[3] ?? '');
            
            $doc = [];
            foreach ($fields as $i => $field) {
                $field = trim($field);
                $value = trim($values[$i], " '\"");
                $doc[$field] = $value;
            }
            
            $collection = $this->mongo_db->$table;
            $result = $collection->insertOne($doc);
            
            return new MongoDBInsertResult($result);
        }
        
        private function handleUpdate($query) {
            // Basic parser for UPDATE queries
            preg_match('/UPDATE\s+(\w+)\s+SET\s+(.*?)\s+WHERE\s+(.*)/is', $query, $matches);
            
            $table = $matches[1] ?? '';
            $set_clause = $matches[2] ?? '';
            $where = $matches[3] ?? '';
            
            // Parse SET clause
            $update = [];
            $set_parts = explode(',', $set_clause);
            foreach ($set_parts as $part) {
                if (strpos($part, '=') !== false) {
                    $kv = explode('=', $part);
                    $field = trim($kv[0]);
                    $value = trim($kv[1], " '\"");
                    $update[$field] = $value;
                }
            }
            
            // Parse WHERE clause
            $filter = [];
            if (strpos($where, '=') !== false) {
                $parts = explode('=', $where);
                $field = trim($parts[0]);
                $value = trim($parts[1], " '\"");
                $filter[$field] = $value;
            }
            
            $collection = $this->mongo_db->$table;
            $result = $collection->updateMany($filter, ['$set' => $update]);
            
            return new MongoDBUpdateResult($result);
        }
        
        private function handleDelete($query) {
            // Basic parser for DELETE queries
            preg_match('/DELETE\s+FROM\s+(\w+)\s+WHERE\s+(.*)/is', $query, $matches);
            
            $table = $matches[1] ?? '';
            $where = $matches[2] ?? '';
            
            // Parse WHERE clause
            $filter = [];
            if (strpos($where, '=') !== false) {
                $parts = explode('=', $where);
                $field = trim($parts[0]);
                $value = trim($parts[1], " '\"");
                $filter[$field] = $value;
            }
            
            $collection = $this->mongo_db->$table;
            $result = $collection->deleteMany($filter);
            
            return new MongoDBDeleteResult($result);
        }
    }
    
    // Result wrapper for MongoDB queries
    class MongoDBResult {
        private $cursor;
        private $current = null;
        
        public function __construct($cursor) {
            $this->cursor = $cursor;
        }
        
        public function fetch_assoc() {
            if (!$this->current) {
                $this->current = $this->cursor->toArray();
            }
            
            if (count($this->current) > 0) {
                $doc = array_shift($this->current);
                $doc = (array)$doc;
                return $doc;
            }
            
            return null;
        }
        
        public function num_rows() {
            return $this->cursor->count();
        }
    }
    
    // Insert result wrapper
    class MongoDBInsertResult {
        private $result;
        
        public function __construct($result) {
            $this->result = $result;
        }
        
        public function affected_rows() {
            return $this->result->getInsertedCount();
        }
        
        public function insert_id() {
            return (string)$this->result->getInsertedId();
        }
    }
    
    // Update result wrapper
    class MongoDBUpdateResult {
        private $result;
        
        public function __construct($result) {
            $this->result = $result;
        }
        
        public function affected_rows() {
            return $this->result->getModifiedCount();
        }
    }
    
    // Delete result wrapper
    class MongoDBDeleteResult {
        private $result;
        
        public function __construct($result) {
            $this->result = $result;
        }
        
        public function affected_rows() {
            return $this->result->getDeletedCount();
        }
    }
    
    // Create wrapper instance that mimics MySQLi for compatibility
    $connection = new MongoDBWrapper($mongo_client, $mongo_db);
    
    // Fetch site configuration
    $collection = $mongo_db->sw_site;
    $site_doc = $collection->findOne([], ['limit' => 1]);
    
    if ($site_doc) {
        // Extract fields to variables
        foreach ($site_doc as $key => $value) {
            if ($key !== '_id') {
                $$key = $value;
            }
        }
    } else {
        // Handling case where site config doesn't exist yet
        // You might want to run migration code here
    }
    
} catch (Exception $e) {
    echo 'Gagal koneksi ke database MongoDB: ' . $e->getMessage();
}

// Base URL function (unchanged)
if (!function_exists('base_url')) {
    function base_url($atRoot = FALSE, $atCore = FALSE, $parse = FALSE)
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];
            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf($tmplt, $http, $hostname, $end);
        } else $base_url = 'http://localhost/';
        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }
        return $base_url;
    }
}
$base_url = base_url();