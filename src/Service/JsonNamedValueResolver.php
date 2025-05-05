<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTaggedItem(self::class)]
class JsonNamedValueResolver implements ValueResolverInterface
{

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $key = $argument->getName();
        $json = json_decode($request->getContent());

        if (!$json || !isset($json->$key)) {
            return [null];
        }

        return [settype($json->$key, $argument->getType()) ? $json->$key : null];
    }
}