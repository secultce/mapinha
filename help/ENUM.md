# Enumerations

Este documento descreve como criar e utilizar as Enums disponíveis no sistema, além de listar todas as Enums atualmente implementadas.

## Como criar?

Para criar uma Enum no sistema, siga o padrão abaixo. Todas as Enums devem:

Declarar o tipo de dado, como `int` ou `string`. Importante utilizar o EnumTrait para ter funcionalidades adicionais ao tipo Enum.

> Ao criar um Enum, é de suma importância usar o EnumTrait. 


Exemplo de Enum
```php
<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum AuthorizedByTypeEnum: int
{
    use EnumTrait;

    case AGENT = 1;
    case ORGANIZATION = 2;
}

```
> Neste exemplo, o AuthorizedByEnum define dois tipos de autorização possíveis no sistema: AGENT e ORGANIZATION.


## Lista de ENUMS disponíveis
As Enums disponíveis no sistema são:
- AuthorizedByEnum
- EntityEnum
- InscriptionOpportunityStatusEnum

### EntityEnum

#### Descrição
Enumera as entidades principais do sistema.

#### Valores

| Constante      | Valor | Descrição                    |
|----------------|-------|------------------------------|
| `AGENT`        | `1`   | Representa um agente.        |
| `ORGANIZATION` | `2`   | Representa uma organização.  |
| `SPACE`        | `3`   | Representa um espaço.        |
| `EVENT`        | `4`   | Representa um evento.        |
| `INITIATIVE`   | `5`   | Representa uma iniciativa.   |
| `OPPORTUNITY`  | `6`   | Representa uma oportunidade. |

### AuthorizedByEnum

#### Descrição
Define os tipos de entidades autorizadas no sistema.

#### Valores

| Constante      | Valor | Descrição                              |
|----------------|-------|----------------------------------------|
| `AGENT`        | `1`   | Representa um agente autorizado.       |
| `ORGANIZATION` | `2`   | Representa uma organização autorizada. |


### InscriptionOpportunityStatusEnum

#### Descrição
Enumera os possíveis estados de uma inscrição em uma oportunidade.

#### Valores

| Constante   | Valor | Descrição                          |
|-------------|-------|------------------------------------|
| `ACTIVE`    | `1`   | Representa uma inscrição ativa.    |
| `INACTIVE`  | `2`   | Representa uma inscrição inativa.  |
| `SUSPENDED` | `3`   | Representa uma inscrição suspensa. |


### RegionEnum

#### Descrição
Enumera as regiões geográficas do sistema.

#### Valores

| Constante      | Valor         | Descrição                     |
|----------------|---------------|-------------------------------|
| `NORTE`        | `Norte`       | Representa a região Norte.    |
| `NORDESTE`     | `Nordeste`    | Representa a região Nordeste. |
| `SUDESTE`      | `Sudeste`     | Representa a região Sudeste.  |
| `SUL`          | `Sul`         | Representa a região Sul.      |
| `CENTRO_OESTE` | `Centro-Oeste`| Representa a região Centro-Oeste. |


### OrganizationTypeEnum

#### Descrição
Enumera os tipos de organizações no sistema.

#### Valores

| Constante   | Valor       | Descrição                     |
|-------------|-------------|-------------------------------|
| `UNDEFINED` | `Undefined` | Tipo de organização indefinido. |
| `MUNICIPIO` | `Municipio` | Representa um município.      |
| `EMPRESA`   | `Empresa`   | Representa uma empresa.       |
| `ENTIDADE`  | `Entidade`  | Representa uma entidade.      |
| `OSC`       | `OSC`       | Representa uma organização da sociedade civil. |

---

### SocialNetworkEnum

#### Descrição
Enumera as redes sociais disponíveis no sistema.

#### Valores

| Constante   | Valor       | Descrição                     |
|-------------|-------------|-------------------------------|
| `FACEBOOK`  | `facebook`  | Rede social Facebook.         |
| `INSTAGRAM` | `instagram` | Rede social Instagram.        |
| `LINKEDIN`  | `linkedin`  | Rede social LinkedIn.         |
| `PINTEREST` | `pinterest` | Rede social Pinterest.        |
| `SPOTIFY`   | `spotify`   | Plataforma Spotify.           |
| `VIMEO`     | `vimeo`     | Plataforma Vimeo.             |
| `TIKTOK`    | `tiktok`    | Rede social TikTok.           |
| `X`         | `x`         | Rede social X (antigo Twitter). |
| `YOUTUBE`   | `youtube`   | Plataforma YouTube.           |

---

### UserRolesEnum

#### Descrição
Enumera os papéis de usuário no sistema.

#### Valores

| Constante           | Valor           | Descrição                     |
|---------------------|-----------------|-------------------------------|
| `ROLE_ADMIN`        | `ROLE_ADMIN`    | Representa um administrador.  |
| `ROLE_MANAGER`      | `ROLE_MANAGER`  | Representa um gerente.        |
| `ROLE_COMPANY`      | `ROLE_COMPANY`  | Representa uma empresa.       |
| `ROLE_MUNICIPALITY` | `ROLE_MUNICIPALITY` | Representa um município.  |
| `ROLE_USER`         | `ROLE_USER`     | Representa um usuário comum.  |

---

### AccessibilityInfoEnum

#### Descrição
Enumera as informações de acessibilidade.

#### Valores

| Constante       | Valor | Descrição                     |
|-----------------|-------|-------------------------------|
| `YES`           | `1`   | Acessibilidade disponível.    |
| `NO`            | `2`   | Acessibilidade indisponível.  |
| `NOT_INFORMED`  | `3`   | Acessibilidade não informada. |

---

### AccountEventTypeEnum

#### Descrição
Enumera os tipos de eventos relacionados à conta.

#### Valores

| Constante    | Valor | Descrição                     |
|--------------|-------|-------------------------------|
| `ACTIVATION` | `1`   | Evento de ativação de conta.  |
| `RECOVERY`   | `2`   | Evento de recuperação de conta. |

---

### CompanyFrameworkEnum

#### Descrição
Enumera os tipos de estrutura de empresas.

#### Valores

| Constante   | Valor                              | Descrição                     |
|-------------|------------------------------------|-------------------------------|
| `PROFIT`    | `Organização com fins lucarativos` | Empresa com fins lucrativos.  |
| `NO_PROFIT` | `Organização sem fins lucarativos` | Empresa sem fins lucrativos.  |

---

### EventFormatEnum

#### Descrição
Enumera os tipos de eventos.

#### Valores

| Constante   | Valor | Descrição                     |
|-------------|-------|-------------------------------|
| `IN_PERSON` | `1`   | Evento presencial.            |
| `ONLINE`    | `2`   | Evento online.                |
| `HYBRID`    | `3`   | Evento híbrido.               |

---

### FlashMessageTypeEnum

#### Descrição
Enumera os tipos de mensagens de *flash*.

#### Valores

| Constante   | Valor     | Descrição                     |
|-------------|-----------|-------------------------------|
| `SUCCESS`   | `success` | Mensagem de sucesso.          |
| `ERROR`     | `error`   | Mensagem de erro.             |

---

### UserStatusEnum

#### Descrição
Enumera os status de usuário no sistema.

#### Valores

| Constante              | Valor               | Descrição                     |
|------------------------|---------------------|-------------------------------|
| `AWAITING_CONFIRMATION`| `AwaitingConfirmation` | Usuário aguardando confirmação. |
| `ACTIVE`               | `Active`           | Usuário ativo.                |
| `BLOCKED`              | `Blocked`          | Usuário bloqueado.            |

## Boas práticas

### Use os valores das Enums diretamente
Evite usar valores literais (como `1`, `2`, etc.) diretamente no código. Sempre utilize as constantes da *Enum* para garantir clareza e manutenibilidade.

**Exemplo:**

```php
// Forma errada
if ($type === 1) { /* lógica */ }

// Forma correta
if ($type === AuthorizedByTypeEnum::AGENT->value) { /* lógica */ }
```

### Valide valores desconhecidos
Certifique-se de tratar casos em que o valor fornecido não corresponde a nenhum dos valores definidos na Enum.

### Centralize as Enums
Todas as Enums devem ser armazenadas no namespace App\Enum e seguir o mesmo padrão de nomenclatura.

### Atualize sempre a documentação
Sempre que um novo valor for adicionado ou modificado, atualize este documento para refletir as mudanças.