<?php
namespace Mailer\Grid\Formatter;

use ZfcDatagrid\Column\AbstractColumn;
use ZfcDatagrid\Column\Formatter\AbstractFormatter;
use Zend\Filter\DateTimeFormatter as ZendDateTimeFormatter;

class DateTimeFormatter extends AbstractFormatter
{
    protected $validRenderers = [
        'jqGrid',
        'bootstrapTable',
    ];

    public function getFormattedValue(AbstractColumn $column)
    {
        $row = $this->getRowData();
        $value = $row[$column->getUniqueId()];

        $formatter = new ZendDateTimeFormatter(['format' => 'Y-m-d H:i:s']);
        return $formatter->filter($value);
    }
}
