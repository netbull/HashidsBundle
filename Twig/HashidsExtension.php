<?php

namespace NetBull\HashidsBundle\Twig;

use Hashids\Hashids;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class HashidsExtension
 * @package NetBull\HashidsBundle\Twig
 */
class HashidsExtension extends AbstractExtension
{
    /**
     * @var Hashids
     */
    private $hashids;

    /**
     * HashidsExtension constructor.
     * @param Hashids $hashids
     */
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
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
    public function encode($number)
    {
        return $this->hashids->encode($number);
    }

    /**
     * @param $hash
     * @return array
     */
    public function decode($hash)
    {
        return $this->hashids->decode($hash);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'netbull_hashids.extension';
    }
}
