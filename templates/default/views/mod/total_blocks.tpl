<table class="table table-striped table-bordered table-vcenter">
    <thead>
    <tr>
        <th style="text-align: center;" class="sort-button" data-type="1" data-sort="asc">Block Type</th>
        <th style="text-align: center;" class="sort-button" data-type="2" data-sort="asc">Destroyed</th>
        <th style="text-align: center;" class="sort-button" data-type="3" data-sort="asc">Placed</th>
    </tr>
    </thead>
    <tbody class="content">
    <?php
    foreach($this->get('block_list') as $block): ?>
    <tr>
        <td>
            <?php echo $block->getImage(32, 'img-polaroid'); ?>
            <?php echo $block->getName(); ?>
        </td>
        <td>
            <?php echo TotalBlock::countAllOfType('destroyed', $block)->format(); ?>
        </td>
        <td>
            <?php echo TotalBlock::countAllOfType('placed', $block)->format(); ?>
        </td>
    </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div id="block_listPagination" class="pagination-centered"></div>

<script type="text/javascript">
    $(document).ready(function() {
        callModulePage(
            'block_list',
            <?php echo $this->get('block_list')->getPages(); ?>,
            <?php echo $this->get('block_list')->getPage(); ?>
        );
    });
</script>