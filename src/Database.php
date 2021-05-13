<?php
namespace Src;

class Database
{

    private $dbConnection = null;

    // Add Category, Playlist and ID tables
    private function createTables() {
        $categoryTable = 'CREATE TABLE IF NOT EXISTS categories(
            category_id INT AUTO_INCREMENT,
            category_name VARCHAR(255) UNIQUE NOT NULL,
            category_view_count INT DEFAULT 0,
            PRIMARY KEY (category_id)
        )';
        $stmt = $this->dbConnection->prepare($categoryTable);
        $stmt->execute();

        $playlistTable = 'CREATE TABLE IF NOT EXISTS playlists(
            playlist_id INT AUTO_INCREMENT,
            playlist_name VARCHAR(255) NOT NULL,
            playlist_description VARCHAR(255) DEFAULT "",
            playlist_view_count INT DEFAULT 0,
            PRIMARY KEY (playlist_id)
        )';
        $stmt = $this->dbConnection->prepare($playlistTable);
        $stmt->execute();

        $playlistLinkTable = 'CREATE TABLE IF NOT EXISTS playlistLink(
            link_id INT AUTO_INCREMENT,
            link VARCHAR(255) UNIQUE NOT NULL,
            playlist_id INT,
            PRIMARY KEY (link_id),
            FOREIGN KEY (playlist_id) REFERENCES playlists (playlist_id) ON DELETE CASCADE
        )';
        $stmt = $this->dbConnection->prepare($playlistLinkTable);
        $stmt->execute();

        $categoryPlaylistTable = 'CREATE TABLE IF NOT EXISTS category_playlist(
            playlist_id INT,
            category_id INT,
            FOREIGN KEY (playlist_id) REFERENCES playlists (playlist_id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories (category_id) On DELETE CASCADE
        )';
        $stmt = $this->dbConnection->prepare($categoryPlaylistTable);
        $stmt->execute();
    }

    public function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;port=$port;dbname=$db",
                $user,
                $pass
            );
            $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (\PDOException$e) {
            exit($e->getMessage());
        }
    }

    public function connect()
    {
        return $this->dbConnection;
    }
}
