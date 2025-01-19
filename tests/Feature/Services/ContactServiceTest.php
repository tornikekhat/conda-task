<?php

namespace Tests\Feature\Services;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Services\ContactService;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    private ContactService $contactService;
    private $contactRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contactRepositoryMock = $this->createMock(ContactRepositoryInterface::class);

        $this->app->instance(ContactRepositoryInterface::class, $this->contactRepositoryMock);

        $this->contactService = app(ContactService::class);

        Mail::fake();
    }

    public function test_store_contact_successfully(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ];

        $contact = new Contact($data);

        $this->contactRepositoryMock
            ->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($contact);

        $response = $this->contactService->storeContact($data);

        $this->assertEquals(Response::HTTP_CREATED, $response['status']);
        $this->assertEquals('Contact submitted successfully', $response['message']['message']);
    }

    public function test_store_contact_email_failure(): void
    {
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'subject' => 'Another Test Subject',
            'message' => 'This is another test message.',
        ];

        $contact = new Contact($data);

        $this->contactRepositoryMock
            ->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($contact);

        Mail::shouldReceive('raw')->andThrow(new \Exception('Unable to connect or send email'));

        $response = $this->contactService->storeContact($data);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response['status']);
        $this->assertEquals('Email failed to send', $response['message']['message']);
        $this->assertEquals('Unable to connect or send email', $response['message']['error']);
    }

    public function test_send_email_successfully(): void
    {
        $contact = new Contact([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ]);

        $result = $this->contactService->sendEmail($contact);

        $this->assertTrue($result);
    }

    public function test_send_email_failure(): void
    {
        $contact = new Contact([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'subject' => 'Another Test Subject',
            'message' => 'This is another test message.',
        ]);

        Mail::shouldReceive('raw')->andThrow(new \Exception('Unable to connect or send email'));

        $result = $this->contactService->sendEmail($contact);

        $this->assertFalse($result);
    }
}
