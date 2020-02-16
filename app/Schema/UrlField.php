<?php

namespace App\Schema;

use App\Base;

class UrlField extends SimpleField
{
    public const TYPE = 'url';

    public function formatValue($value, Base $base)
    {
        return implode('<br>', array_map(
            function($url) {
                return "<a href=\"$url\">$url</a>";
            },
            explode(' ', $value)
        ));
    }
}
