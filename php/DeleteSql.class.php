<?php
/* Classe DeleteSql: essa classe provê meios para manipulação de uma instrução de DELETE no banco de dados
 * Original: TSqlDelete.class.php do livro: PHP - Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */
 
final class DeleteSql extends InstrucaoSql
{
 private static $logger; //objeto de LOG	
	
 /*método getInstrucao(): retorna a instrução DELETE em forma de string */ 
 public function getInstrucao($tipo = '')
 {
  $this->sql = "DELETE FROM {$this->entidade}";
    
  //retorna a cláusula WHERE do objeto $this->criteria
  if ($this->criterio)
  {
   $expressao = $this->criterio->dump();
   
   if ($expressao)
   {
    $this->sql .= ' WHERE ' .$expressao;
   }
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