<?php
$totalUsers    = count($users->getAll());
$approvedUsers = count($users->getApprovedUsers());
$totalQuotes   = count($quotations->getAll());
?>

<h3>System Overview</h3>

<div class="cards">
    <div class="card">Total Users: <?php echo $totalUsers; ?></div>
    <div class="card">Approved Users: <?php echo $approvedUsers; ?></div>
    <div class="card">Quotations: <?php echo $totalQuotes; ?></div>
</div>
