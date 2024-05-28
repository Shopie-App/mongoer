<?php

declare(strict_types=1);

namespace Shopie\Mongoer\Helpers;

class Pagination implements \JsonSerializable
{
    /**
     * How many rows to display per page.
     */
    private int $pageRows;

    /**
     * A query's total rows.
     */
    private int $totalRows;

    /**
     * Current page.
     */
    private int $page;

    /**
     * Last page.
     */
    private int $lastPage;

    /**
     * Next page.
     */
    private int $nextPage;

    /**
     * Previous page.
     */
    private int $previousPage;

    /**
     * Starting row (offset) in db table/collection for paged result.
     */
    private int $rowStart;

    /**
     * Surrounding pages for current page.
     */
    private array $surroundingPages = [];

    /**
     * Construct object.
     */
    public function __construct()
    {
    }

    /**
     * Returns the current page number.
     */
    public function page(): int
    {
        return $this->page;
    }

    /**
     * Returns the last page number.
     */
    public function last(): int
    {
        return $this->lastPage;
    }

    /**
     * Returns the next page number.
     */
    public function next(): int
    {
        return $this->nextPage;
    }

    /**
     * Returns the previous page number.
     */
    public function previous(): int
    {
        return $this->previousPage;
    }

    /**
     * Returns the rows per page number.
     */
    public function pageRows(): int
    {
        return $this->pageRows;
    }

    /**
     * Sets the current page to lookup.
     */
    public function setPage(int $pageNum): void
    {
        $this->page = $pageNum;
    }

    /**
     * Sets how many rows per page to return.
     */
    public function setPageRows(int $pageRows): void
    {
        $this->pageRows = $pageRows;
    }

    /**
     * Sets the total rows that a query returns.
     */
    public function setTotalRows(int $totalRows): void
    {
        $this->totalRows = $totalRows;
    }

    /**
     * Returns the limit part of an SQL query.
     */
    public function limit(): string
    {
        return " LIMIT $this->rowStart,$this->pageRows";
    }

    /**
     * Returns how many results to skip (MongoDB).
     */
    public function skip(): int
    {
        return $this->page == 1 ? 0 : ($this->page - 1) * $this->pageRows;
    }

    /**
     * Returns the surrounding pages of the current page.
     */
    public function surroundingPages(): array
    {
        return $this->surroundingPages;
    }

    /**
     * Generate the pagination properties.
     */
    public function generate(bool $withSurrounding = true): Pagination
    {
        // last page
        $this->lastPage = (int) ceil($this->totalRows / $this->pageRows);

        // make sure we are within limits
        if ($this->page < 1) {
            $this->page = 1;
        } elseif ($this->page > $this->lastPage) {
            $this->page = $this->lastPage;
        }

        // starting row
        $this->rowStart = ($this->page - 1) * $this->pageRows;

        // previous page
        $this->previousPage = $this->page == 1 ? $this->page : $this->page - 1;

        // next page
        $this->nextPage = $this->page == $this->lastPage ? $this->lastPage : $this->page + 1;

        if ($withSurrounding) {
            $this->generateSurroundingPages();
        }

        return $this;
    }

    /**
     * Generate the surrounding pages.
     */
    public function generateSurroundingPages(): void
    {
        $this->surroundingPages = [];

        // limit of surrounding pages to add
        $totalInArray = 5;

        // case we are on first page
        if ($this->page == 1) {

            // if only one page results return one
            if ($this->nextPage == $this->page) {

                $this->surroundingPages = [1];
                return;
            }

            for ($i = 0; $i < $totalInArray; $i++) {
                
                if ($i == $this->lastPage) {
                    break;
                }

                $this->surroundingPages[] = $i + 1;
            }

            return;
        }

        // case we are on last page
        if ($this->page == $this->lastPage) {

            $start = $this->lastPage - $totalInArray;
            
            if ($start < 1) {
                $start = 0;
            }

            for ($i = $start; $i < $this->lastPage; $i++) {
                $this->surroundingPages[] = $i + 1;
            }

            return;
        }


        // case somewhere in the middle of paged results
        $start = $this->page - $totalInArray;

        if ($start < 1) {
            $start = 0;
        }

        for ($i = $start; $i < $this->page; $i++) {
            $this->surroundingPages[] = $i + 1;
        }

        for ($i = ($this->page + 1); $i < ($this->page + $totalInArray); $i++) {
            
            if ($i == ($this->lastPage + 1)) {
                break;
            }

            $this->surroundingPages[] = $i;
        }
    }

    /**
     * Pagination properties to JSON string.
     */
    public function jsonSerialize(): string
    {
        return json_encode([
            'current' => $this->page,
            'previous' => $this->previousPage,
            'next' => $this->nextPage,
            'last' => $this->lastPage,
            'pages' => $this->surroundingPages
        ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
    }
}