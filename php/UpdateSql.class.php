<?php
/* Classe UpdateSql: essa classe provê meios para manipulação de uma instrução de UPDATE no banco de dados
 * Original: TSqlUpdate.class.php do livro: PHP - Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */
 
final class UpdateSql extends InstrucaoSql
{
 private static $logger; //objeto de LOG	
	
 /*método setRowData(): atribui valores à determinadas colunas no banco de dados que serão modificadas
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
 
 /*método getInstrucao(): retorna a instrução UPDATE em forma de string */ 
 public function getInstrucao($tipo = '')
 {
  $this->sql = "UPDATE {$this->entidade}";
  
  //monta os pares: coluna=valor
  if ($this->columnValues)
  {
   foreach ($this->columnValues as $coluna => $valor)
   {
    $conjunto[] = "{$coluna} = {$valor}";
   }
  }
  
  $this->sql .= ' SET ' . implode(', ', $conjunto);
  
  //retorna a cláusula WHERE do objeto $this->criteria
  if ($this->criterio)
  {
   $this->sql .= ' WHERE ' .$this->criterio->dump();
  }
  
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