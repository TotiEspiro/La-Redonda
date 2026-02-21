<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; padding: 40px 0; background-color: #f8fafc; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .header { background-color: #1e3a8a; padding: 40px 20px; text-align: center; }
        .content { padding: 40px; text-align: center; }
        .btn { display: inline-block; padding: 16px 32px; background-color: #1e3a8a; color: #ffffff !important; text-decoration: none; border-radius: 16px; font-weight: 900; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; margin: 30px 0; box-shadow: 0 4px 6px -1px rgba(30, 58, 138, 0.2); }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; background: #f1f5f9; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1 style="color: white; margin: 0; text-transform: uppercase; font-size: 20px; letter-spacing: 2px;">La Redonda</h1>
            </div>
            <div class="content">
                <h2 style="color: #1e3a8a;">¡Bienvenido, {{ $userName }}!</h2>
                <p style="color: #475569; line-height: 1.6;">Estamos muy felices de que te sumes a nuestra comunidad. Para empezar a participar, solo necesitamos que confirmes tu dirección de correo electrónico.</p>
                
                <a href="{{ $url }}" class="btn">Verificar mi cuenta</a>

                <p style="font-size: 12px; color: #94a3b8; margin-top: 30px;">Si el botón no funciona, podés copiar y pegar este link en tu navegador:<br>{{ $url }}</p>
            </div>
            <div class="footer">
                Comunidad Parroquial Inmaculada Concepción - La Redonda
            </div>
        </div>
    </div>
</body>
</html>