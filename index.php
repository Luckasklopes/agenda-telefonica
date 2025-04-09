<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agenda_telefonica";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão");
}

// Adicionar contato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["contato"], $_POST["numero"]) && empty($_POST["id_editar"])) {
    $contato = $_POST["contato"];
    $numero = $_POST["numero"];
    $sql = "INSERT INTO contato (nome, numero) VALUES ('$contato', '$numero')";
    $conn->query($sql);
    header("Location: index.php");
    exit();
}

// Remover contato
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $sql = "DELETE FROM contato WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
    exit();
}

// Atualizar contato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_editar"])) {
    $id = $_POST["id_editar"];
    $nome = $_POST["contato"];
    $numero = $_POST["numero"];
    $sql = "UPDATE contato SET nome='$nome', numero='$numero' WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT * FROM contato ORDER BY nome ASC");

$contato_editar = null;
if (isset($_GET["edit"])) {
    $id = $_GET["edit"];
    $sql = "SELECT * FROM contato WHERE id=$id";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $contato_editar = $res->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Agenda Telefônica</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="sem-contatos.png">
</head>

<body>
    <div class="container">
        <div class="top-container">
            <!-- TITULO -->
            <button class="botao botao-toggle" id="toggle-tema">Alternar Tema</button>

            <!-- TITULO -->
            <p class="titulo">Minha Agenda</p>

            <!-- BOTÃO ADICIONAR -->
            <button class="botao botao-confirma" onclick="abrirModalAdicionar()">Adicionar Contato</button>
        </div>

        <!-- MODAL ADICIONAR -->
        <div id="modalAdicionar" class="modal">
            <div class="modal-conteudo">
                <p class="modal-titulo">Adicionar Contato</p>
                <form method="POST">
                    <input type="text" name="contato" placeholder="Nome Sobrenome" required>
                    <input
                        type="text"
                        name="numero"
                        placeholder="(99) 99999-9999"
                        required
                        maxlength="15"
                        pattern="\(\d{2}\)\s\d{5}-\d{4}"
                        title="Digite um número no formato (99) 99999-9999"
                        oninput="this.value = formatarTelefone(this.value)">
                    <div class="modal-botoes">
                        <button type="submit" class="botao botao-confirma">Confirmar</button>
                        <button type="button" class="botao botao-cancela" onclick="fecharModalAdicionar()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDITAR -->
        <?php if ($contato_editar): ?>
            <div id="modalEditar" class="modal">
                <div class="modal-conteudo">
                    <p class="modal-titulo">Editando Contato</p>
                    <form method="POST">
                        <div class="modal-entradas">
                            <input type="hidden" name="id_editar" value="<?= $contato_editar['id'] ?>">
                            <input type="text" name="contato" placeholder="Nome Sobrenome" required value="<?= $contato_editar['nome'] ?>">
                            <input
                                type="text"
                                name="numero"
                                placeholder="(99) 99999-9999"
                                required
                                maxlength="15"
                                pattern="\(\d{2}\)\s\d{5}-\d{4}"
                                title="Digite um número no formato (99) 99999-9999"
                                value="<?= $contato_editar['numero'] ?>"
                                oninput="this.value = formatarTelefone(this.value)">

                        </div>
                        <div class="modal-botoes">
                            <button type="submit" class="botao botao-confirma">Atualizar</button>
                            <button type="button" class="botao botao-cancela" onclick="fecharModalEditar()">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- MODAL DE EXCLUSÃO -->
        <div id="modalExcluir" class="modal">
            <div class="modal-conteudo">
                <p class="modal-titulo">Tem certeza que deseja<br><span class="modal-titulo" style="color: #ef4444;">Excluir permanentemente</span><br>este contato?</p>
                <div class="modal-botoes">
                    <form method="GET" style="display: inline;">
                        <input type="hidden" name="delete" id="idExcluir">
                        <button type="submit" class="botao botao-cancela">Sim, excluir</button>
                    </form>
                    <button type="button" class="botao botao-confirma" onclick="fecharModalExcluir()">Cancelar</button>
                </div>
            </div>
        </div>

        <!-- LISTA DE EXIBIÇÃO -->
        <!-- SE LISTA ESTIVER VAZIA -->
        <?php if ($result->num_rows === 0): ?>
            <div class="sem-contatos">
                <p class="titulo">Parece que você ainda não tem nenhum contato</p>
                <img src="sem-contatos2.png" alt="Nenhum contato encontrado" />
            </div>
        <?php else: ?>


            <ul class="lista">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="lista-item">
                        <div class="lista-dados">
                            <span class="contato-nome"><?= $row["nome"]; ?></span>
                            <span class="contato-numero"><?= $row["numero"]; ?></span>
                        </div>
                        <div class="lista-botoes">
                            <button class="botao botao-edita" onclick="window.location.href='?edit=<?= $row['id']; ?>'">Editar</button>
                            <button class="botao botao-cancela" onclick="abrirModalExcluir(<?= $row['id']; ?>)">Remover</button>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
    </div>
<?php endif; ?>


<!-- IMPORT JAVASCRIPT -->
<script src="script.js"></script>
</body>

</html>

<?php $conn->close(); ?>