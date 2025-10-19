<?php

/**************************************************************************/
/*  Local.php                                                             */
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

class Local{

    protected static string $baseDB = __DIR__ . '/../../database/';

    /*
    protected static function update(string $file, array $data): array {
        $route = self::path($file);

        if(!file_exists($route)){
            return ["success" => false, "message" => "El archivo no existe.", "error_code" => 404];
        }

        $write = self::write($file, $data);

        if(!$write['success']){
            return ["success" => false, "message" => "Error al actualizar el archivo.", "error_code" => 500];
        }

        return ["success" => true, "message" => "El archivo se actualizó correctamente.", "error_code" => 200];
    }
    */

    protected static function write(string $file, array $data): array {
        $route = self::path($file);

        $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if($content === false){
            return ["success" => false, "message" => "Error al convertir los datos a JSON.", "error_code" => 500];
        }

        $write = file_put_contents($route, $content, LOCK_EX);
        if($write === false){
            return ["success" => false, "message" => "Error al escribir en el archivo.", "error_code" => 500];
        }

        return ["success" => true, "message" => "El archivo se escribió correctamente.", "error_code" => 200];
    }

    protected static function read(string $file): array{
        $route = self::path($file);

        if(!file_exists($route)){
            return ["success" => false, "message" => "El archivo no existe", "error_code" => 404];
        }

        $content = file_get_contents($route);

        if($content === false){
            return ["success" => false, "message" => "Error al leer el archivo.", "error_code" => 500];
        }

        $decode = json_decode($content, true);

        if($decode === null && json_last_error() !== JSON_ERROR_NONE){
            return ["success" => false, "message" => "Error al decodificar el JSON: " . json_last_error_msg(), "error_code" => 500];
        }

        return $decode ?? [];
    }

    public static function path(string $file): string {
        $file = str_replace(".json", "", $file);
        return self::$baseDB . $file . ".json";
    }

    public static function exists(string $file) : bool {
        return file_exists(self::path($file));
    }

    public static function scan(string $folder): array {
        $dir = self::$baseDB . $folder;
        if (!is_dir($dir)) return [];
        $files = scandir($dir);
        return array_values(array_filter($files, fn($f) => !in_array($f, ['.', '..'])));
    }
}