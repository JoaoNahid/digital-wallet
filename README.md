# 🚀 Digital Wallet

Sistema desenvolvido em **Laravel 12** utilizando **Docker** para ambiente de desenvolvimento.

## Configuração do Ambiente de Desenvolvimento

## Pré-requisitos

Antes de começar, certifique-se de que você tem os seguintes softwares instalados em sua máquina:

- **Docker Compose**: Geralmente já vem instalado com o Docker, mas certifique-se de que está disponível.
  - [Instale o Docker Compose](https://docs.docker.com/compose/install/)
- **Git**: Para clonar o repositório do projeto.
  - [Instale o Git](https://git-scm.com/downloads)

## Passo 1: Clonar o Repositório

Primeiro, clone o repositório do projeto para o seu ambiente local:

HTTPS
```bash
git clone https://github.com/JoaoNahid/digital-wallet.git
```
SSH
```bash
git clone git@github.com:JoaoNahid/digital-wallet.git
```

## Passo 2: Acessar o projeto e configurar o .env

```bash
cd digital-wallet && cp .env-example .env
```

## Passo 3: Rodar o composer install
```bash
composer install
```

## Passo 4: Rodar o container MySQL
```bash
docker compose up -d --build
```

## Passo 5: Banco de dados

Rode as migrations:
```bash
sail artisan:migrate
```

## Acessando o sistema
Ao acessaro sistema a base de dados estará vazia, então será necessário criar um usuário