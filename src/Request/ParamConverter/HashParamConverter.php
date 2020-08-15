<?php

namespace NetBull\HashidsBundle\Request\ParamConverter;

use Hashids\Hashids;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

/**
 * Class HashParamConverter
 * @package NetBull\HashidsBundle\Request\ParamConverter
 */
class HashParamConverter implements ParamConverterInterface
{
    /**
     * @var Hashids
     */
    protected $hashids;

    /**
     * HashidsParamConverter constructor.
     * @param Hashids $hashids
     */
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $hash = $request->attributes->get('hash');
        if ($hash) {
            $decoded = $this->hashids->decode($hash);
            $request->attributes->set('hash', $decoded[0] ?? $hash);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return 'hash' === $configuration->getName();
    }
}
