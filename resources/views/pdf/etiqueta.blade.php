<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }
        body { 
            margin: 0; 
            padding: 0; 
            width: 100%;
            height: 100%;
        }
        .container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        /* Imagem de fundo (etiqueta.jpeg) */
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        /* Posicionamento do Patrimônio no centro do retângulo branco */
        .patrimonio {
            position: absolute;
            top: 68%; /* Ajuste esse % para subir ou descer o texto */
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Helvetica', sans-serif;
            font-size: 32px; /* Tamanho do número */
            font-weight: bold;
            color: #000;
            text-align: center;
            width: 60%; /* Limita a largura para não vazar do quadrado */
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('images/logos/etiqueta.jpeg'))) }}" class="background">
        
        <div class="patrimonio">
            {{ $patrimonio }}
        </div>
    </div>
</body>
</html>