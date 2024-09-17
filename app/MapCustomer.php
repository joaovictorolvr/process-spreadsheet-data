<?php

namespace App;

use App\Utils\HeaderMap;

class MapCustomer extends HeaderMap
{
  public function __construct()
  {
    parent::__construct(
      [
        'nome' => ['name', 'Name', 'Nome', 'Cliente', 'cliente', 'customer'],
        'email' => ['email', 'Email', 'E-mail', 'E-mail do cliente', 'email'],
        'cpf' => ['cpf', 'CPF', 'Cpf', 'Cpf do cliente', 'cpf', "CPF/CNPJ"],
        'telefone' => ['telefone', 'Telefone', 'Telefone do cliente', 'phone'],
        'cep' => ['cep', 'CEP', 'Cep', 'Cep do cliente', 'cep'],
        'endereco' => ['endereco', 'Endereco', 'Endereco do cliente', 'address'],
        'numero' => ['numero', 'Número', 'Número do cliente', 'number', 'Number'],
        'data_de_pagamento' => ['data_de_pagamento', 'Data de pagamento', 'Data de pagamento do cliente', 'payment_date', 'Status'],
        'bairro' => ['bairro', 'Bairro', 'Bairro do cliente', 'neighborhood'],
        'cidade' => ['cidade', 'Cidade', 'Cidade do cliente', 'city'],
        'estado' => ['estado', 'Estado', 'Estado do cliente', 'state'],
        'complemento' => ['complemento', 'Complemento', 'Complemento do cliente', 'complement'],
        'rastreio' => ['Código de rastreio', 'Código de rastreio do cliente', 'tracking_code', 'rastreio', 'Código de Rastreio'],
        'valor' => ['valor', 'Valor', 'Valor do cliente', 'value'],
        'produtos' => ['products', 'Produtos', 'products', 'Produtos do cliente', 'products'],
      ]
    );
  }

}