<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .success-container {
            text-align: center;
            padding: 50px;
        }
        .success-image {
            width: 100px; /* Ajuste o tamanho conforme necessário */
            height: auto;
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 3000);
    </script>
</head>
<body>
    <div class="success-container">
        <img src="../image/sucesso.png" alt="Sucesso" class="success-image">
        <h1>Cadastrado com sucesso</h1>
        <p>Você será redirecionado em 3 segundos para o início.</p>
    </div>
</body>
</html>