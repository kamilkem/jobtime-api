<?php

/**
 * This file is part of the JobTime package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Serializer;

use ApiPlatform\Metadata\Patch;
use ApiPlatform\Serializer\AbstractItemNormalizer;
use App\State\InitializableProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

use function get_object_vars;
use function is_iterable;
use function is_object;
use function property_exists;
use function sprintf;

#[AsDecorator(decorates: 'api_platform.serializer.normalizer.item')]
class ItemNormalizerDecorator implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function __construct(
        private readonly AbstractItemNormalizer $decorated,
        /** @var iterable<InitializableProcessorInterface> */
        #[TaggedIterator(tag: 'app_initializable_processor')]
        private readonly iterable $processors
    ) {
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $this->decorated->setSerializer($this->serializer);

        $operation = $context['operation'] ?? null;

        if (!$operation instanceof Patch || !$operation->getInput() || !is_iterable($data)) {
            return $this->decorated->denormalize($data, $type, $format, $context);
        }

        foreach ($this->processors as $processor) {
            if ($processor::class !== $operation->getProcessor()) {
                continue;
            }

            $serializedObject = $this->decorated->denormalize($data, $type, $format, $context);

            if (!is_object($serializedObject)) {
                continue;
            }

            $initializedObject = $processor->initialize($data, $type, $format, $context);

            foreach (get_object_vars($serializedObject) as $field => $value) {
                if (!property_exists($initializedObject, $field)) {
                    continue;
                }

                try {
                    $initializedObject->$field = $value;
                } catch (\TypeError) {
                    throw new UnprocessableEntityHttpException(
                        sprintf('The field "%s" was not expected.', $field)
                    );
                }
            }

            return $initializedObject;
        }

        return $this->decorated->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $this->decorated->supportsDenormalization($data, $type, $format, $context);
    }

    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $this->decorated->setSerializer($this->serializer);

        return $this->decorated->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $this->decorated->supportsNormalization($data, $format, $context);
    }

    public function getSupportedTypes(?string $format): array
    {
        return $this->decorated->getSupportedTypes($format);
    }
}
