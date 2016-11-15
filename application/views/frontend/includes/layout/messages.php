                            <?php /* Notifications: Start */ ?>
                            <?php
                            if (isset($messages) && $messages && count($messages) > 0) {
                                foreach ($messages as $message) {
                                    ?>
                                    <div class="notice <?php echo $message->type; ?> <?php echo $message->pinned ? ' pinned' : ''; ?>">
                                        <div class="notice_inside">
                                            <?php echo $message->msg; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <?php /* Notifications: End */ ?>
