# 🚀 SGB ELITE - Sistema de Gestão

O **SGB ELITE** é uma aplicação web desenvolvida com o framework **Laravel 11** e banco de dados **MySQL**, focada em eficiência e organização de dados.

---

## 🛠️ Tecnologias Utilizadas
* **Framework:** Laravel 11
* **Linguagem:** PHP 8.2+
* **Banco de Dados:** MySQL
* **Ambiente Sugerido:** Laragon ou XAMPP

---

## 📥 Como Baixar o Projeto

Você pode obter o código de duas formas:

### Opção A: Pelo Terminal (Git)
```bash
git clone [https://github.com/Edy4Lucaz/sgb.git](https://github.com/Edy4Lucaz/sgb.git)

```

### Opção B: Download Direto (ZIP)

1. No topo desta página, clique no botão verde **Code**.
2. Selecione a opção **Download ZIP**.
3. Extraia o arquivo na pasta do seu servidor local (ex: `C:\laragon\www\` ou `C:\xampp\htdocs\`).

---

## 📦 Configuração e Instalação

Após baixar o projeto, siga os comandos abaixo no terminal dentro da pasta do sistema:

### 1. Instalar Dependências

Antes de rodar o comando abaixo, certifique-se de ter o **PHP 8.2+** e o **Composer** instalados globalmente.

No terminal, execute:
```bash
composer install
```

### 2. Configurar o Ambiente (.env)

Crie o arquivo de ambiente a partir do exemplo:

```bash
cp .env.example .env

```

### 3. Gerar Chave de Segurança

```bash
php artisan key:generate

```

### 4. Banco de Dados

1. Crie um banco de dados vazio no seu MySQL chamado: **`sgb`**.

### 5. Criar Tabelas

```bash
php artisan migrate --seed

```


### 6. Iniciar o Sistema

Você pode visualizar o sistema de duas maneiras, dependendo da sua preferência:

#### Opção A: Servidor Interno do Laravel

No terminal, dentro da pasta do projeto, execute:

```bash
php artisan serve

```

Acesse no navegador: [http://localhost:8000]

#### Opção B: Via Laragon / XAMPP (Recomendado)

Se você estiver usando o **Laragon** ou **XAMPP**:

1. Certifique-se de que os serviços (Apache/Nginx e MySQL) estão iniciados.
2. Se estiver no **Laragon**, o sistema criará automaticamente o Virtual Host. Acesse: [http://sgb.test]
3. Se estiver no **XAMPP**, acesse via: `http://localhost/sgb/public`

---



## 📧 Testes de E-mail

Para facilitar a avaliação, o sistema utiliza o driver de **Log**. Os e-mails disparados não serão enviados para endereços reais, mas registrados para conferência em:
`storage/logs/laravel.log`

---

## 👤 Desenvolvedor

* **GRUPO Nº 3:**
* **Status:** Em Desemvolvimento/Disponível para avaliação

