<?php

namespace Magebit\PageListWidget\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class PageList extends Template implements BlockInterface
{

    const TITLE = 'title';
    const DISPLAY_TYPE = 'display_type';
    const PAGE_LIST = 'page_list';

    //display_type
    const SPECIFIC_PAGE = 'specific page';
    const ALL_PAGES = 'all pages';

    //set template
    protected $_template = "widget/page-list.phtml";


    //properties

    private $_pageRepositoryInterface;
    private $_searchCriteriaBuilder;


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
        return $this->getData(self::TITLE);
    }

    //getselection (multiselect) from  widget config
    public function  getPageSelection()
    {
        $selection = $this->getData(self::PAGE_LIST);
        //convert to array
        $selectedPages = explode(",", $selection);
        return $selectedPages;
    }
    //display type (all pages || specific page) from widget config
    public function getDisplayType()
    {
        return $this->getData(self::DISPLAY_TYPE);
    }

    //builds search criteria with or without filter
    protected function getSearchCriteria()
    {
        $field = 'identifier';
        $value = $this->getPageSelection();
        $condition = 'in';

        if ($this->getDisplayType() === self::SPECIFIC_PAGE) {
            return $this->_searchCriteriaBuilder->addFilter($field, $value, $condition)->create();
        } else {
            return $this->_searchCriteriaBuilder->create();
        }
    }

    //returns page collection
    public function getPages()
    {
        $searchCriteria = $this->getSearchCriteria();

        return $this->_pageRepositoryInterface->getList($searchCriteria)->getItems();
    }
}
