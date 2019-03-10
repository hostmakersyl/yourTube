<?php require_once "includes/header.php"; ?>
<?php require_once "includes/classes/VideoDetailsFormProvider.php"; ?>
<div class="column">
  <?php
    $formProvider = new VideoDetailsFormProvider($connection);
    echo $formProvider->createUploadForm();



  ?>
</div>

    <!-- Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                   Please Wait, It will take some time.
                    <img src="assets/images/icons/loading-spinner.gif">
                </div>
            </div>
        </div>
    </div>



<?php require_once "includes/fotter.php" ;?>
<script>
  $("form").submit(function () {
    $("#loadingModal").modal("show")
  });
</script>
