<?php
namespace Balaji\ShippingHide\Plugin\Magento\Shipping\Model;
 
class Shipping
{
    protected $product;
 
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $product
    ) {
        $this->product = $product; 
    }
 
    public function aroundCollectCarrierRates(
        \Magento\Shipping\Model\Shipping $subject,
        \Closure $proceed,
        $carrierCode,
        $request
    ) {
        $samedaydelivery = false;
        $allItems = $request->getAllItems();
         
        // iterate all cart products to check if samedaydelivery_attribute is true
        
        
        foreach ($allItems as $item) {    
            $_product = $this->product->create()->load($item->getProduct()->getId());
            // if product has samedaydelivery_attribute true
            if ($_product->getSamedaydeliveryAttribute()) {
                $samedaydelivery = true;
                break;
            }
        }
        
       /* 
        if ($samedaydelivery && $carrierCode == 'freeshipping') {
            return false;
        }
         */
         
          if ($samedaydelivery && $carrierCode == 'same_day_delivery_available') {
            return false;
        }
         
 
        $result = $proceed($carrierCode, $request);
        return $result;
    }
}