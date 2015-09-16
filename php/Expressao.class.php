<?php
/* Classe Expressao: classe abstrata para gerenciar expressoes
 * Original: TExpression.class.php do livro: Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */

abstract class Expressao
{
 //Operadores Logicos
 const AND_OPERATOR = "AND ";
 const OR_OPERATOR = "OR ";
 
 //Marca método dump como obrigatório
 abstract public function dump();
} 
?>