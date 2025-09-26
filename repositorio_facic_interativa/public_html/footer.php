<style>
.footer-image {
    position: absolute;
    width: 80vw;
    left: 10vw;
    right: 10vw;
    opacity: .5;
    z-index: -999;
}
@media print {
    .footer-image {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100vw;
    }
}
</style>

<div id="titulo" class="grid-100 titulo">
  <?php include 'docconfig.php'; ?>
  <img src="<?= $__footer_img ?>" class="footer-image"
</div>