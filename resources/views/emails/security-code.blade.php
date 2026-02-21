<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 20px; overflow: hidden; }
        .header { background-color: #5cb1e3; padding: 30px; text-align: center; color: white; }
        .content { padding: 40px; text-align: center; color: #334155; }
        .code { font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #1e3a8a; padding: 20px; background: #f8fafc; border-radius: 10px; display: inline-block; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header"><h1>La Redonda | Código de Validación de Cuenta</h1></div>
        <div class="content">
            <h2>Hola, {{ $userName }}</h2>
            <p>Por seguridad, ingresa este código para validar tu cuenta:</p>
            <div class="code">{{ $code }}</div>
            <p>Este código fue generado porque no has ingresado en más de una semana.</p>
        </div>
    </div>
</body>
</html>