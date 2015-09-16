<?php
/* Classe SelectSql: essa classe provê meios para manipulação de uma instrução de SELECT no banco de dados
 * Original: TSqlSelect.class.php do livro: PHP - Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */
 
final class SelectSql extends InstrucaoSql
{
 private $colunas; //array de colunas a serem retornadas
 private $grupo;   //array de grupo a ser retornadas
 private static $logger; //objeto de LOG
 
 /*método addColuna(): adiciona uma coluna a ser retornada pelo SELECT
  *@param $coluna = coluna da tabela
  */
 public function addColuna($coluna)
 {
  //adiciona a coluna no array
  $this->colunas[] = $coluna;
 }
 
 /*método getInstrucao(): retorna a instrução SELECT em forma de string */ 
 public function getInstrucao($tipo = '')
 {
  $this->sql = 'SELECT ';
  
  //monta string com os nomes de colunas
  $this->sql .= implode(', ', $this->colunas);
  
  //adiciona a na cláusula FROM o nome da tabela
  if (!empty($this->entidade))
  {
   $this->sql .= ' FROM '. $this->entidade;
  }
  
  //retorna a cláusula WHERE do objeto criteria
  if ($this->criterio)
  {
   $expressao = $this->criterio->dump();
   
   if ($expressao)
   {
    $this->sql .= ' WHERE '.$expressao;
   }
   
   //obtem as propriedades do critério
   $limit  = $this->criterio->getPropriedade('limit');
   $offset = $this->criterio->getPropriedade('offset');
   $group = $this->criterio->getPropriedade('group');  
   $order  = $this->criterio->getPropriedade('order');    
   
   //obtém a ordenação do SELECT
   if ($group)
   {
    $this->sql .= ' GROUP BY ' .$group;
   }   
      
   if ($limit)
   {
    $this->sql .= ' LIMIT ' .$limit;
   }
   
   if ($offset)
   {
    $this->sql .= ' OFFSET ' .$offset;
   }
   
   if ($order)
   {	   
    $this->sql .= ' ORDER BY ' .$order;
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