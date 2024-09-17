<?php

namespace App;

use App\Utils\HeaderMap;

class MapCustomer extends HeaderMap
{
  public function __construct()
  {
    parent::__construct(
      [
        'nome' => ['name', 'Name', 'Nome', 'Cliente', 'customer'],
        'email' => ['email', 'Email', 'E-mail', 'E-mail do cliente', 'email'],
        'cpf' => ['cpf', 'CPF', 'Cpf', 'Cpf do cliente', 'cpf'],
        'telefone' => ['telefone', 'Telefone', 'Telefone do cliente', 'phone'],
        'cep' => ['cep', 'CEP', 'Cep', 'Cep do cliente', 'cep'],
        'endereco' => ['endereco', 'Endereco', 'Endereco do cliente', 'address'],
        'bairro' => ['bairro', 'Bairro', 'Bairro do cliente', 'neighborhood'],
        'cidade' => ['cidade', 'Cidade', 'Cidade do cliente', 'city'],
        'estado' => ['estado', 'Estado', 'Estado do cliente', 'state'],
        'complemento' => ['complemento', 'Complemento', 'Complemento do cliente', 'complement'],
      ]
    );
  }

  public function getHeaders(): array
  {
    return $this->getMap();
  }
}