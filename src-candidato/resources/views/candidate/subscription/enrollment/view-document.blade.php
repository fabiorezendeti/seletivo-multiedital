<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Arquivos</title>
    <style>
        body {
            margin: 0px;
            padding: 0x;
        }
    </style>
</head>

<body>
    <img width="100%" src="data:{{$mimeType}};base64,{{ $file }}" />
</body>

</html>