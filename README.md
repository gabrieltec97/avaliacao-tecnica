## 📘 Descrição

Avaliação técnica solicitada em 08/07/2025.

## 🛠️ Como rodar o projeto

1. Tenha o ambiente Docker parametrizado em sua máquina juntamente com virtualização e WSL.
2. Acesse o terminal em sua pasta de projetos do Docker e clone o repositório:
```bash
git clone https://github.com/gabrieltec97/avaliacao-tecnica.git
```
3. Altere para a pasta do projeto clonado:
```bash
cd avaliacao-tecnica
```
4. Copie o arquivo .env.example para .env:
```bash
cp .env.example .env
```
5. Parametrize as variáveis de banco de dados em seu arquivo .env:
```bash
DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=password
```
6. Instale as dependências com o Composer em seu ambiente Docker:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/app" \
    composer install --ignore-platform-reqs
```
7. Gere a chave da aplicação:
```bash
docker run --rm \
    -v "$(pwd):/var/www/html" \
    php:8.3-fpm-alpine \
    php artisan key:generate
```
8. Suba os contêineres do projeto:
```bash
docker compose up -d
```
9. Rode as migrations e seeders necessárias para dar a configuração inicial para o sistema executar corretamente.
```bash
php (ou sail) artisan migrate --seed
```
10. Pronto! Agora é só acessar http://localhost

## 📸 Screenshots

<h4>Com o usuário de administrador, acesse o dashboard informativo com gráfico de entregas recebidas x retiradas ao longo dos meses.</h4>

![Dashboard](assets/dashboard-parte1.png)


