<?php

namespace App\Queries\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Exceptions\UnknownIncludedFieldsQuery;

trait AddsFieldsToQuery
{
    protected function addRequestedModelFieldsToQuery(): void
    {
        $modelTableName = $this->getModel()->getTable();

        $fields = $this->request->fields();

        $modelFields = $fields->has($modelTableName) ? $fields->get($modelTableName) : $fields->get('_');

        if (empty($modelFields)) {
            return;
        }

        $prependedFields = $this->prependFieldsWithTableName($modelFields, $modelTableName);

        $this->select($prependedFields);
    }

    public function getRequestedFieldsForRelatedTable(string $relation): array
    {
        $tableOrRelation = config('query-builder.convert_relation_names_to_snake_case_plural', true)
            ? Str::plural(Str::snake($relation))
            : $relation;

        $fields = $this->request->fields()
            ->mapWithKeys(fn ($fields, $table) => [
                $table => config('query-builder.allow_selecting_fields_as_camel_case', false)
                    ? Arr::map($fields, fn ($field) => Str::snake($field))
                    : $fields,
            ]
            )->get($tableOrRelation);

        if (! $fields) {
            return [];
        }

        if (! $this->allowedFields instanceof Collection) {
            // We have requested fields but no allowed fields (yet?)

            throw new UnknownIncludedFieldsQuery($fields);
        }

        return $fields;
    }

    protected function prependField(string $field, ?string $table = null): string
    {
        if (! $table) {
            $table = $this->getModel()->getTable();
        }

        // Already prepended
        if (Str::contains($field, '.')) {
            if (config('query-builder.allow_selecting_fields_as_camel_case', false)) {
                $last = Str::afterLast($field, '.');

                return Str::replaceLast($last, Str::snake($last), $field);
            }

            return $field;
        }

        if (config('query-builder.allow_selecting_fields_as_camel_case', false)) {
            $field = Str::snake($field);
        }

        return "{$table}.{$field}";
    }
}
