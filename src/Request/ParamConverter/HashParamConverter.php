<?php

namespace NetBull\HashidsBundle\Request\ParamConverter;

use Hashids\Hashids;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

class HashParamConverter implements ParamConverterInterface
{
    /**
     * @var Hashids
     */
    protected Hashids $hashids;

    /**
     * @param Hashids $hashids
     */
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return true
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $hash = $request->attributes->get($configuration->getName());
        if ($hash && $hash !== 'null') {
            $decoded = $this->hashids->decode($hash);
            $request->attributes->set($configuration->getName(), $decoded[0] ?? $hash);
        }

        if ($hash === 'null') {
            $request->attributes->set($configuration->getName(), null);
        }
		
        return true;
    }

    /**
     * @param ParamConverter $configuration
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return false !== strpos(strtolower($configuration->getName()), 'hash');
    }
}
