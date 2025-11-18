<?php
function addLog($conn, $user_id, $action) {
  $user_id = intval($user_id);
  $action = mysqli_real_escape_string($conn, $action);
  mysqli_query($conn, "INSERT INTO logs (user_id, action, created_at) VALUES ('$user_id', '$action', NOW())");
}
?>
