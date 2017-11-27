<?php
/**
 * Class     Price.php
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class SchumacherFM_FastIndexer_Model_Resource_Catalog_Product_Indexer_Price extends Mage_Catalog_Model_Resource_Product_Indexer_Price
{

    public function reindexAll()
    {
        $disableTransaction = Mage::helper('schumacherfm_fastindexer')->isTransactionDisabledForPriceReindex();

        $this->useIdxTable(true);
        if(!$disableTransaction){
            $this->beginTransaction();
        }
        try {
            $this->clearTemporaryIndexTable();
            $this->_prepareWebsiteDateTable();
            $this->_prepareTierPriceIndex();
            $this->_prepareGroupPriceIndex();

            $indexers = $this->getTypeIndexers();
            foreach ($indexers as $indexer) {
                /** @var $indexer Mage_Catalog_Model_Resource_Product_Indexer_Price_Interface */
                $indexer->reindexAll();
            }

            $this->syncData();
            if(!$disableTransaction) {
                $this->commit();
            }
        } catch (Exception $e) {
            if(!$disableTransaction) {
                $this->rollBack();
            }
            throw $e;
        }
        return $this;
    }


}