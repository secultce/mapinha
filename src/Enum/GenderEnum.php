<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum GenderEnum: string
{
    use EnumTrait;

    case MASCULINO = 'Masculino';
    case FEMININO = 'Feminino';
    case TRANS_MASCULINO = 'Trans Masculino';
    case TRANS_FEMININO = 'Trans Feminino';
    case NAO_BINARIO = 'Não Binário';
    case GENERO_FLUIDO = 'Gênero Fluido';
    case AGENERO = 'Agênero';
    case BIGENERO = 'Bigênero';
    case INTERSEXO = 'Intersexo';
    case PANGENERO = 'Pangênero';
    case TWO_SPIRIT = 'Two-Spirit'; // Termo de comunidades indígenas norte-americanas
    case OUTRO = 'Outro';
    case PREFERE_NAO_INFORMAR = 'Prefere não informar';
}
