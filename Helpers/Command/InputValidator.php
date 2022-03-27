<?php

namespace Modules\App\Helpers\Command;

use Illuminate\Support\Facades\Validator;

trait InputValidator
{
    public function validate( $object )
    {
        $validator = Validator::make( $object->toArray(), $object->rules(), $object->messages() );

        if( $validator->fails() ) {
            foreach ( $validator->errors()->all() as $error ) {
                $this->error( $error );
            }
            return false;
        }
        return true;
    }
}
