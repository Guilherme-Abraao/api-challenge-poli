# Task Management API

Esta aplicação é uma API para gerenciar tarefas (tasks), com funcionalidades como criação, listagem, atualização, exclusão e filtragem por status.

O projeto utiliza **Laravel** como framework principal, seguindo boas práticas de arquitetura para serviços, repositórios e testes.  

---

## 🌟 Funcionalidades

- Criação de tarefas com validação de entrada.
- Listagem de tarefas com filtro por status.
- Atualização de tarefas existentes.
- Exclusão de tarefas.
- Testes automatizados para validação de lógica e integrações.

---

## 🧩 Decisões de Design

O projeto segue uma arquitetura modular e escalável baseada em camadas. Isso facilita a manutenção, a expansão e a separação de responsabilidades.

### 📚 Estrutura de Pastas

    app/

            http/
                    Controllers/

                        # Recebe requisições HTTP

                    Request/

                        # Validações dos dados

            Models/ 

                # Modelos de dados

            Repositories/

                # Integração com banco de dados

            services/

                # Lógica de negócios

    test/

       # Teste uniário e de integração.
        

### Camada de Controlador

Centraliza as requisições HTTP, como criação, listagem, 
atualização, exclusão e filtragem por status.

### Camada de Serviço

Centraliza a lógica de negócios.
Valida os dados antes de delegar ao repositório.

### Camada de Repositório

Gerencia a interação com o banco de dados.
Desacopla a lógica de negócios do modelo de persistência.

### Testes Automatizados:

Testes unitários abrangentes cobrem as camadas de serviço e repositório.
Garantem que alterações futuras não causem regressões.

---

## 🚀 Como Rodar o Projeto

### Pré-requisitos

- **PHP** (versão 8.1 ou superior).
- **Composer** (gerenciador de dependências do PHP).
- **Banco de dados MySQL** ou qualquer banco suportado pelo Laravel.
- **Servidor local** (Laravel Sail, XAMPP, etc.).

### Passos Para Rodar a Aplicaçao

1. **Clone o repositório**

   ```bash
   git clone https://github.com/Guilherme-Abraao/api-challenge-poli
   cd api-challenge-poli/task-manager

2. **Instale as dependências**

    ```bash
    npm install

3. **Configure o ambiente**

    Copie o arquivo .env.example para .env e configure as variáveis de ambiente, incluindo as credenciais do banco de dados:

    ```bash
    cp .env.example .env

4. **Migre o banco de dados**
    
    ```bash
    php artisan migrate

5. **Inicie o servidor**

    ```bash
    php artisan serve

    A API estará disponível em: http://127.0.0.1:8000/

### Passos Para Rodar os Testes

Os testes garantem a qualidade e a confiabilidade do sistema.

1. **Execute os Testes no Terminal**

   ```bash
   php artisan test

---

## 🛠️ Tecnologias Utilizadas

- **Laravel**: Framework PHP para desenvolvimento web.
- **PHPUnit**: Framework para testes automatizados.
- **MySQL**: Banco de dados relacional.
- **PokéAPI**: API para buscar dados sobre Pokémons.

