<?php
/**
 * Class     Stock.php
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class SchumacherFM_FastIndexer_Model_Resource_CatalogInventory_Indexer_Stock extends Mage_CatalogInventory_Model_Resource_Indexer_Stock
{

    /**
     * @inheritdoc
     */
    public function reindexAll()
    {
        $disableTransaction = Mage::helper('schumacherfm_fastindexer')->isTransactionDisabledForStockReindex();

        $this->useIdxTable(true);
        if(!$disableTransaction) {
            $this->beginTransaction();
        }
        try {
            $this->clearTemporaryIndexTable();

            foreach ($this->_getTypeIndexers() as $indexer) {
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