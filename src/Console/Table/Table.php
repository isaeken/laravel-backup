<?php

namespace IsaEken\LaravelBackup\Console\Table;

use function collect;
use Illuminate\Console\Command;

class Table
{
    public array $columns = [];

    public array $rows = [];

    public TableStyle $style = TableStyle::Box;

    public function addColumn(Column $column): self
    {
        $this->columns[] = $column;

        return $this;
    }

    public function addRow(object|array $row): self
    {
        if (is_object($row)) {
            $row = (array) $row;
        }

        $record = [];

        /**
         * @var int $index
         * @var Column $column
         */
        foreach ($this->columns as $index => $column) {
            $record[$index] = array_key_exists($column->key, $row) ? $row[$column->key] : '-';
        }

        $this->rows[] = $record;

        return $this;
    }

    public function getHeaders(): array
    {
        return collect($this->columns)->pluck('header')->toArray();
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function getTableStyle(): string
    {
        return $this->style->value;
    }

    public function getColumnStyles(): array
    {
        $columns = [];

        /**
         * @var int $index
         * @var Column $column
         */
        foreach ($this->columns as $index => $column) {
            if ($column->rightAligned === true) {
                $columns[$index] = new RightAlignedCell();
            }
        }

        return $columns;
    }

    public function render(Command $command): void
    {
        $command->table(
            $this->getHeaders(),
            $this->getRows(),
            $this->getTableStyle(),
            $this->getColumnStyles(),
        );
    }
}
