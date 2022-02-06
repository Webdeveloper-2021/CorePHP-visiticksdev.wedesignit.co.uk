<?php
namespace includes\classes\models;

use includes\classes\API;

class Customer extends BaseModel
{
    public $id;
    public $memberships;
    public $membershipCount;

    public function __construct()
    {
        parent::__construct();

        $this->id = $_SESSION['customer_id'] ?? null;
        $this->memberships = $_SESSION['customer']->membershipSubscriptions ?? [];
        $this->membershipCount = count($this->memberships) ?? 0;
    }

    public function getMemberships() {
        $requestresult = API::get("membershipsubscriptions/customer/{$this->id}?include=Membership,Members,Address,Members.Contact,Membership.Items");

        if ($requestresult['ok'])
        {
            $this->memberships = $requestresult['content'];

            return true;
        }

        return false;
    }

    static public function register($data) {
        $requestresult = API::post("customers", $data);

        if (!$requestresult['ok'])
        {
            return [
                'status' => false,
                'errorNumber' => $requestresult['content'][0]->errorNumber
            ];
        }

        return [
            'status' => true,
            'content' => $requestresult['content']
        ];
    }

    static public function loginLocal($customer) {
        $_SESSION["customer_id"] = $customer->id;
        $_SESSION["customer_email"] = $customer->customerContacts->contact->email;
        $_SESSION["customer"] = $customer;
    }
}