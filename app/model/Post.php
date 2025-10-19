<?php

/**************************************************************************/
/*  Post.php                                                              */
/**************************************************************************/
/*                        This file is part of:                           */
/*                              daamlite                                  */
/*                 https://github.com/armindeck/daamlite                  */
/**************************************************************************/
/* Copyright (c) 2025 Armin Deck                                          */
/*                                                                        */
/* Permission is hereby granted, free of charge, to any person obtaining  */
/* a copy of this software and associated documentation files (the        */
/* "Software"), to deal in the Software without restriction, including    */
/* without limitation the rights to use, copy, modify, merge, publish,    */
/* distribute, sublicense, and/or sell copies of the Software, and to     */
/* permit persons to whom the Software is furnished to do so, subject to  */
/* the following conditions:                                              */
/*                                                                        */
/* The above copyright notice and this permission notice shall be         */
/* included in all copies or substantial portions of the Software.        */
/*                                                                        */
/* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,        */
/* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF     */
/* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. */
/* IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY   */
/* CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,   */
/* TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE      */
/* SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.                 */
/**************************************************************************/

class Post extends Local{
    
    private static string $route = 'post/';

    public static function create(string $route, array $data): array{
        // Historial de posts
        $posts = self::read("posts");

        if(isset($posts["success"]) && $posts["success"] === false){
            if(!in_array($posts["error_code"], [404])){
                return $posts;
            } else {
                $posts = [];
            }
        }

        // ruta única
        while (array_search($route, array_column($posts, 'route')) !== false) {
            return ["success" => false, "message" => "El post ya existe en los posts, actualiza el existente.", "error_code" => 400];
        }

        // id único
        $data['id'] = count($posts) + 1;
        while (array_search($data['id'], array_column($posts, 'id')) !== false) {
            $data['id']++;
        }

        $data['reactions'] = [];
        $data['comments'] = [];
        $data['reports'] = [];

        $data['date_published'] = date('Y-m-d H:i:s');

        $file_name = str_replace('/', '-', $route);

        if(self::exists(self::$route . $file_name)) {
            return ["success" => false, "message" => "El post ya existe, actualiza el existente.", "error_code" => 400];
        }

        $save_post = self::write(self::$route . $file_name, $data);

        if(isset($save_post["success"]) && $save_post["success"] === false) {
            return $save_post;
        }

        $new_data = [
            'id' => $data['id'],
            'route' => $data['route'],
            'title' => $data['title'],
            'user_token' => $data['user_token'],
            'type' => $data['type'],
            'date_published' => $data['date_published']
        ];

        $posts[] = $new_data;

        $save_posts = self::write("posts", $posts);

        if(isset($save_posts["success"]) && $save_posts["success"] === false) {
            return $save_posts;
        }

        return ["success" => true, "message" => "El post se guardó correctamente"];
    }

    public static function get(string $route = ''): array {
        $route = !empty($route) ? $route : Route::get();
        $file_name = str_replace('/', '-', $route) . ".json";
        if(!self::exists(self::$route . $file_name)){
            return ["success" => false, "message" => "El post no existe", "error_code" => 404];
        }

        return self::read(self::$route . $file_name);
    }

    public static function all(): array{
        $posts = self::read("posts");
        if(isset($posts["success"]) && $posts["success"] === false){
            return [];
        }

        $scan = self::scan(self::$route);

        if(isset($scan["success"]) && $scan["success"] === false){
            return $posts;
        }

        foreach ($posts as $index => $post) {
            $file_name = str_replace('/', '-', $post['route']) . ".json";
            if(in_array($file_name, $scan)){
                $post_data = self::read(self::$route . $file_name);
                if(isset($post_data["success"]) && $post_data["success"] === false){
                    continue;
                }
                $posts[$index] = array_merge($post, $post_data);
            }
        }

        return $posts;
    }

    public static function allType($types, int $limit = 10, bool $reverse = false): array {
        $posts = self::all();
        $filtered_posts = [];
        $types = explode(',', is_array($types) ? implode(',', $types) : $types);

        if($reverse){
            $posts = array_reverse($posts);
        }

        $i = 0;
        foreach ($posts as $post) {
            if (isset($post['type']) && !empty($post['type'])) {
                if (in_array($post['type'], array_map('trim', $types))) {
                    $filtered_posts[] = $post;
                }
            }
            $i++;
            if ($i >= $limit) {
                break;
            }
        }

        return $filtered_posts;
    }
}