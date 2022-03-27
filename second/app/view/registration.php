<form action="/?controller=Site&action=actionRegistration" method="post">
    <input type="text"      placeholder="name"      name="name"     value="<?= $_POST['name'] ?? ''; ?>""><br>
    <input type="email"     placeholder="email"     name="email"    value="<?= $_POST['email'] ?? ''; ?>"><br>
    <input type="password"  placeholder="password"  name="password" value="<?= $_POST['password'] ?? ''; ?>"><br>
    <input type="submit">

    <div class="error">
        <?= $data['view']['error'] ?? '';?>
    </div>
</form>