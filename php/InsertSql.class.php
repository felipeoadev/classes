<?php
/* Classe InsertSql: essa classe provê meios para manipulação de uma instrução de INSERT no banco de dados
 * Original: TSqlInsert.class.php do livro: PHP - Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */

final class InsertSql extends InstrucaoSql
{
 private static $logger; //objeto de LOG

/*método setRowData(): atribui valores à determinadas colunas no banco de dados que serão inseridas
  *@param $coluna = coluna da tabela
  *@param $valor = valor a ser armazenado
  */
 public function setRowData($coluna, $valor)
 {
  //monta um array indexado pelo nome da coluna
  if (is_string($valor))
  {
   //adiciona \ em aspas
   $valor = addslashes($valor);
   
   //caso seja uma string
   $this->columnValues[$coluna] = "'".$valor."'";
  }
  else if (is_bool($valor))
  {
   //caso seja um boolean
   $this->columnValues[$coluna] = $valor ? 'TRUE' : 'FALSE';
  }
  else if (isset($valor))
  {
   //caso seja outro tipo de dado
   $this->columnValues[$coluna] = $valor;
  }
  else
  {
   //caso seja NULL
   $this->columnValues[$coluna] = "NULL";  
  }
 }
 
 /*método setCriterio(): não existe no contexto desta classe, logo, irá lancar um erro se for executado */ 
 public function setCriterio($criterio)
 {
  //lança o erro
  throw new Exception("Não pode chamar setCriterio em ". __CLASS__);
 }
 
 /*método getInstrucao(): retorna a instrução INSERT em forma de string */ 
 public function getInstrucao($tipo = '')
 {
  $this->sql = "INSERT INTO {$this->entidade} (";
  
  //monta uma string contendo os nomes de colunas
  $colunas = implode(', ', array_keys($this->columnValues));
  
  //monta uma string contendo os valores
  $valores = implode(', ', array_values($this->columnValues));
  
  $this->sql .= $colunas . ')';
  $this->sql .= " VALUES ({$valores})";
  
  return $this->sql;
 }
 
 /* Método setLogger(): define qual a estratégia (algoritmo de LOG a ser usado)*/
 public static function setLogger(Logger $logger)
 {
  self::$logger = $logger;
 }
 
 /* Método log(): armazena uma mensagem no arquivo de LOG baseada na estratégia ($logger) atual.*/
 public static function log($mensagem)
 {
  //verifica se existe um logger
  if(self::$logger)
  {
   self::$logger->escrever($mensagem);
  }
 }
} 
?>