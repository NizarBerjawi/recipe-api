<?php

namespace App\Queries\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Exceptions\UnknownIncludedFieldsQuery;

trait AddsFieldsToQuery
{
    protected function addRequestedModelFieldsToQuery(): void
    {
        $modelTableName = $this->getModel()->getTable();

        $fields = $this->request->fields();

        if (! $fields->isEmpty() && config('query-builder.convert_field_names_to_snake_case', false)) {
            $fields = $fields->mapWithKeys(fn ($fields, $table) => [$table => collect($fields)->map(fn ($field) => Str::snake($field))->toArray()]);
        }

        // Apply additional table name conversion based on strategy
        if (config('query-builder.convert_relation_table_name_strategy', false) === 'camelCase') {
            $modelFields = $fields->has(Str::camel($modelTableName)) ? $fields->get(Str::camel($modelTableName)) : $fields->get('_');
        } else {
            $modelFields = $fields->has($modelTableName) ? $fields->get($modelTableName) : $fields->get('_');
        }

        if (empty($modelFields)) {
            return;
        }

        if (config('query-builder.always_select_primary_key', false)) {
            $modelFields = collect($modelFields)
                // ->map(fn(string $item) => Str::snake($item))
                ->prepend($this->getModel()->getKeyName())
                ->unique()
                ->values()
                ->all();
        }

        $prependedFields = $this->prependFieldsWithTableName($modelFields, $modelTableName);

        $this->select($prependedFields);
    }

    public function getRequestedFieldsForRelatedTable(string $relation, ?string $tableName = null): array
    {
        // Possible table names to check
        $possibleRelatedNames = [
            // Preserve existing relation name conversion logic
            config('query-builder.convert_relation_names_to_snake_case_plural', true)
                ? Str::plural(Str::snake($relation))
                : $relation,
        ];

        $strategy = config('query-builder.convert_relation_table_name_strategy', false);

        // Apply additional table name conversion based on strategy
        if ($strategy === 'snake_case' && $tableName) {
            $possibleRelatedNames[] = Str::snake($tableName);
        } elseif ($strategy === 'camelCase' && $tableName) {
            $possibleRelatedNames[] = Str::camel($tableName);
        } elseif ($strategy === 'none') {
            $possibleRelatedNames = $tableName;
        }

        // Remove any null values
        $possibleRelatedNames = array_filter($possibleRelatedNames);

        $fields = $this->request->fields()
            ->mapWithKeys(fn ($fields, $table) => [$table => collect($fields)->map(fn ($field) => config('query-builder.convert_field_names_to_snake_case', false) ? Str::snake($field) : $field)])
            ->filter(fn ($value, $table) => in_array($table, $possibleRelatedNames))
            ->first();

        if (! $fields) {
            return [];
        }

        if (config('query-builder.always_select_primary_key', false)) {
            $fields = collect($fields)
                // ->map(fn(string $item) => Str::snake($item))
                ->prepend($this->getModel()->getKeyName())
                ->unique()
                ->values();
        }

        $fields = $fields->toArray();

        if ($tableName !== null) {
            $fields = $this->prependFieldsWithTableName($fields, $tableName);
        }

        if (! $this->allowedFields instanceof Collection) {
            throw new UnknownIncludedFieldsQuery($fields);
        }

        return $fields;
    }

    // protected function prependField(string $field, ?string $table = null): string
    // {
    //     if (!$table) {
    //         $table = $this->getModel()->getTable();
    //     }

    //     // Already prepended
    //     if (Str::contains($field, '.')) {
    //         if (config('query-builder.convert_field_names_to_snake_case', false)) {
    //             $last = Str::afterLast($field, '.');

    //             return Str::replaceLast($last, Str::snake($last), $field);
    //         }

    //         return $field;
    //     }

    //     if (config('query-builder.convert_field_names_to_snake_case', false)) {
    //         $field = Str::snake($field);
    //     }

    //     return "{$table}.{$field}";
    // }

    protected function prependField(string $field, ?string $table = null): string
    {
        if (! $table) {
            $table = $this->getModel()->getTable();
        }

        if (Str::contains($field, '.')) {
            // Already prepended

            return $field;
        }

        return "{$table}.{$field}";
    }
}
