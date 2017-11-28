<?php
/**
 * Class     Process.php
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class SchumacherFM_FastIndexer_Model_System_Config_Process
{

    public function toOptionArray()
    {

        $collection = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $options = [];
        foreach ($collection as $process) {
            $options[] = [
                'value' => $process->getIndexerCode(),
                'label' => $process->getIndexerCode(),
            ];
        }

        return $options;
    }
}