# 🐾 CiaoPet - Sistema de CRUD de Pets

API RESTful para gerenciamento de animais de estimação desenvolvida em Laravel 12 com MySQL.

## 🚀 Características

- ✅ **API REST completa** para CRUD de pets
- ✅ **Validações de negócio robustas**
- ✅ **Soft Delete** para histórico
- ✅ **Busca avançada** por múltiplos critérios
- ✅ **Containerização** com Docker

## 🛠 Tecnologias

- **Backend**: Laravel 12 (PHP 8.4)
- **Banco de Dados**: MySQL 8.0
- **Containerização**: Docker + Docker Compose
- **Web Server**: Nginx 1.17
- **Assets**: Vite + Bootstrap 5

## 📋 Pré-requisitos

- Docker Desktop
- Docker Compose
- Git

## ⚡ Instalação e Execução

### 1. Clone o repositório

```bash
git clone <repository-url>
cd CiaoPet
```

### 2. Configure o ambiente

```bash
cp .env.example .env
```

### 3. Inicie os containers

```bash
docker compose up -d --build
```

O sistema estará disponível em:
- **API**: http://localhost:8098/api
- **Web**: http://localhost:8098
- **Banco de Dados**: localhost:33068

## 📚 Estrutura da API

Em desenvolvimento...
