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
        $hash = $request->attributes->get($configuration->getName());
        if ($hash && $hash !== 'null') {
            $decoded = $this->hashids->decode($hash);
            $request->attributes->set($configuration->getName(), $decoded[0] ?? $hash);
        }
	if ($hash && $hash === 'null') {
	    $request->attributes->set($configuration->getName(), null);
	}
		
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return false !== strpos(strtolower($configuration->getName()), 'hash');
    }
}
