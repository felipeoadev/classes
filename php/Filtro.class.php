<?php
/* Classe Filtro: essa classe provê uma interface para definição de filtros de seleção
 * Original: TFilter.class.php do livro: PHP -  Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 * Alterado em: 18/08/2010 para nao colocar '' em operador NOT IN com sub_query
 * Alterado em: 16/12/2014 para aceitar REGEXP (consulta com expressões regulares)
 */
 
class Filtro extends Expressao
{
 private $variavel; //Variavel
 private $operador; //Operador
 private $valor;    //Valor
 
 /* método __construct(): instancia um novo filtro
  * @param $variavel = variavel
  * @param $operador = operador (<, >, NOT IN, =)
  * @param $valor = valor a ser comparado
  */
 public function __construct($variavel, $operador, $valor)
 {
  //Armazena as propriedades
  $this->variavel = $variavel;
  $this->operador = $operador;
  
  //Transforma o valor de acordo com certas regras ante de atribuir à propriedade
  if ($this->operador == 'NOT IN' || $this->operador == 'IN' || $this->operador == 'IS')
  {  
   $this->valor = $valor;
  }
  else
  {
   $this->valor = $this->transformar($valor);  
  }
 }
 
 /* método transformar(): recebe um valor e faz as modificações necessárias para ele ser interpretado pelo BD
  * podendo ser um integer/string/boolean ou array
  * @param $valor = valor a ser tranformado
  */
 public function transformar($valor)
 {
  //caso seja um array
  if (is_array($valor))
  {
   //percorre o array
   foreach ($valor as $x)
   {
	//se for um inteiro
	if (is_integer($x))
	{
	 $foo[] = $x;
	}
	else if (is_string($x))
	{
	 //se for string, adiciona aspas
	 $foo[] = "'$x'";
	}
   }
   //converte o array em uma string separada por ","
   $resultado = '('.implode(',', $foo).')';
  }
  //caso seja um string
  else if (is_string($valor))
  {
   //adiciona aspas
   $resultado = "'$valor'";
  }
  //caso seja valor nulo
  else if (is_null($valor))
  {
   //armazena NULL
   $resultado = 'NULL';
  }
  //caso seja booleano
  else if (is_bool($valor))
  {
   //armazena TRUE ou FALSE
   $resultado = $valor ? 'TRUE' : 'FALSE';
  }
  else
  {
   $resultado = $valor;
  }
  //retorna o valor
  return $resultado;
 }

 /* método dump(): retorna o filtro em forma de expressão
  * podendo ser um integer/string/boolean ou array
  */
 public function dump()
 {
  //concatena a expressão
  return "{$this->variavel} {$this->operador} {$this->valor}";
 }
} 
?>