<?php

namespace Yireo\OrderGridFilterCategories\Ui\Component\Listing\Column;

use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Psr\Log\LoggerInterface;

class Category extends Column
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        LoggerInterface $logger,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $products = $this->getProductsByOrderId((int)$item['entity_id']);
                $item[$this->getData('name')] = implode('<br/>', $products);
            }
        }

        return $dataSource;
    }

    private function getProductsByOrderId(int $orderId): array
    {
        $products = [];
        $order = $this->orderRepository->get($orderId);
        foreach ($order->getItems() as $orderItem) {
            $products[] = $orderItem->getName();
        }

        return $products;
    }
}