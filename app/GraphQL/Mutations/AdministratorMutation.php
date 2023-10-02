<?php

namespace App\GraphQL\Mutations;

use Auth;
use App\Models\Administrator;


use Str;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;
use Log;
use Hash;
use Config;
use Crypt;

class AdministratorMutation extends Mutation
{

   protected $attributes = [
      'name' => 'AdministratorMutation'
   ];


   public function args(): array
   {
      return [
         'admin' => ['type' => GraphQL::type('administrator_input')],
         'file' => ['type' => GraphQL::type('Upload')]
      ];
   }

   public function type(): Type
   {
      return GraphQL::type('response_type');
   }


   public function validationErrorMessages(array $args = []): array
   {
      return [
         'admin.firstname.required' => 'Please enter your firstname',
         'admin.lastname.required' => 'Please enter your lastname',
         'admin.mobile.required' => 'Please enter your mobile no',
         'admin.administrator_id.required' => 'Please enter administrator id',
         'admin.status.required' => 'Please enter your status',
         'admin.email.required' => 'Please enter your email',
         'admin.email.email' => 'Please enter your valid email address',
         'admin.email.unique' => 'Email address already in used',
         'admin.password.required' => 'Please enter your password',
         'admin.password.min' => 'Password must be at least 8 characters in length',
         'admin.password.regex' => 'Password must be at least one lowercase, uppercase, digit and character',
         'admin.confirm_password.required' => 'Please enter your verify password',
         'admin.confirm_password.same' => 'Password and verify password must be same',
         'admin.contact_no.required' => 'Please enter your contact no',
         'admin.activation_code.required' => 'Please enter your activation code',
      ];
   }

   public function rules(array $args = []): array
   {
      $rules = [];

      $admin = $args['admin'];


      if ($admin['action_type'] == "login") {
         $rules['admin.email'] = ['required', 'email'];
         $rules['admin.password'] = ['required'];
      }
      else if ($admin['action_type'] == "forgot_password") {
         $rules['admin.email'] = ['required', 'email'];
      }
      else if ($admin['action_type'] == "forgot_password_security_token") {
         $rules['admin.email'] = ['required', 'email'];
         $rules['admin.security_token'] = ['required'];
      }
      else if ($admin['action_type'] == "reset_password") {
         $rules['admin.email'] = ['required', 'email'];
         $rules['admin.password'] = ['required', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&_]/'];
         $rules['admin.confirm_password'] = ['required', 'same:admin.password'];
       }
      else if ($admin['action_type'] == "new_record") {
         $rules['admin.firstname'] = ['required'];
         $rules['admin.lastname'] = ['required'];
         $rules['admin.mobile'] = ['required'];
         $rules['admin.email'] = ['required', 'email', 'unique:tblAdministrator,fldAdministratorEmail'];
         $rules['admin.password'] = ['required'];
      }
      else if ($admin['action_type'] == "update_record") {
         $admin = $args['admin'];
         $admin_id = Crypt::decryptString($admin["administrator_id"]);

         $rules['admin.firstname'] = ['required'];
         $rules['admin.lastname'] = ['required'];
         $rules['admin.mobile'] = ['required'];
         $rules['admin.email'] = ['required', 'email', 'unique:tblAdministrator,fldAdministratorEmail,' . $admin_id . ',fldAdministratorID'];
         $rules['admin.password'] = ['required'];
      }
      else if ($admin["action_type"] == "change_status") {
         $rules['admin.administrator_id'] = ['required'];
         $rules['admin.status'] = ['required'];
      }



      return $rules;
   }


   public function resolve($root, $args)
   {
      $admin = $args['admin'];
      $response_obj = new \stdClass();

      $admin_model = new Administrator();

      if ($admin['action_type'] == "login") {
         $response_obj = $admin_model->checkAccess($admin);
      }

      /* if ($admin['action_type'] == "forgot_password") {
         $response_obj = $admin_model->forgotPassword($admin['email']);
      }

      if ($admin['action_type'] == "forgot_password_check_otp") {
         $response_obj = $admin_model->forgotPasswordCheckOTP($admin);
      }

      if ($admin['action_type'] == "reset_password") {
         $response_obj = $admin_model->resetPassword($admin);
      }

      if ($admin["action_type"] == "new_record") {
         $response_obj = $admin_model->addUpdateRecord(0, $admin, $args['file']);
      }

      if ($admin["action_type"] == "update_record") {
         $admin_id = Crypt::decryptString($admin['administrator_id']);

         $administrator = Administrator::find($admin_id);
         if ($administrator) {
            $response_obj = $admin_model->addUpdateRecord($admin_id, $admin, $args['file']);
         } else {
            $response_obj = new \stdClass();
            $response_obj->error = true;
            $response_obj->message = Config::get('Constants.ERROR_MESSAGE')['RECORD_NOT_FOUND'];
         }
      }

      if ($admin["action_type"] == "change_status") {
         $response_obj = $admin_model->changeStatus($admin);
      }

      if ($admin["action_type"] == "verify_document") {
         $customer_document_model = new CustomerDocument();
         $response_o1bj = $customer_document_model->verifyDocument($admin);
      }

      if ($admin['action_type'] == "validate_security_link") {
         $response_obj = $admin_model->validateAdminToken($admin['security']);
      } */



      return $response_obj;
   }
}
