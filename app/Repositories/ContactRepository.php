<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;

class ContactRepository implements ContactRepositoryInterface
{
    public function create(array $data): Contact
    {
        return Contact::create($data);
    }
}
