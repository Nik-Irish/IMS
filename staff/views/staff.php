<?php
/**
 * views/staff.php - Manage staff accounts
 *
 * @var array $staffUsers
 * @var array|null $editStaff
 */
$staffCount = count($staffUsers);
$staffCountLabel = $staffCount === 1 ? 'account' : 'accounts';
?>
<div class="staff-management">
<!-- ═══════ Add Staff ═══════ -->
<div class="form-card add-staff-card">
    <div class="staff-card-heading">
        <h2>Add Staff User</h2>
    </div>
    <p class="form-hint">
        Username: 3-15 letters and numbers.
        Password: 8+ characters with uppercase, lowercase, number, and symbol.
    </p>
    <form method="POST" action="dashboard.php?view=staff">
        <input type="hidden" name="action" value="staff_create">
        <div class="form-grid">
            <div class="form-group">
                <label>Username <span class="req">*</span></label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password <span class="req">*</span></label>
                <div class="pw-input-wrap">
                    <input type="password" name="password" id="staff-password-add" required>
                    <button type="button" class="pw-toggle" data-target="staff-password-add">Show</button>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Staff</button>
        </div>
    </form>
</div>

<!-- ═══════ Staff list ═══════ -->
<section class="table-wrap staff-directory">
    <div class="staff-directory-heading">
        <div>
            <h2>Staff accounts</h2>
        </div>
        <span class="staff-count">
            <?php echo $staffCount; ?>
            <span><?php echo $staffCountLabel; ?></span>
        </span>
    </div>
    <?php if (empty($staffUsers)): ?>
        <div class="empty-state staff-empty-state">
            <p>No staff accounts yet.</p>
            <span>Add a staff account using the form above.</span>
        </div>
    <?php else: ?>
        <table class="data-table staff-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($staffUsers as $st): $sid = (int)$st['id']; ?>
                <?php $isEditing = !empty($editStaff) && (int)$editStaff['id'] === $sid; ?>

                <?php if ($isEditing): ?>
                    <!-- ── Inline edit row ── -->
                    <tr class="editing-row">
                        <td><?php echo (int)$st['staff_number']; ?></td>
                        <td colspan="3">
                            <form method="POST" action="dashboard.php?view=staff"
                                  class="inline-edit-form">
                                <input type="hidden" name="action" value="staff_update">
                                <input type="hidden" name="id" value="<?php echo $sid; ?>">

                                <label class="edit-label">Username</label>
                                <input type="text" name="username"
                                       value="<?php echo htmlspecialchars($st['username']); ?>"
                                       class="edit-input edit-username" required>

                                <label class="edit-label">New password
                                    <span class="edit-optional">(leave blank to keep)</span>
                                </label>
                                <div class="pw-input-wrap edit-pw-wrap">
                                    <input type="password" name="password"
                                           id="staff-password-edit-<?php echo $sid; ?>"
                                           class="edit-input">
                                    <button type="button" class="pw-toggle"
                                            data-target="staff-password-edit-<?php echo $sid; ?>">Show</button>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                <a href="dashboard.php?view=staff" class="btn btn-sm btn-ghost">Cancel</a>
                            </form>
                        </td>
                    </tr>

                <?php else: ?>
                    <!-- ── Staff row ── -->
                    <tr>
                        <td><span class="staff-number"><?php echo (int)$st['staff_number']; ?></span></td>
                        <td class="staff-username">
                            <?php echo htmlspecialchars($st['username']); ?>
                        </td>
                        <td class="staff-added">
                            <?php echo !empty($st['created_at'])
                                ? htmlspecialchars(date('Y-m-d', strtotime($st['created_at'])))
                                : '-'; ?>
                        </td>
                        <td class="row-actions">
                            <a href="dashboard.php?view=staff&amp;edit=<?php echo $sid; ?>"
                               class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" class="inline-form"
                                  data-confirm="<?php echo htmlspecialchars('Delete staff user "' . $st['username'] . '"?', ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="action" value="staff_delete">
                                <input type="hidden" name="id" value="<?php echo $sid; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>

            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
</div>
