<?php

return [
    'themes' => env('ARTICLES_THEMES') ? explode(',', env('ARTICLES_THEMES')) : [],
    'delay_per_article' => env('PER_ARTICLE_REQUEST_DELAY', 1)
];
