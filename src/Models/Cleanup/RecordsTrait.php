<?php

namespace Nip\Mail\Models\Cleanup;

use Nip\Database\Query\Update;

/**
 * Trait RecordsTrait
 * @package Nip\Mail\Models\Cleanup
 */
trait RecordsTrait
{
    protected $sentDateField = 'date_sent';
    protected $daysToKeepData = 365;

    protected function reduceOldEmailsData()
    {
        /** @var Update $query */
        $query = $this->newUpdateQuery();
        $query->where('
        `' . $this->sentDateField . '` <= DATE_SUB(CURRENT_DATE(), INTERVAL ' . $this->daysToKeepData . ' DAY)
        ');

        $query->execute();
    }
}
