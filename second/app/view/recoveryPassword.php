<form action="/" method="get">
    <input type="hidden" name="controller"  value="Site">
    <input type="hidden" name="action"      value="actionRecoveryPassword">
    <input type="email"     placeholder="email"     name="email"    value="<?= $_GET['email'] ?? ''; ?>"><br>
    <input type="submit">
</form>
<div class="error">
    <?= $data['view']['error'] ?? '';?>
</div>
<?php if (isset($data['view']['code']) && $data['view']['code'] != '') : ?>
    <a href="/?controller=Site&action=actionRecoveryPassword&code=<?= $data['view']['code']; ?>">Change Password</a>
<?php endif; ?>