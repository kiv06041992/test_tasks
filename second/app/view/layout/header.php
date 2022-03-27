<html>
    <head>
        <link href="/public/css/main.css" rel="stylesheet">
    </head>
    <body>
        <div class="menu">
            <ul>
                <li>
                    <a href="/">Index</a>
                </li>

                <?php if (!$this->isUser()) : ?>
                    <li>
                        <a href="/?controller=Site&action=actionLogin">Login</a>
                    </li>
                    <li>
                        <a href="/?controller=Site&action=actionRecoveryPassword">Rec.pass.</a>
                    </li>
                    <li>
                        <a href="/?controller=Site&action=actionRegistration">Registration</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/?controller=User&action=actionIndex">Profile</a>
                    </li>
                    <li>
                        <a href="/?controller=Site&action=actionLogout">Logout</a>
                    </li>
                <?php endif;?>
            </ul>
        </div>
        <div class="title">
            <h3>
                <?= $data['view']['title'] ?? ''; ?>
            </h3>
        </div>
