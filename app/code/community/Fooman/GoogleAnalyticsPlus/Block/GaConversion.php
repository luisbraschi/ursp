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
 * to kristof@fooman.co.nz so we can send you a copy immediately.
 *
 * @category   Fooman
 * @package    Fooman_GoogleAnalyticsPlus
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Analytics block
 *
 * @category   Fooman
 * @package    Fooman_GoogleAnalyticsPlus
 * @author     Fooman, Kristof Ringleff <kristof@fooman.co.nz>
 */
class  Fooman_GoogleAnalyticsPlus_Block_GaConversion extends Mage_Core_Block_Template
{

    private $_quote;

    public function isEnabled(){
        return Mage::helper('googleanalyticsplus')->getGoogleanalyticsplusStoreConfig('conversionenabled',true);
    }

    public function getLabel(){
        return 'Purchase';
    }

    public function getColor(){
        return '#FFFFFF';
    }

    public function getLanguage(){
        return Mage::helper('googleanalyticsplus')->getGoogleanalyticsplusStoreConfig('conversionlanguage');
    }

    public function getConversionId(){
        return Mage::helper('googleanalyticsplus')->getGoogleanalyticsplusStoreConfig('conversionid');
    }

    public function getConversionUrl(){
        return ($this->getRequest()->isSecure())? 'https://www.googleadservices.com/pagead/conversion.js': 'http://www.googleadservices.com/pagead/conversion.js';      
    }

    public function getValue(){
        $quote = $this->_getQuote();
        if($quote){
            return $quote->getBaseGrandTotal();
        }else {
            return 0;
        }
    }

    private function _getQuote(){
        if(!$this->_quote){
            $quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
            if($quoteId){
                $this->_quote = Mage::getModel('sales/quote')->load($quoteId);
            }else{
               $this->_quote = false;
            }
        }
        return $this->_quote;
    }
}

