🚀 Guia de Configuração: Sistema Alucom
Siga estes passos na ordem exata para garantir que o ambiente funcione corretamente.

1. Preparação Inicial
Abra o seu terminal na pasta onde deseja salvar o projeto e execute:

Bash
# Clonar o repositório
git clone https://github.com/seu-usuario/nome-do-repositorio.git

# Entrar na pasta
cd nome-do-repositorio

# Instalar dependências do PHP (Laravel)
composer install

# Instalar dependências do Frontend (Tailwind/Vite)
npm install
2. Configuração do Ambiente (.env)
O arquivo .env contém as configurações sensíveis. Como ele não é enviado ao repositório por segurança, você deve criá-lo manualmente:

Copiar o exemplo:

Bash
cp .env.example .env
Configurar o Banco de Dados: Abra o arquivo .env recém-criado em seu editor (VS Code, etc.) e ajuste as linhas abaixo:

Snippet de código
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_seu_banco  # Crie este banco no MySQL antes
DB_USERNAME=root               # Seu usuário do banco
DB_PASSWORD=                   # Sua senha do banco
Gerar a chave da aplicação:

Bash
php artisan key:generate
3. Banco de Dados e Arquivos
Agora vamos criar a estrutura das tabelas e preparar o sistema para receber fotos de equipamentos.

Bash
# Criar as tabelas no banco de dados
php artisan migrate

# Criar o link simbólico para armazenamento de imagens
# (Essencial para as fotos do Guia ADI aparecerem)
php artisan storage:link
4. Compilação e Inicialização
Com tudo configurado, basta colocar os motores para rodar:

Compilar o CSS e JS (Tailwind):

Bash
# Deixe este terminal rodando ou use 'npm run build' para compilar uma vez
npm run dev
Iniciar o servidor Laravel:

Bash
# Em outro terminal
php artisan serve
Acesse no seu navegador: http://127.0.0.1:8000

🛠️ Resumo de Comandos Pós-Instalação
Sempre que você baixar atualizações (um git pull), execute estes três para garantir que tudo esteja em dia:

composer install (novos pacotes PHP)

php artisan migrate (novas colunas/tabelas no banco)

npm run build (atualizar o visual/CSS)