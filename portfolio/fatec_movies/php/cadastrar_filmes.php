<?php
session_start();

// Aumenta o limite de upload e o tamanho máximo de POST
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_execution_time', '300'); // Opcional: aumenta o tempo máximo de execução

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Mensagem de erro ou sucesso
$message = '';

// Conecta ao banco de dados (substitua pelos seus dados de conexão)
$servername = 'localhost';
$dbusername = 'root';
$dbpassword = '';
$dbname = 'fatec_movies';

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $release_year = $_POST['release_year'] ?? '';
    $director = $_POST['director'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $rating = $_POST['rating'] ?? '';

    // Diretório para salvar os arquivos
    $target_dir = "uploads/";

    // Verifica se o diretório existe, se não, cria
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Verifica se o arquivo foi enviado
    if (isset($_FILES["file"])) {
        $file_name = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se o arquivo é um MOV ou MP4
        if ($file_type != "mov" && $file_type != "mp4") {
            $message = "Somente arquivos MOV e MP4 são permitidos.";
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO movies (title, genre, release_year, director, duration, rating, reg_date)
                        VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    $message = 'Prepare failed: ' . $conn->error;
                } else {
                    $stmt->bind_param('sssisi', $title, $genre, $release_year, $director, $duration, $rating);
                    if ($stmt->execute()) {
                        header('Location: sucesso.php');
                        exit();
                    } else {
                        $message = "Erro ao registrar o filme: " . $stmt->error;
                    }
                    $stmt->close();
                }
            } else {
                $message = "Erro ao fazer upload do arquivo.";
            }
        }
    } else {
        $message = "Nenhum arquivo enviado.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Filme</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        header {
            flex-shrink: 0;
        }
        main {
            width: 60%;
            max-width: 600px;
            margin: 20px auto;
            padding: 50px;
            border: 1px solid white;
            background-color: rgba(168, 168, 168, 0.6);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
        }
        main h1{
            text-align:center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
        }
        footer {
            flex-shrink: 0;
            color: white;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="topo conteudo-flex">
            <div class="topo-esquerdo conteudo-flex">
                <div class="topo-logo">
                    <img src="../image/logoteste2a.png" alt="Imagem logotipo do site" title="Imagem logotipo do site">
                </div>
            </div>
            <div class="topo-centro conteudo-flex">
                <nav>
                    <ul class="topo-menu conteudo-flex">
                        <li><a href="#">Início</a></li>
                        <li><a href="../html/series.html">Séries</a></li>
                        <li><a href="../html/filmes.html">Filmes</a></li>
                        <li><a href="../html/categorias.html">Categorias</a></li>
                        <li><a href="../html/assinaturas.html">Assinaturas</a></li>
                    </ul>
                </nav>
                <div class="topo-pesquisa">
                    <div class="conteudo-pesquisa">
                        <form class="conteudo-flex" method="post">
                            <fieldset class="bar-pesquisa">
                                <input type="text" placeholder="Pesquisar um filme ou série..." alt="Digite aqui um filme ou série para pesquisar">
                            </fieldset>
                            <fieldset class="btn-pesquisar conteudo-flex">
                                <button type="button" title="Pesquisar">
                                    <span class="fas fa-search"></span>
                                </button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="topo-direito">
                <div class="sublogin">
                    <?php if(isset($_SESSION['username'])): ?>
                        <div class="profile">
                            <img src="../image/icone-perfil.png" alt="Ícone de Perfil" onclick="toggleMenu()">
                            <span><?php echo htmlspecialchars(ucwords(strtolower($_SESSION['username']))); ?></span>
                            <div id="profileMenu" class="profile-menu">
                                <a href="cadastrar_filmes.php">Cadastrar Filmes</a>
                                <a href="configuracoes.php">Configurações</a>
                                <a href="logout.php">Logout</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <main>
        <h1>Registrar Filme</h1>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="cadastrar_filmes.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="genre">Gênero:</label>
                <input type="text" id="genre" name="genre">
            </div>
            <div class="form-group">
                <label for="release_year">Ano de Lançamento:</label>
                <input type="number" id="release_year" name="release_year" min="1888" max="<?php echo date("Y"); ?>">
            </div>
            <div class="form-group">
                <label for="director">Diretor:</label>
                <input type="text" id="director" name="director">
            </div>
            <div class="form-group">
                <label for="duration">Duração (minutos):</label>
                <input type="number" id="duration" name="duration" min="1">
            </div>
            <div class="form-group">
                <label for="rating">Classificação:</label>
                <input type="number" id="rating" name="rating" step="0.1" min="0" max="10">
            </div>
            <div class="form-group">
                <label for="file">Arquivo do Filme (MOV ou MP4):</label>
                <input type="file" id="file" name="file" accept=".mov,.mp4" required>
            </div>
            <div class="form-group">
                <button type="submit">Registrar</button>
            </div>
        </form>
    </main>
    <footer>
      <div class="site-map conteudo-flex">
        <div class="site-map-conteudo">
          <ul>
            <li><a href="#">Sobre nós</a></li>
            <li><a href="#">Carreiras</a></li>
            <li><a href="#">Perguntas frequentes</a></li>
            <li><a href="#">Imprensa</a></li>
          </ul>
        </div>
        <div class="site-map-conteudo">
          <ul>
            <li><a href="#">Política de Privacidade</a></li>
            <li><a href="#">Política de Segurança</a></li>
            <li><a href="#">Termo de Uso</a></li>
            <li><a href="#">Avisos Legais</a></li>
          </ul>
        </div>
        <div class="site-map-conteudo">
          <ul>
            <li><a href="#">Conta</a></li>
            <li><a href="../html/contato.html">Contato</a></li>
            <li><a href="#">Centro de Ajuda</a></li>
            <li><a href="#">Teste de Velocidade</a></li>
          </ul>
        </div>
      </div>
      <div>
        <img src="../image/logoteste2a.png" alt="Imagem logotipo do site" title="Imagem logotipo do site">
      </div>
    </footer>

  </body>

</html>