<?php

namespace Tests\Feature\Controllers;

use App\Services\Interfaces\ContactServiceInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $contactServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contactServiceMock = $this->createMock(ContactServiceInterface::class);

        $this->app->instance(ContactServiceInterface::class, $this->contactServiceMock);
    }

    public function test_store_contact_successfully(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ];

        $responseMessage = 'Contact submitted successfully';
        $status = Response::HTTP_CREATED;

        $this->contactServiceMock
            ->expects($this->once())
            ->method('storeContact')
            ->with($data)
            ->willReturn([
                'status' => $status,
                'message' => ['message' => $responseMessage],
            ]);

        $response = $this->postJson(route('contact.store'), $data);

        $response->assertStatus($status);
        $response->assertJson([
            'message' => $responseMessage,
        ]);
    }

    public function test_store_contact_email_failure(): void
    {
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'subject' => 'Another Test Subject',
            'message' => 'This is another test message.',
        ];

        $responseMessage = 'Email failed to send';
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        $errorMessage = 'Unable to connect or send email';

        $this->contactServiceMock
            ->expects($this->once())
            ->method('storeContact')
            ->with($data)
            ->willReturn([
                'status' => $status,
                'message' => [
                    'message' => $responseMessage,
                    'error' => $errorMessage,
                ],
            ]);

        $response = $this->postJson(route('contact.store'), $data);

        $response->assertStatus($status);
        $response->assertJson([
            'message' => $responseMessage,
            'error' => $errorMessage,
        ]);
    }
}
