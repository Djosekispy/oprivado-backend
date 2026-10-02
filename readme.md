## Arquivos editados

Foram ajustados estes arquivos:

- `GPOController.php`
- `ReferenceController.php`

### O que foi alterado
- Remoção das secrets hardcoded do Azure
- Substituição por leitura via variáveis de ambiente:
  - `APPYPAY_CLIENT_ID`
  - `APPYPAY_CLIENT_SECRET`
  - `APPYPAY_RESOURCE`

### Texto básico para descrever a mudança
"Removemos as credenciais sensíveis do Azure que estavam codificadas diretamente no código e passamos a utilizar variáveis de ambiente para armazenar e carregar essas informações com segurança."

## Tarefas a serem feitas

* Adicionar uma nova opção ao lado da funcionalidade de "Destacar" no perfil do usuário, chamada "Tornar-se Pro".
 
 ## Links de Acesso
  -  https://84.247.172.14:8443/
  - Username : admin
  - Passoword : @;Vicente1998