@php 
use Illuminate\Support\Facades\Auth; 
@endphp
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda - The English Voice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/welcome.css')
    @vite('resources/css/help.css')
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm pb-3 pt-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">The English Voice</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            @guest
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ url('/') }}">Início</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('artigos.index') }}">Artigos</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('help') }}">Ajuda</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link" href="{{ route('sobre') }}">Sobre</a>
                    </li>
                    <li class="nav-item underline">
                        <a class="nav-link active" href="{{ route('contato') }}">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('login') }}">Entrar</a>
                        <a class="btn btn-success ms-2" href="{{ route('register') }}">Criar Conta</a>
                    </li>
                </ul>
            @endguest
            @auth
                @php $role = strtolower(Auth::user()->role ?? ''); @endphp
                @if($role === 'admin')
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.panel') }}">Painel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">Usuários</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.artigos.pendentes') }}">Denúncias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/courses') }}">Cursos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('keywords.index') }}">Tags</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('forbidden_words.index') }}">Palavras Proibidas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.logs.index') }}">Logs</a>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item underline">
                            <a class="nav-link" href="{{ route('dashboard') }}">Artigos</a>
                        </li>
                        <li class="nav-item underline">
                            <a class="nav-link" href="{{ route('students.account') }}">Meus Artigos</a>
                        </li>
                        <li class="nav-item underline">
                            <a class="nav-link" href="{{ route('artigos.postar') }}">Postar Artigo</a>
                        </li>
                        <li class="nav-item underline">
                            <a class="nav-link" href="{{ route('articles.favorites') }}">Favoritos</a>
                        </li>
                        <li class="nav-item underline">
                            <a class="nav-link active" href="{{ route('help') }}">Ajuda</a>
                        </li>
                        <li class="nav-item underline">
                            <a class="nav-link" href="{{ route('students.profile') }}">Conta</a>
                        </li>
                    </ul>
                @endif
            @endauth
            @auth
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-2">
                    <form class="d-flex align-items-center" method="GET" action="{{ route('dashboard') }}" style="gap:0.25rem;">
                        <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Pesquisar artigos..." aria-label="Pesquisar" value="{{ request('q') }}" style="min-width: 180px;">
                        <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                        @if(request('q'))
                            @php
                                $query = request()->except('q');
                                $url = route('dashboard') . ($query ? ('?' . http_build_query($query)) : '');
                            @endphp
                            <a href="{{ $url }}" class="btn btn-sm btn-outline-danger ms-1" title="Limpar pesquisa"><i class="fas fa-times"></i></a>
                        @endif
                    </form>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-danger ms-2" href="{{ route('logout') }}">Sair</a>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>


    <div class="help-section container p-4 mt-4 mb-4 border border-dark">
        <div class="help-header container p-4 mt-4 mb-4 border border-dark">
            <h1>Precisa de Ajuda?</h1>
            <p>Encontre respostas rápidas para as dúvidas mais comuns ou entre em contato com nosso suporte.</p>
        </div>
        <div class="help-card p-4 mt-4 mb-4 border border-dark">
            <span class="icon"><i class="fas fa-user-plus"></i></span>
            <h3>Cadastro e Login</h3>
            <p>Para começar, <a href="{{ route('register') }}" class="help-link">cadastre-se</a> no sistema usando seu e-mail institucional e RA. Após o cadastro, faça login com suas credenciais para acessar o sistema.</p>
        </div>

        <div class="help-card p-4 mt-4 mb-4 border border-dark">
            <span class="icon"><i class="fas fa-key"></i></span>
            <h3>Redefinição de Senha</h3>
            <p>Esqueceu sua senha? Acesse a página de <a href="{{ route('password.request') }}" class="help-link">redefinição de senha</a> para receber um link por e-mail.</p>
        </div>

        <div class="help-card p-4 mt-4 mb-4 border border-dark">
            <span class="icon"><i class="fas fa-upload"></i></span>
            <h3>Como enviar artigos?</h3>
            <p>1. Acesse a página de <a href="{{ route('artigos.postar') }}" class="help-link">escrever artigos</a><br>
               2. Escolha se deseja escrever ou fazer upload de um arquivo<br>
               3. Preencha os dados do artigo (título, resumo, palavras-chave)<br>
               4. Faça upload do seu arquivo<br>
               5. Clique em "Publicar"</p>
        </div>

        <div class="help-card p-4 mt-4 mb-4 border border-dark">
            <span class="icon"><i class="fas fa-star"></i></span>
            <h3>Sistema de Avaliação</h3>
            <p>1. Artigos são avaliados pelos estudantes<br>
               2. Avaliação é feita em estrelas (1-5)<br>
               3. Você pode avaliar artigos de outros usuários<br>
               4. Resultados aparecem no seu <a href="{{ route('dashboard') }}" class="help-link">painel</a></p>
        </div>

        <div class="help-card p-4 mt-4 mb-4 border border-dark">
            <span class="icon"><i class="fas fa-book-reader"></i></span>
            <h3>Palavras-Chave e Tags</h3>
            <p>Utilize palavras-chave relevantes para facilitar a busca de seus artigos.</p>
        </div>

        <div class="help-card p-4 mt-4 mb-4 border border-dark">
            <span class="icon"><i class="fas fa-user-shield"></i></span>
            <h3>Segurança</h3>
            <p>- Palavras proibidas são filtradas automaticamente<br>
               - Conteúdo inapropriado é bloqueado<br>
               - Denúncias podem ser feitas pelos usuários<br>
        </div>

        <div class="help-card p-4 mt-4 mb-4 border border-dark">
            <span class="icon"><i class="fas fa-user-check"></i></span>
            <h3>Meu Perfil</h3>
            <p>1. Atualize seus dados pessoais<br>
               2. Gerencie suas preferências<br>
               3. Visualize seu histórico de artigos<br>
               4. Acesse suas avaliações<br>
               5. Gerencie suas configurações em seu <a href="{{ route('students.profile') }}" class="help-link">perfil</a></p>
        </div>

        <div class="help-card p-4 mt-4 mb-4 border border-dark">
            <span class="icon"><i class="fas fa-headset"></i></span>
            <h3>Suporte</h3>
            <p>Para dúvidas, sugestões ou problemas:</p>
            <ul>
                <li>Envie um e-mail para: theenglishvoicefatecgt@gmail.com</li>
            </ul>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer bg-light py-2 mt-auto">
    <div class="container text-center">
        <p class="mb-0">&copy; 2024 The English Voice - Todos os direitos reservados</p>
        <div class="social-icons mt-3">
            <a href="https://www.linkedin.com/company/fatec-guaratinguetá/" target="_blank" class="social-link" aria-label="LinkedIn">
                <i class="fab fa-linkedin fa-lg"></i>
            </a>
            <a href="https://www.instagram.com/fatecguaratingueta/" target="_blank" class="mx-2 social-link" aria-label="Instagram">
                <i class="fab fa-instagram fa-lg"></i>
            </a>
            <a href="https://www.fatecguaratingueta.edu.br" target="_blank" class="social-link" aria-label="Fatec Guaratinguetá">
                <i class="fas fa-globe fa-lg"></i>
            </a>
        </div>
    </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>