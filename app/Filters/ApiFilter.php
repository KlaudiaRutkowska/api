<?php

namespace App\Filters;

use Illuminate\Http\Request;

Class ApiFilter
{
    protected $allowedFields = [];
    protected $columnMap = [];
    protected $operatorMap = [];

    public function transform(Request $request)
    {
        $eloquentQuery = [];

        foreach($this->allowedFields as $field => $operators)
        {
            $query = $request->query($field);

            if(!isset($query))
            {
                continue;
            }

            $column = $this->columnMap[$field] ?? $field;

            foreach($operators as $operator)
            {
                if (isset($query[$operator])) {
                    $eloquentQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloquentQuery;
    }
}