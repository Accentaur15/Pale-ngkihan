<?php
if(isset($_SESSION['message'])){
    ?>
        <div class="alert alert-default-warning alert-dismissible fade show" role="alert">
            <strong>Hey!</strong> <?= $_SESSION['message']; ?>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        </div>
    <?php
    unset($_SESSION['message']);
}
?>
