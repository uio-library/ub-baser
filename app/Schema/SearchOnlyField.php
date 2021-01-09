<?php

namespace App\Schema;

class SearchOnlyField extends SchemaField
{
    public const TYPE = 'search_only';

    public function setDefaults()
    {
        $this->data['showInTableView'] = false;
        $this->data['showInRecordView'] = false;
        $this->data['edit']->init(false);
        $this->data['search']->widget = 'autocomplete';
    }
}
