<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    ...
</nav>
