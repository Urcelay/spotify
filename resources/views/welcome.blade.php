<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        /* Botones de navegación superior */
        .nav-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 15px;
        }

        .nav-buttons a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .nav-buttons a:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .welcome-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .version {
            margin-top: 20px;
            font-size: 0.9em;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Botones de autenticación -->
    @auth
        <div class="nav-buttons">
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </div>
    @else
        <div class="nav-buttons">
            <a href="{{ route('login') }}">Log in</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    @endauth

    <div class="welcome-container">
        <h1>¡Bienvenido a Laravel! 🎉</h1>
        <p>Tu aplicación está funcionando correctamente.</p>
        <p><strong>¡Felicidades por tu primer proyecto!</strong></p>
        <div class="version">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </div>
    </div>
</body>
</html>
