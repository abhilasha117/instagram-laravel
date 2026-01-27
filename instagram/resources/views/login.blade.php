<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Dark - Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ig-dark-bg: #121212;
            --ig-card-bg: #1e1e1e;
            --ig-text-primary: #ffffff;
            --ig-text-secondary: #a8a8a8;
            --ig-primary: #0095f6;
            --ig-primary-hover: #1877f2;
            --ig-border: #363636;
            --ig-error: #ed4956;
        }
        
        body {
            background-color: var(--ig-dark-bg);
            color: var(--ig-text-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .instagram-logo {
            font-family: 'Cookie', cursive;
            font-size: 3.5rem;
            font-weight: normal;
            margin: 10px 0 20px;
        }
        
        .login-card {
            background-color: var(--ig-card-bg);
            border: 1px solid var(--ig-border);
            border-radius: 8px;
            padding: 30px 40px;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .form-control {
            background-color: var(--ig-dark-bg);
            border: 1px solid var(--ig-border);
            color: var(--ig-text-primary);
            padding: 12px;
            font-size: 14px;
        }
        
        .form-control:focus {
            background-color: var(--ig-dark-bg);
            border-color: var(--ig-text-secondary);
            color: var(--ig-text-primary);
            box-shadow: none;
        }
        
        .form-control::placeholder {
            color: var(--ig-text-secondary);
        }
        
        .btn-primary {
            background-color: var(--ig-primary);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 10px;
            margin-top: 10px;
            width: 100%;
        }
        
        .btn-primary:hover {
            background-color: var(--ig-primary-hover);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: var(--ig-text-secondary);
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--ig-border);
        }
        
        .divider span {
            padding: 0 15px;
            font-size: 13px;
            font-weight: 600;
        }
        
        .facebook-login {
            color: #3797EF;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .facebook-login i {
            font-size: 18px;
            margin-right: 8px;
        }
        
        .forgot-password {
            color: var(--ig-text-secondary);
            text-decoration: none;
            font-size: 12px;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        
        .signup-card {
            background-color: var(--ig-card-bg);
            border: 1px solid var(--ig-border);
            border-radius: 8px;
            padding: 20px;
            max-width: 400px;
            margin: 15px auto;
            text-align: center;
            font-size: 14px;
        }
        
        .signup-card a {
            color: var(--ig-primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .app-download {
            text-align: center;
            max-width: 400px;
            margin: 20px auto 0;
        }
        
        .app-download p {
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .app-stores img {
            height: 40px;
            margin: 0 5px;
        }
        
        .footer {
            max-width: 800px;
            margin: 40px auto 0;
            text-align: center;
            font-size: 12px;
            color: var(--ig-text-secondary);
        }
        
        .footer-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .footer-links a {
            color: var(--ig-text-secondary);
            text-decoration: none;
            margin: 0 8px 10px;
        }
        
        .error-message {
            color: var(--ig-error);
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }
        
        .input-group {
            margin-bottom: 15px;
        }
        
        .input-group-text {
            background-color: var(--ig-dark-bg);
            border: 1px solid var(--ig-border);
            border-right: none;
            color: var(--ig-text-secondary);
        }
        
        @media (max-width: 450px) {
            .login-card, .signup-card {
                background-color: transparent;
                border: none;
                padding: 20px;
            }
            
            body {
                padding-top: 0;
            }
        }
        
        .username-note {
            font-size: 12px;
            color: var(--ig-text-secondary);
            margin-top: 5px;
            text-align: center;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="login-card">
    <h1 class="instagram-logo text-center">Instagram</h1>

    @if(session('error'))
        <div class="alert alert-danger text-center py-2" style="font-size: 14px; background: #1a0a0a; border-color: #ed4956; color: #ed4956;">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

   <form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" name="username" class="form-control" placeholder="Username" autocomplete="username" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>

    @if($errors->has('username'))
        <p style="color:red">{{ $errors->first('username') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif
</form>
    </div>
</div>

</body>
</html>
