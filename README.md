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
git clone git@github.com:eciosilva/ciaopet-teste.git
cd ciaopet-teste
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

## 📚 API Endpoints

### 🐾 **Pets CRUD**

| Método | URL | Descrição |
|--------|-----|-----------|
| `GET` | `/api/pets` | Lista todos os pets (com paginação) |
| `POST` | `/api/pets` | Cria um novo pet |
| `GET` | `/api/pets/{id}` | Busca pet específico |
| `PUT/PATCH` | `/api/pets/{id}` | Atualiza pet |
| `DELETE` | `/api/pets/{id}` | Remove pet (soft delete) |
| `GET` | `/api/pets/options` | Opções para formulários |

### 📋 **Campos do Pet**

```json
{
  "nome": "string (obrigatório)",
  "especie": "string (obrigatório)",
  "raca": "string (opcional)",
  "genero": "enum: Macho|Fêmea|Desconhecido (opcional)",
  "data_nascimento": "date YYYY-MM-DD (opcional)",
  "peso": "decimal até 999.99 kg (opcional)",
  "numero_microchip": "string único (opcional)",
  "observacoes": "text até 5000 chars (opcional)"
}
```

### 🔍 **Filtros Disponíveis**

**Query Parameters para `/api/pets`:**
- `especie` - Filtrar por espécie
- `genero` - Filtrar por gênero
- `search` - Busca por nome, raça ou microchip
- `sort_by` - Ordenar por: nome|especie|created_at|data_nascimento
- `sort_direction` - asc|desc
- `per_page` - Items por página (máx: 100)

### 💡 **Exemplos de Uso**

**1. Listar pets com filtros:**
```bash
GET /api/pets?especie=Cachorro&search=rex&per_page=10
```

**2. Criar pet:**
```bash
POST /api/pets
Content-Type: application/json

{
  "nome": "Rex",
  "especie": "Cachorro",
  "raca": "Golden Retriever", 
  "genero": "Macho",
  "data_nascimento": "2020-05-15",
  "peso": 25.5,
  "numero_microchip": "123456789012345",
  "observacoes": "Pet muito dócil e brincalhão."
}
```

**3. Buscar opções para formulários:**
```bash
GET /api/pets/options
# Retorna: {"generos": [...], "especies_comuns": [...]}
```
