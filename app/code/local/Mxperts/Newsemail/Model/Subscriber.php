<?php
/**
 * @category Mxperts
 * @package Mxperts_Newsemail
 * @author TMEDIA cross communications <info@tmedia.de>, Igor Jankovic <jankovic@tmedia.de>
 * @copyright TMEDIA cross communications, Doris Teitge-Seifert
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Mxperts_Newsemail_Model_Subscriber extends Mage_Newsletter_Model_Subscriber 
{
        
        public function sendConfirmationRequestEmail()
        {

          if ( Mage::getStoreConfig('newsletter/admin/requestemail') == 1) 
		      {
             return parent::sendConfirmationRequestEmail();                     
          }
          else
          {
             return $this;
          }
      }
        
        

	    public function sendConfirmationSuccessEmail()
    	{
    	
    	    if (Mage::getStoreConfig('newsletter/admin/successemail') == 1) 
		      {
             return parent::sendConfirmationSuccessEmail();                                 
          }
          else
          {
            return $this;
          }
    	}


      public function sendUnsubscriptionEmail()
    	{
          
           if (Mage::getStoreConfig('newsletter/admin/unsubscriptionemail') == 1) 
		      {  
              return parent::sendUnsubscriptionEmail();                       
          }
          else
          {
              return $this;
          }
        }
}