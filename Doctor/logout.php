<?php
// Start the session
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page with success message
echo "<script>
    alert('You have been logged out successfully!');
    window.location.href = '../index.php';
</script>";

exit();
?>