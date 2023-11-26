<?php

namespace App\Services;

use App\Repositories\Interfaces\LogLegalPersonRepositoryInterface;
use stdClass;

class FakeSabteasnad {

    protected $logLegalPersonRepository;

    public function __construct(LogLegalPersonRepositoryInterface $logLegalPerson)
    {
        $this->logLegalPersonRepository = $logLegalPerson;
    }

    public function inquirySpecialByNationalCode(){
        $legal_person['aAddress'] = "استان تهران - منطقه 14 ، شهرستان تهران ، بخش مركزي ، شهر تهران، محله شهرك قدس(غرب) ، كوچه باغ راه فدك ، خيابان گذر شهريور ، پلاك 7 ، طبقه اول";
        $legal_person['aBankRuptcyDate'] = "";
        $legal_person['aBranchList'] = "null";
        $legal_person['aBreakUpDate'] = "";
        $legal_person['aEstablishmentDate'] = "1392/08/26";
        $legal_person['aFollowUpNo'] = "704728854";
        $legal_person['aIsBankRupt'] = "false";
        $legal_person['aIsBranch'] = "false";
        $legal_person['aIsBreakUp'] = "false";
        $legal_person['aIsSettle'] = "false";
        $legal_person['aIsSuspention'] = "false";
        $legal_person['aIsTaxRestricted'] = "false";
        $legal_person['aJSONRseult'] = "null";
        $legal_person['aLastChangeDate'] = "1399/04/10";
        $legal_person['aLegalPersonType'] = "";
        $legal_person['aLegalPersonTypeId'] = "";
        $legal_person['aLicenseDate'] = "";
        $legal_person['aLicenseNumber'] = "";
        $legal_person['aMessage'] = "null";
        $legal_person['aName'] = "مهان پردازش امين";
        $legal_person['aNationalCode'] = "14003724366";
        $legal_person['aParentLegalPerson'] = "null";
        $legal_person['aPostCode'] = "1465673633";
        $legal_person['aRegisterDate'] = "1392/08/26";
        $legal_person['aRegisterNumber'] = "445272";
        $legal_person['aRegisterUnit'] = "";
        $legal_person['aResidency'] = "";
        $legal_person['aReviewTypeID'] = "null";
        $legal_person['aReviewTypeState'] = "false";
        $legal_person['aReviewTypeTitle'] = "null";
        $legal_person['aSettleDate'] = "";
        $legal_person['aState'] = "فعال";
        $legal_person['aSuccessful'] = "true";
        $legal_person['aTaxRestrictDate'] = "";
        $legal_person['aUnitId'] = "";
        $legal_person['astatus'] = "ru";
        $legal_person['amessage'] = "پاسخ مناسب دریافت شد";
        $legal_person['aConversationID'] ="";

        $this->logLegalPersonRepository->create($legal_person);

        return ['status' => true, 'message' => '', 'data' => $legal_person];
    }
}