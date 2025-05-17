
# 📘 User Admin Portal (Laravel 10)

Projeto desenvolvido como solução para o **Desafio Técnico – Desenvolvedor(a) PHP/Laravel Pleno/Sênior**.

## ✅ Funcionalidades Entregues

- Autenticação com **Laravel Sanctum**
- Cadastro de usuários com preenchimento automático via **ViaCEP**
- Recuperação de senha via e-mail com **MailHog**
- Perfis de acesso (**admin** e **user**) com controle via middleware
- Listagem de usuários com filtros e paginação (somente admin)
- **Edição e exclusão de usuários** (somente admin)
- **Dashboard** com métricas básicas
- Arquitetura desacoplada com **Form Requests, Services, Events/Listeners**
- Testes automatizados com **PHPUnit**

---

## ⚙️ Requisitos

- Docker + Docker Compose
- Laravel 10 (instalado via Composer)
- PHP 8.2+ (já vem via Docker)
- Git (opcional)

---

## 🚀 Instruções de Setup

### 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/seu-projeto.git
cd seu-projeto
```

### 2. Criar arquivo `.env`

```bash
cp .env.example .env
```

### 3. Criar o banco SQLite

```bash
touch database/database.sqlite
```

No `.env`, ajuste:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite
```

### 4. Subir com Docker

```bash
docker compose up --build -d
```

---

## 🔐 Endpoints Principais

### 🔸 Registro

```http
POST /api/register
```

Campos: name, email, password, password_confirmation, cep, numero

### 🔸 Login

```http
POST /api/login
```

Campos: email, password  
Retorna: token Sanctum

### 🔸 Recuperação de Senha

```http
POST /api/forgot-password
```

Campos: email  
→ Ver e-mail no MailHog: http://localhost:8025

### 🔸 Reset de Senha

```http
POST /api/reset-password
```

Campos: token, email, password, password_confirmation

### 🔸 Listar usuários (admin)

```http
GET /api/users?name=&email=
```

Autenticado com token  
Headers:
```
Authorization: Bearer {token}
```

### 🔸 Dashboard (admin)

```http
GET /api/dashboard
```

Retorna:
- total de usuários
- por cidade
- por perfil

---

## 📬 Acesso ao MailHog

Abra no navegador:

[http://localhost:8025](http://localhost:8025)

---

## 🧪 Rodando os Testes

Dentro do container:

```bash
php artisan test
```

### Testes disponíveis:

- ✅ `AuthTest` → integração: registro, login, proteção
- ✅ `ViaCepServiceTest` → unitário/fake: busca endereço
- ✅ Cobertura total das principais funcionalidades

---

## 📁 Organização e Arquitetura

| Camada         | Descrição                                |
|----------------|-------------------------------------------|
| `Services/`    | Regras de negócio, como `ViaCepService`  |
| `Requests/`    | Validação com mensagens amigáveis        |
| `Events/`      | Evento de recuperação de senha           |
| `Listeners/`   | Listener que envia e-mail assíncrono     |
| `Mail/`        | Mailable de redefinição de senha         |
| `Middleware/`  | `EnsureUserIsAdmin`                      |
| `Controllers/` | Dividido por função: auth, users, etc    |

---

## ✅ Conclusão

O projeto foi desenvolvido com foco em:

- Código limpo (Clean Code)
- Princípios SOLID
- Arquitetura escalável
- Testabilidade
- Segurança em rotas e dados
- Alinhado 100% ao PDF do desafio técnico
