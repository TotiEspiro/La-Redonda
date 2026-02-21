<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f8fafc; 
            margin: 0; 
            padding: 0; 
            -webkit-text-size-adjust: 100%;
        }
        .wrapper { 
            width: 100%; 
            padding: 40px 0; 
            background-color: #f8fafc; 
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: #ffffff; 
            border-radius: 24px; 
            overflow: hidden; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); 
        }
        .header { 
            background-color: #5cb1e3; 
            padding: 40px 30px; 
            text-align: center; 
        }
        .content { 
            padding: 40px; 
            text-align: center; 
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 120px;
            height: auto;
            display: inline-block;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 18px;
            line-height: 1.4;
            font-weight: 600;
        }
        .content h2 {
            color: white;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 800;
        }
        .content p {
            color: #475569;
            line-height: 1.8;
            font-size: 15px;
        }
        .btn { 
            display: inline-block; 
            padding: 18px 36px; 
            background-color: #1e3a8a; 
            color: #ffffff !important; 
            text-decoration: none; 
            border-radius: 18px; 
            font-weight: 900; 
            font-size: 14px; 
            margin: 30px 0; 
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2); 
        }
        .footer { 
            padding: 30px; 
            text-align: center; 
            font-size: 11px; 
            color: #94a3b8; 
            background: #f1f5f9; 

        }
        .link-alt {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 20px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="logo">
                    <img src="{{ asset('img/logo_redonda.png') }}" alt="La Redonda">
                </div>
                <h1>La Redonda<br><span style="font-size: 13px; opacity: 0.9;">Inmaculada Concepción de Belgrano</span></h1>
            </div>
            
            <div class="content">
                <h2>¡Hola, {{ $userName }}!</h2>
                <p>Estamos muy felices de que te sumes a nuestra comunidad parroquial. Para completar tu registro y asegurar tu cuenta, solo necesitamos que confirmes tu dirección de correo electrónico.</p>
                
                <a href="{{ $url }}" class="btn">Verificar mi Cuenta</a>

                <p class="link-alt">Si el botón no funciona, podés copiar y pegar este link en tu navegador:<br>
                <span style="color: #5cb1e3;">{{ $url }}</span></p>
            </div>
            
            <div class="footer">
                © 2026 La Redonda | Parroquia Inmaculada Concepción de Belgrano <br>
                Buenos Aires, Argentina
            </div>
        </div>
    </div>
</body>
</html>