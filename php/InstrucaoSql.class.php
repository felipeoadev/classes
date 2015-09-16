<?php
/* Classe InstrucaoSql: essa classe provê os métodos em comum entre todas as instruções SQL (INSERT, SELECT, DELETE e UPDATE)
 * Original: TSqlInstruction.class.php do livro: PHP -  Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */
 
abstract class InstrucaoSql
{
 protected $sql;       //armazena a instrução SQL
 protected $criterio;  //armazena o objeto critério
 
 /* Método setEntidade(): define o nome da entidade (tabela) manipulada pela instrução SQL
  * @param $entidade = tabela  
  */
 final public function setEntidade($entidade)
 {
  $this->entidade = $entidade;
 }
 
 /* Método getEntidade(): retorna o nome da entidade (tabela) manipulada pela instrução SQL*/
 final public function getEntidade()
 {
  return $this->entidade;
 }
 
 /* Método setCriteria(): define um critério de seleção dos dados através da composição de um objeto do tipo TCriteria,
  * que oferece uma interface para definição de critérios
  * @param $criteria =  objeto do tipo Criterio 
  */
 public function setCriterio(Criterio $criterio)
 {
  $this->criterio = $criterio;
 }
 
 /* Método getInstrucao(): declarando-o como <abstract> obrigamos sua declaração nas classes filhas
  * uma vez que seu comportamento será distinto em cada uma delas, configurando polimorfismo
  */
 abstract function getInstrucao($tipo);
} 
?>