<?php
$file = "online_log.txt"; 
$timeout = 300; // 5 minutes in seconds
$ip = $_SERVER['REMOTE_ADDR'];
$time = time();

// 1. Read existing active users from the file
$data = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$active_users = [];

// 2. Filter out old sessions and the current user's old entry
foreach ($data as $line) {
    list($stored_ip, $stored_time) = explode("|", $line);
    if ($time - $stored_time < $timeout && $stored_ip != $ip) {
        $active_users[] = "$stored_ip|$stored_time";
    }
}

// 3. Add current user with fresh timestamp
$active_users[] = "$ip|$time";

// 4. Save updated list back to the file
file_put_contents($file, implode("\n", $active_users), LOCK_EX);

// 5. Output the result
echo count($active_users);
?>
