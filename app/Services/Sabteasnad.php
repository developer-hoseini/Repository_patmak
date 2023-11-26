<?php

namespace App\Services;

use App\Repositories\Interfaces\LogLegalPersonRepositoryInterface;
use App\Repositories\Interfaces\LogSabteAhvalRepositoryInterface;
use RicorocksDigitalAgency\Soap\Facades\Soap;
use SoapClient;

/**
 * Class Ssaa
 * 
 * Description: 
 * This class is made to implement functions of Sazman-e sabt-e asnad va amlak (ssaa.ir)
 * 
 * 
 * @author Poustini <mmp1368@gmail.com>
 */
class Sabteasnad 
{

    protected $base_url;
    /**
     * TUIX Credentials
     */
    protected $tuix_username;
    protected $tuix_password;

    protected $rest;

    protected $LogLegalPersonRepository;

    protected $has_error = false;
        
    protected $message = 'OK'; // message 

    // --------------------------------------------------------------------

    public function __construct(LogLegalPersonRepositoryInterface $LogLegalPersonRepository)
    {

        $this->LogLegalPersonRepository = $LogLegalPersonRepository;

        $this->base_url = config('services.sabteasnad.base_url');
        $this->tuix_username = config('services.tuix.username');
        $this->tuix_password = config('services.tuix.password');

        $config = [
            'server' => $this->base_url,
            'http_auth' => 'basic',
            'http_user' => $this->tuix_username,
            'http_pass' => $this->tuix_password
        ];

        $this->rest = new Rest($config);

        
    }

    // --------------------------------------------------------------------

    /**
     * In this function we user REST to call a SOAP web service :)
     *
     * @param [type] $org_natioanl_code
     * @return void
     */
    public function inquirySpecialByNationalCode($org_natioanl_code)
    {
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:ilen="http://schemas.datacontract.org/2004/07/ilenc.NationalCodeWCF.LegalPersonService">
                <soapenv:Header/>
                    <soapenv:Body>
                        <tem:InquirySpecialByNationalCode>
                            <!--Optional:-->
                            <tem:param>
                                <!--Optional:-->
                                <ilen:Date>?</ilen:Date>
                                <!--Optional:-->
                                <ilen:Name>?</ilen:Name>
                                <!--Optional:-->
                                <ilen:NationalCode>'. $org_natioanl_code . '</ilen:NationalCode>
                                <!--Optional:-->
                                <ilen:PassWord></ilen:PassWord>
                                <!--Optional:-->
                                <ilen:UserName></ilen:UserName>
                            </tem:param>
                        </tem:InquirySpecialByNationalCode>
                    </soapenv:Body>
                </soapenv:Envelope>';

        $this->rest->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->rest->option(CURLOPT_SSL_VERIFYHOST, 0); // zero to 2
        // $this->rest->option(CURLOPT_PROXY, 'socks5://localhost:4567');
        $this->rest->header('SOAPAction: http://tempuri.org/ILegalPersonService/InquirySpecialByNationalCode');
        $this->rest->format('text/xml');  
        $res = $this->rest->post('/services/GSB_SabteAsnad_Legal.GSB_SabteAsnad_LegalHttpSoap11Endpoint', $xml);  
        // $this->rest->debug();
        $res = $this->_parse_rest_response($res);
        $status = ! ($this->has_error);
        return ['status' => $status, 'message' => $this->message, 'data' => $res];
    }

    // --------------------------------------------------------------------


    protected function _parse_rest_response($xml){
        $xmlobj = @new \SimpleXMLElement(preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", preg_replace("/(.*?)(<soap:Envelope.*<\/soap:Envelope>)(.*)/s", "$2", $xml)));
        $result = json_decode(json_encode((array) $xmlobj->xpath('//sBody')[0]), TRUE);

        try{

            $data = $result['InquirySpecialByNationalCodeResponse']['InquirySpecialByNationalCodeResult'];
            $this->_checkData($data);            
            return $data;

        } catch (\Exception $e)  {

            //dd($e);
            $data = [];
            $this->_checkData($data);
            return $data;
        }
    }

    // --------------------------------------------------------------------

    protected function _checkData($data)
    {
        if(isset($data['aMessage'])) {
            // response received
            if(is_string($data['aMessage']) && strlen($data['aMessage'])) {
                // response has error message
                $this->has_error = true;
                $this->message = $data['aMessage'];
            } else {
                // no error 
                $this->_map($data);
            }

        } else {
            // error in response
            $this->has_error = true;
            $this->message = 'پیام مناسب از ثبت اسناد دریافت نشد';
        }
    }

    // --------------------------------------------------------------------


    protected function _map($data){
        $this->LogLegalPersonRepository->create($data);
    }

    // --------------------------------------------------------------------
}