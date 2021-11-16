<?php

declare(strict_types=1);

namespace YaroslavN\RegularCustomer\Model\ResourceModel;

class DiscountRequest extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('ya_roslavn_regular_customer_request', 'request_id');
    }
}
