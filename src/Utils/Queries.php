<?php
namespace Src\Utils;

class Queries
{
    public static $getAllCategories = "SELECT * FROM categories ORDER BY category_view_count DESC";
    public static $getPopularCategories = "SELECT * FROM categories ORDER BY category_view_count DESC LIMIT 5";

    public static $getPlaylistsByCategory = "SELECT P.playlist_id, P.playlist_name, P.playlist_description, P.playlist_view_count, R.category_id, R.category_name FROM playlists P,
                                            (SELECT category_id, category_name FROM categories WHERE category_name = ?) AS R
                                            WHERE P.category_id = R.category_id ORDER BY P.playlist_view_count DESC";
    public static $getPlaylistById = "SELECT * FROM playlistlink PL, (SELECT playlist_id, playlist_name FROM playlists WHERE playlist_id = ?) as R
                                        WHERE R.playlist_id = PL.playlist_id";

    public static $increasePlaylistViewCount = "UPDATE playlists SET playlist_view_count = playlist_view_count + 1 WHERE playlist_id = ?";
    public static $increaseCategoryViewCount = "UPDATE categories SET category_view_count = category_view_count + 1 WHERE category_id = ?";

    public static $createCategory = "INSERT INTO categories (category_id, category_name, category_view_count) VALUES (NULL, ?, 0)";
    public static $getCategoryId = "SELECT category_id FROM categories WHERE category_name = ?";

    public static $createPlaylist = "INSERT INTO playlists (playlist_id, category_id, playlist_name, playlist_description, playlist_view_count, created_at)
                                    VALUES (NULL, :category_id, :playlist_name, :playlist_description, 0, :created_at)";

    public static $getPlaylist = "SELECT playlist_id FROM playlists WHERE playlist_name = :playlist_name and category_id = :category_id and created_at = :created_at";

    public static $addLinks = "INSERT INTO playlistlink (link_id, playlist_id, link, title, author_name, author_url, thumbnail_url) 
                                VALUES (NULL, :playlist_id, :link, :title, :author_name, :author_url, :thumbnail_url)";

    /**
     * Parse and retreive metadata from a URL
     * @param string $link
     * @return array Array containing title, author_name, author_url nad thumbnail_url
     */
    public static function getLinkData($link)
    {
        $url = "https://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=:id&format=json";
        $id = Queries::getYoutubeIdFromUrl($link);
        $url = str_replace(":id", $id, $url);

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        );

        $curl = curl_init($url);
        curl_setopt_array($curl, $options);

        $content = json_decode(curl_exec($curl));
        $result['title'] = $content->title;
        $result['author_name'] = $content->author_name;
        $result['author_url'] = $content->author_url;
        $result['thumbnail_url'] = $content->thumbnail_url;
        return $result;
    }

    /**
     * Get Youtube video ID from URL
     *
     * @param string $url
     * @return mixed Youtube video ID or FALSE if not found
     */
    private static function getYoutubeIdFromUrl($url)
    {
        $parts = parse_url($url);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $qs);
            if (isset($qs['v'])) {
                return $qs['v'];
            } else if (isset($qs['vi'])) {
                return $qs['vi'];
            }
        }
        if (isset($parts['path'])) {
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path) - 1];
        }
        return false;
    }
}
