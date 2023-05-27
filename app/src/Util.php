<?php

namespace App;

class Util
{
    public static function slugify(array $book): string
    {
        $slug = strtolower($book['author'] .'_'.$book['title']);
        $slug = str_replace([' '], '_', $slug);
        $slug = str_replace(['àäâá'], 'a', $slug);
        $slug = str_replace(['éèëê'], 'e', $slug);
        $slug = str_replace(['ïî'], 'i', $slug);
        $slug = str_replace(['öô'], 'o', $slug);
        $slug = str_replace(['œ'], 'oe', $slug);
        $slug = str_replace(['æ'], 'ae', $slug);
        $slug = str_replace(['ùûü'], 'u', $slug);
        $slug = str_replace(['ç'], 'c', $slug);

        return preg_replace('/\W*/', '', $slug);
    }
}
