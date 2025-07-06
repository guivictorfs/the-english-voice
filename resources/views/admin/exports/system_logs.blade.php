<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Logs do Sistema - The English Voice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="section">
        <div class="header">Logs do Sistema</div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Registro</th>
                    <th>Data/Hora</th>
                    <th>Ação</th>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->record_id }}</td>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->user->name ?? 'Usuário não encontrado' }}</td>
                        <td>{{ $log->user->email ?? 'Email não encontrado' }}</td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="header">Histórico de Artigos</div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Artigo</th>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Tipo de Alteração</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articleHistory as $history)
                    <tr>
                        <td>{{ $history->article_id }}</td>
                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $history->user->name ?? 'Usuário não encontrado' }}</td>
                        <td>{{ $history->change_type }}</td>
                        <td>{{ $history->change_description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="header">Histórico de Comentários</div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Comentário</th>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Conteúdo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comments as $comment)
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>{{ $comment->updated_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $comment->user->name ?? 'Usuário não encontrado' }}</td>
                        <td>{{ $comment->content }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
