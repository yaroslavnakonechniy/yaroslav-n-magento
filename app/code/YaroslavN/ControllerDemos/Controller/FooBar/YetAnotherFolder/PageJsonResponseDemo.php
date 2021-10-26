<?php
declare(strict_types=1);

namespace YaroslavN\ControllerDemos\Controller\FooBar\YetAnotherFolder;

use Magento\Framework\View\Result\Page;

class PageJsonResponseDemo implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    private \Magento\Framework\View\Result\PageFactory $pageFactory;

    /**
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     */
    public function __construct(\Magento\Framework\View\Result\PageFactory $pageFactory)
    {

        $this->pageFactory = $pageFactory;
    }

    /**
     * Lyaout result demo: https://yaroslav-n-magento.local/yaroslavn_controllerdemo/foobar_yetanotherfolder/pagejsonresponsedemo
     *
     *
     * @return Page
     */
    public function execute(): Page
    {
        return $this->pageFactory->create();
    }
}
