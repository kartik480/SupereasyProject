<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="password"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }
        .navigation-buttons {
            margin-top: 20px;
            text-align: center;
        }
        .navigation-buttons a {
            display: inline-block;
            margin: 0 10px;
            padding: 8px 16px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .navigation-buttons a:hover {
            background-color: #5a6268;
        }
        .navigation-buttons a.btn {
            background-color: #007bff;
            width: auto;
            padding: 8px 16px;
        }
        .navigation-buttons a.btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        
        @if (!$isAuthenticated)
            <div style="background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ffeaa7;">
                <strong>Notice:</strong> You need to be logged in to change your password. Please <a href="{{ route('login') }}" style="color: #856404;">login first</a>.
            </div>
        @else
            <div style="background-color: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #bee5eb;">
                <strong>Ready:</strong> You are logged in and can change your password.
            </div>
        @endif
        
        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('change-password') }}">
            @csrf
            
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password:</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>
            
            <button type="submit" class="btn">Change Password</button>
        </form>

        <div class="navigation-buttons">
            <a href="{{ route('home') }}">Go to Home</a>
            <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
</body>
</html>
