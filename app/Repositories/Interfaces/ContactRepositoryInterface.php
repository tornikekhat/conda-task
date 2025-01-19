<?php

namespace App\Repositories\Interfaces;

use App\Models\Contact;

interface ContactRepositoryInterface
{
    public function create(array $data): Contact;
}
