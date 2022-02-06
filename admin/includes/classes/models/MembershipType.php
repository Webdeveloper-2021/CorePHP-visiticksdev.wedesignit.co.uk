<?php
namespace includes\classes\models;

use includes\classes\API;

class MembershipType extends BaseModel
{
    public $id;
    public $name;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->_getFromAPI();
    }

    private function _getFromAPI()
    {
        $requestresult = API::get("memberships/".$this->id);

        if ($requestresult['ok'])
        {
            $this->name = $requestresult['content']->name;
            $this->membershipLengthType = $requestresult['content']->membershipLengthType;
            $this->specificExpirationDate = $requestresult['content']->specificExpirationDate;
            $this->membershipLength = $requestresult['content']->membershipLength;
            $this->numberOfPeople = $requestresult['content']->numberOfPeople ?? 0;
            $this->memberTypes = $requestresult['content']->memberTypes;
            $this->maximumAdults = $requestresult['content']->maximumAdults ?? 0;
            $this->maximumChildren = $requestresult['content']->maximumChildren ?? 0;
            $this->primaryMemberNameCaptureType = $requestresult['content']->primaryMemberNameCaptureType ?? 0;
            $this->primaryMemberDateOfBirthCaptureType = $requestresult['content']->primaryMemberDateOfBirthCaptureType ?? 0;
            $this->primaryMemberPhoneCaptureType = $requestresult['content']->primaryMemberPhoneCaptureType ?? 0;
            $this->primaryMemberEmailCaptureType = $requestresult['content']->primaryMemberEmailCaptureType ?? 0;
            $this->primaryMemberMarketingCaptureType = $requestresult['content']->primaryMemberMarketingCaptureType ?? 0;
            $this->posPrimaryMemberPhotoCaptureType = $requestresult['content']->posPrimaryMemberPhotoCaptureType ?? 0;
            $this->posPrimaryMemberMagstripeCaptureType = $requestresult['content']->posPrimaryMemberMagstripeCaptureType ?? 0;
            $this->additionalMemberAdultNameCaptureType = $requestresult['content']->additionalMemberAdultNameCaptureType ?? 0;
            $this->additionalMemberAdultDateOfBirthCaptureType = $requestresult['content']->additionalMemberAdultDateOfBirthCaptureType ?? 0;
            $this->additionalMemberAdultPhoneCaptureType = $requestresult['content']->additionalMemberAdultPhoneCaptureType ?? 0;
            $this->additionalMemberAdultEmailCaptureType = $requestresult['content']->additionalMemberAdultEmailCaptureType ?? 0;
            $this->additionalMemberAdultMarketingCaptureType = $requestresult['content']->additionalMemberAdultMarketingCaptureType ?? 0;
            $this->posAdditionalMemberAdultPhotoCaptureType = $requestresult['content']->posAdditionalMemberAdultPhotoCaptureType ?? 0;
            $this->posAdditionalMemberAdultMagstripeCaptureType = $requestresult['content']->posAdditionalMemberAdultMagstripeCaptureType ?? 0;
            $this->additionalMemberChildNameCaptureType = $requestresult['content']->additionalMemberChildNameCaptureType ?? 0;
            $this->additionalMemberChildDateOfBirthCaptureType = $requestresult['content']->additionalMemberChildDateOfBirthCaptureType ?? 0;
            $this->additionalMemberChildPhoneCaptureType = $requestresult['content']->additionalMemberChildPhoneCaptureType ?? 0;
            $this->additionalMemberChildEmailCaptureType = $requestresult['content']->additionalMemberChildEmailCaptureType ?? 0;
            $this->additionalMemberChildMarketingCaptureType = $requestresult['content']->additionalMemberChildMarketingCaptureType ?? 0;
            $this->posAdditionalMemberChildPhotoCaptureType = $requestresult['content']->posAdditionalMemberChildPhotoCaptureType ?? 0;
            $this->posAdditionalMemberChildMagstripeCaptureType = $requestresult['content']->posAdditionalMemberChildMagstripeCaptureType ?? 0;

            return $requestresult['content'];
        }

        return false;
    }

    public function items()
    {
        $requestresult = API::get("memberships/".$this->id);

        return $requestresult['ok'] ? $requestresult : [];
    }
}