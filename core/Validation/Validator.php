<?php

namespace Validation;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Validation\Constraint\Image;

class Validator {

    public function validate(array $valueConstrantMap) {
        $violations = new ConstraintViolationList();
        foreach ($valueConstrantMap as $constraints => $value) {
            $newViolations = Validation::createValidator()
            ->validate($value, $this->instantiateConstraints($constraints));
            $violations->addAll($newViolations);
        }
        return $violations;
    }

    /**
     * @return Constraint[]
     */
    private function instantiateConstraints(string $constraints) {
        $constraintInstances = [];
        $identifiers = explode("|", $constraints);
        foreach($identifiers as $id) {
            $constraintInstances[] = $this->createConstraint($id);
        }
        return $constraintInstances;
    }

     /**
     * @return Constraint
     */
    private function createConstraint(string $identifier) {
        list($constraint, $param) = array_pad(explode(":", $identifier), 2, null);
        switch($constraint) {
            case "required" :
                return new NotBlank();
            case "min" :
                return new Length(["min" => $param]);
            case "max" :
                return new Length(["max" => $param]);
            case "image" : 
                return new Image();
            default:
                throw new InvalidArgumentException("The constraint string ${constraint} is not available!");
        }
    }

}