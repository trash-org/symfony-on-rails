<?php

namespace App\Bundle\Web\Widgets;

use PhpLab\Domain\Data\DataProviderEntity;

class PaginationWidget extends BaseWidget implements WidgetInterface
{

    private $dataProviderEntity;
    private $perPageArray = [10, 20, 50];

    public function __construct(DataProviderEntity $dataProviderEntity)
    {
        $this->dataProviderEntity = $dataProviderEntity;
    }

    public function render(): string
    {
        if($this->dataProviderEntity->getPageCount() == 1) {
            return '';
        }
        $itemsHtml = '';
        $itemsHtml .= $this->renderPrevItem();
        for ($page = 1; $page <= $this->dataProviderEntity->getPageCount(); $page++) {
            $itemsHtml .= $this->renderPageItem($page);
        }
        $itemsHtml .= $this->renderNextItem();
        $renderPageSizeSelector = $this->renderPageSizeSelector();
        $itemsHtml .= $renderPageSizeSelector ? '<li>' . $renderPageSizeSelector . '</li>' : '';
        return $this->renderLayout($itemsHtml);
    }

    private function renderLayout(string $items) {
        return "
            <nav aria-label=\"Page navigation\">
                <ul class=\"pagination\">
                    {$items}
                </ul>
            </nav>
        ";
    }

    private function renderPageSizeSelector() {
        if(empty($this->perPageArray)) {
            return '';
        }
        $html = '';
        foreach ($this->perPageArray as $size) {
            $html .= "<li><a href='?per-page={$size}'>{$size}</a></li>";
        }
        return "
            <span class=\"dropup btn btn-link\">
                <span class=\"dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\">
                    {$this->dataProviderEntity->getPageSize()}
                    <span class=\"caret\"></span>
                </span>
                <ul class=\"dropdown-menu\">
                    {$html}
                </ul>
            </span>
        ";
    }

    private function renderPageItem(int $page) {
        $selectedClass = ($this->dataProviderEntity->page == $page) ? 'active' : '';
        return "
            <li class=\"{$selectedClass}\">
                <a href=\"?page={$page}\">
                    {$page}
                </a>
            </li>
        ";
    }

    private function renderPrevItem() {
        $prevClass = $this->dataProviderEntity->isFirstPage() ? 'disabled' : '';
        return "
            <li class=\"{$prevClass}\">
                <a href=\"?page={$this->dataProviderEntity->getPrevPage()}\" aria-label=\"Previous\">
                    <span aria-hidden=\"true\">&laquo;</span>
                </a>
            </li>
        ";
    }

    private function renderNextItem() {
        $nextClass = $this->dataProviderEntity->isLastPage() ? 'disabled' : '';
        return "
            <li class=\"{$nextClass}\">
                <a href=\"?page={$this->dataProviderEntity->getNextPage()}\" aria-label=\"Next\">
                    <span aria-hidden=\"true\">&raquo;</span>
                </a>
            </li>
        ";
    }

}