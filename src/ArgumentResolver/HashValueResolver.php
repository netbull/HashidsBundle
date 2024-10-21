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
     * @param HashId $defaults
     */
    public function __construct(
        private ?Hashids $hashids = null,
        private HashId $defaults = new HashId(),
    ) {
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return array
     */
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if (\is_object($request->attributes->get($argument->getName()))) {
            return [];
        }

        $options = $argument->getAttributes(HashId::class, ArgumentMetadata::IS_INSTANCEOF);
        $options = ($options[0] ?? $this->defaults)->withDefaults($this->defaults);

        if ($options->disabled) {
            return [];
        }
        if (!$hash = $this->getHash($request, $options, $argument)) {
            return [];
        }

        $decoded = $this->hashids->decode($hash);

        if (empty($decoded) && !$argument->isNullable()) {
            throw new NotFoundHttpException($options->message ?? (sprintf('"%s" The hash could not be converted "%s".', $options->class, self::class)));
        }

        return $decoded;
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
            if (\is_array($id = $request->attributes->get($name))) {
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
