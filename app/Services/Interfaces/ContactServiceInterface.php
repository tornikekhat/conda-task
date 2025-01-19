<?php

namespace App\Services\Interfaces;

use App\Models\Contact;

interface ContactServiceInterface
{
    public function storeContact(array $data): array;

    public function sendEmail(Contact $contact): bool;
}
