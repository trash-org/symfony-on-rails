<?php

namespace App\Bundle\Web\Widgets;

use PhpLab\Domain\Data\DataProviderEntity;

class PaginationWidget extends BaseWidget implements WidgetInterface
{

    private $dataProviderEntity;

    public function __construct(DataProviderEntity $dataProviderEntity)
    {
        $this->dataProviderEntity = $dataProviderEntity;
    }

    public function render(): string
    {

        if($this->dataProviderEntity->getPageCount() == 1) {
            return '';
        }

        $items = '';

        $prevClass = $this->dataProviderEntity->isFirstPage() ? 'disabled' : '';
        $nextClass = $this->dataProviderEntity->isLastPage() ? 'disabled' : '';


        $items .= "
            <li class=\"{$prevClass}\">
                <a href=\"?page={$this->dataProviderEntity->getPrevPage()}\" aria-label=\"Previous\">
                    <span aria-hidden=\"true\">&laquo;</span>
                </a>
            </li>
        ";

        for ($page = 1; $page <= $this->dataProviderEntity->getPageCount(); $page++) {
            $selectedClass = ($this->dataProviderEntity->page == $page) ? 'active' : '';
            $items .= "
                 <li class=\"{$selectedClass}\">
                        <a href=\"?page={$page}\">{$page}</a>
                    </li>
            ";
        }


        $items .= "
            <li class=\"{$nextClass}\">
                <a href=\"?page={$this->dataProviderEntity->getNextPage()}\" aria-label=\"Next\">
                    <span aria-hidden=\"true\">&raquo;</span>
                </a>
            </li>
        ";

        $layout = "
            <nav aria-label=\"Page navigation\">
                <ul class=\"pagination\">
                    {$items}
                </ul>
            </nav>
        ";

        return $layout;
    }

}