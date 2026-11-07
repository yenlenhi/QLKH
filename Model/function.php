<?php

function writeActivityLog($pdo, $userId, $actionType, $targetObject, $objectId, $description) {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $sql = "INSERT INTO tbl_activity_logs (user_id, action_type, target_object, object_id, description, ip_address, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $actionType, $targetObject, $objectId, $description, $ip]);
    } catch (Exception $e) {
        error_log("Log Error: " . $e->getMessage());
    }
}