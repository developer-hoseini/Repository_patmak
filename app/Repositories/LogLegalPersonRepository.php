<?php

namespace App\Repositories;

use App\Models\LogLegalPersonModel;
use App\Repositories\Interfaces\LogLegalPersonRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LogLegalPersonRepository implements LogLegalPersonRepositoryInterface{

    public function find($id){
        return DB::table('log_legal_person')->where('NationalCode', $id)->first();
    }

    public function create($data)
    {
        $record = new LogLegalPersonModel();              
        $record->Address = $data['aAddress'];
        $record->BankRuptcyDate = json_encode($data['aBankRuptcyDate']);
        $record->BranchList = json_encode($data['aBranchList']);
        $record->BreakUpDate = json_encode($data['aBreakUpDate']);
        $record->EstablishmentDate = $data['aEstablishmentDate'];
        $record->FollowUpNo = $data['aFollowUpNo'];
        $record->IsBankRupt = $data['aIsBankRupt'];
        $record->IsBranch = $data['aIsBranch'];
        $record->IsBreakUp = $data['aIsBreakUp'];
        $record->IsSettle = $data['aIsSettle'];
        $record->IsSuspention = $data['aIsSuspention'];
        $record->IsTaxRestricted = $data['aIsTaxRestricted'];
        $record->JSONRseult = json_encode($data['aJSONRseult']);
        $record->LastChangeDate = $data['aLastChangeDate'];
        $record->LegalPersonType = json_encode($data['aLegalPersonType']);
        $record->LegalPersonTypeId = json_encode($data['aLegalPersonTypeId']);
        $record->LicenseDate = json_encode($data['aLicenseDate']);
        $record->LicenseNumber = json_encode($data['aLicenseNumber']);
        $record->Message = json_encode($data['aMessage']);
        $record->Name = $data['aName'];
        $record->NationalCode = $data['aNationalCode'];
        $record->ParentLegalPerson = json_encode($data['aParentLegalPerson']);
        $record->PostCode = $data['aPostCode'];
        $record->RegisterDate = $data['aRegisterDate'];
        $record->RegisterNumber = $data['aRegisterNumber'];
        $record->RegisterUnit = json_encode($data['aRegisterUnit']);
        $record->Residency = json_encode($data['aResidency']);
        $record->ReviewTypeID = json_encode($data['aReviewTypeID']);
        $record->ReviewTypeState = $data['aReviewTypeState'];
        $record->ReviewTypeTitle = json_encode($data['aReviewTypeTitle']);
        $record->SettleDate = json_encode($data['aSettleDate']);
        $record->State = $data['aState'];
        $record->Successful = $data['aSuccessful'];
        $record->TaxRestrictDate = json_encode($data['aTaxRestrictDate']);
        $record->UnitId = json_encode($data['aUnitId']);

        $record->created_at = date('Y-m-d H:i:s');

        $record->save();
        return $record->id;
    }
}