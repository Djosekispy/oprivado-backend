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

 ## Limite de vídeos para contas não PRO

 Configure o limite e o período de reposição com estas variáveis de ambiente:

 - `ENABLE_POST_VIDEO_NON_PRO_LIMIT`: habilita (`true`) ou desabilita (`false`, padrão) a aplicação do limite a contas não PRO.
 - `POST_VIDEO_NON_PRO_LIMIT`: quantidade máxima de vídeos de publicações que uma conta não PRO pode iniciar (padrão: `10`).
 - `POST_VIDEO_LIMIT_RESET_PERIOD`: período de reposição: `never` (padrão, limite total), `daily` ou `monthly`.