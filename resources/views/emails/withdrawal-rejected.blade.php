<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 25px 0;">
                <img src="{{ asset('images/logo.png') }}" style="border: none; max-width: 150px;">
            </td>
        </tr>
        <tr>
            <td style="padding: 35px; background-color: #FFF; font-family: Arial, Helvetica, sans-serif; color: #2F3133;">
                <h1 style="font-size: 17px;">Olá, {{ $fullname }}.</h1>
                <p style="color: #74787E; font-size: 16px; line-height: 1.5em;">
                    O seu pedido de levantamento no valor de <strong>{{ $amount }}</strong> foi rejeitado.
                </p>
                <div style="background-color: #FEF2F2; border-radius: 8px; padding: 20px; margin: 24px 0; color: #991B1B;">
                    <strong>Motivo informado:</strong>
                    <p style="margin-bottom: 0; white-space: pre-line;">{{ $reason }}</p>
                </div>
                <p style="color: #74787E; font-size: 16px; line-height: 1.5em;">
                    Entre em contacto com o suporte caso precise de mais informações.
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 35px; text-align: center; font: 12px Arial, Helvetica, sans-serif; color: #74787E;">
                &copy; {{ date('Y') }} {{ $title_site }}
            </td>
        </tr>
    </table>
</body>
</html>