<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Estilos base para clientes que los soporten */
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header { background-color: #1f2937; color: #ffffff; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .detail-row { border-bottom: 1px solid #eee; padding: 10px 0; display: flex; justify-content: space-between; }
        .detail-label { font-weight: bold; color: #555; }
        .detail-value { color: #1f2937; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb; }
        .btn { display: inline-block; background-color: #dc2626; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-weight: bold; margin-top: 20px; }
        .logo { width: 80px; height: auto; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?= base_url('imagenes/logoinicial.png') ?>" alt="LeanBarber Logo" class="logo" style="width: 80px;">
            <h1 style="margin:0; font-size: 24px;">¡Turno Confirmado!</h1>
        </div>
        
        <div class="content">
            <p style="font-size: 16px;">Hola <strong><?= esc($nombre) ?></strong>,</p>
            <p>Tu reserva ha sido procesada con éxito. Te esperamos para brindarte el mejor servicio.</p>
            
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <div class="detail-row">
                    <span class="detail-label">📅 Fecha:</span>
                    <span class="detail-value"><?= esc($fecha) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">⏰ Hora:</span>
                    <span class="detail-value"><?= esc($hora) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">✂️ Servicio:</span>
                    <span class="detail-value"><?= esc($servicio) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">👤 Barbero:</span>
                    <span class="detail-value"><?= esc($barbero) ?></span>
                </div>
                <div class="detail-row" style="border-bottom: none;">
                    <span class="detail-label">💰 Total a Pagar:</span>
                    <span class="detail-value" style="color: #dc2626; font-weight: bold;">$<?= number_format($precio, 0, ',', '.') ?></span>
                </div>
            </div>

            <p style="font-size: 14px; color: #666;">
                Recuerda que debes abonar una seña de <strong>$<?= number_format($sena, 0, ',', '.') ?></strong> para congelar el precio.
            </p>

            <div style="text-align: center;">
                <p>¿Necesitas cambiar la fecha?</p>
                <a href="<?= $link_reprogramar ?>" class="btn" style="color: #ffffff !important;">Reprogramar Turno</a>
            </div>
        </div>

        <div class="footer">
            <p>LeanBarber - San Martín 1349, San Luis</p>
            <p>Si tienes dudas, contáctanos por WhatsApp.</p>
        </div>
    </div>
</body>
</html>