<?php

/**************************************************************************/
/*  layout-view.php                                                       */
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

?>
<!-- daamlite core v<?= Core::get('version') ?> (<?= Core::get('state') ?>) (Copyright © 2025 Armin Deck – MIT License) – https://github.com/armindeck/daamlite -->
<!DOCTYPE html>
<html lang="<?= Setting::get('app_lang', 'es') ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($title ?? '') . " - " . (Setting::get('app_name') ?? '') ?></title>
    <link rel="stylesheet" href="<?= Setting::get('app_url') ?>/assets/css/style.css">
	<link rel="preload" href="<?= Setting::get('app_url') ?>/assets/image/favicon.png" as="image">
    <link rel="icon" type="image/png" href="<?= Setting::get('app_url') ?>/assets/image/favicon.png">
    <meta name="description" content="<?= $description ?? '' ?>" />
	<meta property="og:title" content="<?= $title ?? '' ?>" />
	<meta property="og:description" content="<?= $description ?? '' ?>" />
	<meta property="og:url" content="<?= Setting::get('app_url') ?>/<?= $route ?? '' ?>">
	<link rel="canonical" href="<?= Setting::get('app_url') ?>/<?= $route ?? '' ?>">
	<meta property="og:image" content="<?= Setting::get('app_url') ?>/<?= $thumbnail ?? '' ?>" />
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="<?= Setting::get('app_name') ?? '' ?>" />
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?= $title ?? '' ?>">
	<meta name="twitter:description" content="<?= $description ?? '' ?>">
	<meta name="twitter:image" content="<?= Setting::get('app_url') ?>/<?= $thumbnail ?? '' ?>">
	<meta name="keywords" content="<?= ($keywords ?? '') . ', ' . (Setting::get('app_name') ?? '') . ', ' . Setting::get('app_url') ?>">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="app">
        <header class="header" id="header">
            <div class="header__logo">
                <a href="<?= Setting::get("app_url") ?>"><?= Setting::get("app_name") ?></a>
            </div>
            <nav class="header__nav">
                <a href="<?= Setting::get("app_url") ?>">
                    <div class="icon"><i class="fa-solid fa-house"></i></div>
                    <div class="text">Home</div>
                </a>
                <a href="<?= Setting::get("app_url") ?>/blog/">
                    <div class="icon"><i class="fa-solid fa-blog"></i></div>
                    <div class="text">Blog</div>
                </a>
                <a href="<?= Setting::get("app_url") ?>/post/">
                    <div class="icon"><i class="fa-solid fa-pager"></i></div>
                    <div class="text">Posts</div>
                </a>
            </nav>
        </header>
        <main class="main">
            <?php if(isset($title) && !empty($title)): ?>
                <h1 class="main__title"><?= htmlspecialchars($title) ?></h1>
            <?php endif; ?>
            <?php if(isset($content) && !empty($content)): ?>
                <div class="main__content"><?= nl2br(htmlspecialchars($content)) ?></div>
            <?php endif; ?>
            <?php if(isset($type) && $type == "page" && isset($entries)): ?>
                <?php if (!empty($posts)): ?>
                    <ul>
                        <?php foreach ($posts as $post): ?>
                            <li>
                                <h2><?= htmlspecialchars($post['title']) ?></h2>
                                <p><?= htmlspecialchars($post['description'] ?? '') ?></p>
                                <a href="<?= Setting::get('app_url') . '/' . ($post['route'] ?? '') ?>">Read More</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No posts available.</p>
                <?php endif; ?>
            <?php endif; ?>
        </main>
        <footer class="footer">
            <nav class="footer__nav">
                <a href="" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                <a href="" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                <a href="" target="_blank"><i class="fa-brands fa-github"></i></a>
            </nav>
            <div class="footer__copyright">
                <p>&copy; <?= date('Y') ?> <?= Setting::get('app_name', 'daamlite') ?>. All rights reserved.</p>
                <?php if (Setting::get('show_project_footer', true)): ?>
                    <small>Powered by <a href="https://github.com/armindeck/daamlite" target="_blank" rel="noopener noreferrer">daamlite</a> v<?= Core::get('version', '1.0.0') . ' (' . Core::get('state', 'stable') . ' ~ ' . str_replace("-", ".", Core::get('updated', '2025-10-16')) . ')' ?> – MIT License</small>
                <?php endif; ?>
            </div>
        </footer>
    </div>
</body>
</html>