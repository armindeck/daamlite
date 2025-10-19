<?php

/**************************************************************************/
/*  User.php                                                              */
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

class User extends Local{
    
    private static string $file = 'users';

    public static function create(array $data): array {
        $users = self::read(self::$file);

        $data['id'] = count($users) + 1;
        while (array_search($data['id'], array_column($users, 'id')) !== false) {
            $data['id']++;
        }

        $data['token'] = bin2hex(random_bytes(16));
        while (array_search($data['token'], array_column($users, 'token')) !== false) {
            $data['token'] = bin2hex(random_bytes(16));
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $data['date_registered'] = date('Y-m-d H:i:s');

        $users[] = $data;
        return self::write(self::$file, $users);
    }

    public static function all(): array{
        return self::read(self::$file);
    }
}