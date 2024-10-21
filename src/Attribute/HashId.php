<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NetBull\HashidsBundle\Attribute;

use NetBull\HashidsBundle\ArgumentResolver\HashValueResolver;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class HashId extends ValueResolver
{
    /**
     */
    public function __construct(
        public ?array $mapping = null,
        public ?bool $stripNull = null,
        public array|string|null $hash = null,
        bool $disabled = false,
        string $resolver = HashValueResolver::class,
        public ?string $message = null,
    ) {
        parent::__construct($resolver, $disabled);
    }

    public function withDefaults(self $defaults): static
    {
        $clone = clone $this;
        $clone->mapping ??= $defaults->mapping;
        $clone->stripNull ??= $defaults->stripNull ?? false;
        $clone->hash ??= $defaults->hash;
        $clone->message ??= $defaults->message;

        return $clone;
    }
}
