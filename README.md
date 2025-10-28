#DEPENDÊNCIAS

Para rodar o projeto, é necessário ter instalado localmente as seguintes dependências:
1. GIT.
2. Docker.


#RODANDO O PROJETO:

Este projeto possui é uma API segregada e Dockerizada, tal qual para rodar o projeto é necessário somente o Git e Docker instalados na máquina local.
Ao subir o projeto, o mesmo realiza automaticamente as migrations e os testes unitários e de feature.

Uma vez instalados o Git e Docker seguir os seguintes passos:
1. Realizar o clone do projeto público: `git clone https://github.com/Drecov/Laravel_Docker_API.git`.
2. Subindo o ambiente a primeira vez: deve-se fazer o build do ambiente docker: `docker compose up --build` na pasta raiz do projeto.
3. Subindo o projeto após realizada a build: rodar o comando `docker compose up` na pasta raiz do projeto. 
4. Para parar o projeto, rodar `docker compose down` para remover os containers (ou `docker compose down -v --remove-orphans` para remover os volumes e networks também).
5. Certificar-se que o arquivo `docker/php/entrypoint.sh` está salvo como LF, não CLRF. Caso esteja, não rodará corretamente no container.


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


#ESTRUTURA

Esse é um projeto Laravel, tal qual a única classe de objeto que temos é a Account. Ela é dividida em Model, Resource, Controller, Service e Test, tal qual se dá destaque para o *AccountController*, que possui a camada HTML das requisições, e o *AccountService*, que possui as regras de negócio.

O projeto está disponível no github `https://github.com/Drecov/Laravel_Docker_API`