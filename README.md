# Jam (Backend)

This repository hosts the server side code for Jam, a playlist making website.

## Getting Started

- Install and run `composer install`. This will install the dependencies in a `vendor/` folder.

- Create a `.env` file in `/` and copy the ENV schema from `.env.example`.

- To Create a local user, follow these steps (inside a terminal):

```sql
1. mysql -u root -p
2. CREATE DATABASE jam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
3. CREATE USER 'dev'@'localhost' identified by 'dev';
4. GRANT ALL on jam.* to 'dev'@'localhost';
5. quit
```

- VS Code Extensions used: [phpfmt](https://marketplace.visualstudio.com/items?itemName=kokororin.vscode-phpfmt) and [PHP IntelliSense](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-intellisense)


