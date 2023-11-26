<div class="container">
    <div class="row">
        <div class="col-12">
            <table class="w-100 text-center">
                <tr>
                    <td id="step1" class="steps-icon">
                        <img class="step1 step-icon" src="{{ url('/assets/img/step-conditions.png') }}" alt="">
                        <h6 class="step1 step-title pt-1">تایید شرایط و دریافت خدمات</h6>
                    </td>
                    <td><i class="fa fa-long-arrow-alt-left fa-2x"></i></td>
                    <td id="step2" class="steps-icon">
                        <img class="step2 step-icon" src="{{ url('/assets/img/step-info1.png') }}" alt="">
                        <h6 class="step2 step-title pt-1">ورود اطلاعات اولیه</h6>
                    </td>
                    <td><i class="fa fa-long-arrow-alt-left fa-2x"></i></td>
                    <td id="step3" class="steps-icon">
                        <img class="step3 step-icon" src="{{ url('/assets/img/step-info2.png') }}" alt="">
                        <h6 class="step3 step-title pt-1">تکمیل اطلاعات درخواست</h6>
                    </td>
                    <td><i class="fa fa-long-arrow-alt-left fa-2x"></i></td>
                    <td id="step4" class="steps-icon">
                        <img class="step4 step-icon" src="{{ url('/assets/img/step-pay.png') }}" alt="">
                        <h6 class="step4 step-title pt-1">پرداخت</h6>
                    </td>
                    <td><i class="fa fa-long-arrow-alt-left fa-2x"></i></td>
                    <td id="step5" class="steps-icon">
                        <img class="step5 step-icon" src="{{ url('/assets/img/step-trackingcode.png') }}" alt="">
                        <h6 class="step5 step-title pt-1">نمایش کد رهگیری درخواست</h6>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    function activeIconStep2() {
        $('.steps-icon').removeClass('inactive-step');
        $('#step1').addClass('inactive-step');
    }

    function activeIconStep3() {
        $('.steps-icon').removeClass('inactive-step');
        $('#step1').addClass('inactive-step');
        $('#step2').addClass('inactive-step');
    }

    function activeIconStep4() {
        $('.steps-icon').removeClass('inactive-step');
        $('#step1').addClass('inactive-step');
        $('#step2').addClass('inactive-step');
        $('#step3').addClass('inactive-step');
    }

    function activeIconStep5() {
        $('.steps-icon').removeClass('inactive-step');
        $('#step1').addClass('inactive-step');
        $('#step2').addClass('inactive-step');
        $('#step3').addClass('inactive-step');
        $('#step4').addClass('inactive-step');
    }
</script>