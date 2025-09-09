<?php

declare(strict_types=1);

namespace App\Doctrine\DQL\Functions\Postgresql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class JsonGetText extends FunctionNode
{
    public ?Node $jsonField = null;
    public ?Node $jsonKey = null;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->jsonField = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->jsonKey = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return $this->jsonField->dispatch($sqlWalker).'->>'.$this->jsonKey->dispatch($sqlWalker);
    }
}
