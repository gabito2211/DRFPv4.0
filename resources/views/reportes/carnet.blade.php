<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carnet Bibliotecario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .carnet {
            width: 380px;
            border: 2px solid #2c3e50;
            border-radius: 15px;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #7f8c8d;
        }
        .contenido {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .foto img {
            width: 120px;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #2c3e50;
        }
        .info {
            flex: 1;
        }
        .info p {
            margin: 5px 0;
            font-size: 13px;
        }
        .info strong {
            font-weight: bold;
        }
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
        }
        .footer p {
            margin: 3px 0;
            font-size: 11px;
        }
        .fechas {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }
        .fechas p {
            margin: 3px 0;
            font-size: 12px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .carnet {
                box-shadow: none;
                border: 2px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="carnet">
        <div class="header">
            <h2>Biblioteca Dr. Félix Pifano</h2>
            <p>Carnet de Bibliotecario</p>
        </div>
        
        <div class="contenido">
            <div class="foto">
                <img src="{{ $foto }}" alt="Foto">
            </div>
            <div class="info">
                <p><strong>Nombre:</strong> {{ $nombre }}</p>
                <p><strong>Cédula:</strong> {{ $cedula }}</p>
                <p><strong>Profesión:</strong> {{ $profesion }}</p>
                <p><strong>Nº Carnet:</strong> {{ $num_carnet }}</p>
            </div>
        </div>
        
        <div class="fechas">
            <p><strong>Fecha Emisión:</strong> {{ $fecha_emision }}</p>
            <p><strong>Fecha Vencimiento:</strong> {{ $fecha_vencimiento }}</p>
        </div>
        
        <div class="footer">
            <p>Este carnet es personal e intransferible</p>
        </div>
    </div>
    
    <script>
        window.print();
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>