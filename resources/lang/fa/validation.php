<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => ':attribute انتخاب شده معتبر نیست',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => ':attribute انتخاب شده معتبر نیست.',
    'in_array' => 'The :attribute field does not exist in :other.',
    // 'integer' => 'The :attribute must be an integer.',
    'integer' => 'فیلد :attribute می بایست عدد باشد.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        // 'file' => 'The :attribute may not be greater than :max kilobytes.',
        'file' => ':attribute نباید بیشتر از :max کیلوبایت باشد.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => ':attribute می بایست یکی از انواع :values باشد.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => ':attribute باید حداقل شامل یک رکورد باشد',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'فیلد :attribute اجباری است.',
    // 'required_if' => 'The :attribute field is required when :other is :value.',
    'required_if' => 'فیلد :attribute در صورتیکه فیلد :other برابر :value باشد اجباری است.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */    

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    'error' => 'خطای اعتبار سنجی',

    'invalid_person_nationl_code' => 'کد ملی معتبر نیست',
    'invalid_organization_national_code' => 'شناسه ملی شرکت ملی معتبر نیست.',
    'invalid_jalali_date' => 'مقدار وارد شده برای فیلد :attribute تاریخ معتبر نیست.',
    'invalid_mobile_number' => 'موبایل وارد شده معتبر نیست.',
    'invalid_tel_number' => 'فیلد :attribute باید حداقل 4 و حداکثر 8 رقم باشد',
    'invalid_tel_number_and_code' => 'فیلد :attribute باید حداقل 7 و حداکثر 11 رقم حاوی کد شهر و شماره تلفن باشد.',
    'invalid_tel_code' => 'فیلد :attribute باید حداقل 3 و حداکثر 4 رقم باشد و با صفر شروع می شود',
    'invalid_postal_code' => 'فیلد :attribute باید ده رقم باشد.',
    'invalid_captcha' => 'کد امنیتی به درستی وارد نشده است',
    'invalid_otp' => 'کد ارسال شده به عنوان رمز یکبار مصرف اشتباه است.',
    'invalid_membership' => 'شماره عضویت معتبر نیست.',
    'invalid_employment_license_number' => 'شماره پروانه اشتغال بکار معتبر نیست.',

    'mismatch_mobile' => 'شما قبلا با شماره :old_number وارد سامانه شده اید. در صورت تمایل به ادامه با شماره قبلی روی ویرایش کلیک کنید و با شماره قبلی وارد شوید. در غیر اینصورت جهت ورود با شماره جدید روی دکمه تایید کلیک کنید.',

    'captcha' => 'نتیجه عبارت ریاضی صحیح نیست.',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'requesttype' => 'نوع درخواست',
        'morg' => 'سازمان (عضویت در)',
        'province' => 'استان',
        'licensetype' => 'نوع پروانه',
        'studyfield' => 'مدرک تحصیلی',
        'birthdate' => 'تاریخ تولد',
        'mobile' => 'شماره تلفن همراه',
        'orgncode' => 'شناسه ملی (شماره ثبت دفتر)',
        'ncode' => 'کد ملی',

        'birthplace' => 'محل صدور',
        'marital_status' => 'وضعیت تاهل',
        'educationRecords' => 'تحصیلات',
        'requestRecords' => 'پایه و صلاحیت مورد نظر برای پروانه جدید',
        'file' => 'فایل ارسال شده',
        'records.*.date' => 'تاریخ احراز صلاحیت',
        'records.*.basis' => 'پایه',

        'trackingcode' => 'کدپیگیری متقاضی',
        'orderid' => 'سریال تراکنش',
        'captcha' => 'نتیجه عملیات ریاضی',

        'work_address' => 'آدرس محل کار',
        'work_tel_number' => 'تلفن محل کار',
        'persontype' => 'نوع شخص',
        

    ],
    'values' => [
        'persontype' => [
            'legal' => 'حقوقی'
         ],
     ],

];
