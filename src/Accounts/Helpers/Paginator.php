<?php

declare(strict_types=1);

namespace Bitinflow\Accounts\Helpers;

use Bitinflow\Accounts\Result;
use stdClass;

/**
 * @author René Preuß <rene@preuss.io>
 */
class Paginator
{

    /**
     * Next desired action (first, after, before).
     *
     * @var null|string
     */
    public $action = null;
    /**
     * bitinflow Accounts response pagination cursor.
     *
     * @var null|stdClass
     */
    private $pagination;

    /**
     * Constructor.
     *
     * @param null|stdClass $pagination bitinflow Accounts response pagination cursor
     */
    public function __construct(stdClass $pagination = null)
    {
        $this->pagination = $pagination;
    }

    /**
     * Create Paginator from Result object.
     *
     * @param Result $result Result object
     *
     * @return self   Paginator object
     */
    public static function from(Result $result): self
    {
        return new self($result->pagination);
    }

    /**
     * Return the current active cursor.
     *
     * @return string bitinflow Accounts cursor
     */
    public function cursor(): string
    {
        return $this->pagination->cursor;
    }

    /**
     * Set the Paginator to fetch the next set of results.
     *
     * @return self
     */
    public function first(): self
    {
        $this->action = 'first';

        return $this;
    }

    /**
     * Set the Paginator to fetch the first set of results.
     *
     * @return self
     */
    public function next(): self
    {
        $this->action = 'after';

        return $this;
    }

    /**
     * Set the Paginator to fetch the last set of results.
     *
     * @return self
     */
    public function back(): self
    {
        $this->action = 'before';

        return $this;
    }
}