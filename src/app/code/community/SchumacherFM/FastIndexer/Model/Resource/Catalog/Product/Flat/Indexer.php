<?php
/**
 * Class     Indexer.php
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class SchumacherFM_FastIndexer_Model_Resource_Catalog_Product_Flat_Indexer extends Mage_Catalog_Model_Resource_Product_Flat_Indexer
{

    /**
     * @inheritdoc
     */
    public function reindexAll()
    {
        $disableTransaction = Mage::helper('schumacherfm_fastindexer')->isTransactionDisabledForProductFlatReindes();

        foreach (Mage::app()->getStores() as $storeId => $store) {
            $this->prepareFlatTable($storeId);
            if(!$disableTransaction) {
                $this->beginTransaction();
            }
            try {
                $this->rebuild($store);
                if(!$disableTransaction) {
                    $this->commit();
                }
            } catch (Exception $e) {
                if(!$disableTransaction) {
                    $this->rollBack();
                }
                throw $e;
            }
        }

        return $this;
    }

}