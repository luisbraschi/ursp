<?php
/**
 * OrangeValley
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
 * @category   OrangeValley
 * @package    OrangeValley_YahooAnalytics
 * @copyright  Copyright (c) 2010 OrangeValley (http://www.orangevalley.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * YahooAnalytics Page Block
 *
 * @category   OrangeValley
 * @package    OrangeValley_YahooAnalytics
 * @author     OrangeValley <info@orangevalley.nl>
 */
class OrangeValley_YahooAnalytics_Block_Ya extends Mage_Core_Block_Text
{
	 /**
     * Retrieve Quote Data HTML
     *
     * @return string
     */
    public function getYahooSearchHtml()
    {
		$query = Mage::getBlockSingleton('catalogsearch/result')->helper('catalogSearch')->getEscapedQueryText(); 

        if (!$query) {
            return '';
        }

		$number = Mage::getBlockSingleton('catalogsearch/result')->getResultCount();
		if (!isset($numer)) { $numer = 0;};

        $html = '';
		$html .= 'YWATracker.setAction("INTERNAL_SEARCH");';
		$html .= 'YWATracker.setISK("'. $query .'");';
		$html .= 'YWATracker.setISR("'. $number . '");';
		$html .= 'YWATracker.setDocumentGroup("Search");';

        return $html;
    }

    /**
     * Retrieve Quote Data HTML
     *
     * @return string
     */
    public function getYahooQuoteOrdersHtml()
    {
        $quote = $this->getQuote();
        if (!$quote) {
            return '';
        }

        if ($quote instanceof Mage_Sales_Model_Quote) {
            $quoteId = $quote->getId();
        } else {
            $quoteId = $quote;
        }

        if (!$quoteId) {
            return '';
        }

        $orders = Mage::getResourceModel('sales/order_collection')
            ->addAttributeToFilter('quote_id', $quoteId)
            ->load();

        $html = '';
        foreach ($orders as $order) {
            $html .= $this->setOrder($order)->getOrderHtml();
        }

        return $html;
    }

    /**
     * Retrieve Order Data HTML
     *
     * @return string
     */
    public function getOrderHtml()
    {

        $order = $this->getOrder();
        if (!$order) {
            return '';
        }

        if (!$order instanceof Mage_Sales_Model_Order) {
            $order = Mage::getModel('sales/order')->load($order);
        }

        if (!$order) {
            return '';
        }

		$html = '';
		$sku = '';
		$units = '';
		$amounts = '';

        $address = $order->getBillingAddress();

        $html .= 'YWATracker.setAction("01");';

        foreach ($order->getAllItems() as $item) {
            if ($item->getParentItemId()) {
                continue;
            }

            $sku .=  $this->jsQuoteEscape($item->getSku()) . ';';
			$units .= $item->getQtyOrdered() . ';';
			$amounts .= round($item->getBasePrice(), 2) . ';';
        }

		$html .= 'YWATracker.setSKU("' . $sku . '");';
		$html .= 'YWATracker.setUnits("' . $units . '");';
		$html .= 'YWATracker.setAmounts("'. $amounts . '");';
		$html .= 'YWATracker.setAmount("' . Mage::app()->getStore()-> getCurrentCurrencyCode()  . round($order->getBaseGrandTotal(), 2) . '");';
		$html .= 'YWATracker.setShipping("' . round($order->getBaseShippingAmount(), 2) . '");';
		$html .= 'YWATracker.setDocumentGroup("Checkout");';
		$html .= 'YWATracker.setOrderId("' . $order->getIncrementId() . '");';
		$html .= 'YWATracker.setDocumentName("Sale Confirmation");';


        return $html;
    }

    /**
     * Retrieve Yahoo Account Identifier
     *
     * @return string
     */
    public function getAccount()
    {
        if (!$this->hasData('account')) {
            $this->setAccount(Mage::getStoreConfig('yahoo/analytics/account'));
        }
        return $this->getData('account');
    }

	
  /**
    public function pageGroup()
    {
        return $this->_getData('body_class');
    }

	*/


	public function customerID()
    {
		if($this->helper('customer')->isLoggedIn()) {
			$customer = "LoggedIn";	
		} else {
			$customer = "NoLogged";
		}
		return $customer;
    }

    /**
     * Retrieve current page URL
     *
     * @return string
     */
    public function getPageName()
    {
        if (!$this->hasData('page_name')) {
            //$queryStr = '';
            //if ($this->getRequest() && $this->getRequest()->getQuery()) {
            //    $queryStr = '?' . http_build_query($this->getRequest()->getQuery());
            //}
            $this->setPageName(Mage::getSingleton('core/url')->escape($_SERVER['REQUEST_URI']));
        }
        return $this->getData('page_name');
    }

    /**
     * Prepare and return block's html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!Mage::getStoreConfigFlag('yahoo/analytics/active')) {
            return '';
        }

		/**
		 * YWATracker.setDocumentGroup("' . $this->pageGroup() . '");
		 */

		 //$product = Mage::getModel('catalog/product');

        $this->addText('
<!-- BEGIN YAHOO ANALYTICS CODE -->
<script type="text/javascript">
//<![CDATA[
var yaJsHost = (("https:" == document.location.protocol) ? "https://s." : "http://d.");
document.write(unescape("%3Cscript src=\'" + yaJsHost + "yimg.com/mi/ywa.js\' type=\'text/javascript\'%3E%3C/script%3E"));
//]]>
</script>


<script type="text/javascript">
YWATracker = YWA.getTracker("' . $this->getAccount() . '");
YWATracker.setMemberId("' . $this->customerID() . '");
' . $this->getYahooSearchHtml() . '
' .  $this->getYahooQuoteOrdersHtml() . '
YWATracker.submit();
</script>
<noscript><div><img src="http://a.analytics.yahoo.com/p.pl?a=' . $this->getAccount() . '&amp;js=no" width="1" height="1" alt="" /></div></noscript>

<!-- END Yahoo ANALYTICS CODE -->

        ');

        return parent::_toHtml();
    }
}
