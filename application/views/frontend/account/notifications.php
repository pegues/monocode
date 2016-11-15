<?php
$offset = intval($this->uri->segment(3));
?>
<div class="sorterinfo">
    <?php if ((isset($entities) && $entities)) { ?>
        <p>Showing <?php echo $offset + 1; ?> of <?php echo $offset + (isset($entities) && $entities ? count($entities) : 0); ?></p>
    <?php } ?>
    <form>
        <div class="sorterfields">
            <!--div class="sorterfielditem">
                <select id="statussorter" class="select sorter" onchange="$(this).closest('form').submit()">
                    <option value="">All</option>
                    <option value="">Complete</option>
                    <option value="">Pending</option>
                    <option value="">Cancelled</option>
                </select>

                <div class="clear"></div>
            </div-->
            <div class="sorterfielditem">
                <select name="icpp" id="countersorter" class="select counter" onchange="$(this).closest('form').submit()">
                    <option <?php echo isset($icpp) && $icpp == 10 ? 'selected' : ''; ?> value="10">10</option>
                    <option <?php echo isset($icpp) && $icpp == 25 ? 'selected' : ''; ?> value="25">25</option>
                    <option <?php echo isset($icpp) && $icpp == 50 ? 'selected' : ''; ?>  value="50">50</option>
                    <option <?php echo isset($icpp) && $icpp == 75 ? 'selected' : ''; ?>  value="75">75</option>
                </select>

                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
    </form>
    <div class="clear"></div>
</div>


<table class="userdatatable" border="0">
    <thead>
        <tr class="headrow">
            <th class="trownum">No.</th>
            <th class="ttranid"><a href="<?php echo current_url(); ?>#"><span>Message</span><!--i class="fa fa-unsorted"></i--></a></th>
            <th class="tdate"><a href="<?php echo current_url(); ?>#"><span>Date</span><!--i class="fa fa-caret-down"></i--></a></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($entities)) {
            $index = 0;
            foreach ($entities as $ety) {
                ?>
                <tr class="bodyrow <?php ++$index % 2 == 0 ? 'odd' : 'even'; ?>">
                    <td class="trownum"><?php echo $offset + $index; ?></td>
                    <td class="ttranid">
                        <a href="<?php echo $ety->url ? (base_url() . $ety->url) : current_url() . '#'; ?>"><?php echo $ety->message; ?></a>
                    </td>
                    <td class="tgateway"><?php echo date('Y-m-d H:i', strtotime($ety->created_time)); ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<div class="pagination">
    <?php echo $pagination ?>

    <div class="clear"></div>
</div>
