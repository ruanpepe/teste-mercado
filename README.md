# Desafio (Teste do mercado)

Projeto criado com finalidade de testar conhecimentos

## Instalação

Após realizar o pull no projeto do git, faça uma cópia do arquivo ".env.example" e renomeie para ".env".
No seu ".env", insira as informações necessárias para a conexão com o banco de dados.

Instale os pacotes do composer
```bash
composer install
```

Inicialize os containers do docker
```bash
docker-compose up -d
```

Habilite o driver do postgre no PHP
```bash
sudo apt install php-pgsql
```

Inicialize o servidor do PHP
```bash
php -S localhost:8080
```

## License

[MIT](https://choosealicense.com/licenses/mit/)