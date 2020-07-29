<?php

namespace App\Http\Controllers;

use App\AntiFraudForm;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;

use URL;

use Carbon\Carbon;

class AntiFraudController extends Controller
{
    public function getCreate()
    {
      return view('antifraud.create');
    }

    public function getCreateUrl()
    {
      $inputs = request()->all();

      $form = AntiFraudForm::create(['type' => $inputs['type'], 'contact_id' => $inputs['contact'], 'token' => hash('md5', $inputs['contact'].microtime())]);

      return URL::to('/antifraud-form') . '/' . $form->token;
    }

    public function getForm($token)
    {
      $form = AntiFraudForm::where('token', $token)->first();

      if ($form->created_at < Carbon::now()->subMinutes(120)) {
        return view('antifraud.expired-form');
      }

      return view('antifraud.form')->with(compact('form'));
    }

    public function postEditAntifraud($id)
    {
      $inputs = request()->all();

      $form = AntiFraudForm::find($id);

      if ($form->email != '') {
        return Redirect::back()->with('error', 'This form has already been stored.');
      }

      if (isset($inputs['authorization-document'])) {
        $authorization_document_file = request()->file('authorization-document');
        $authorization_document_name = time().strtolower(str_replace(' ', '', $authorization_document_file->getClientOriginalName()));
        $authorization_document_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $authorization_document_name);

        $inputs['payment']['authorization_document'] = $authorization_document_name;

        $authorization_document_file->move(base_path().'/public/assets/authorization_documents/'.'/', $authorization_document_name);
      }

      //cash Deposit
      if ($form->type == 'GIFT_CARD' && isset($inputs['gift-card-photo'])) {
        $gift_card_file = request()->file('gift-card-photo');
        $gift_card_name = time().strtolower(str_replace(' ', '', $gift_card_file->getClientOriginalName()));
        $gift_card_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $gift_card_name);

        $inputs['payment']['gift_card_photo'] = $gift_card_name;

        $gift_card_file->move(base_path().'/public/assets/gift_card_photos/'.'/', $gift_card_name);

        $invoice_picture_file = request()->file('invoice-picture');
        $invoice_picture_name = time().strtolower(str_replace(' ', '', $invoice_picture_file->getClientOriginalName()));
        $invoice_picture_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $invoice_picture_name);

        $inputs['payment']['invoice_picture'] = $invoice_picture_name;

        $invoice_picture_file->move(base_path().'/public/assets/invoice_pictures/'.'/', $invoice_picture_name);
      }

      //cash Deposit
      if ($form->type == 'CASH_DEPOSIT' && isset($inputs['teller-business-card'])) {
        $teller_business_file = request()->file('teller-business-card');
        $teller_business_name = time().strtolower(str_replace(' ', '', $teller_business_file->getClientOriginalName()));
        $teller_business_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $teller_business_name);

        $inputs['payment']['teller_business_card'] = $teller_business_name;

        $teller_business_file->move(base_path().'/public/assets/teller_business_cards/'.'/', $teller_business_name);

        $bank_deposit_file = request()->file('bank-deposit-photo');
        $bank_deposit_name = time().strtolower(str_replace(' ', '', $bank_deposit_file->getClientOriginalName()));
        $bank_deposit_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $bank_deposit_name);

        $inputs['payment']['bank_deposit_photo'] = $bank_deposit_name;

        $bank_deposit_file->move(base_path().'/public/assets/bank_deposit_photos/'.'/', $bank_deposit_name);
      }

      if ($form->type == 'VARO_MONEY') {
        $varo_money_file = request()->file('varo-deposit-photo');
        $varo_money_name = time().strtolower(str_replace(' ', '', $varo_money_file->getClientOriginalName()));
        $varo_money_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $varo_money_name);

        $inputs['payment']['varo_money_photo'] = $varo_money_name;

        $varo_money_file->move(base_path().'/public/assets/varo_money_photos/'.'/', $varo_money_name);
      }

      //id_document image
      $id_document_file = request()->file('id-document');
      $id_document_name = time().strtolower(str_replace(' ', '', $id_document_file->getClientOriginalName()));
      $id_document_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $id_document_name);

      $id_document_file->move(base_path().'/public/assets/id_documents/'.'/', $id_document_name);

      //id_document_selfie image
      $id_selfie_file = request()->file('id-selfie');
      $id_selfie_name = time().strtolower(str_replace(' ', '', $id_selfie_file->getClientOriginalName()));
      $id_selfie_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $id_selfie_name);

      $id_selfie_file->move(base_path().'/public/assets/id_document_selfies/'.'/', $id_selfie_name);

      $new_data = ['fullname' => $inputs['full-name'], 'email' => $inputs['email'], 'location' => $inputs['location_string'], 'phone' => $inputs['phone-code'].$inputs['phone-number'], 'id_document' => $inputs['id-document'], 'id_document_selfie' => $inputs['id-selfie'], 'id_document' => $id_document_name, 'id_document_selfie' => $id_selfie_name];

      if ($form->type != 'SIMPLE_FORM') {
        $new_data['form_data'] = $inputs['payment'];
      }

      //updating form
      $form->update($new_data);

      return Redirect::back()->with('success', 'Your data has been send.');
    }

    public function allForms()
    {
      $forms = AntiFraudForm::where('email', '!=', '')->paginate(20);

      return view('antifraud.all-forms')->with(compact('forms'));
    }

    public function formView($id)
    {
      $form = AntiFraudForm::find($id);

      return view('antifraud.form-view')->with(compact('form'));
    }
}
