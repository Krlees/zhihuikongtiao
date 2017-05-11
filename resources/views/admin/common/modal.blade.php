<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">

        </div>
    </div>
</div>

<script>
    // 关闭modal清空内容
    $(".modal").on("hidden.bs.modal",function(e){
        $(this).removeData("bs.modal");
    });
</script>