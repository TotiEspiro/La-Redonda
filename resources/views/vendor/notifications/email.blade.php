<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - La Redonda</title>
    <style>
        /* Estilos base para clientes de correo */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            width: 100% !important;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            width: 80px;
            height: auto;
            border-radius: 50%;
        }
        h1 {
            color: #111827;
            font-size: 24px;
            font-weight: 800;
            text-align: center;
            margin: 0 0 16px 0;
            text-transform: uppercase;
            letter-spacing: -0.025em;
        }
        p {
            color: #4b5563;
            font-size: 16px;
            line-height: 24px;
            text-align: center;
            margin: 0 0 30px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            background-color: #1e3a8a;
            color: #ffffff !important;
            padding: 18px 36px;
            border-radius: 16px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #9ca3af;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .divider {
            border-top: 1px solid #f3f4f6;
            margin: 30px 0;
        }
        .small-text {
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
            line-height: 18px;
        }
        .link-text {
            word-break: break-all;
            color: #1e3a8a;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo de la Parroquia -->
        <div class="logo">
            <img src="{{ asset('img/logo_redonda.png') }}" alt="La Redonda Joven">
        </div>

        <!-- Contenido Principal -->
        <div class="card">
            <h1>Restablecer Contraseña</h1>
            
            <p>
                ¡Hola, <strong>{{ $notifiable->name }}</strong>!<br>
                Recibimos una solicitud para cambiar tu contraseña en la comunidad de <strong>La Redonda</strong>.
            </p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">Cambiar Contraseña</a>
            </div>

            <p>
                Este enlace expirará en 60 minutos.<br>
                Si no solicitaste este cambio, puedes ignorar este mensaje con total tranquilidad. Tu cuenta sigue segura.
            </p>
            
            <div class="divider"></div>
            
            <!-- Link de respaldo -->
            <p class="small-text">
                Si tienes problemas con el botón, copia y pega este enlace en tu navegador:<br>
                <a href="{{ $url }}" class="link-text">{{ $url }}</a>
            </p>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            &copy; {{ date('Y') }} La Redonda Joven. Nos vemos pronto en la parroquia.
        </div>
    </div>
</body>
</html>