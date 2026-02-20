# 📋 API Reference - CiaoPet

Esta documentação detalha todos os endpoints disponíveis na API CiaoPet, incluindo autenticação JWT e gerenciamento de pets.

---

## 🔐 Autenticação

### Registro de Usuário

**POST** `/api/auth/register`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ❌ Não requerida |
| **Content-Type** | `application/json` |

#### Parâmetros de Requisição

```json
{
  "name": "string|required|max:255",
  "email": "string|required|email|unique:users", 
  "password": "string|required|min:8|confirmed"
}
```

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8080/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "password": "minhasenha123",
    "password_confirmation": "minhasenha123"
  }'
```

#### Respostas

**✅ Sucesso (201 Created)**
```json
{
  "success": true,
  "message": "Usuário registrado com sucesso!",
  "data": {
    "user": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@exemplo.com",
      "created_at": "2026-02-20T10:00:00.000000Z",
      "updated_at": "2026-02-20T10:00:00.000000Z"
    },
    "token": "1|abcd1234efgh5678ijkl9012mnop3456qrst7890uvwx",
    "token_type": "Bearer"
  }
}
```

**❌ Erro de Validação (422 Unprocessable Entity)**
```json
{
  "success": false,
  "message": "Dados inválidos fornecidos.",
  "errors": {
    "email": ["O e-mail já está sendo utilizado."],
    "password": ["A senha deve ter pelo menos 8 caracteres."]
  }
}
```

---

### Login de Usuário

**POST** `/api/auth/login`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ❌ Não requerida |
| **Content-Type** | `application/json` |

#### Parâmetros de Requisição

```json
{
  "email": "string|required|email",
  "password": "string|required"
}
```

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@exemplo.com",
    "password": "minhasenha123"
  }'
```

#### Respostas

**✅ Sucesso (200 OK)**
```json
{
  "success": true,
  "message": "Login realizado com sucesso!",
  "data": {
    "user": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@exemplo.com"
    },
    "token": "2|W0hslrCPsTGFIdS9kv1SGydokrSXOxh2PjrxWVWB0be5951e",
    "token_type": "Bearer"
  }
}
```

**❌ Credenciais Inválidas (401 Unauthorized)**
```json
{
  "success": false,
  "message": "Credenciais inválidas."
}
```

---

### Visualizar Perfil do Usuário

**GET** `/api/auth/me`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ✅ Bearer Token |
| **Headers** | `Authorization: Bearer {token}` |

#### Exemplo de Requisição

```bash
curl -X GET http://localhost:8080/api/auth/me \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

#### Respostas

**✅ Sucesso (200 OK)**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "created_at": "2026-02-20T10:00:00.000000Z",
    "updated_at": "2026-02-20T10:00:00.000000Z"
  }
}
```

**❌ Token Inválido (401 Unauthorized)**
```json
{
  "message": "Unauthenticated."
}
```

---

### Logout de Usuário

**POST** `/api/auth/logout`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ✅ Bearer Token |
| **Headers** | `Authorization: Bearer {token}` |

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8080/api/auth/logout \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

#### Respostas

**✅ Sucesso (200 OK)**
```json
{
  "success": true,
  "message": "Logout realizado com sucesso!"
}
```

---

## 🐾 Gerenciamento de Pets

### Listar Pets (com Paginação)

**GET** `/api/pets`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ✅ Bearer Token |
| **Headers** | `Authorization: Bearer {token}` |

#### Parâmetros de Query (Opcionais)

| Parâmetro | Tipo | Descrição | Exemplo |
|-----------|------|-----------|---------|
| `page` | integer | Número da página (padrão: 1) | `?page=2` |
| `per_page` | integer | Itens per página (padrão: 15, max: 100) | `?per_page=10` |

#### Exemplo de Requisição

```bash
curl -X GET "http://localhost:8080/api/pets?page=1&per_page=10" \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

#### Respostas

**✅ Sucesso (200 OK)**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nome": "Rex",
      "especie": "Cachorro",
      "raca": "Pastor Alemão",
      "genero": "Macho",
      "data_nascimento": "2021-06-10",
      "peso": 35.5,
      "numero_microchip": "123456789012345",
      "observacoes": "Pet muito protetor e obediente.",
      "created_at": "2026-02-20T10:00:00.000000Z",
      "updated_at": "2026-02-20T10:00:00.000000Z",
      "tutor": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@exemplo.com"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 25,
    "last_page": 3
  },
  "links": {
    "first": "http://localhost:8080/api/pets?page=1",
    "last": "http://localhost:8080/api/pets?page=3",
    "prev": null,
    "next": "http://localhost:8080/api/pets?page=2"
  }
}
```

---

### Criar Pet

**POST** `/api/pets`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ✅ Bearer Token |
| **Headers** | `Authorization: Bearer {token}`, `Content-Type: application/json` |

#### Parâmetros de Requisição

```json
{
  "nome": "string|required|max:100",
  "especie": "string|required|max:50",
  "raca": "string|nullable|max:50",
  "genero": "enum|required|in:Macho,Fêmea",
  "data_nascimento": "date|required",
  "peso": "numeric|nullable|min:0",
  "numero_microchip": "string|nullable|unique:pets|size:15",
  "tutor_id": "integer|nullable|exists:users,id",
  "observacoes": "string|nullable"
}
```

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8080/api/pets \
  -H "Authorization: Bearer SEU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Bella",
    "especie": "Gato",
    "raca": "Persa",
    "genero": "Fêmea",
    "data_nascimento": "2022-03-15",
    "peso": 4.2,
    "numero_microchip": "987654321098765",
    "tutor_id": 1,
    "observacoes": "Gata muito carinhosa e brincalhona."
  }'
```

#### Respostas

**✅ Sucesso (201 Created)**
```json
{
  "success": true,
  "message": "Pet criado com sucesso!",
  "data": {
    "id": 2,
    "nome": "Bella",
    "especie": "Gato",
    "raca": "Persa",
    "genero": "Fêmea",
    "data_nascimento": "2022-03-15",
    "peso": 4.2,
    "numero_microchip": "987654321098765",
    "observacoes": "Gata muito carinhosa e brincalhona.",
    "created_at": "2026-02-20T10:30:00.000000Z",
    "updated_at": "2026-02-20T10:30:00.000000Z",
    "tutor": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@exemplo.com"
    }
  }
}
```

**❌ Erro de Validação (422 Unprocessable Entity)**
```json
{
  "success": false,
  "message": "Dados inválidos fornecidos.",
  "errors": {
    "nome": ["O nome é obrigatório."],
    "genero": ["O gênero selecionado é inválido."],
    "numero_microchip": ["O número do microchip já está sendo utilizado."]
  }
}
```

---

### Visualizar Pet por ID

**GET** `/api/pets/{id}`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ✅ Bearer Token |
| **Headers** | `Authorization: Bearer {token}` |

#### Parâmetros de URL

| Parâmetro | Tipo | Descrição |
|-----------|------|-----------|
| `id` | integer | ID único do pet |

#### Exemplo de Requisição

```bash
curl -X GET http://localhost:8080/api/pets/1 \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

#### Respostas

**✅ Sucesso (200 OK)**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nome": "Rex",
    "especie": "Cachorro",
    "raca": "Pastor Alemão",
    "genero": "Macho",
    "data_nascimento": "2021-06-10",
    "peso": 35.5,
    "numero_microchip": "123456789012345",
    "observacoes": "Pet muito protetor e obediente.",
    "created_at": "2026-02-20T10:00:00.000000Z",
    "updated_at": "2026-02-20T10:00:00.000000Z",
    "tutor": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@exemplo.com"
    }
  }
}
```

**❌ Pet Não Encontrado (404 Not Found)**
```json
{
  "success": false,
  "message": "Pet não encontrado."
}
```

---

### Atualizar Pet

**PUT** `/api/pets/{id}`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ✅ Bearer Token |
| **Headers** | `Authorization: Bearer {token}`, `Content-Type: application/json` |

#### Parâmetros de URL

| Parâmetro | Tipo | Descrição |
|-----------|------|-----------|
| `id` | integer | ID único do pet |

#### Parâmetros de Requisição

```json
{
  "nome": "string|required|max:100",
  "especie": "string|required|max:50",
  "raca": "string|nullable|max:50",
  "genero": "enum|required|in:Macho,Fêmea",
  "data_nascimento": "date|required",
  "peso": "numeric|nullable|min:0",
  "numero_microchip": "string|nullable|unique:pets,numero_microchip,{id}|size:15",
  "tutor_id": "integer|nullable|exists:users,id",
  "observacoes": "string|nullable"
}
```

#### Exemplo de Requisição

```bash
curl -X PUT http://localhost:8080/api/pets/1 \
  -H "Authorization: Bearer SEU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Rex Atualizado",
    "especie": "Cachorro",
    "raca": "Pastor Alemão",
    "genero": "Macho", 
    "data_nascimento": "2021-06-10",
    "peso": 36.0,
    "numero_microchip": "123456789012345",
    "tutor_id": 2,
    "observacoes": "Pet muito protetor, obediente e leal."
  }'
```

#### Respostas

**✅ Sucesso (200 OK)**
```json
{
  "success": true,
  "message": "Pet atualizado com sucesso!",
  "data": {
    "id": 1,
    "nome": "Rex Atualizado",
    "especie": "Cachorro",
    "raca": "Pastor Alemão",
    "genero": "Macho",
    "data_nascimento": "2021-06-10",
    "peso": 36.0,
    "numero_microchip": "123456789012345",
    "observacoes": "Pet muito protetor, obediente e leal.",
    "created_at": "2026-02-20T10:00:00.000000Z",
    "updated_at": "2026-02-20T11:00:00.000000Z",
    "tutor": {
      "id": 2,
      "name": "Maria Santos",
      "email": "maria@exemplo.com"
    }
  }
}
```

---

### Deletar Pet

**DELETE** `/api/pets/{id}`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ✅ Bearer Token |
| **Headers** | `Authorization: Bearer {token}` |

#### Parâmetros de URL

| Parâmetro | Tipo | Descrição |
|-----------|------|-----------|
| `id` | integer | ID único do pet |

#### Exemplo de Requisição

```bash
curl -X DELETE http://localhost:8080/api/pets/1 \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

#### Respostas

**✅ Sucesso (200 OK)**
```json
{
  "success": true,
  "message": "Pet deletado com sucesso!"
}
```

**❌ Pet Não Encontrado (404 Not Found)**
```json
{
  "success": false,
  "message": "Pet não encontrado."
}
```

---

### Opções para Formulários

**GET** `/api/pets/options`

| **Atributo** | **Valor** |
|--------------|-----------|
| **Autenticação** | ✅ Bearer Token |
| **Headers** | `Authorization: Bearer {token}` |

#### Exemplo de Requisição

```bash
curl -X GET http://localhost:8080/api/pets/options \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

#### Respostas

**✅ Sucesso (200 OK)**
```json
{
  "success": true,
  "data": {
    "generos": [
      {"value": "Macho", "label": "Macho"},
      {"value": "Fêmea", "label": "Fêmea"}
    ],
    "especies_comuns": [
      {"value": "Cachorro", "label": "Cachorro"},
      {"value": "Gato", "label": "Gato"},
      {"value": "Pássaro", "label": "Pássaro"},
      {"value": "Peixe", "label": "Peixe"},
      {"value": "Hamster", "label": "Hamster"},
      {"value": "Coelho", "label": "Coelho"}
    ]
  }
}
```

---

## 📊 Códigos de Status HTTP

| Código | Status | Descrição |
|--------|--------|-----------|
| **200** | OK | Requisição processada com sucesso |
| **201** | Created | Recurso criado com sucesso |
| **401** | Unauthorized | Token de autenticação inválido ou ausente |
| **404** | Not Found | Recurso não encontrado |
| **422** | Unprocessable Entity | Dados de entrada inválidos (erros de validação) |
| **500** | Internal Server Error | Erro interno do servidor |

---

## 🔧 Informações Gerais

### Base URL
```
http://localhost:8080/api
```

### Autenticação
- **Tipo**: Bearer Token (JWT via Laravel Sanctum)
- **Header**: `Authorization: Bearer {token}`
- **Obtenção**: Através dos endpoints `/auth/register` ou `/auth/login`

### Content-Type
- **Requisições**: `application/json`
- **Respostas**: `application/json`

### Paginação
- **Parâmetro**: `page` (número da página)
- **Parâmetro**: `per_page` (itens por página, máximo 100)
- **Padrão**: 15 itens por página

### Formato de Datas
- **Padrão**: `YYYY-MM-DD` (ISO 8601)
- **Exemplo**: `2026-02-20`

---

*Documentação gerada para API CiaoPet v1.0 - Laravel 12 com PHP 8.4*