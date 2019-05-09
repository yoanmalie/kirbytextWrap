<?php
/**
 * @package yoanmalie/kirbytextwrap
 * @version 1.2.0
 * @license MIT
 * @author Yoan Malié <hello@yoan-malie.fr>
 */

use Kirby\Toolkit\Html as Html;

/**
 * Check for Kirby version
 * @param int $v
 *      Kirby version targeted
 * @return false/true
 */
function isVersion($v)
{
    return Str::startsWith(Kirby::version(), strval($v));
}

// Kirby 2 plugin definition
if (isVersion(2)) {
    field::$methods['kirbytextWrap'] = field::$methods['ktw'] = function (
        $field,
        $tag = false,
        $attr = []
    ) {
        return kirbytextWrap($field, $tag, $attr);
    };
}

// Kirby 3 plugin definition
if (isVersion(3)) {
    Kirby::plugin('yoanmalie/kirbytextwrap', [
        'fieldMethods' => [
            'kirbytextWrap' => function ($field, $tag = false, $attr = []) {
                return kirbytextWrap($field, $tag, $attr);
            },
            'ktw' => function ($field, $tag = false, $attr = []) {
                return kirbytextWrap($field, $tag, $attr);
            },
        ],
    ]);
}

/**
 * Plugin core
 * @param object $field
 *      Kirby field object
 * @param string $tag
 *      A new enclosing tag
 * @param string|array $attr
 *      Pass HTML attributes and their values to the new tag
 * @return mixed
 */
function kirbytextWrap($field, $tag, $attr)
{
    $text = $field->kirbytext();

    if (function_exists('kirbytextinline')) {
        // Remove <p> tag natively
        $text = $field->kirbytextinline();
    } else {
        // Remove <p> tag manually
        if (Str::startsWith($text, '<p>') && Str::endsWith($text, '</p>')) {
            $text = Str::between($text, '<p>', '</p>');
        }
    }

    // Add new tag and his attributes
    if ($tag) {
        $text = isVersion(2) ? brick($tag, $text, $attr) : $text;
        $text = isVersion(3) ? Html::tag($tag, $text, $attr) : $text;
    }

    return $text;
}
