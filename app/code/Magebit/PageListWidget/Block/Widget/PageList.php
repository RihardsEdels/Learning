<?php

namespace Magebit\PageListWidget\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;

class PageList extends Template implements BlockInterface
{

    const TITLE = 'title';
    const DISPLAY_TYPE = 'display_type';
    const PAGE_LIST = 'page_list';

    //display_type
    const SPECIFIC_PAGE = 'specific_page';
    const ALL_PAGES = 'all_pages';

    //set template
    protected $_template = "widget/page-list.phtml";


    //properties
    public $title;
    public $selection;
    public $display_type;
    private $_pageRepositoryInterface;
    private $_searchCriteriaBuilder;
    public $selectedPages;


    public function __construct(
        Context $context,
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        //include the parent dependencies
        parent::__construct($context, $data);
        $this->_pageRepositoryInterface = $pageRepositoryInterface;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }


    //gets the title from the widget config
    public function getTitle()
    {
        $this->title = $this->getData('title');
        return $this->title;
    }

    //getselection (multiselect) from  widget config
    public function  getPageSelection()
    {
        $this->selection = $this->getData('list_pages');
        //convert to array
        $this->selectedPages = explode(",", $this->selection);
        return $this->selectedPages;
        //returns array(2) { [0]=> string(4) "home" [1]=> string(38) "privacy-policy-cookie-restriction-mode" }

    }
    //display type (all pages || specific page) from widget config
    public function getDisplayType()
    {
        $this->display_type = $this->getData('display_type');
        return $this->display_type;
    }

    //builds search criteria with or without filter
    protected function getSearchCriteria()
    {
        $field = 'identifier';
        $value = $this->getPageSelection();
        $condition = 'in';



        if ($this->getDisplayType() == 'specific page') {
            return $this->_searchCriteriaBuilder->addFilter($field, $value, $condition)->create();
        } else {
            return $this->_searchCriteriaBuilder->create();
        }
    }

    //returns page collection
    public function getCollection()
    {
        $searchCriteria = $this->getSearchCriteria();
        $pageCollection = $this->_pageRepositoryInterface->getList($searchCriteria)->getItems();
        return $pageCollection;
    }


    //returns array with page title and identifier
    public function getPages()
    {
        $pages = [];
        foreach ($this->getCollection() as $page) {
            $pages[] = [
                'value' => $page->getIdentifier(),
                'label' => $page->getTitle()
            ];
        }
        return $pages;
    }
}
