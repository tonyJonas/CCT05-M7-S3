<?php
$host = getenv('host');
$user = getenv('user');
$password = getenv('password');
$dbname = getenv('dbname');
$port = getenv('port');

// Criar conexão
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Checar conexão
if (!$conn) {
    die("Conexão com o banco de dados falhou: " . pg_last_error());
}

// Insere uma tarefa no banco de dados
function inserirTarefa($conn, $titulo, $responsavel, $prioridade, $descricao, $status) {
    $data_criacao = date('Y-m-d'); // Data atual
    $query = "INSERT INTO tarefas (titulo, responsavel, data_criacao, prioridade, descricao, status) VALUES ('$titulo', '$responsavel', '$data_criacao', $prioridade, '$descricao', '$status')";
    $result = pg_query($conn, $query);
    if (!$result) {
        echo "Ocorreu um erro.\n";
        exit;
    }
}

// Função para pegar tarefas
function getTarefas($conn) {
    $result = pg_query($conn, "SELECT * FROM tarefas");
    return pg_fetch_all($result);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    inserirTarefa($conn, $_POST['titulo'], $_POST['responsavel'], $_POST['prioridade'], $_POST['descricao'], $_POST['status']);
}

$tarefas = getTarefas($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ISWORK - Gerenciamento de Tarefas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            padding: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="post">
            Título: <input type="text" name="titulo" required>
            Responsável: <input type="text" name="responsavel" required>
            Prioridade: <input type="number" name="prioridade" min="1" max="5" required>
            Descrição: <input type="text" name="descricao" required>
            Status: <select name="status" required>
                        <option value="TO DO">TO DO</option>
                        <option value="DOING">DOING</option>
                        <option value="DONE">DONE</option>
                    </select>
            <input type="submit" value="Criar Tarefa">
        </form>
        
        <table>
            <tr>
                <th>Título</th>
                <th>Responsável</th>
                <th>Data de Criação</th>
                <th>Prioridade</th>
                <th>Descrição</th>
                <th>Status</th>
            </tr>
            <?php if (!empty($tarefas)): ?>
                <?php foreach ($tarefas as $tarefa): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tarefa['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($tarefa['responsavel']); ?></td>
                        <td><?php echo htmlspecialchars($tarefa['data_criacao']); ?></td>
                        <td><?php echo htmlspecialchars($tarefa['prioridade']); ?></td>
                        <td><?php echo htmlspecialchars($tarefa['descricao']); ?></td>
                        <td><?php echo htmlspecialchars($tarefa['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhuma tarefa encontrada</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>

