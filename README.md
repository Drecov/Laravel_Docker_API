#RODANDO O PROJETO:

Este projeto possui é uma API segregada e Dockerizada, tal qual para rodar o projeto é necessário somente o Git e Docker instalados na máquina local.

Uma vez instalados o Git e Docker seguir os seguintes passos:
1. Realizar o clone do projeto público: `git clone https://github.com/Drecov/Laravel_Docker_API.git`.
2. Subir o ambiente docker: `docker compose up --build` na pasta raiz do projeto.

#CONTAINERS
1. app: Container onde roda a aplicação Laravel.
2. nginx: Container onde roda o nginx, que disponibiliza a porta 80 para o app Laravel, ao invés da 8000.
3. mysql: Container do banco de dados, tal qual o mysql é disponibilizado na porta 3306.
4. phpmyadmin: Container do phpmyadmin, disponibilizado na porta 8080, possibilitando a administração do servidor.

#LINKS
- localhost:8080/: PhPMyAdmin.
- localhost/: URI base da API.

#ENDPOINTS
- /reset (POST): limpa o banco de dados.
- /event (POST): processa os eventos de depósito, saque e transferência.
- /balance (GET): retorna o saldo de uma conta.
- /allAccounts (GET): retorna a conta e saldo de todas as contas do sistema.