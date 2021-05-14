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

    public static $addLinks = "INSERT INTO playlistlink (link_id, playlist_id, link) VALUES (NULL, :playlist_id, :link)";
}

