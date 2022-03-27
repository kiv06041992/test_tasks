<form action="/?controller=User&action=actionChangePassword" method="post">
    <input type="password" name="newPassword" placeholder="newPassword" ><br>
    <input type="password" name="newPasswordRepeat" placeholder="newPasswordRepeat" ><br>
    <input type="submit">
</form>
<div class="error">
    <?= $data['view']['error'] ?? '';?>
</div>