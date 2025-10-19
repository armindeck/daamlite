<?php

/**************************************************************************/
/*  Core.php                                                              */
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

class Core extends Local{
    private static array $cache = [];
    private static string $file_core = 'core/core';
    private static string $file_version = 'core/version';

    public static function init(): void {
        if (empty(self::$cache)) {
            self::$cache = self::read(self::$file_core);
            self::$cache['versions'] = self::read(self::$file_version);
        }
    }

    public static function get(string $key = '', $default = null) {
        return !empty($key) ? (self::$cache[$key] ?? $default) : self::$cache;
    }

    public static function getVersion(string $key = '', $default = null) {
        return !empty($key) ? (self::$cache['versions'][$key] ?? $default) : self::$cache['versions'];
    }

    public static function reload(): void {
        self::$cache = self::read(self::$file_core);
        self::$cache['versions'] = self::read(self::$file_version);
    }
}