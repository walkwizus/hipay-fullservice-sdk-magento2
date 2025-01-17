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

namespace HiPay\FullserviceMagento\Model\Method;

use Magento\Framework\Exception\LocalizedException;
use Zend\Validator;
use Magento\Directory\Model;

/**
 * SDD Method
 *
 * @author                                           Kassim Belghait <kassim@sirateck.com>
 * @copyright                                        Copyright (c) 2016 - HiPay
 * @license                                          http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0 Licence
 * @link                                             https://github.com/hipay/hipay-fullservice-sdk-magento2
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Sdd extends FullserviceMethod
{
    public const HIPAY_METHOD_CODE = 'hipay_sdd';

    /**
     * @var string
     */
    protected static $_technicalCode = 'sdd';

    /**
     * @var string
     */
    protected $_code = self::HIPAY_METHOD_CODE;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canUseInternal = false;

    /**
     *  Additional datas
     *
     * @var array
     */
    protected $_additionalInformationKeys = [
        'sdd_gender',
        'sdd_bank_name',
        'sdd_code_bic',
        'sdd_iban',
        'sdd_firstname',
        'sdd_lastname',
        'cc_type'
    ];

    /**
     * Get Additional Information Keys
     *
     * @return array|string[]
     */
    protected function getAdditionalInformationKeys()
    {
        return array_merge(['profile_id'], $this->_additionalInformationKeys);
    }

    /**
     * Assign data to info model instance
     *
     * @param  \Magento\Framework\DataObject $additionalData
     * @return $this
     * @throws LocalizedException
     */
    public function _assignAdditionalInformation(\Magento\Framework\DataObject $additionalData)
    {
        parent::_assignAdditionalInformation($additionalData);
        $info = $this->getInfoInstance();
        $info->setCcType($additionalData->getCcType());

        return $this;
    }

    /**
     * Validate payment method information object
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validate()
    {
        /**
         * Calling parent validate function
         */
        parent::validate();
        $info = $this->getInfoInstance();

        if (!$info->getCcType()) {
            return $this;
        }

        $errorMsg = '';

        // Get iso code from order or quote ( Validate is called twice per magento core )
        $order = $info->getQuote();
        if ($info->getOrder()) {
            $order = $info->getOrder();
        }

        // Instantiate validators for the model
        $validatorIban = new \Zend\Validator\Iban(
            array('country_code' => $order->getBillingAddress()->getCountryId())
        );
        $validatorEmpty = new \Zend\Validator\NotEmpty();

        if (!$validatorIban->isValid($info->getAdditionalInformation('sdd_iban'))) {
            $errorMsg = __('Iban is not correct, please enter a valid Iban.');
        } else {
            if (!$validatorEmpty->isValid($info->getAdditionalInformation('sdd_firstname'))) {
                $errorMsg = __('Firstname is mandatory.');
            } elseif (!$validatorEmpty->isValid($info->getAdditionalInformation('sdd_lastname'))) {
                $errorMsg = __('Lastname is mandatory.');
            } elseif (!$validatorEmpty->isValid($info->getAdditionalInformation('sdd_code_bic'))) {
                $errorMsg = __('Code BIC is not correct, please enter a valid Code BIC.');
            } elseif (!$validatorEmpty->isValid($info->getAdditionalInformation('sdd_bank_name'))) {
                $errorMsg = __('Bank name is not correct, please enter a valid Bank name.');
            }
        }

        if ($errorMsg) {
            throw new \Magento\Framework\Exception\LocalizedException($errorMsg);
        }
        return $this;
    }
}
