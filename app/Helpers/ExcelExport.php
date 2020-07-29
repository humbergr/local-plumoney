<?php
namespace App\Helpers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ExcelExport implements FromCollection, WithHeadings
{
    protected $collection;
    protected $headings;
    public function __construct($collection, $headings)
    {
        $this->collection = $collection;
        $this->headings   = $headings;
    }

    public function headings(): array
    {
        return $this->headings;
    }
    public function collection()
    {
        return $this->collection;
    }
}