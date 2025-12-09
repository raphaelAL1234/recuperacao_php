<?php
$pdo = new PDO("mysql:host=localhost;dbname=loja_recuperacao", "root", "");


if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $pdo->prepare("DELETE FROM produtos WHERE id_produto = ?")->execute([$id]);
    echo "<p>Produto excluído!</p>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = $pdo->prepare("INSERT INTO produtos (nome, categoria, preco, quantidade, descricao) VALUES (?, ?, ?, ?, ?)");
    $sql->execute([
        $_POST['nome'],
        $_POST['categoria'],
        $_POST['preco'],
        $_POST['quantidade'],
        $_POST['descricao']
    ]);
    echo "<p>Produto cadastrado!</p>";
}


$produto_editar = null;
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
    $stmt->execute([$id]);
    $produto_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

$resultado = $pdo->query("SELECT * FROM produtos");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastro e Lista de Produtos</title>
</head>
<body>

<h2>Cadastro de Produto</h2>
<form method="POST">
    Nome: <input type="text" name="nome" value="<?= $produto_editar['nome'] ?? '' ?>" required><br><br>
    Categoria: <input type="text" name="categoria" value="<?= $produto_editar['categoria'] ?? '' ?>" required><br><br>
    Preço: <input type="text" name="preco" value="<?= $produto_editar['preco'] ?? '' ?>" required><br><br>
    Quantidade: <input type="text" name="quantidade" value="<?= $produto_editar['quantidade'] ?? '' ?>" required><br><br>
    Descrição: <input type="text" name="descricao" value="<?= $produto_editar['descricao'] ?? '' ?>"><br><br>
    <input type="submit" value="<?= $produto_editar ? 'Atualizar' : 'Cadastrar' ?>">
</form>

<h2>Lista de Produtos</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Categoria</th>
        <th>Preço</th>
        <th>Quantidade</th>
        <th>Descrição</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($resultado as $produto): ?>
    <tr>
        <td><?= htmlspecialchars($produto['id_produto']) ?></td>
        <td><?= htmlspecialchars($produto['nome']) ?></td>
        <td><?= htmlspecialchars($produto['categoria']) ?></td>
        <td><?= htmlspecialchars($produto['preco']) ?></td>
        <td><?= htmlspecialchars($produto['quantidade']) ?></td>
        <td><?= htmlspecialchars($produto['descricao']) ?></td>
        <td>
            <a href="?excluir=<?= $produto['id_produto'] ?>" onclick="return confirm('Deseja realmente excluir este produto?');">Excluir</a> | 
            <a href="?editar=<?= $produto['id_produto'] ?>">Editar</a> | 
            <a href="?consultar=<?= $produto['id_produto'] ?>">Consultar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php

if (isset($_GET['consultar'])) {
    $id = (int)$_GET['consultar'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
    $stmt->execute([$id]);
    $produto_consultar = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto_consultar) {
        echo "<h2>Detalhes do Produto</h2>";
        echo "<p>ID: " . htmlspecialchars($produto_consultar['id_produto']) . "</p>";
        echo "<p>Nome: " . htmlspecialchars($produto_consultar['nome']) . "</p>";
        echo "<p>Categoria: " . htmlspecialchars($produto_consultar['categoria']) . "</p>";
        echo "<p>Preço: " . htmlspecialchars($produto_consultar['preco']) . "</p>";
        echo "<p>Quantidade: " . htmlspecialchars($produto_consultar['quantidade']) . "</p>";
        echo "<p>Descrição: " . htmlspecialchars($produto_consultar['descricao']) . "</p>";
    } else {
        echo "<p>Produto não encontrado.</p>";
    }
}
?>

</body>
</html>