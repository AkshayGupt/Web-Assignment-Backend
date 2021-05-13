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

|                          Func                           |  Type  | Description                                                                      |
| :-----------------------------------------------------: | :----: | :------------------------------------------------------------------------------- |
|          [getAllCategories](#getallcategories)          | `GET`  | Fetch all unique categories present in the database ordered by their view count. |
|      [getPopularCategories](#getpopularcategories)      | `GET`  | Same as _getAllCategories_ except limits the result to 5 entries.                |
|    [getPlaylistsByCategory](#getplaylistsbycategory)    | `GET`  | Fetch all playlists by Category name ordered by their view count.                |
|           [getPlaylistById](#getplaylistbyid)           | `GET`  | Fetch all the links of a playlist.                                               |
|            [createPlaylist](#createplaylist)            | `POST` | Create a new playlist.                                                           |
| [increaseCategoryViewCount](#increasecategoryviewcount) | `PUT`  | Increase a Category's view count by 1.                                           |
| [increasePlaylistViewCount](#increaseplaylistviewcount) | `PUT`  | Increase a Playlist's view count by 1.                                           |

The format of the Endpoint is: `{URL}/api/{func}`. For example, `http://www.example.com/api/getAllCategories`

### Sample Input/Output of the API Endpoints

#### getAllCategories

##### Input

NONE

##### Output

```json
{
  "status": "200",
  "data": [
    {
      "category_id": "1",
      "category_name": "meditation",
      "category_view_count": "0"
    },
    {
      "category_id": "2",
      "category_name": "gaming",
      "category_view_count": "0"
    },
    {
      "category_id": "5",
      "category_name": "rock",
      "category_view_count": "0"
    },
    {
      "category_id": "24",
      "category_name": "pop",
      "category_view_count": "0"
    },
    {
      "category_id": "52",
      "category_name": "soulful",
      "category_view_count": "0"
    },
    {
      "category_id": "241",
      "category_name": "motivational",
      "category_view_count": "0"
    },
    {
      "category_id": "53",
      "category_name": "party",
      "category_view_count": "0"
    },
    {
      "category_id": "241",
      "category_name": "dj",
      "category_view_count": "0"
    }
  ]
}
```

#### getPopularCategories

This is the same as `getAllCategories` except that this one just limits the data to 5 rows.

##### Input

NONE

##### Output

```json
{
  "status": "200",
  "data": [
    {
      "category_id": "1",
      "category_name": "meditation",
      "category_view_count": "0"
    },
    {
      "category_id": "2",
      "category_name": "gaming",
      "category_view_count": "0"
    },
    {
      "category_id": "5",
      "category_name": "rock",
      "category_view_count": "0"
    },
    {
      "category_id": "24",
      "category_name": "pop",
      "category_view_count": "0"
    },
    {
      "category_id": "52",
      "category_name": "soulful",
      "category_view_count": "0"
    }
  ]
}
```

#### getPlaylistsByCategory

##### Input

```json
{
  "category_name": "music"
}
```

##### Output

```json
{
  "status": "200",
  "data": [
    {
      "playlist_id": "3",
      "playlist_name": "Playlist 3",
      "playlist_description": "",
      "playlist_view_count": "4",
      "category_id": "1",
      "category_name": "music"
    },
    {
      "playlist_id": "1",
      "playlist_name": "Playlist 1",
      "playlist_description": "",
      "playlist_view_count": "0",
      "category_id": "1",
      "category_name": "music"
    }
  ]
}
```

#### getPlaylistById

##### Input

```json
{
  "playlist_id": "1"
}
```

##### Output

```json
{
  "status": "200",
  "data": [
    {
      "link_id": "1",
      "link": "Link 1",
      "playlist_id": "1",
      "playlist_name": "Playlist 1"
    },
    {
      "link_id": "2",
      "link": "Link 2",
      "playlist_id": "1",
      "playlist_name": "Playlist 1"
    }
  ]
}
```

#### createPlaylist

##### Input

`playlist_description` is Optional.

```json
{
  "playlist_name": "Random Playlist",
  "playlist_description": "Playlist Description",
  "category_name": "music"
}
```

##### Output

```json
{
  "status": "200",
  "data": "Playlist created successfully!"
}
```

#### increaseCategoryViewCount

##### Input

```json
{
  "category_id": "1"
}
```

##### Output

```json
{
  "status": "200",
  "data": []
}
```

#### increasePlaylistViewCount

##### Input

```json
{
  "playlist_id": "1"
}
```

##### Output

```json
{
  "status": "200",
  "data": []
}
```
