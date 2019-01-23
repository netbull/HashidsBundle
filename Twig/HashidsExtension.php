<?php

namespace NetBull\HashidsBundle\Twig;

use Hashids\Hashids;

/**
 * Class HashidsExtension
 * @package NetBull\HashidsBundle\Twig
 */
class HashidsExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('hashid_encode', [$this, 'encode']),
            new \Twig_SimpleFilter('hashid_decode', [$this, 'decode']),
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
