<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk;

use BackedEnum;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\VisitorInterface;
use SandwaveIo\Freshdesk\ValueObject\Dictionary;

class SerializerFactory
{
    public static function create(): Serializer
    {
        $serializerBuilder = new SerializerBuilder();
        $serializerBuilder->addDefaultHandlers();
        $serializerBuilder->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerHandler(
                GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'Enum',
                'json',
                function (VisitorInterface $visitor, BackedEnum $object, array $type) {
                    return $object->value;
                }
            );

            $registry->registerHandler(
                GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'Enum',
                'json',
                function (VisitorInterface $visitor, mixed $data, array $type) {
                    $class = $type['params'][0];
                    return $class::tryFrom($data);
                }
            );

            $registry->registerHandler(
                GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                Dictionary::class,
                'json',
                function (VisitorInterface $visitor, Dictionary $object, array $type) {
                    return $object->toObject();
                }
            );

            $registry->registerHandler(
                GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                Dictionary::class,
                'json',
                function (VisitorInterface $visitor, mixed $data, array $type) {
                    return new Dictionary($data);
                }
            );
        });

        return $serializerBuilder->build();
    }
}
