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

### API Endpoints

| Func                      | Type   | Description                                                                      |
|:-------------------------:|:------:|:---------------------------------------------------------------------------------|
| getAllCategories          | `GET`  | Fetch all unique categories present in the database ordered by their view count. |
| getPopularCategories      | `GET`  | Same as *getAllCategories* except limits the result to 5 entries.                |
| getPlaylistsByCategory    | `GET`  | Fetch all playlists by Category name ordered by their view count.                |
| getPlaylistById           | `GET`  | Fetch all the links of a playlist.                                               |
| createPlaylist            | `POST` | Create a new playlist.                                                           |
| createCategory            | `POST` | Create a new Category.                                                           |
| increaseCategoryViewCount | `PUT`  | Increase a Category's view count by 1.                                           |
| increasePlaylistViewCount | `PUT`  | Increase a Playlist's view count by 1.                                           |

The format of the Endpoint is: `{URL}/api/{func}`. For example, `http://www.example.com/api/getAllCategories`
