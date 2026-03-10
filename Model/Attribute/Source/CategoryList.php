<?php
namespace WeltPixel\NavigationLinks\Model\Attribute\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CategoryList implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;

    public function __construct(CollectionFactory $categoryCollectionFactory)
    {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Recursively build category options in Magento hierarchy order
     *
     * @param array $categoriesByParent
     * @param int $parentId
     * @param int $level
     * @return array
     */
    protected function buildOptionsTree($categoriesByParent, $parentId = 1, $level = 0)
    {
        $options = [];
        if (!isset($categoriesByParent[$parentId])) {
            return $options;
        }
        foreach ($categoriesByParent[$parentId] as $category) {
            $indent = str_repeat('= ', max(0, $level));
            $label = $indent . $category->getName() . ' || ID: ' . $category->getId();
            $options[] = [
                'value' => $category->getId(),
                'label' => $label
            ];
            // Recursively add children
            $options = array_merge($options, $this->buildOptionsTree($categoriesByParent, $category->getId(), $level + 1));
        }
        return $options;
    }

    /**
     * Get options array for all categories as a tree in actual Magento hierarchy, including all roots
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name')
            ->addAttributeToSelect('parent_id')
            ->addAttributeToSelect('level')
            ->addAttributeToSelect('position')
            ->setOrder('position', 'ASC');

        $categoriesByParent = [];
        foreach ($collection as $category) {
            $categoriesByParent[$category->getParentId()][] = $category;
        }
        return $this->buildOptionsTree($categoriesByParent, 1, 0);
    }
}
