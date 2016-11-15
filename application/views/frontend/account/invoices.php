
<div class="sorterinfo">
    <?php if (isset($entities)) { ?>
        <!--
        <p>Showing <?php echo count($entities); ?> of <?php echo $total_count; ?></p>
        -->
    <?php } ?>

    <form>
        <div class="sorterfields">
            <div class="sorterfielditem">
                <select name="status" id="statussorter" class="select sorter" onchange="$(this).closest('form').submit()">
                    <option value="">All</option>
                    <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : ''; ?>>Complete</option>
                    <option value="-1" <?php echo isset($status) && $status == -1 ? 'selected' : ''; ?>>Pending</option>
                    <!--option value="">Cancelled</option-->
                </select>

                <div class="clear"></div>
            </div>
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
            <th class="trownum">No. </th>
            <th class="tdate">Paid Date</th>
            <th class="tdate">Created Date</th>
            <th class="ttranid">Invoice Number</th>
            <th class="tprice">Price</th>
            <!--th class="tgateway">Gateway</th-->
            <th class="tstatus">Status</th>
            <th class="tactions">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($entities)) {
            $offset = intval($this->uri->segment(3));
            $index = 0;
            foreach ($entities as $ety) {
                ?>
                <tr class="bodyrow <?php ++$index % 2 == 0 ? 'odd' : 'even'; ?>">
                    <td class="trownum"><?php echo $offset + $index; ?></td>
                    <td class="ttranid">
                        <?php echo date('Y-m-d H:i', strtotime($ety->paid_date)); ?>
                    </td>
                    <td class="ttranid">
                        <?php echo date('Y-m-d H:i', strtotime($ety->created_date)); ?>
                    </td>
                    <td class="ttranid">#<?php echo $ety->invoice_number; ?></td>
                    <td class="tprice" style="text-align: right">$<?php echo number_format($ety->amount, 2); ?></td>
                    <!--td class="tgateway"></td-->
                    <td class="tstatus">
                        <?php if ($ety->is_paid) { ?>
                            <div class="tpaymentstatus complete">
                                <i class="fa fa-check"></i>
                                <span>(Complete)</span>
                            </div>
                        <?php } else { ?>
                            <div class="tpaymentstatus pending">
                                <i class="fa fa-exclamation"></i>
                                <span>(Pending)</span>
                            </div>
                        <?php } ?>
                    </td>
                    <td class="tactions">
                        <div class="tactionsholder">
                            <a href="#" onclick="return showInvoice('<?php echo $ety->invoice_number; ?>');" class="tactionsview">
                                <i class="fa fa-file-text">
                                    <span>View</span>
                                </i>
                            </a>
                            <a href="<?php echo $base_url . 'invoice/' . $ety->invoice_number . '/download'; ?>" class="tactionsdownload">
                                <i class="fa fa-download">
                                    <span>Download</span>
                                </i>
                            </a>
                        </div>
                    </td>
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

<script type="text/javascript">
    function showInvoice(invoiceNumber) {
        popupwindow('<?php echo $base_url; ?>invoice/' + invoiceNumber, 'Invoice #' + invoiceNumber, 500, 700);
        return false;
    }
    function popupwindow(url, title, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }
</script>