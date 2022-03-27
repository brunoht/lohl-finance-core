<?php

namespace Modules\App\Helpers\Command;

trait MenuCreateUpdate
{
    /**
     * Show the terminal menu for object update
     *
     * @param $object
     * @param $create
     * @return int
     */
    private function menu ($object, $store) : int
    {
        $repeat = true;

        // terminate application in case nothing is found
        if ( !$object ) {
            $this->error('NOT FOUND');
            return 1;
        }

        $this->alert('available fields:');

        do {
            // check which fields are available to mass writing
            $fields = $object->getFillable();

            // prints all available fields to terminal
            foreach($fields as $field){
                $this->line("[$field] => " . $object[$field]);
            }

            // asks user to type the name field
            $key = $this->ask('FIELD', "-1");

            if ($key !== "-1") {

                // validation: field must be set as fillable
                $fillable = false;
                foreach ($fields as $field) {
                    if ( $field === $key ) {
                        $fillable = true;
                        break;
                    }
                }

                if ( $fillable ) {
                    // memorize the old value
                    $oldValue = $object[$key];

                    // asks user to type the new value
                    $object[$key] = $this->ask("VALUE", $object[$key]);

                    // if field is not validated, return old value
                    if ( !$this->validate($object) ) {
                        $object[$key] = $oldValue;
                    }
                } else {
                    $this->error('[ ERROR ] FIELD NOT FOUND OR NOT AVAILABLE');
                }
            }

            // terminates application when typing -1, only if there is no validation error
            else if( $this->validate($object) ) {
                $repeat = false;
            }

        } while ( $repeat );

        // runs the function to store the object into database
        $store( $object );

        return 0;
    }
}
