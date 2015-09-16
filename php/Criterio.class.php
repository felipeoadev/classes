<?php
/* Classe Criterio: essa classe provê uma interface para definição de critérios de seleção
 * Original: TCriteria.class.php do livro: PHP -  Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */
 
class Criterio extends Expressao
{
 private $expressoes;   //Armazena a lista de expressões
 private $operadores;   //Armazena a lista de operadores
 private $propriedades; //Propriedades do critério
 
 /* método add(): adiciona uma expressão ao critério
  * @param $expressao = expressão (objeto TExpression)
  * @param $operador = operador lógico de comparação
  */
 public function add(Expressao $expressao, $operador = self::AND_OPERATOR)
 {
  //Na primeira vez não precisamos de operador lógico para concatenar
  if(empty($this->expressoes))
  {
   unset($operador);
  }
    
  //Agrega o resultado da expressão à lista de expressões
  $this->expressoes[] = $expressao;
  $this->operadores[] = $operador;
 }
 
 /* método dump(): retorna a expressão final */
 public function dump()
 {
  //concatena a lista de expressões
  if(is_array($this->expressoes))
  {
   foreach($this->expressoes as $i=>$expressao)
   {
    $operador = $this->operadores[$i];
	//Concatena o operador com a respectiva expressão
	$resultado .= $operador.$expressao->dump().' ';
   }
   $resultado = trim($resultado);
   return "({$resultado})";
  }
 }
 
 /* método setPropridade(): define o valor de uma propriedade
  * @param $propriedade = propriedade
  * @param $valor = valor
  */
 public function setPropriedade($propriedade, $valor)
 {
  $this->propriedades[$propriedade] = $valor;
 }
 
 /* método setPropridade(): retoena o valor de uma propriedade
  * @param $propriedade = propriedade
  */
 public function getPropriedade($propriedade)
 {
  return $this->propriedades[$propriedade];
 }
} 
?>