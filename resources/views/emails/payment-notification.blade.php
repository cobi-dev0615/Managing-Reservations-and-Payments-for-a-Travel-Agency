<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #2563eb; color: #fff; padding: 20px 30px; }
        .header h1 { margin: 0; font-size: 20px; }
        .content { padding: 30px; }
        .footer { padding: 20px 30px; background: #f9fafb; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $emailSubject }}</h1>
        </div>
        <div class="content">
            {!! nl2br(e($emailBody)) !!}
        </div>
        <div class="footer">
            <p>Este e-mail foi enviado automaticamente. Por favor, nao responda diretamente.</p>
        </div>
    </div>
</body>
</html>
