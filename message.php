<?php
$statusMessage = $statusMessage ?? new Resultat();
?>

<?php if ($statusMessage->isPopup()): ?>
    <div class="popup">
        <p class="popup_message"><strong>
                <?php echo $statusMessage->getMessage(); ?>
            </strong></p>
    </div>
<?php endif; ?>

<?php if ($statusMessage->isErreur()): ?>
    <div class="error-msg">
        <?php echo $statusMessage->getMessage(); ?>
    </div>
<?php endif; ?>