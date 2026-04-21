<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRM Panel - Log In</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/all.min.css">

    <style>
        :root {
            --cp-orange: #ff6c2c;
            --cp-dark: #1e293b;
            --cp-bg: #f1f5f9;
            --cp-border: #e2e8f0;
            --cp-text: #64748b;
            --primary-blue: #2563eb;
        }

        body {
            background-color: var(--cp-bg);
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* login container animation */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cp-login-container {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--cp-border);
            overflow: hidden;
            animation: fadeInDown 0.6s ease-out;
        }

        .cp-header {
            background-color: var(--cp-dark);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .cp-logo-text {
            color: #fff;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: -0.5px;
        }

        .cp-logo-text span {
            color: var(--cp-orange);
            font-weight: 900;
        }

        .cp-subtitle {
            color: #94a3b8;
            font-size: 14px;
            margin-top: 10px;
            font-weight: 400;
        }

        .cp-body {
            padding: 40px 35px;
        }

        .cp-alert {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 12px 16px;
            margin-bottom: 25px;
            font-size: 14px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .input-group-label {
            font-weight: 600;
            color: #334155;
            font-size: 13px;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cp-input-wrapper {
            position: relative;
            margin-bottom: 25px;
        }

        .cp-input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
            z-index: 10;
        }

        .form-control {
            height: 52px;
            border: 1px solid var(--cp-border);
            border-radius: 8px;
            padding: 10px 15px 10px 45px;
            font-size: 15px;
            transition: all 0.3s ease;
            color: #1e293b;
            background-color: #f8fafc;
        }

        .form-control:focus {
            border-color: var(--cp-orange);
            box-shadow: 0 0 0 4px rgba(255, 108, 44, 0.15);
            background-color: #fff;
            outline: none;
        }

        .btn-cp-login {
            background-color: var(--cp-orange);
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            font-weight: 700;
            font-size: 15px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(255, 108, 44, 0.3);
            margin-top: 5px;
        }

        .btn-cp-login:hover {
            background-color: #ea580c;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(255, 108, 44, 0.4);
        }

        .btn-cp-login:active {
            transform: translateY(0);
        }

        .cp-footer {
            padding: 40px 20px;
            text-align: center;
            color: var(--cp-text);
            font-size: 13px;
        }

        .lang-list {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .lang-list span {
            cursor: pointer;
            color: var(--primary-blue);
            font-weight: 500;
            transition: color 0.2s;
        }

        .lang-list span:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .copyright-text {
            border-top: 1px solid var(--cp-border);
            padding-top: 25px;
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .brand-footer {
            font-weight: 700;
            color: #334155;
        }

        .version-tag {
            background: #e2e8f0;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            margin-top: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="cp-login-container">
        
        <div class="cp-header">
            <h1 class="cp-logo-text">
                <span>SRM</span> Pannel
            </h1>
            <div class="cp-subtitle">Secure Administration Login</div>
        </div>

        <div class="cp-body">
            <?php if(isset($e) && $e): ?>
            <div class="cp-alert">
                <i class="fas fa-circle-exclamation"></i>
                <span><strong>Invalid:</strong> <?= htmlspecialchars($e) ?></span>
            </div>
            <?php endif; ?>

            <form method="POST">
                
                <div class="mb-4">
                    <label class="input-group-label">Administrator Password</label>
                    <div class="cp-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="••••••••••••" 
                               required 
                               autofocus>
                    </div>
                </div>

                <button type="submit" 
                        name="<?= (isset($t) && $t=='Initial Setup' ? 'setup_pass' : 'login') ?>" 
                        class="btn-cp-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Log in
                </button>

            </form>
            
            <div style="text-align: center; margin-top: 25px;">
                <a href="#" style="color: var(--primary-blue); text-decoration: none; font-size: 14px; font-weight: 500;">
                    <i class="fas fa-key me-1"></i> Reset Password
                </a>
            </div>
        </div>
    </div>
</div>

<div class="cp-footer">
    <div class="lang-list">
        <span>English</span> • <span>Bengali</span> • <span>Spanish</span> • <span>German</span> • <span>More...</span>
    </div>
    
    <div class="copyright-text">
        Copyright © <?=date('Y')?> <span class="brand-footer">SRM Panel by OTTKING</span>.<br>
        All rights reserved. <br>
        <span class="version-tag">BUILD VERSION: 11.106.0.11</span>
    </div>
</div>

</body>
</html>