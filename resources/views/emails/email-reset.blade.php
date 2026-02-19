<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; }
        .card { background-color: #ffffff; border-radius: 24px; padding: 40px; max-width: 500px; margin: auto; border: 1px solid #f3f4f6; text-align: center; }
        .button { background-color: #1e3a8a; color: #ffffff !important; padding: 18px 36px; border-radius: 16px; text-decoration: none; display: inline-block; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Restablecer Contraseña</h1>
        <p>¡Hola, <strong>{{ $user->name }}</strong>!<br>Haz clic en el botón para cambiar tu clave en La Redonda Joven.</p>
        <div style="margin: 30px 0;">
            <a href="{{ $url }}" class="button">Cambiar Contraseña</a>
        </div>
        <p style="font-size: 12px; color: #9ca3af;">Si no solicitaste este cambio, ignora este mensaje.</p>
    </div>
</body>
</html>