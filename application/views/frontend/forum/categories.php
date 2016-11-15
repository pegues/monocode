<?php include 'header.php' ?>

<ul class="categorylist">
    <li class="categorylistheader">
        <span class="topiccount right">Topics</span>
        <span class="title">Category</span>
    </li>

    <?php
    if (isset($categories) && sizeof($categories) > 0) {
        foreach ($categories as $ety) {
            $time = $ety->updated_at;
            ?>
            <li class="categorylistrow">
                <span class="topiccount right"><?php echo $ety->topic_count; ?></span>
                <span class="title"><a href="<?php echo $base_url; ?>?c=<?php echo $ety->id; ?>"><?php echo $ety->name; ?></a></span>
            </li>
            <?php
        }
    }
    ?>

</ul>