<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum StatusProposalEnum: string
{
    use EnumTrait;

    case ENVIADA = 'Enviada';
    case RECEBIDA = 'Recebida';
    case SEM_ADESAO = 'Sem Adesão do Município';
    case ANUIDA = 'Anuída';
    case NAO_ANUIDA = 'Não Anuída';
    case SELECIONADA = 'Selecionada';
    case NAO_SELECIONADA = 'Não Selecionada';
}
