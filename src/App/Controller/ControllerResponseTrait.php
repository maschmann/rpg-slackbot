<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Serializer\SerializerInterface;

trait ControllerResponseTrait
{
    private SerializerInterface $serializer;

    public function createJsonResponseFromEnvelope(
        Envelope $envelope,
        int $status = JsonResponse::HTTP_OK
    ): JsonResponse {
        /** @var HandledStamp $stamp */
        $stamp = $envelope->last(HandledStamp::class);
        $result = $stamp->getResult();

        $data = $this->serializer->serialize(
            $result,
            'json',
            [
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS |
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_PRESERVE_ZERO_FRACTION,
            ]
        );

        return JsonResponse::fromJsonString($data, $status);
    }
}
