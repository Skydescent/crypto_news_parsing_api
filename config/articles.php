<?php

return [
    'themes' => env('ARTICLES_THEMES') ? explode(',', env('ARTICLES_THEMES')) : []
];
