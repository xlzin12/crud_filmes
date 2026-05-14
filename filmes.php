<?php
// Inicia a sessão para guardar as alterações na memória do navegador
session_start();

// =====================================================================
// ⚠️ ÁREA DO CONFLITO DO GITHUB ⚠️
//  alterar as linhas abaixo nas suas branches 
// para provocar o conflito
// =====================================================================
$filmes_iniciais = [
    ["id" => 1, "titulo" => "Interestelar", "genero" => "Ficção Científica", "imagem" => "fotos/interstelar.jpg"],
    ["id" => 2, "titulo" => "O Poderoso Chefão", "genero" => "Crime", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 3, "titulo" => "Roben Hood", "genero" => "Ação", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 4, "titulo" => "Parasita", "genero" => "Drama", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 5, "titulo" => "O Rei Leão", "genero" => "Animação", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 6, "titulo" => " Zootopia", "genero" => "arcade", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 7, "titulo" => "Batman", "genero" => "Aventura", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 8, "titulo" => "mugen train", "genero" => "aventura", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 9, "titulo" => "titanic", "genero" => "Drama", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 10, "titulo" => "Vingadore", "genero" => "Ação", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 11, "titulo" => "Se Beber Não Case", "genero" => "Comédia", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 12, "titulo" => "Avatar", "genero" => "Ficção Científica", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 13, "titulo" => "Todo Mundo em Panico", "genero" => "Comédia", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 14, "titulo" => "Invocação do Mal", "genero" => "Terror", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 15, "titulo" => "Barbie - O Castelo de Diamantes", "genero" => "Desenho", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 16, "titulo" => "Esquadrão Suicida", "genero" => "Ação", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 17, "titulo" => "Homem Aranha", "genero" => "Aventura", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 18, "titulo" => "Vovozona", "genero" => "Comedia", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 19, "titulo" => "Velozes e Furiosos 4", "genero" => "Ação", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 20, "titulo" => "Clube da Luta", "genero" => "Drama", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 21, "titulo" => "Blade Runner 2049", "genero" => "Ficção Científica", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 22, "titulo" => "Drive", "genero" => "Crime", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 23, "titulo" => "The Batman", "genero" => "Suspense", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 24, "titulo" => "O Túmulo dos Vagalumes", "genero" => "Guerra", "imagem" => "https://via.placeholder.com/150"],
    ["id" => 25, "titulo" => "As Memorás de Marnie", "genero" => "Aminação", "imagem" => "https://via.placeholder.com/150"],
];

if (!isset($_SESSION['filmes_salvos'])) {
    $_SESSION['filmes_salvos'] = $filmes_iniciais;
}

// Para facilitar o uso no código
$filmes = &$_SESSION['filmes_salvos'];

// --- LÓGICA DO CRUD ---

// 1. EXCLUIR
if (isset($_GET['excluir'])) {
    $idExcluir = $_GET['excluir'];
    foreach ($filmes as $key => $f) {
        if ($f['id'] == $idExcluir) {
            unset($filmes[$key]); // Remove o filme da sessão
        }
    }
    // Reorganiza os índices do array e redireciona
    $_SESSION['filmes_salvos'] = array_values($filmes);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 2. SALVAR (Adicionar ou Editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $id = $_POST['id'];
    $titulo = htmlspecialchars($_POST['titulo']);
    $genero = htmlspecialchars($_POST['genero']);
    $imagem = htmlspecialchars($_POST['imagem']);

    if (!empty($id)) {
        // Editando um filme existente
        foreach ($filmes as $key => $f) {
            if ($f['id'] == $id) {
                $filmes[$key]['titulo'] = $titulo;
                $filmes[$key]['genero'] = $genero;
                $filmes[$key]['imagem'] = $imagem;
            }
        }
    } else {
        // Criando um filme novo
        $novoId = 1;
        if (count($filmes) > 0) {
            $ids = array_column($filmes, 'id');
            $novoId = max($ids) + 1;
        }
        $filmes[] = ["id" => $novoId, "titulo" => $titulo, "genero" => $genero, "imagem" => $imagem];
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 3. PREPARAR FORMULÁRIO DE EDIÇÃO
$fEdit = ['id' => '', 'titulo' => '', 'genero' => '', 'imagem' => 'https://via.placeholder.com/150'];
$editando = false;

if (isset($_GET['editar'])) {
    foreach ($filmes as $f) {
        if ($f['id'] == $_GET['editar']) {
            $fEdit = $f;
            $editando = true;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD Filmes - Trabalho em Grupo</title>
    <link rel="stylesheet" href="css    /style.css">

</head>

<body>

    <div class="container">
        <form method="POST" style="display:inline;">
            <button type="submit" name="resetar" class="btn reset">Resetar Dados Iniciais</button>
        </form>
        <?php
        // Botão apenas para ajudar vocês a limparem a sessão durante os testes
        if (isset($_POST['resetar'])) {
            session_destroy();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        ?>

        <h1>🎬 Gestão de Filmes</h1>

        <div class="form-box">
            <h3><?php echo $editando ? "Editar Filme" : "Adicionar Novo Filme"; ?></h3>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="id" value="<?php echo $fEdit['id']; ?>">
                <input type="text" name="titulo" placeholder="Título" value="<?php echo $fEdit['titulo']; ?>" required>
                <input type="text" name="genero" placeholder="Gênero" value="<?php echo $fEdit['genero']; ?>" required>
                <input type="text" name="imagem" placeholder="URL da Imagem" value="<?php echo $fEdit['imagem']; ?>">

                <button type="submit" name="salvar" class="btn btn-salvar">
                    <?php echo $editando ? "Atualizar" : "Salvar"; ?>
                </button>

                <?php if ($editando): ?>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" style="color:#ccc; margin-left: 10px;">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Título</th>
                    <th>Gênero</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($filmes) && count($filmes) > 0): ?>
                    <?php foreach ($filmes as $f): ?>
                        <tr>
                            <td><img src="<?php echo $f['imagem']; ?>" class="poster" alt="Poster"></td>
                            <td><?php echo $f['titulo']; ?></td>
                            <td><?php echo $f['genero']; ?></td>
                            <td>
                                <a href="?editar=<?php echo $f['id']; ?>" class="btn btn-edit">Editar</a>
                                <a href="?excluir=<?php echo $f['id']; ?>" class="btn btn-del" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">Nenhum filme cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
