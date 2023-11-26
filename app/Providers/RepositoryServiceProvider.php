<?php

namespace App\Providers;

use App\Repositories\AdministratorRepository;
use App\Repositories\ApplicationRepository;
use App\Repositories\ContactInfoRepository;
use App\Repositories\EducationRepository;
use App\Repositories\InsuranceRepository;
use App\Repositories\Interfaces\AdministratorRepositoryInterface;
use App\Repositories\Interfaces\ApplicationRepositoryInterface;
use App\Repositories\Interfaces\ContactInfoRepositoryInterface;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use App\Repositories\Interfaces\InsuranceRepositoryInterface;
use App\Repositories\Interfaces\LegalPersonRepositoryInterface;
use App\Repositories\Interfaces\LogAdminRepositoryInterface;
use App\Repositories\Interfaces\LogIpgCallRepositoryInterface;
use App\Repositories\Interfaces\LogLegalPersonRepositoryInterface;
use App\Repositories\Interfaces\LogOtpRepositoryInterface;
use App\Repositories\Interfaces\LogSabteAhvalRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\PersonalInfoRepositoryInterface;
use App\Repositories\Interfaces\PreviousLicenseAuthBasisRepositoryInterface;
use App\Repositories\Interfaces\PreviousLicenseRepositoryInterface;
use App\Repositories\Interfaces\RefCityRepositoryInterface;
use App\Repositories\Interfaces\RefCostRepositoryInterface;
use App\Repositories\Interfaces\RefEducationGradeRepositoryInterface;
use App\Repositories\Interfaces\RefGtpRepositoryInterface;
use App\Repositories\Interfaces\RefLicenseAuthRepositoryInterface;
use App\Repositories\Interfaces\RefLicenseBasisRepositoryInterface;
use App\Repositories\Interfaces\RefProvinceRepositoryInterface;
use App\Repositories\RefProvinceRepository;
use App\Repositories\Interfaces\RefMrudOrganizationRepositoryInterface;
use App\Repositories\RefMrudOraganizationRepository;
use App\Repositories\RefStudyFieldRepository;
use App\Repositories\Interfaces\RefLicenseTypeRepositoryInterface;
use App\Repositories\Interfaces\RefRequestTypeRepositoryInterface;
use App\Repositories\Interfaces\RefStudyFieldRepositoryInterface;
use App\Repositories\Interfaces\RequestAuthBasisRepositoryInterface;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\LegalPersonRepository;
use App\Repositories\LogAdminRepository;
use App\Repositories\LogIpgCallRepository;
use App\Repositories\LogLegalPersonRepository;
use App\Repositories\LogOtpRepository;
use App\Repositories\LogSabteAhvalRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PersonalInfoRepository;
use App\Repositories\PreviousLicenseAuthBasisRepository;
use App\Repositories\PreviousLicenseRepository;
use App\Repositories\RefCityRepository;
use App\Repositories\RefCostRepository;
use App\Repositories\RefEducationGradeRepository;
use App\Repositories\RefGtpRepository;
use App\Repositories\RefLicenseAuthRepository;
use App\Repositories\RefLicenseBasisRepository;
use App\Repositories\RefLicenseTypeRepository;
use App\Repositories\RefRequestTypeRepository;
use App\Repositories\RequestAuthBasisRepository;
use App\Repositories\RequestRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(RefProvinceRepositoryInterface::class, RefProvinceRepository::class);
        $this->app->bind(RefCityRepositoryInterface::class, RefCityRepository::class);
        $this->app->bind(RefMrudOrganizationRepositoryInterface::class, RefMrudOraganizationRepository::class);
        $this->app->bind(RefStudyFieldRepositoryInterface::class, RefStudyFieldRepository::class);
        $this->app->bind(RefLicenseTypeRepositoryInterface::class, RefLicenseTypeRepository::class);
        $this->app->bind(RefRequestTypeRepositoryInterface::class, RefRequestTypeRepository::class);
        $this->app->bind(RefEducationGradeRepositoryInterface::class, RefEducationGradeRepository::class);
        $this->app->bind(RefLicenseBasisRepositoryInterface::class, RefLicenseBasisRepository::class);
        $this->app->bind(RefLicenseAuthRepositoryInterface::class, RefLicenseAuthRepository::class);
        $this->app->bind(RefCostRepositoryInterface::class, RefCostRepository::class);
        $this->app->bind(RefGtpRepositoryInterface::class, RefGtpRepository::class);
        
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
        $this->app->bind(LegalPersonRepositoryInterface::class, LegalPersonRepository::class);
        $this->app->bind(PersonalInfoRepositoryInterface::class, PersonalInfoRepository::class);
        $this->app->bind(ContactInfoRepositoryInterface::class, ContactInfoRepository::class);
        $this->app->bind(InsuranceRepositoryInterface::class, InsuranceRepository::class);
        $this->app->bind(EducationRepositoryInterface::class, EducationRepository::class);
        $this->app->bind(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->bind(RequestAuthBasisRepositoryInterface::class, RequestAuthBasisRepository::class);
        $this->app->bind(PreviousLicenseRepositoryInterface::class, PreviousLicenseRepository::class);
        $this->app->bind(PreviousLicenseAuthBasisRepositoryInterface::class, PreviousLicenseAuthBasisRepository::class);

        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);

        $this->app->bind(LogLegalPersonRepositoryInterface::class, LogLegalPersonRepository::class);
        $this->app->bind(LogSabteAhvalRepositoryInterface::class, LogSabteAhvalRepository::class);
        $this->app->bind(LogOtpRepositoryInterface::class, LogOtpRepository::class);
        $this->app->bind(LogIpgCallRepositoryInterface::class, LogIpgCallRepository::class);

        $this->app->bind(AdministratorRepositoryInterface::class, AdministratorRepository::class);
        $this->app->bind(LogAdminRepositoryInterface::class, LogAdminRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
