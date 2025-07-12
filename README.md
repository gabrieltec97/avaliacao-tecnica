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
4. Instale as dependências com o Composer:
```bash
composer install
```
5. Gere a chave de API do Laravel.
```bash
php (ou sail) artisan key:generate
```
6. Parametrize crie seu banco de dados e preenchendo com as variáveis de nome do banco, usuário, senha e porta no arquivo .env.
7. Rode as migrations e seeders necessárias para dar a configuração inicial para o sistema executar corretamente.
```bash
php (ou sail) artisan migrate --seed
```
8. Inicie o servidor.
```bash
php (ou sail) artisan serve
```
9. Pronto! Agora é só acessar http://localhost:8000

## 📸 Screenshots

<h4>Com o usuário de administrador, acesse o dashboard informativo com gráfico de entregas recebidas x retiradas ao longo dos meses.</h4>

![Dashboard](assets/dashboard-parte1.png)


