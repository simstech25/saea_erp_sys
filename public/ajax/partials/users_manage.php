<?php
$usersList = $users->getAll();
?>

<h2>User Management</h2>

<table class="users-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usersList as $u): ?>
        <tr id="user-row-<?= $u['id'] ?>">
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>

            <td>
                <select onchange="changeRole(<?= $u['id'] ?>, this.value)">
                    <option value="user" <?= $u['role']=='user'?'selected':'' ?>>User</option>
                    <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Admin</option>
                </select>
            </td>

            <td><?= ucfirst($u['status']) ?></td>

            <td>
                <?php if ($u['status'] !== 'approved'): ?>
                    <button onclick="approveUser(<?= $u['id'] ?>)">Approve</button>
                <?php else: ?>
                    ✓ Approved
                <?php endif; ?>

                <button onclick="editUser(<?= $u['id'] ?>)">Edit</button>
                <button onclick="deleteUser(<?= $u['id'] ?>)">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
