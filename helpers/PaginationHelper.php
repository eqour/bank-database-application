<?php

namespace app\helpers;

class PaginationHelper {
    private string $pageAttribute;
    private int $amountPages;
    private int $currentPage;
    private int $radius;

    public function __construct(int $total, int $itemsPerPage, int $currentPage, int $radius, string $pageAttribute = 'p') {
        $this->amountPages = (int)ceil((float)$total / (float)$itemsPerPage);
        $this->currentPage = $currentPage;
        $this->radius = $radius;
        $this->pageAttribute = $pageAttribute;
    }

    public function render(): void {
        ?><ul class="pagination"><?php
        $this->renderElement('«', '?' . $this->pageAttribute . '=0');
        for ($i = $this->calculateStartPage(); $i < $this->calculateEndPage(); $i++) {
            $this->renderElement($i + 1, '?' . $this->pageAttribute . '=' . $i, $i == $this->currentPage);
        }
        $this->renderElement('»', '?' . $this->pageAttribute . '=' . $this->amountPages - 1);
        ?></ul><?php
    }

    private function renderElement(string $content, string $href = "#", bool $active = false, bool $disabled = false): void {
        ?>
        <li class="page-item <?= ($active ? 'active' : '') ?>">
            <a class="page-link <?= ($disabled ? 'disabled' : '') ?>" href="<?= htmlspecialchars($href) ?>">
                <span><?= htmlspecialchars($content) ?></span>
            </a>
        </li>
        <?php
    }

    private function calculateStartPage(): int {
        return $this->currentPage < $this->radius ? 0 : $this->currentPage - $this->radius;
    }

    private function calculateEndPage(): int {
        return $this->currentPage > $this->amountPages - $this->radius - 1 ? $this->amountPages : $this->currentPage + $this->radius + 1;
    }
}
