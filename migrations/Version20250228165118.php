<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250228165118 extends AbstractMigration
{
    private const array STATE_TO_CAPITAL = [
        '3d31e092-0316-4204-8627-4470dafa7e40' => '99ce2c19-78e4-4e25-bc33-4b69cc7603bd',
        '68f5c8ec-7fd7-45b6-aa72-5d931b0297b3' => 'eb897dde-1a17-4b3e-9a5e-6884658fb2c9',
        '4ee55191-ed02-42df-b5c2-975998c8062f' => '63474fcf-1af1-406b-90a4-67f7e1ca7d05',
        '180d4cff-4e9a-4f09-b8d7-8638a2851650' => '4b431a69-cc89-4bd1-968f-871a6d745805',
        'a029aa1d-ea17-4bf1-95e9-45570a3d44c3' => 'ae05a7ef-b4c8-448d-b8ba-414cf9b38ecd',
        '5ae1c5ab-bdc9-4659-9873-cb737df23a0a' => '97847c18-ac1c-4a00-93d4-b4a3e72a262c',
        'aae47bf7-ca4b-4da5-9a0a-9bc8e3269233' => 'f6005001-9abf-4295-8ab5-572d54ec1ba0',
        'd1c14c45-a238-4635-af6b-0a4bd414f352' => '9b17b9ba-f651-44ee-93b6-ee1329d1c47a',
        'deb85ac9-0afb-4070-9acc-46352e00978f' => 'c1f0d7a0-64aa-486e-a3f5-4b29e0d01ef7',
        '162d6a8c-255d-490c-9c6c-a46a554f1622' => '60e37453-19a2-4ff8-bed7-f08e28d14f78',
        'f42bac42-da0f-49fe-a192-8d43a1e03ab3' => '6cb20b67-55ca-4182-90a5-3ff6c1746903',
        'eb514029-f7b3-45c2-baf7-a7e6a1c63b74' => '01dba92f-bec4-427a-8e8d-b27e7bbf0ae6',
        '23f83a12-62a7-4b33-8a2d-4b6af9ac4160' => '9a8a592e-fb12-4fce-8575-a5ddd3f3422c',
        'f18a9806-7475-4fec-923b-e301f383ccfd' => 'a42ee2b4-0c85-4a1b-bad5-2d0499988d88',
        'affb0c11-1ba2-49ce-8c3c-aa4453fe82fd' => 'f637df65-c335-49b5-b10d-6d9897b1332c',
        '3f5242e9-7124-42ae-b3bf-d60cd091d4b1' => 'c52035af-d85e-4fec-aa5d-8798476782d7',
        '92a1bb4d-6821-4860-bd58-1fd0359febf0' => 'a97c7beb-9476-4347-ad2a-b60aaa58abd5',
        'ec3fa074-502b-48ca-a7fd-796204253490' => '97012c8a-56e8-4437-b1e2-11e3872edecb',
        '376143fc-fd69-4fbb-b301-18f50ee5ec6a' => '75f95b37-00f1-4a75-bac5-8eb15a46c6f3',
        'a8f90f37-0ff4-443d-96c0-80435947d0f5' => '49b708cc-e7a4-474a-a814-1309880fe672',
        '3c6c5ba0-bb92-415e-85d5-4bac08d7b860' => 'fb22ee01-1806-481f-af83-7deb980c89c3',
        '6402d6c9-09f7-49a7-83b5-360a7314d5bf' => '68587afc-5a71-4577-ab29-b5feb81e9dd8',
        '010ccde8-6b06-4068-b379-e937fce008bc' => 'bd7e287f-911a-489e-a9a8-2a62b708eba8',
        'e596c701-dc76-4a69-8f7b-28704aa8931f' => '08eaa026-0129-4190-a3bb-d3917dd31bd2',
        'ee6cb1de-ccbd-4779-80bd-1bbe06c5a79d' => '914b3de2-fed5-410d-bba8-837b9f13e422',
        'caffbdf6-0d55-4453-aaa0-3332c4e85fd7' => 'c4c0828d-968e-42c6-91cf-bc74cabb6bc0',
        '1ecf2981-70e0-43df-acb6-1af96eb27e72' => 'bdcfbcd3-b596-4d80-a526-8aaa25571471',
    ];

    public function getDescription(): string
    {
        return 'Atualiza a coluna capital_id na tabela state com os IDs das capitais correspondentes';
    }

    public function up(Schema $schema): void
    {
        foreach (self::STATE_TO_CAPITAL as $stateId => $capitalId) {
            $this->addSql(sprintf(
                "UPDATE state SET capital_id = '%s' WHERE id = '%s'",
                $capitalId,
                $stateId
            ));
        }
    }

    public function down(Schema $schema): void
    {
        foreach (self::STATE_TO_CAPITAL as $stateId => $capitalId) {
            $this->addSql(sprintf(
                "UPDATE state SET capital_id = NULL WHERE id = '%s'",
                $stateId
            ));
        }
    }
}
