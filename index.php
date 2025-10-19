<?php

/**************************************************************************/
/*  index.php                                                             */
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


session_start();
require_once __DIR__ . '/app/model/Local.php';
require_once __DIR__ . '/app/core/Core.php'; Core::init();
require_once __DIR__ . '/app/core/Route.php';
require_once __DIR__ . '/app/model/Setting.php'; Setting::init();
require_once __DIR__ . '/app/model/User.php';
require_once __DIR__ . '/app/model/Post.php';
require_once __DIR__ . '/app/core/View.php';

date_default_timezone_set(Setting::get('timezone', 'America/Bogota'));

//User::create(['name' => 'Pepe', 'password' => 'secret', 'email' => 'john@example.com', 'age' => 30]);

//var_dump(User::all());



/*
$one = Post::create("blog/mi-nuevo-setup", [
    'title' => 'Mi nuevo setup de juegos de dbproject',
    'description' => 'Conoce el nuevo setup de juegos de dbproject para este año.',
    'content' => 'Este es el contenido completo del post acerca de mi nuevo setup de juegos de dbproject. Aquí hablo sobre los componentes, la configuración y por qué es genial para jugar.',
    'user_token' => 'abc123',
    'type' => 'blog',
    'route' => 'blog/mi-nuevo-setup'
]);

/*
var_dump($one);

$two = Post::create("acerca-de", [
    'title' => 'About',
    'content' => 'This is the body of the about post.',
    'user_token' => 'abc123',
    'type' => 'page',
    'route' => 'acerca-de'
]);

var_dump($two);

//echo "<br><br>";

//var_dump(Core::get("creator_name"));
*/

require_once __DIR__ . '/app/routes/web.php';