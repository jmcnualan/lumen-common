<?php

namespace Dmn\Cmn\Rues\ValidSort;

use Illuminate\Contracts\Validation\Rule;

class ValidSort implements Rule
{
    protected $sortFields;

    protected $sortTypes = ['asc', 'desc'];

    public function __construct(array $sortFields)
    {
        $this->sortFields = $sortFields;
    }

    /**
     * {@inheritDoc}
     */
    public function passes($attribute, $value)
    {
        if (false == is_array($value)) {
            return false;
        }

        if (count($value) < 1) {
            return true;
        }

        foreach ($value as $sort => $sortType) {
            if (false == in_array($sort, $this->sortFields)) {
                return false;
            }

            if (false == in_array($sortType, $this->sortTypes)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function message()
    {
        return 'Invalid :attribute format.';
    }
}
