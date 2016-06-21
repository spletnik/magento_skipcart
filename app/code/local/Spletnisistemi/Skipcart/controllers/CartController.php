<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

// include default controller
require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'CartController.php';

/**
 * Shopping cart controller
 */
class Spletnisistemi_Skipcart_CartController extends Mage_Checkout_CartController {
    protected function _goBack() {
        // if enabled in configuration
        if (Mage::getStoreConfig('checkout/options/skip_enabled')) {

            $returnUrl = $this->getRequest()->getParam('return_url');
            if ($returnUrl) {
                // clear layout messages in case of external url redirect
                if ($this->_isUrlInternal($returnUrl)) {
                    $this->_getSession()->getMessages(true);
                }
                $this->getResponse()->setRedirect($returnUrl);
            } elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart')
                && !$this->getRequest()->getParam('in_cart')
                && $backUrl = $this->_getRefererUrl()
            ) {
                $this->getResponse()->setRedirect($backUrl);
            } else {
                if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
                    $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
                }
                $this->_redirect('checkout/onepage');
            }
            return $this;
        } else {
            // else default action
            parent::_goBack();
        }
    }
}
