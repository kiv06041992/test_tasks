<table>
    <tr>
        <td>User Name: </td>
        <td><?= $data['view']['user']['name'] ?? ''; ?></td>
    </tr>
    <tr>
        <td>Email: </td>
        <td><?= $data['view']['user']['email'] ?? ''; ?></td>
    </tr>
    <tr>
        <td>Change Password: </td>
        <td>
            <a href="/?controller=User&action=actionChangePassword">link</a>
        </td>
    </tr>
</table>