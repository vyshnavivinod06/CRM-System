<?php

namespace App\Contracts;

use App\Data\ContactData;
use Illuminate\Database\Eloquent\Model;

interface ContactSource
{
    public function contactData(): ContactData;

    public function sourceModel(): Model;
}
