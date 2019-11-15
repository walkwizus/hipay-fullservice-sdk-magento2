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
 * @copyright      Copyright (c) 2016 - HiPay
 * @license        http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0 Licence
 *
 */
namespace HiPay\FullserviceMagento\Model\Method\Astropay;

use HiPay\FullserviceMagento\Model\HostedMethod;
use HiPay\FullserviceMagento\Model\Method\AbstractMethodAPI;

/**
 * Aura Model payment method
 *
 * @package HiPay\FullserviceMagento
 * @author Kassim Belghait <kassim@sirateck.com>
 * @copyright Copyright (c) 2016 - HiPay
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0 Licence
 * @link https://github.com/hipay/hipay-fullservice-sdk-magento2
 */
class Aura extends AbstractAstropay
{
    const HIPAY_METHOD_CODE = 'hipay_aura';
    const HIPAY_METHOD_IDENTIFICATION = parent::IDENTIFICATION_CPF;

    /**
     * @var string
     */
    protected static $_technicalCode = 'aura';

    /**
     * @var string
     */
    protected $_code = self::HIPAY_METHOD_CODE;

    /**
     *  CPN or CPF identification
     *
     * @var bool
     */
    protected $_typeIdentification = self::HIPAY_METHOD_IDENTIFICATION;
}
