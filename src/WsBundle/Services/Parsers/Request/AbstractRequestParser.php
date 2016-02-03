<?php
declare(strict_types=1);

namespace WsBundle\Services\Parsers\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WsBundle\Exceptions\Request\ParsingException;

/**
 * Class AbstractRequestParser
 * @package WsBundle\Services\Parsers\Request
 */
abstract class AbstractRequestParser
{

    /** @var ValidatorInterface  */
    protected $validator;

    /** @var  array */
    protected $parameters;

    /** @var \SplStack  */
    protected $constraintsMap;

    /**
     * AbstractRequestParser constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {

        $this->validator = $validator;
        $this->parameters = [];
        $this->constraintsMap = new \SplStack();
    }

    /**
     * @return string
     */
    abstract protected function getSupportedEntityClass(): string;

    /**
     * @param Request $request
     * @throws ParsingException
     */
    public function parse(Request $request)
    {

        $targetClass = $this->getSupportedEntityClass();

        /** @var ClassMetadata $metadata */
        $metadata=$this->validator->getMetadataFor($targetClass);

        $constrainedProperties = $metadata->getConstrainedProperties();

        foreach($constrainedProperties as $constrainedProperty)
        {
            $value = $request->get($constrainedProperty);
            $this->parameters[$constrainedProperty] = $value;

            $propertyMetadata=$metadata->getPropertyMetadata($constrainedProperty);
            $constraints=$propertyMetadata[0]->constraints;

            /** @var ConstraintViolation[] $errors */
            $errors = $this->validator->validate($value, $constraints);

            if (count($errors) > 0) {
                $details = [];
                foreach ($errors as $error) {
                    $details[$constrainedProperty] = $error->getMessage();
                }

                throw new ParsingException('Parameters passed to request are not valid', $details);
            }

        }

    }

}
