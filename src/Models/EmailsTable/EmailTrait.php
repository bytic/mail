<?php

namespace Nip\Mail\Models\EmailsTable;

use Nip\Mail\Models\Mailable\RecordTrait as MailableRecordTrait;
use Nip\Mail\Models\MergeTags\RecordTrait as MergeTagsRecordTrait;

/**
 * Trait EmailTrait
 * @package Nip\Mail\Models\EmailsTable
 */
trait EmailTrait
{
    use MailableRecordTrait;
    use MergeTagsRecordTrait;
}
