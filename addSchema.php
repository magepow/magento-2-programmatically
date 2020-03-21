<?php

use Magento\Framework\DB\Ddl\Table;

require dirname(__FILE__) . '/app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

class Outslide extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface {
    public function launch()
    {
        $installer = $this->_objectManager->create('\Magento\Setup\Module\Setup');

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('magepow_comments'))
            ->addColumn(
                'comment_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Comment ID'
            )
            ->addColumn('title', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null], 'Title')
            ->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Status')
            ->addColumn('store', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => '0'])
            ->addColumn('created_time', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Created Time')
            ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => true, 'default' => null], 'Update Time')
            ->addIndex($installer->getIdxName('comment_id', ['comment_id']), ['comment_id'])
            ->setComment('Magepow Comments');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();

		echo 'Done!';
        //the method must end with this line
        return $this->_response;
    }
}

/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication('Outslide');
$bootstrap->run($app);