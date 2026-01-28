<?php
/**
 * @var bool $has_errors
 * @var bool $is_latest
 * @var string $latest_version
 * @var bool $gcaptcha_enabled
 * @var array $config
 * @var $validation
 */
?>

<!doctype html>
<html lang="<?= current_language_code() ?>">

<head>
    <meta charset="utf-8">
    <base href="<?= base_url() ?>">
    <title><?= $config['company'] . ' | ' . ' | ' .  lang('Login.login') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    
    <!-- Tailwind CSS Configuration -->
    <script>
        window.tailwind = {
            config: {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'Inter', 'system-ui', 'sans-serif'],
                        },
                        fontSize: {
                            'sm': '0.9375rem',
                            'xs': '0.8125rem',
                        }
                    }
                }
            }
        };
    </script>
    
    <!-- Local Tailwind CSS -->
    <script src="resources/js/tailwind-cdn.js"></script>
    
    <!-- Local Lucide Icons -->
    <script src="resources/js/lucide.min.js"></script>
    
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-wrapper">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="brand-container">
                <h1 class="brand-name">Anis <span class="brand-accent">Rose</span></h1>
                <p class="brand-tagline">Modern Point of Sale System</p>
            </div>
            
            <div class="decorative-elements">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
                <div class="circle circle-3"></div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-container">
                <div class="login-header">
                    <h2 class="login-title"><?= lang('Login.welcome', ['']) ?></h2>
                    <p class="login-subtitle">Sign in to continue to your account</p>
                </div>

                <?= form_open('login', ['class' => 'login-form']) ?>
                
                <?php if ($has_errors): ?>
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <div class="alert alert-error">
                            <i data-lucide="alert-circle"></i>
                            <span><?= $error ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (!$is_latest): ?>
                    <div class="alert alert-info">
                        <i data-lucide="info"></i>
                        <span><?= lang('Login.migration_needed', [$latest_version]) ?></span>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="input-username" class="form-label">
                        <i data-lucide="user"></i>
                        <?= lang('Login.username') ?>
                    </label>
                    <input 
                        type="text" 
                        id="input-username" 
                        name="username" 
                        class="form-input" 
                        placeholder="Enter your username"
                        <?php if (ENVIRONMENT == "testing") echo 'value="admin"'; ?>
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="input-password" class="form-label">
                        <i data-lucide="lock"></i>
                        <?= lang('Login.password') ?>
                    </label>
                    <input 
                        type="password" 
                        id="input-password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter your password"
                        <?php if (ENVIRONMENT == "testing") echo 'value="pointofsale"'; ?>
                    >
                </div>

                <?php
                if ($gcaptcha_enabled) {
                    echo '<div class="recaptcha-container">';
                    echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
                    echo '<div class="g-recaptcha" data-sitekey="' . $config['gcaptcha_site_key'] . '"></div>';
                    echo '</div>';
                }
                ?>

                <button type="submit" name="login-button" class="btn-submit">
                    <span><?= lang('Login.go') ?></span>
                    <i data-lucide="arrow-right"></i>
                </button>

                <?= form_close() ?>

                <div class="login-footer">
                    <p>&copy; <?= date('Y') ?> <?= $config['company'] ?>. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
