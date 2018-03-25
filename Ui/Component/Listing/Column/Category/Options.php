<?php
namespace Yireo\OrderGridFilterCategories\Ui\Component\Listing\Column\Category;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Options
 */
class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        \Psr\Log\LoggerInterface $logger
    ){
        $this->collectionFactory = $collectionFactory;
        $this->logger = $logger;
    }

    /**
     * Get options
     *
     * @return array
     * @throws LocalizedException
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addAttributeToSelect('name');

            $this->logger->notice('Collection size: '.$collection->getSize());

            foreach ($collection as $category) {
                $this->options[] = ['value' => $category->getId(), 'label' => $category->getName()];
            }
        }

        return $this->options;
    }
}
