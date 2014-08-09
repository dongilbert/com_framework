<?php defined('_JEXEC') or die;

/** @var Framework\Entities\DetailEntity[] $items */
/** @var JPagination $pagination */

?>
<div class="container">
    <div class="row-fluid">
        <?php
        foreach ($items as $item)
        {
            $this->set('item', $item);

            echo $this->partial('single');
        }
        ?>
    </div>
    <div id="main-pagination">
        <?php echo $pagination->getListFooter(); ?>
    </div>
</div>
