<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use MyCLabs\Enum\Enum;

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
                function ($visitor, Enum $object, array $type) {
                    return $object->getValue();
                }
            );

            $registry->registerHandler(
                GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'Enum',
                'json',
                function ($visitor, $data, array $type) {
                    $class = $type['params'][0];
                    return new $class($data);
                }
            );
        });

        return $serializerBuilder->build();
    }
}
