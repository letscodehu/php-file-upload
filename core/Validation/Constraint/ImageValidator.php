<?php

namespace Validation\Constraint;

use FileSystem\UploadedFile;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ImageValidator extends ConstraintValidator {

    public function validate($value, \Symfony\Component\Validator\Constraint $constraint)
    {
        if (!$constraint instanceof Image) {
            throw new UnexpectedTypeException($constraint, Image::class);
        }
        if (null === $value || '' === $value) {
            return;
        }
        if (!$value instanceof UploadedFile) {
            throw new UnexpectedTypeException($value, UploadedFile::class);
        }
        
        switch($value->error()) {
            case UPLOAD_ERR_OK:
                $check = getimagesize($value->getTemporaryName());
                if (!$check) {
                    $this->context->buildViolation("Not an image.")->addViolation();    
                }
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->context->buildViolation("No file sent.")->addViolation();
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->context->buildViolation("Exceeded filesize limit.")->addViolation();
                break;
            default:
                $this->context->buildViolation("Error during file upload.")->addViolation();
                break;
        }
    }


}