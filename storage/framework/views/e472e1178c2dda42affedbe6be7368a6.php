<?php
    $method = "get_static_option";
?>

<?php if($method('google_analytics_gt4_status') === 'on'): ?>

<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e($method('google_analytics_gt4_ID')); ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', "<?php echo e($method('google_analytics_gt4_ID')); ?>");
</script>
<?php endif; ?>

<?php if($method('whatsapp_status') == 'on'): ?>
<style>
    .whatsapp-integration-wrap a{
        position: fixed;
        right: 90px;
        bottom: 20px;
        background-color: #40c351d1;
        z-index: 99;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<?php endif; ?>
<?php if($method('crsip_status') == 'on'): ?>
<script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="<?php echo e(get_static_option('crsip_website_id')); ?>";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
<?php endif; ?>
<?php /**PATH D:\laravel_workspace\Fassla\planb\core\Modules/Integrations\Resources/views/tracking-before-head.blade.php ENDPATH**/ ?>