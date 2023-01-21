<?php

namespace NetBull\HashidsBundle\Twig;

use Hashids\Hashids;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HashidsExtension extends AbstractExtension
{
    /**
     * @var Hashids
     */
    private Hashids $hashids;

    /**
     * @param Hashids $hashids
     */
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('hashid_encode', [$this, 'encode']),
            new TwigFilter('hashid_decode', [$this, 'decode']),
        ];
    }

    /**
     * @param $number
     * @return string
     */
    public function encode($number): string
    {
        return $this->hashids->encode($number);
    }

    /**
     * @param $hash
     * @return array
     */
    public function decode($hash): array
    {
        return $this->hashids->decode($hash);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'netbull_hashids.extension';
    }
}
