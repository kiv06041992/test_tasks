<form action="/?controller=Site&action=actionLogin" method="post">
    <input type="email"     placeholder="email"     name="email"    value="<?= $_POST['email'] ?? ''; ?>"><br>
    <input type="password"  placeholder="password"  name="password" value="<?= $_POST['password'] ?? ''; ?>"><br>
    <input type="submit">

    <div class="error">
        <?= $data['view']['error'] ?? '';?>
    </div>
</form>