<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Services\Interfaces\ContactServiceInterface;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class ContactService implements ContactServiceInterface
{
    public function __construct(protected ContactRepositoryInterface $contactRepository)
    {
    }

    public function storeContact(array $data): array
    {
        $contact = $this->contactRepository->create($data);

        $emailSent = $this->sendEmail($contact);

        if (!$emailSent) {
            return [
                'message' => [
                    'message' => 'Email failed to send',
                    'error' => 'Unable to connect or send email'
                ],
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }

        return [
            'message' => [
                'message' => 'Contact submitted successfully'
            ],
            'status' => Response::HTTP_CREATED,
        ];
    }

    public function sendEmail(Contact $contact): bool
    {
        try {
            Mail::raw($contact->message, function ($message) use ($contact) {
                $message->to('test@example.com')
                    ->subject($contact->subject)
                    ->from($contact->email, $contact->name);
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
