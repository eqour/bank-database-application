<?php

namespace app\widgets;

class PaginationWidget {
    private string $pageAttribute;
    private int $amountPages;
    private int $currentPage;
    private int $radius;
    private array $hrefParameters;

    public function __construct(int $amountPages, int $currentPage, int $radius, array $appendParams = [], string $pageAttribute = 'p') {
        $this->amountPages = $amountPages;
        $this->currentPage = $currentPage;
        $this->radius = $radius;
        $this->pageAttribute = $pageAttribute;
        $this->hrefParameters = $appendParams;
    }

    public function render(): void {
        ?><ul class="pagination"><?php
        $this->renderElement('«', $this->generateQueryWithPageAttribute(0));
        for ($i = $this->calculateStartPage(); $i < $this->calculateEndPage(); $i++) {
            $this->renderElement($i + 1, $this->generateQueryWithPageAttribute($i), $i == $this->currentPage);
        }
        $this->renderElement('»', $this->generateQueryWithPageAttribute($this->amountPages - 1));
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

    private function generateQueryWithPageAttribute(int $pageAttribute): string {
        $parameters = $this->hrefParameters;
        $parameters[$this->pageAttribute] = $pageAttribute;
        return '?' . http_build_query($parameters);
    }

    private function calculateStartPage(): int {
        return $this->currentPage < $this->radius ? 0 : $this->currentPage - $this->radius;
    }

    private function calculateEndPage(): int {
        return $this->currentPage > $this->amountPages - $this->radius - 1 ? $this->amountPages : $this->currentPage + $this->radius + 1;
    }
}
