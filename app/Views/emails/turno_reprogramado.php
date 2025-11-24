<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
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
        .badge-change { background-color: #fef08a; color: #854d0e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Logo (asegúrate de que sea una URL pública en producción) -->
            <h1 style="margin:0; font-size: 24px;">🔄 Turno Reprogramado</h1>
        </div>
        
        <div class="content">
            <p style="font-size: 16px;">Hola <strong><?= esc($nombre) ?></strong>,</p>
            <p>Te informamos que tu turno ha sido reprogramado exitosamente. A continuación, los nuevos detalles:</p>
            
            <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <div class="detail-row">
                    <span class="detail-label">📅 Nueva Fecha:</span>
                    <span class="detail-value" style="font-weight:bold;"><?= esc($fecha) ?></span>
                </div>
                <div class="detail-row" style="border-bottom: none;">
                    <span class="detail-label">⏰ Nueva Hora:</span>
                    <span class="detail-value" style="font-weight:bold;"><?= esc($hora) ?></span>
                </div>
            </div>

            <p>Servicio: <strong><?= esc($servicio) ?></strong></p>

            <div style="text-align: center; margin-top: 30px;">
                <p style="font-size: 14px; color: #666;">Si no realizaste este cambio o tienes dudas, contáctanos.</p>
                <!-- Opcional: Link para volver a ver el turno -->
                <a href="<?= $link_ver_turno ?>" class="btn" style="color: #ffffff !important;">Ver mi Turno</a>
            </div>
        </div>

        <div class="footer">
            <p>LeanBarber - San Martín 1349, San Luis</p>
        </div>
    </div>
</body>
</html>