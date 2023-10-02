<?php

namespace App\GraphQL\Inputs;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class AdministratorInput extends InputType
{


   protected $attributes = [
      'name' => 'AdministratorInput',
      'description' => 'Administrator information'
   ];

   public function fields(): array
   {
      return [
         'firstname' => [
            'type' => Type::string(),
         ],
         'lastname' => [
            'type' => Type::string(),
         ],
         'email' => [
            'type' => Type::string(),
         ],
         'mobile' => [
            'type' => Type::string(),
         ],
         'password' => [
            'type' => Type::string(),
         ],
         'action_type' => [
            'type' => Type::string(),
         ],
         'status' => [
            'type' => Type::string(),
         ],
         'security_token' => [
            'type' => Type::string(),
         ],
         'administrator_id' => [
            'type' => Type::string(),
         ],
         'status' => [
            'type' => Type::int(),
         ],
         'otp' => [
            'type' => Type::string(),
         ],
         'confirm_password' => [
            'type' => Type::string(),
         ],
         'document_id' => [
            'type' => Type::string(),
         ],
         'security' => [
            'type' => Type::string(),
         ]
      ];
   }
}
