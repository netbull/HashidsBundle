<?php

namespace NetBull\HashidsBundle\ArgumentResolver;

use Hashids\Hashids;
use NetBull\HashidsBundle\Attribute\HashId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class HashValueResolver implements ValueResolverInterface
{
    /**
     * @param Hashids|null $hashids
     * @param int $minHashLength
     */
    public function __construct(
        private ?Hashids $hashids = null,
        private int $minHashLength = 0
    ) {
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return array
     */
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if (!$options = $argument->getAttributesOfType(HashId::class, ArgumentMetadata::IS_INSTANCEOF)) {
            return [];
        }

        $options = $options[0];

        if ($options->disabled || !($hash = $this->getHash($request, $options, $argument)) || strlen($hash) < $this->minHashLength) {
            return [];
        }

        if ($decoded = $this->hashids->decode($hash)) {
            return $decoded;
        }

        if ($argument->isNullable()) {
            return [null];
        }

        throw new NotFoundHttpException($options->message ?? (sprintf('The hash could not be converted "%s".', self::class)));
    }

    /**
     * @param Request $request
     * @param HashId $options
     * @param ArgumentMetadata $argument
     * @return mixed
     */
    private function getHash(Request $request, HashId $options, ArgumentMetadata $argument): mixed
    {
        if (\is_array($options->hash)) {
            $id = [];
            foreach ($options->hash as $field) {
                // Convert "%s_uuid" to "foobar_uuid"
                if (str_contains($field, '%s')) {
                    $field = sprintf($field, $argument->getName());
                }

                $id[$field] = $request->attributes->get($field);
            }

            return $id;
        }

        if ($options->hash) {
            return $request->attributes->get($options->hash) ?? ($options->stripNull ? false : null);
        }

        $name = $argument->getName();

        if ($request->attributes->has($name)) {
            if (is_array($id = $request->attributes->get($name))) {
                return false;
            }

            foreach ($request->attributes->get('_route_mapping') ?? [] as $parameter => $attribute) {
                if ($name === $attribute) {
                    $options->mapping = [$name => $parameter];

                    return false;
                }
            }

            return $id ?? ($options->stripNull ? false : null);
        }

        if ($request->attributes->has('id')) {
            return $request->attributes->get('id') ?? ($options->stripNull ? false : null);
        }

        return false;
    }
}
