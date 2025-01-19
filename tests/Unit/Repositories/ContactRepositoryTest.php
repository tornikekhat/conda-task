<?php

namespace Tests\Unit\Repositories;

use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContactRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private ContactRepository $contactRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contactRepository = new ContactRepository();
    }

    public function test_create_method_stores_contact_in_database(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ];

        $contact = $this->contactRepository->create($data);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals($data['name'], $contact->name);
        $this->assertEquals($data['email'], $contact->email);
        $this->assertEquals($data['subject'], $contact->subject);
        $this->assertEquals($data['message'], $contact->message);

        $this->assertDatabaseHas('contacts', $data);
    }
}
