<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

   'accepted'             => 'O :attribute deve ser aceite.',
'active_url'           => 'O :attribute não é uma URL válida.',
'after'                => 'O :attribute deve ser uma data posterior a :date.',
'after_or_equal'       => 'O :attribute deve ser uma data posterior ou igual a :date.',
'alpha'                => 'O :attribute pode conter apenas letras.',
'alpha_dash'           => 'O :attribute pode conter apenas letras, números e traços.',
"ascii_only"           => "O :attribute pode conter apenas letras, números e traços.",
'alpha_num'            => 'O :attribute pode conter apenas letras e números.',
'array'                => 'O :attribute deve ser um array.',
'before'               => 'O :attribute deve ser uma data anterior a :date.',
'before_or_equal'      => 'O :attribute deve ser uma data anterior ou igual a :date.',
'between'              => [
    'numeric' => 'O :attribute deve estar entre :min e :max.',
    'file'    => 'O :attribute deve estar entre :min e :max kilobytes.',
    'string'  => 'O :attribute deve ter entre :min e :max caracteres.',
    'array'   => 'O :attribute deve ter entre :min e :max itens.',
],
'boolean'              => 'O campo :attribute deve ser verdadeiro ou falso.',
'confirmed'            => 'A confirmação do :attribute não corresponde.',
'date'                 => 'O :attribute não é uma data válida.',
'date_format'          => 'O :attribute não corresponde ao formato :format.',
'different'            => 'O :attribute e o :other devem ser diferentes.',
'digits'               => 'O :attribute deve ter :digits dígitos.',
'digits_between'       => 'O :attribute deve ter entre :min e :max dígitos.',
'dimensions'           => 'O :attribute tem dimensões de imagem inválidas (:min_width x :min_height px).',
'distinct'             => 'O campo :attribute tem um valor duplicado.',
'email'                => 'O :attribute deve ser um endereço de e-mail válido.',
'exists'               => 'O :attribute selecionado é inválido.',
'file'                 => 'O :attribute deve ser um arquivo.',
'filled'               => 'O campo :attribute deve ter um valor.',
'gt'                   => [
    'numeric' => 'O :attribute deve ser maior que :value.',
    'file'    => 'O :attribute deve ser maior que :value kilobytes.',
    'string'  => 'O :attribute deve ser maior que :value caracteres.',
    'array'   => 'O :attribute deve ter mais de :value itens.',
],
'gte'                  => [
    'numeric' => 'O :attribute deve ser maior ou igual a :value.',
    'file'    => 'O :attribute deve ser maior ou igual a :value kilobytes.',
    'string'  => 'O :attribute deve ser maior ou igual a :value caracteres.',
    'array'   => 'O :attribute deve ter :value itens ou mais.',
],
'image'                => 'O :attribute deve ser uma imagem.',
'in'                   => 'O :attribute selecionado é inválido.',
'in_array'             => 'O campo :attribute não existe em :other.',
'integer'              => 'O :attribute deve ser um número inteiro.',
'ip'                   => 'O :attribute deve ser um endereço IP válido.',
'ipv4'                 => 'O :attribute deve ser um endereço IPv4 válido.',
'ipv6'                 => 'O :attribute deve ser um endereço IPv6 válido.',
'json'                 => 'O :attribute deve ser uma string JSON válida.',
'lt'                   => [
    'numeric' => 'O :attribute deve ser menor que :value.',
    'file'    => 'O :attribute deve ser menor que :value kilobytes.',
    'string'  => 'O :attribute deve ser menor que :value caracteres.',
    'array'   => 'O :attribute deve ter menos de :value itens.',
],
'lte'                  => [
    'numeric' => 'O :attribute deve ser menor ou igual a :value.',
    'file'    => 'O :attribute deve ser menor ou igual a :value kilobytes.',
    'string'  => 'O :attribute deve ser menor ou igual a :value caracteres.',
    'array'   => 'O :attribute não deve ter mais de :value itens.',
],
'max'                  => [
    'numeric' => 'O :attribute não pode ser maior que :max.',
    'file'    => 'O :attribute não pode ser maior que :max kilobytes.',
    'string'  => 'O :attribute não pode ser maior que :max caracteres.',
    'array'   => 'O :attribute não pode ter mais de :max itens.',
],
'mimes'                => 'O :attribute deve ser um arquivo do tipo: :values.',
'mimetypes'            => 'O :attribute deve ser um arquivo do tipo: :values.',
'min'                  => [
    'numeric' => 'O :attribute deve ser pelo menos :min.',
    'file'    => 'O :attribute deve ser pelo menos :min kilobytes.',
    'string'  => 'O :attribute deve ter pelo menos :min caracteres.',
    'array'   => 'O :attribute deve ter pelo menos :min itens.',
],
'not_in'               => 'O :attribute selecionado é inválido.',
'not_regex'            => 'O formato do :attribute é inválido.',
'numeric'              => 'O :attribute deve ser um número.',
'present'              => 'O campo :attribute deve estar presente.',
'regex'                => 'O formato do :attribute é inválido.',
'required'             => 'O campo :attribute é obrigatório.',
'required_if'          => 'O campo :attribute é obrigatório quando :other for :value.',
'required_unless'      => 'O campo :attribute é obrigatório a menos que :other esteja em :values.',
'required_with'        => 'O campo :attribute é obrigatório quando :values estiver presente.',
'required_with_all'    => 'O campo :attribute é obrigatório quando :values estiver presente.',
'required_without'     => 'O campo :attribute é obrigatório quando :values não estiver presente.',
'required_without_all' => 'O campo :attribute é obrigatório quando nenhum de :values estiver presente.',
'same'                 => 'O :attribute e o :other devem coincidir.',
'size'                 => [
    'numeric' => 'O :attribute deve ser :size.',
    'file'    => 'O :attribute deve ser :size kilobytes.',
    'string'  => 'O :attribute deve ter :size caracteres.',
    'array'   => 'O :attribute deve conter :size itens.',
],
'string'               => 'O :attribute deve ser uma string.',
'timezone'             => 'O :attribute deve ser uma zona válida.',
'unique'               => 'O :attribute já foi registado.',
'uploaded'             => 'O :attribute falhou no carregamento.',
'url'                  => 'O formato do :attribute é inválido.',
"account_not_confirmed" => "A sua conta não está confirmada, por favor verifique o seu e-mail.",
"user_suspended"        => "A sua conta foi suspensa por irregularidades dentro do Oprivado, caso queira contestar, por favor contacte-nos.",
"letters"              => "O :attribute deve conter pelo menos uma letra ou número.",
'video_url'            => 'URL inválida, só suporta YouTube e Vimeo.',
'update_max_length'    => 'A publicação não pode ter mais de :max caracteres.',
'update_min_length'    => 'A publicação deve ter pelo menos :min caracteres.',
'video_url_required'   => 'O campo URL do vídeo é obrigatório quando o Conteúdo em Destaque for Vídeo.',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
    'agree_gdpr' => 'Eu concordo com o tratamento dos dados pessoais',
    'agree_terms' => 'Eu concordo com os Termos e Condições',
    'agree_terms_privacy' => 'Eu concordo com os Termos e Condições e a Política de Privacidade',
    'full_name' => 'Nome Completo',
    'name' => 'Nome',
    'username' => 'Nome de Utilizador',
    'username_email' => 'Nome de Utilizador ou Email',
    'email' => 'Email',
    'password' => 'Palavra-passe',
    'password_confirmation' => 'Confirmação de Palavra-passe',
    'website' => 'Website',
    'location' => 'Localização',
    'countries_id' => 'País',
    'twitter' => 'Twitter',
    'facebook' => 'Facebook',
    'google' => 'Google',
    'instagram' => 'Instagram',
    'comment' => 'Comentário',
    'title' => 'Título',
    'description' => 'Descrição',
    'old_password' => 'Palavra-passe Antiga',
    'new_password' => 'Nova Palavra-passe',
    'email_paypal' => 'Email PayPal',
    'email_paypal_confirmation' => 'Confirmação de Email PayPal',
    'bank_details' => 'Detalhes Bancários',
    'video_url' => 'URL do Vídeo',
    'categories_id' => 'Categoria',
    'story' => 'História',
    'image' => 'Imagem',
    'avatar' => 'Avatar',
    'message' => 'Mensagem',
    'profession' => 'Profissão',
    'thumbnail' => 'Miniatura',
    'address' => 'Endereço',
    'city' => 'Cidade',
    'zip' => 'Código Postal',
    'payment_gateway' => 'Gateway de Pagamento',
    'payment_gateway_tip' => 'Dica de Gateway de Pagamento',
    'MAIL_FROM_ADDRESS' => 'Email de não resposta',
    'FILESYSTEM_DRIVER' => 'Disco',
    'price' => 'Preço',
    'amount' => 'Montante',
    'birthdate' => 'Data de Nascimento',
    'navbar_background_color' => 'Cor de fundo da barra de navegação',
    'navbar_text_color' => 'Cor do texto da barra de navegação',
    'footer_background_color' => 'Cor de fundo do rodapé',
    'footer_text_color' => 'Cor do texto do rodapé',

      'AWS_ACCESS_KEY_ID' => 'Amazon Key', // Not necessary edit
      'AWS_SECRET_ACCESS_KEY' => 'Amazon Secret', // Not necessary edit
      'AWS_DEFAULT_REGION' => 'Amazon Region', // Not necessary edit
      'AWS_BUCKET' => 'Amazon Bucket', // Not necessary edit

      'DOS_ACCESS_KEY_ID' => 'DigitalOcean Key', // Not necessary edit
      'DOS_SECRET_ACCESS_KEY' => 'DigitalOcean Secret', // Not necessary edit
      'DOS_DEFAULT_REGION' => 'DigitalOcean Region', // Not necessary edit
      'DOS_BUCKET' => 'DigitalOcean Bucket', // Not necessary edit

      'WAS_ACCESS_KEY_ID' => 'Wasabi Key', // Not necessary edit
      'WAS_SECRET_ACCESS_KEY' => 'Wasabi Secret', // Not necessary edit
      'WAS_DEFAULT_REGION' => 'Wasabi Region', // Not necessary edit
      'WAS_BUCKET' => 'Wasabi Bucket', // Not necessary edit

      //===== v2.0
      'BACKBLAZE_ACCOUNT_ID' => 'Backblaze Account ID', // Not necessary edit
      'BACKBLAZE_APP_KEY' => 'Backblaze Master Application Key', // Not necessary edit
      'BACKBLAZE_BUCKET' => 'Backblaze Bucket Name', // Not necessary edit
      'BACKBLAZE_BUCKET_REGION' => 'Backblaze Bucket Region', // Not necessary edit
      'BACKBLAZE_BUCKET_ID' => 'Backblaze Bucket Endpoint', // Not necessary edit

      'VULTR_ACCESS_KEY' => 'Vultr Key', // Not necessary edit
      'VULTR_SECRET_KEY' => 'Vultr Secret', // Not necessary edit
      'VULTR_REGION' => 'Vultr Region', // Not necessary edit
      'VULTR_BUCKET' => 'Vultr Bucket', // Not necessary edit
  	],

];
