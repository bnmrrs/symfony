<?php

namespace Symfony\Components\Validator\Constraints;

use Symfony\Components\Validator\Constraint;
use Symfony\Components\Validator\ConstraintValidator;
use Symfony\Components\Validator\Exception\ConstraintDefinitionException;
use Symfony\Components\Validator\Exception\UnexpectedTypeException;

class ValidValidator extends ConstraintValidator
{
    public function isValid($value, Constraint $constraint)
    {
        if ($value === null) {
            return true;
        }

        $walker = $this->context->getGraphWalker();
        $group = $this->context->getGroup();
        $propertyPath = $this->context->getPropertyPath();
        $factory = $this->context->getClassMetadataFactory();

        if (is_array($value)) {
            foreach ($value as $key => $element) {
                $walker->walkConstraint($constraint, $element, $group, $propertyPath.'['.$key.']');
            }
        } else if (!is_object($value)) {
            throw new UnexpectedTypeException($value, 'object or array');
        } else if ($constraint->class && !$value instanceof $constraint->class) {
            $this->setMessage($constraint->message, array('class' => $constraint->class));

            return false;
        } else {
            $metadata = $factory->getClassMetadata(get_class($value));
            $walker->walkClass($metadata, $value, $group, $propertyPath);
        }

        return true;
    }
}