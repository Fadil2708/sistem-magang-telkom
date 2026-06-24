<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

class ResendTransport extends AbstractTransport
{
    public function __construct(
        protected string $apiKey,
        ?\Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher = null,
        ?\Psr\Log\LoggerInterface $logger = null,
    ) {
        parent::__construct($dispatcher, $logger);
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $envelope = $message->getEnvelope();

        $from = $envelope->getSender()->toString();

        $recipients = array_filter(
            $envelope->getRecipients(),
            fn(Address $addr) => !in_array($addr, $email->getCc(), true) && !in_array($addr, $email->getBcc(), true),
        );

        $payload = [
            'from' => $from,
            'to' => $this->stringifyAddresses($recipients),
            'subject' => $email->getSubject(),
            'html' => $email->getHtmlBody() ?? '',
        ];

        if ($text = $email->getTextBody()) {
            $payload['text'] = $text;
        }

        if ($cc = $email->getCc()) {
            $payload['cc'] = $this->stringifyAddresses($cc);
        }

        if ($bcc = $email->getBcc()) {
            $payload['bcc'] = $this->stringifyAddresses($bcc);
        }

        if ($replyTo = $email->getReplyTo()) {
            $payload['reply_to'] = $this->stringifyAddresses($replyTo);
        }

        $response = Http::withToken($this->apiKey)
            ->post('https://api.resend.com/emails', $payload);

        if ($response->failed()) {
            throw new TransportException(
                'Resend API error: ' . $response->body(),
            );
        }
    }

    protected function stringifyAddresses(array $addresses): array
    {
        return array_map(fn(Address $addr) => $addr->toString(), $addresses);
    }

    public function __toString(): string
    {
        return 'resend-api';
    }
}
