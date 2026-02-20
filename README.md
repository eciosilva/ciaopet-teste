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

### � **Autenticação (Desprotegidas)**

| Método | URL | Descrição |
|--------|-----|-----------|
| `POST` | `/api/auth/register` | Cadastro de usuário |
| `POST` | `/api/auth/login` | Login e geração de token |
| `POST` | `/api/auth/logout` | Logout (invalida token) |
| `GET` | `/api/auth/me` | Dados do usuário autenticado |

### 🐾 **Pets CRUD (Protegidas)** 🔒

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
  "observacoes": "text até 5000 chars (opcional)",
  "tutor_id": "integer - ID do usuário tutor (opcional)"
}
```

### 👤 **Campos do Usuário**

```json
{
  "name": "string (obrigatório)",
  "email": "string email único (obrigatório)", 
  "password": "string min 8 chars (obrigatório)",
  "password_confirmation": "string - confirmação (registro)"
}
```

### 🔐 **Autenticação**

**Todas as rotas de pets requerem autenticação via Bearer Token.**

1. **Registrar ou fazer login** para obter token
2. **Incluir header** em todas as requisições protegidas:
   ```bash
   Authorization: Bearer SEU_TOKEN_AQUI
   ```

**Usuários de teste disponíveis:**
- **João Silva:** `joao@ciaopet.com` / `password123`
- **Maria Santos:** `maria@ciaopet.com` / `password123`

### 🔍 **Filtros e Paginação Disponíveis**

**Query Parameters para `/api/pets`:**
- `page` - Número da página (padrão: 1)
- `per_page` - Items por página (padrão: 15, máx: 100)
- `especie` - Filtrar por espécie
- `genero` - Filtrar por gênero
- `search` - Busca por nome, raça ou microchip
- `sort_by` - Ordenar por: nome|especie|created_at|data_nascimento
- `sort_direction` - asc|desc

### 💡 **Exemplos de Uso**

**1. Login para obter token:**
```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "joao@ciaopet.com",
  "password": "password123"
}
```

**2. Listar pets (com autenticação):**
```bash
GET /api/pets?page=1&per_page=5&especie=Cachorro
Authorization: Bearer SEU_TOKEN_AQUI
```

**3. Criar pet com tutor:**
```bash
POST /api/pets
Content-Type: application/json
Authorization: Bearer SEU_TOKEN_AQUI

{
  "nome": "Thor",
  "especie": "Cachorro",
  "raca": "Pastor Alemão",
  "genero": "Macho",
  "data_nascimento": "2021-06-10", 
  "peso": 35.5,
  "tutor_id": 1,
  "observacoes": "Pet muito protetor e obediente."
}
```

**4. Registrar novo usuário:**
```bash
POST /api/auth/register
Content-Type: application/json

{
  "name": "João Silva",
  "email": "joao@exemplo.com",
  "password": "123456",
  "password_confirmation": "123456"
}
```

**5. Fazer login:**
```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "joao@exemplo.com", 
  "password": "123456"
}
# Retorna: {"success":true,"data":{"user":{...},"token":"Bearer_Token_Here"}}
```

**6. Obter dados do usuário autenticado:**
```bash
GET /api/auth/me
Authorization: Bearer SEU_TOKEN_AQUI
```

**7. Fazer logout:**
```bash
POST /api/auth/logout
Authorization: Bearer SEU_TOKEN_AQUI
```

**8. Buscar opções para formulários:**
```bash
GET /api/pets/options
# Retorna: {"generos": [...], "especies_comuns": [...]}
```

### 📄 **Estrutura de Resposta com Paginação**

```json
{
  "success": true,
  "data": [...],  // Array com os pets da página atual
  "pagination": {
    "current_page": 2,    // Página atual
    "per_page": 15,       // Itens por página
    "total": 50,          // Total de registros
    "last_page": 4        // Última página disponível
  },
  "links": {
    "first": "http://.../api/pets?page=1",
    "last": "http://.../api/pets?page=4", 
    "prev": "http://.../api/pets?page=1",
    "next": "http://.../api/pets?page=3"
  }
}
```

## 🔐 **Autenticação JWT**

### **Rotas Protegidas**
Todas as rotas de pets (`/api/pets/*`) requerem autenticação via token JWT.

### **Rotas Públicas**
- `POST /api/auth/register` - Registro de usuário
- `POST /api/auth/login` - Login de usuário

### **Como usar:**
1. Registre-se ou faça login para obter um token
2. Inclua o token no cabeçalho de todas as requisições protegidas:
   ```bash
   Authorization: Bearer SEU_TOKEN_AQUI
   ```
3. O token expira conforme configuração do Laravel Sanctum

### **Relacionamento Pet-Tutor**
- Cada pet pode ter um tutor (usuário) opcional
- Campo `tutor_id` na tabela pets referencia `users.id`
- Ao buscar pets, dados do tutor são incluídos automaticamente

---
*Sistema desenvolvido com Laravel 12, PHP 8.4, MySQL 8.0 e Docker*
