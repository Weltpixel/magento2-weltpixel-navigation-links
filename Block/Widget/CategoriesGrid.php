<?php
namespace WeltPixel\NavigationLinks\Block\Widget;

use Magento\Framework\View\Element\Template\Context;

class CategoriesGrid extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Constructor
     *
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryFactory = $categoryFactory;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/categories_grid.phtml');
    }

    /**
     * Get selected category IDs from widget params
     * @return array
     */
    public function getSelectedCategoryIds()
    {
        $categories = $this->getData('categories');
        if (!$categories) {
            return [];
        }
        return explode(',', $categories);
    }

    /**
     * Get category objects for selected IDs
     * @return \Magento\Catalog\Model\Category[]
     */
    public function getSelectedCategories()
    {
        $ids = $this->getSelectedCategoryIds();
        $categories = [];
        foreach ($ids as $id) {
            $category = $this->_categoryFactory->create()->load($id);
            if ($category && $category->getId()) {
                $categories[] = $category;
            }
        }

        return $categories;
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        $selectedCategories = $this->getSelectedCategories();
        if (!$selectedCategories) {
            return $identities;
        }
        foreach ($selectedCategories as $category) {
            $identities = array_merge($identities, $category->getIdentities());
        }
        return $identities;
    }
}
