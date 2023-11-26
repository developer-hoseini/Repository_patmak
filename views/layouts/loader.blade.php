<div id="loading" class="loader-layer flex align-items-center justify-content-center">
    <div class="loader-content">
        <div class="text-center"><i class="fa fa-spinner fa-spin fa-4x"></i></div>  
        <div><p class="text-center mt-3 fw-bold fs-4">چند لحظه منتظر باشید ...</p>  </div>
        
    </div>
</div>
<script type="text/javascript">
    function hideLoading(){
        $('#loading').addClass('hidden');
        $('#loading').removeClass('flex');
    }

    function showLoading(){
        $('#loading').addClass('flex');
        $('#loading').removeClass('hidden');
    }

    $(window).ready(function(){
        hideLoading();
    });
</script>