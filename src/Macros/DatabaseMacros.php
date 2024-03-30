<?php

declare(strict_types=1);

namespace Webard\LaravelMacros\Macros;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class DatabaseMacros extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (! QueryBuilder::hasMacro('rawInsert')) {
            /**
             * Handle fastest way to insert row or many rows at same time.
             *
             * @param  array<string,mixed>|array<string>  $columnsOrData  ['col1','col2'] or ['col1' => 'data1','col2' => 'data2']
             * @param  null|array<mixed>  $dataList  If $columnsOrData has ['col1','col2'], this array should has data for columns
             * @param  bool  $ignore  INSERT IGNORE behavior
             * @return bool
             */
            QueryBuilder::macro('rawInsert', function (
                array $columnsOrData,
                ?array $dataList = null,
                bool $ignore = false
            ): bool {
                /** @var QueryBuilder $this */
                $values = null;

                if ($columnsOrData === [] || (array_is_list(
                    $columnsOrData
                ) && ($dataList === null || $dataList === []))) {
                    return false;
                }

                $prepare_value = function (mixed $data) {
                    if ($data === null) {
                        return 'NULL';
                    }
                    if ($data === true) {
                        return '1';
                    }
                    if ($data === false) {
                        return '0';
                    }
                    if ($data instanceof Expression) {
                        return $data->getValue(DB::connection()->getQueryGrammar());
                    }

                    if (\is_string($data)) {
                        $data = addslashes($data);
                    }

                    return '"'.$data.'"';
                };

                if ($dataList !== null && array_is_list($dataList)) {
                    foreach ($dataList as $row) {
                        $prepared_values = array_map($prepare_value, $row);
                        $imploded_values = implode(', ', $prepared_values);
                        $values .= ($values !== null ? ', ' : '').'('.$imploded_values.')';
                    }
                } elseif (! array_is_list($columnsOrData)) {
                    $data_values = array_values($columnsOrData);
                    $columnsOrData = array_keys($columnsOrData);
                    $prepared_values = array_map($prepare_value, $data_values);
                    $imploded_values = implode(', ', $prepared_values);
                    $values .= '('.$imploded_values.')';
                } else {
                    return false;
                }

                $columns = '`'.implode('`, `', $columnsOrData).'`';

                // @phpstan-ignore-next-line
                $sql = 'INSERT'.($ignore === true ? ' IGNORE' : '').' INTO `'.$this->from.'` ('.$columns.') VALUES '.$values;

                return DB::insert($sql);
            });
        }

    }
}
