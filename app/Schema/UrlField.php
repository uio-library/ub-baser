<?php

namespace App\Schema;

class UrlField extends SimpleField
{
    public const TYPE = 'url';

    public function formatValue($value)
    {
        return implode('<br>', array_map(
            function($url) {
                return "<a href=\"$url\">$url</a>";
            },
            explode(' ', $value)
        ));
    }
}
