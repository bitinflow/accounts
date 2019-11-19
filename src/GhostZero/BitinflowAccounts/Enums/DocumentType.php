<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Enums;

/**
 * @author René Preuß <rene@preuss.io>
 */
class DocumentType
{
    // Read authorized user´s email address.
    public const TYPE_PDF_INVOICE = 'pdf.invoice';

    // Manage a authorized user object.
    public const TYPE_PDF_ORDER = 'pdf.order';
}