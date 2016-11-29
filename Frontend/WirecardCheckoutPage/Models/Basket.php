<?php
/**
 * Shop System Plugins - Terms of Use
 *
 * The plugins offered are provided free of charge by Wirecard Central Eastern Europe GmbH
 * (abbreviated to Wirecard CEE) and are explicitly not part of the Wirecard CEE range of
 * products and services.
 *
 * They have been tested and approved for full functionality in the standard configuration
 * (status on delivery) of the corresponding shop system. They are under General Public
 * License Version 2 (GPLv2) and can be used, developed and passed on to third parties under
 * the same terms.
 *
 * However, Wirecard CEE does not provide any guarantee or accept any liability for any errors
 * occurring when used in an enhanced, customized shop system configuration.
 *
 * Operation in an enhanced, customized configuration is at your own risk and requires a
 * comprehensive test phase by the user of the plugin.
 *
 * Customers use the plugins at their own risk. Wirecard CEE does not guarantee their full
 * functionality neither does Wirecard CEE assume liability for any disadvantages related to
 * the use of the plugins. Additionally, Wirecard CEE does not guarantee the full functionality
 * for customized shop systems or installed plugins of other vendors of plugins within the same
 * shop system.
 *
 * Customers are responsible for testing the plugin's functionality before starting productive
 * operation.
 *
 * By installing the plugin into the shop system the customer agrees to these terms of use.
 * Please do not use the plugin if you do not agree to these terms of use!
 */

/**
 * class representing a basket object stored to the database
 */
class Shopware_Plugins_Frontend_WirecardCheckoutPage_Models_Basket
{
    /**
     * getter for basket content
     * @return array|null
     */
    public function getBasket()
    {
        if (FALSE == Shopware()->WirecardCheckoutPage()->Config()->restoreBasket()) {
            return NULL;
        }

        Shopware()->WirecardCheckoutPage()->getLog()->Info('ID: ' . Shopware()->SessionID());
        $sql = Shopware()->Db()->select()
            ->from('s_order_basket')
            ->where('sessionID = ?', array(Shopware()->SessionID()));
        $basket = Shopware()->Db()->fetchAll($sql);
        return $basket;
    }

    /**
     * getter for serialized basket item
     *
     * @return string
     */
    public function getSerializedBasket()
    {
        return serialize($this->getBasket());
    }

    /**
     * Restore basket if it's enabled in the configuration
     *
     * @param array $basket
     * @return bool
     */
    public function setBasket($basket = array())
    {
        if (FALSE == Shopware()->WirecardCheckoutPage()->Config()->restoreBasket()) {
            return FALSE;
        }
        Shopware()->Db()->delete('s_order_basket', 'sessionID = "' . Shopware()->SessionID() . '"');
        foreach ($basket as $row) {
            Shopware()->Db()->insert('s_order_basket', $row);
        }
        return TRUE;
    }

    /**
     * setter for serialized basketItems
     *
     * @param $basket
     * @return bool
     */
    public function setSerializedBasket($basket)
    {
        return $this->setBasket(unserialize($basket));
    }

}