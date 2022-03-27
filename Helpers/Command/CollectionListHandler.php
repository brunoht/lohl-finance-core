<?php

namespace Modules\App\Helpers\Command;

use Illuminate\Database\Eloquent\Collection;

trait CollectionListHandler
{
    /**
     * Print a table into the terminal
     *
     * @param Collection $collection
     * @return void
     */
    public function listHandler( Collection $collection, $showTotal = true )
    {
        $total = $collection->count();

        if ( $total > 0 ) {
            $header = []; // table header
            $rows = []; // table rows
            $emptyHeader = true;

            // iterates all objects to reorder fields
            foreach ( $collection as $object ) {
                $computedValues = $object->computedValues();

                $row = [];
                // ordering fields as set inside object's table() function and create a table row
                foreach ( $object->namedFields() as $field => $name ) {
                    // overrides the value of a specific object field
                    if ( isset($computedValues[$field]) )
                        $row[$name] = call_user_func($computedValues[$field], $object[$field]);
                    else $row[$name] = $object[$field];
                    // set the table header only on the first object
                    if ($emptyHeader) $header[] = $name;
                }
                $rows[] = $row;
                $emptyHeader = false;
            }
            // mount and print the table
            $this->table($header, $rows);
            if ( $showTotal ) $this->info('TOTAL FOUND: ' . $total);
        } else {
            $this->info('EMPTY');
        }
    }
}
