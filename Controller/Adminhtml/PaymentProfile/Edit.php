<?php

/**
 * HiPay Fullservice Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Apache 2.0 Licence
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * @copyright Copyright (c) 2016 - HiPay
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0 Licence
 */

namespace HiPay\FullserviceMagento\Controller\Adminhtml\PaymentProfile;

use Magento\Backend\App\Action;

/**
 * Edit payment profile
 *
 * @author    Kassim Belghait <kassim@sirateck.com>
 * @copyright Copyright (c) 2016 - HiPay
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0 Licence
 * @link      https://github.com/hipay/hipay-fullservice-sdk-magento2
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \HiPay\FullserviceMagento\Model\PaymentProfile\Factory
     */
    private $paymentProfileFactory;

    /**
     * Edit constructor.
     *
     * @param Action\Context                                         $context
     * @param \Magento\Framework\View\Result\PageFactory             $resultPageFactory
     * @param \Magento\Framework\Registry                            $registry
     * @param \HiPay\FullserviceMagento\Model\PaymentProfile\Factory $paymentProfileFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \HiPay\FullserviceMagento\Model\PaymentProfile\Factory $paymentProfileFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->paymentProfileFactory = $paymentProfileFactory;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /**
 * @var \Magento\Backend\Model\View\Result\Page $resultPage
*/
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('HiPay_FullserviceMagento::hipay_payment_profile')
            ->addBreadcrumb(__('HiPay'), __('HiPay'))
            ->addBreadcrumb(__('Payment Profiles'), __('Payment Profiles'));

        return $resultPage;
    }

    /**
     * Edit Payment Profile page
     *
     * @return                                  \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('profile_id');
        $model = $this->paymentProfileFactory->create();

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This payment profile no longer exists.'));
                /**
 * \Magento\Backend\Model\View\Result\Redirect $resultRedirect
*/
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        $this->_coreRegistry->register('payment_profile', $model);

        // 5. Build edit form
        /**
 * @var \Magento\Backend\Model\View\Result\Page $resultPage
*/
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Payment Profile') : __('New Payment Profile'),
            $id ? __('Edit Payment Profile') : __('New Payment Profile')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Payment Profiles'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Payment Profile'));

        return $resultPage;
    }
}
