<?php
$allUsers = $users->getAll();
?>

<h3>Users</h3>

<table border="1" cellpadding="5">
    <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Role</th>
        <th>Status</th>
    </tr>

    <?php foreach ($allUsers as $u): ?>
    <tr>
        <td><?= $u['full_name']; ?></td>
        <td><?= $u['username']; ?></td>
        <td><?= $u['role']; ?></td>
        <td><?= $u['status']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
