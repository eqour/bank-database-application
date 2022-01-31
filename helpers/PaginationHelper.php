<?php

namespace app\helpers;

class PaginationHelper {
    private int $startRecordIndex;
    private int $endRecordIndex;
    private int $amountPages;
    private int $currentPage;
    private int $recordsPerPage;

    public function __construct(int $totalRecords, int $currentPage, int $recordsPerPage) {
        $this->currentPage = $currentPage;
        $this->amountPages =(int)ceil((float)$totalRecords / (float)$recordsPerPage);
        $this->startRecordIndex = $recordsPerPage * $this->currentPage + 1;
        $endIndex = $recordsPerPage * ($this->currentPage + 1);
        $this->endRecordIndex = $endIndex > $totalRecords ? $totalRecords : $endIndex;
        $this->recordsPerPage = $recordsPerPage;
    }

    public function getStartRecordIndex(): int {
        return $this->startRecordIndex;
    }

    public function getEndRecordIndex(): int {
        return $this->endRecordIndex;
    }

    public function getAmountPages(): int {
        return $this->amountPages;
    }

    public function getCurrentPage(): int {
        return $this->currentPage;
    }

    public function getRecordsPerPage(): int {
        return $this->recordsPerPage;
    }
}
