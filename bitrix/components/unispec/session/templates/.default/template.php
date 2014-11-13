<script language="JavaScript">
$(function () {
    <?php foreach ( $arResult['hosts'] as $host ) { ?>
    $('body').append('<iframe width=1 height=1 border=0 style="position:absolute;left:-10000px;top:-10000px;" src="http://<?php echo $host; ?>/s.php?s1=<?php echo $arResult['source'];?>&s2=<?php echo urlencode($arResult['session']);?>&s3=<?php echo urlencode($arResult['iv']); ?>&s4=<?php echo $arResult['hash']; ?>"></iframe>');
    <?php } ?>
});
</script>