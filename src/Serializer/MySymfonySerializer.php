<?php

namespace App\Serializer;

use App\Serializer\SerializerGroups;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class MySymfonySerializer
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    private function configNormalizer($groups = [], $ignoredFields = []): array
    {
        $defaultGroups = [SerializerGroups::PUBLIC];
        $groups = array_merge($defaultGroups, $groups);

        return [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => false,
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredFields,
            AbstractNormalizer::GROUPS => $groups,
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return  $object->getId();
            }
        ];
    }

    public function serialize($data, $groups = [], $ignoredFields = []): string
    {
        $context = $this->configNormalizer($groups, $ignoredFields);
        return $this->serializer->serialize($data, 'json', $context);
    }
}
