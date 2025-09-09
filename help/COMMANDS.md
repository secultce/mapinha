# Documentação dos comandos disponíveis no Aurora

O Makefile automatiza várias etapas do processo de configuração e manutenção do Aurora usando Docker. Abaixo estão listados todos os comandos e suas respectivas descrições.

## Comandos disponíveis
<details>
<summary>UP</summary>

### `up`
Inicia os serviços Docker em modo *detached* (em segundo plano).
- **Uso:** `make up`
- **Descrição:** Este comando executa `docker compose up -d`, iniciando todos os contêineres definidos no arquivo `docker-compose.yml` em segundo plano, para que você possa continuar usando o terminal.
</details>

<details>
<summary>STOP</summary>

### `stop`
Para os serviços Docker.
- **Uso:** `make stop`
- **Descrição:** Executa `docker compose stop`, encerrando todos os contêineres e redes iniciados pelo comando `up`.
</details>

<details>
<summary>INSTALL_DEPENDECIES</summary>

### `install_dependencies`
Instala as dependências PHP dentro do contêiner.
- **Uso:** `make install_dependencies`
- **Descrição:** Executa `composer install` dentro do contêiner PHP, instalando todas as dependências listadas no arquivo `composer.json`.

</details>

<details>
<summary>GENERATE_PROXIES</summary>

### `generate_proxies`
Gera os proxies do MongoDB.
- **Uso:** `make generate_proxies`
- **Descrição:** Executa `php bin/console doctrine:mongodb:generate:proxies`, gerando os arquivos de proxy necessários para a integração com MongoDB no projeto.
</details>

<details>
<summary>MIGRATE_DATABASE</summary>

### `migrate_database`
Executa as migrações no banco relacional e do não relacional
- **Uso:** `make migrate_database`
- **Descrição:** Executa `make migration_orm` e `make migration_odm` dentro do contêiner, aplicando todas as migrações pendentes.
</details>

<details>
<summary>MIGRATE_ORM</summary>

### `migrate_orm`
Executa as migrações no banco relacional
- **Uso:** `make migrate_orm`
- **Descrição:** Executa `php bin/console doctrine:migrations:migrate -n` dentro do contêiner, aplicando todas as migrações pendentes no banco de dados sem pedir confirmação adicional (`-n` significa *no interaction*).
</details>

<details>
<summary>MIGRATE_ODM</summary>

### `migrate_odm`
Executa as migrações no banco não relacional
- **Uso:** `make migrate_odm`
- **Descrição:** Executa `php bin/console app:mongo:migrations:execute` dentro do contêiner, aplicando todas as migrações pendentes no banco de dados não relacional.
</details>

<details>
<summary>LOAD_FIXTURES</summary>

### `load_fixtures`
Carrega os dados de *fixtures* no banco de dados.
- **Uso:** `make load_fixtures`
- **Descrição:** Executa `php bin/console doctrine:fixtures:load -n`, carregando dados fictícios (fixtures) no banco de dados. Útil para popular o banco com dados de teste.
</details>

<details>
<summary>INSTALL_FRONTEND</summary>

### `install_frontend`
Instala as dependências do frontend.
- **Uso:** `make install_frontend`
- **Descrição:** Executa `php bin/console importmap:install`, instalando as dependências frontend necessárias para o Aurora.
</details>

<details>
<summary>COMPILE_FRONTEND</summary>

### `compile_frontend`
Compila os arquivos do frontend.
- **Uso:** `make compile_frontend`
- **Descrição:** Executa `php bin/console asset-map:compile`, compilando os arquivos frontend (como CSS e JavaScript) para o Aurora.
</details>

<details>
<summary>OPEN_CYPRESS</summary>

### `tests_front`
Executa as fixtures de dados, concede permissão ao container e abre uma instância gráfica.
> Certifique-se de que o DISPLAY está corretamente configurado no seu ambiente. Você pode verificar isso rodando echo $DISPLAY no terminal. O valor típico é :0 ou :1.
- **Uso:** `make open_cypress`
    - **Descrição:** Carrega os dados de fixtures no banco de dados, concede a permissão ao acesso do servidor X11, permitindo que qualquer aplicação o acesse e em seguida abre uma instância gráfica.
      Após terminar de usar o Cypress, você pode revogar as permissões de acesso ao servidor X11 com o seguinte comando: `xhost -local:`.
> Se você estiver usando um sistema Linux com Wayland (em vez de X11), você pode precisar de configurações adicionais ou mudar para o X11.
</details>

<details>
<summary>TESTS_FRONT</summary>

### `tests_front`
Executa as fixtures de dados e os testes de frontend.
- **Uso:** `make tests_front`
- **Descrição:** Carrega os dados de fixtures no banco de dados e depois roda os testes de frontend com Cypress.
</details>

<details>
<summary>TESTS_BACK</summary>

### `tests_back`
Executa as fixtures de dados e os testes de backend.
- **Uso:** `make tests_back`
- **Descrição:** Carrega os dados de fixtures e roda os testes backend usando PHPUnit.

### `tests_back filename=tests/tests.php fixtures=no`
Executa apenas os testes para um arquivo especifico sem rodar as fixtures
- **Uso:** `make tests_back filename=tests/tests.php fixtures=no`
- **Descrição:** Roda um arquivo especifico do teste.
</details>

<details>

<summary>TESTS_BACK_COVERAGE</summary>

### `tests_back_coverage`
Executa as fixtures de dados, os testes de backend e gera um relatório sobre a atual cobertura de testes.
- **Uso:** `make tests_back_coverage`
- **Descrição:** Carrega os dados de fixtures e roda os testes backend usando PHPUnit, utiliza também o xdebug e a biblioteca phpunit/php-code-coverage.
> O resultado da análise de cobertura pode ser visto no diretório coverage-html, ou no arquivo coverage.xml
</details>

<details>
<summary>RESET</summary>

### `reset`
Limpa o cache do Aurora.
- **Uso:** `make reset`
- **Descrição:** Executa `php bin/console cache:clear` para limpar o cache gerado pela aplicação.
</details>

<details>
<summary>RESET DEEP</summary>

### `reset`
Faz um reset de tudo do diretório storage.
- **Uso:** `make reset-deep`
- **Descrição:** Executa `php bin/console cache:clear` para limpar o cache gerado pela aplicação, e outros comandos para excluir o conteudo do diretório `/var`
</details>

<details>
<summary>STYLE</summary>

### `style`
Executa o PHP CS Fixer.
- **Uso:** `make style`
- **Descrição:** Executa `php bin/console app:code-style` e `php vendor/bin/phpcs`  dentro do contêiner PHP para garantir que o código segue os padrões de estilo definidos pelo Aurora.
</details>

<details>
<summary>GENERATE_KEYS</summary>

### `generate_keys`
Gera as chaves de autenticação JWT.
- **Uso:** `make generate_keys`
- **Descrição:** Executa `php bin/console lexik:jwt:generate-keypair --overwrite` para gerar ou sobrescrever as chaves usadas para autenticação JWT.
</details>

<details>
<summary>SETUP</summary>

### `setup`
Executa uma sequência de passos de configuração.
- **Uso:** `make setup`
- **Descrição:** Este comando é um *shortcut* para rodar os comandos: `up`, `install_dependencies`, `generate_proxies`, `migrate_database`, `load_fixtures`, `install_frontend`, `compile_frontend`, e `generate_keys` de uma vez só.
</details>

## Instruções de Uso

Para usar qualquer um dos comandos, basta executar no terminal:
```bash
make <nome_do_comando>
